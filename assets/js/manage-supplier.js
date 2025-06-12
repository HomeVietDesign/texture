window.addEventListener('DOMContentLoaded', function(){
	jQuery(function($){
		$('label[for="tag-name"],label[for="name"]').html('Số điện thoại');
		$('label[for="tag-description"],label[for="description"]').html('Tên gọi');
		$('#description-description').html('Tên gọi hiển thị thay cho số điện thoại để dễ nhận biết.');
		
	});
});