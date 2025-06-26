window.addEventListener('DOMContentLoaded', function(){
	const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
	const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

	jQuery(function($){

		let texture_rating_star = $('.texture-rating .star');
		const vote_toast = document.getElementById('vote-toast');
		const toastBootstrap = bootstrap.Toast.getOrCreateInstance(vote_toast);
		texture_rating_star.on('click', function(e){
			let $star = $(this),
				vote = parseInt($star.data('value')),
				$toast = $(toastBootstrap._element);
			if(vote==0) {
				$toast.find('.toast-body').html('Đã hủy đánh giá.');
			} else {
				$toast.find('.toast-body').html('Đã đánh giá '+vote+' sao.');
			}
			
			toastBootstrap.show();
		});

		$('.title .code').on('inserted.bs.popover', function(e){
			let $this = $(this),
				content = $this.data('content'),
				$popover = $('#'+$this.attr('aria-describedby'));

			$popover.find('.popover-body').html(content);
		});

		$('.texture-download').on('click', function(e){
			let $this = $(this),
				id = $this.data('id');
			window.open(theme.ajax_url+'?action=texture_download&id='+id);
		});

		$('.texture-rating .star').on('mouseenter', function(e){
			let $this = $(this),
				$wrap = $this.closest('.texture-rating'),
				$stars = $wrap.find('>.star'),
				val = $this.data('value');

			$stars.removeClass('voted');
			$stars.removeClass('hovered');
			for (let i = 0; i < val; i++)
				$($stars.get(i)).addClass('hovered');

		}).on('mouseleave', function(e){
			let $this = $(this),
				$wrap = $this.closest('.texture-rating'),
				$stars = $this.closest('.texture-rating').find('>.star')
				rating = $wrap.attr('data-rating');

			$stars.removeClass('hovered');
			for (let i = 0; i < rating; i++) {
				$($stars.get(i)).addClass('voted');
			}

		}).on('click', function(e){
			let $this = $(this),
				$wrap = $this.closest('.texture-rating'),
				$stars = $wrap.find('>.star'),
				id = $wrap.data('id'),
				rating = $this.data('value'),
				url = $wrap.data('url');

			$.ajax({
				url: theme.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action:'texture_rating',
					id:id,
					url:url,
					rating: rating
				},
				beforeSend: function(xhr) {
				},
				success: function(response) {
					if(response) {
						$wrap.attr('data-rating', rating);
						$stars.removeClass('voted');
						for (let i = 0; i < rating; i++) {
							$($stars.get(i)).addClass('voted');
						}
					}
				},
				error: function() {
					
				},
				complete: function() {
					
				}
			});
		});

		$('#texture-detail').on('show.bs.modal', function (event) {
			let $modal = $(this),
				$button = $(event.relatedTarget)
				,$body = $modal.find('.modal-body')
				,id = $button.data('id')
				,code = $button.data('code')
				;

			$('#texture-detail-label').text(code);
			
			$modal.find('.texture-download').data('id', id);
			
			$.ajax({
				url: $button.attr('href'),
				type: 'GET',
				data: {
					id:id
				},
				beforeSend: function(xhr) {
					$body.html('<div class="p-3 text-center">Đang tải..</div>');
				},
				success: function(response) {
					$body.html(response);
				},
				error: function() {
					$body.html('<div class="p-3 text-center">Lỗi khi tải. Thử tắt và mở lại.</div>');
				},
				complete: function() {
					
				}
			});
			
		}).on('hidden.bs.modal', function (e) {
			let $modal = $(this),
				$body = $modal.find('.modal-body');

			$('#texture-detail-label').text('');
			$body.text('');
			$modal.find('.texture-download').data('id', '');
		});

		$('a[href$="#"]').on('click', function(e){
			e.preventDefault();
			return false;
		});

		function check_input_phone_number(p) {
			const patt = /^(\+?\d{1,3}[-.\s]?)?(\(?\d{3}\)?[-.\s]?)?\d{3}[-.\s]?\d{4}$/;
			return patt.test(p);
		}
		
		// $(".texture-slider").owlCarousel({
		// 	items:1,
		// 	lazyLoad:false,
		// 	loop:false,
		// 	autoplay:false,
		// 	autoplayTimeout:3000,
		// 	autoplayHoverPause:true,
		// 	nav:false,
		// 	dots:false
		// });

		$(".single-texture-slider").owlCarousel({
			items:1,
			margin:12,
			lazyLoad:false,
			loop:true,
			autoplay:false,
			autoplayTimeout:3000,
			autoplayHoverPause:true,
			nav:true,
			dots:false,
			responsive: {
				// 576: {
				// 	items: 2
				// },
				992: {
					items: 2
				}
			}
		});

		var lightbox = new PhotoSwipeLightbox({
			gallery: '.pswp-gallery',
			children: 'a',
			pswpModule: PhotoSwipe 
		});
		lightbox.init();
		
		$('body').on('click', function (e) {
			$('[data-bs-toggle="popover"]').each(function () {
				// hide any open popovers when the anywhere else in the body is clicked
				if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
					$(this).popover('hide');
				}
			});
		});

		let ajax_material_search = null;

		$('#search-material').on('input', debounce(function(event){
			let $input = $(this),
				kw = $input.val().toLowerCase(),
				$search_list = $('#material-list-search'),
				$list = $('#material-list');

			if(kw!='') {
				$search_list.removeClass('hidden');
				$list.addClass('hidden');

				let html = $('<div class="nav-item-wrapper"></div>');

				$list.find('.nav-link-text').filter(function(){
					let $link_text = $(this);
					if($link_text.text().toLowerCase().indexOf(kw) > -1) {
						let a = '<a class="nav-link dropdown-indicator label-1" href="'+$link_text.closest('a').attr('href')+'"><div class="d-flex align-items-center"><span class="nav-link-text-wrapper"><span class="nav-link-icon"></span><span class="nav-link-text">'+$link_text.text()+'</span></span></div></a>';
						html.append(a);
					}
				});
				
				$search_list.html(html);

			} else {
				$search_list.addClass('hidden');
				$list.removeClass('hidden');
			}
		}));
	});
});