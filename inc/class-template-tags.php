<?php
/**
 * 
 * 
 */
namespace HomeViet;

class Template_Tags {

	public static function site_main_open() {
		?>
		<main class="main" id="top">
		<?php
	}

	public static function header_html() {
		// body open custom code 5
		add_action('wp_body_open', [__CLASS__, 'site_main_open'], 15);
		add_action('wp_body_open', [__CLASS__, 'navbar_vertical'], 20);
		add_action('wp_body_open', [__CLASS__, 'navbar_top'], 30);
		add_action('wp_body_open', [__CLASS__, 'site_content_open'], 40);
	}

	public static function navbar_vertical() {
		$queried = get_queried_object();
		$current_url = fw_current_url();
		?>
		<nav class="navbar navbar-vertical navbar-expand-lg">
		<div class="collapse navbar-collapse" id="navbarVerticalCollapse">
		<!-- scrollbar removed-->
			<div class="navbar-vertical-content">
				<ul class="navbar-nav flex-column" id="navbarVerticalNav">
					<?php if(is_home() || is_front_page()) { ?>
					<li class="nav-item">
						<p class="navbar-vertical-label">Map mới đăng</p>
						<hr class="navbar-vertical-line">
						<?php
						$query = new \WP_Query([
							'post_type' => 'texture',
							'posts_per_page' => 5,
							'post_status' => 'publish',
							'orderby' => 'date',
							'order' => 'DESC'
						]);

						if($query->have_posts()) {
							while ($query->have_posts()) {
								$query->the_post();
								get_template_part( 'parts/texture-side-loop' );
							}

							wp_reset_postdata();
						}
						?>
					</li>
					<?php } else if(is_tax('design_type')) { ?>
					<li class="nav-item">
						<div class="navbar-vertical-label d-flex justify-content-between align-items-center">
							<span>Chất liệu</span>
							<?php
							$all_url = remove_query_arg('mat', $current_url);
							?>
							<a class="filter-remove d-block me-2 px-2 pt-1 bg-secondary-subtle border border-dark-subtle rounded-1" data-tax="material" href="<?=esc_url($all_url)?>" title="Tất cả">Tất cả</a>
						</div>
						<hr class="navbar-vertical-line">
						<?php
						
							$queried = get_queried_object();
							$mat = isset($_GET['mat']) ? absint($_GET['mat']) : '';
							$parent = '';
							if($mat) {
								$mat_obj = get_term_by( 'term_id', $mat, 'material' );
								$parent = $mat_obj->parent;
							}

							$materials = get_terms([
								'taxonomy' => 'material',
								'hide_empty' => false,
								//'fields' => 'ids',
								'parent' => 0
							]);

							if($materials) {
								foreach ($materials as $key => $value) {
									$children = get_term_children( $value->term_id, 'material' );
									//debug($children);
									$url = add_query_arg(['mat'=>$value->term_id], $current_url);
									$class = ($mat==$value->term_id)?'active':'';
									//$class .= ($parent==$value->term_id)?'':'';

									?>
									<div class="nav-item-wrapper">
										<?php if(empty($children)) { ?>
										<a class="nav-link label-1 <?=$class?> text-uppercase" href="<?=esc_url($url)?>" role="button" data-bs-toggle="" aria-expanded="false">
											<div class="d-flex align-items-center">
												<span class="nav-link-text-wrapper">
													<span class="nav-link-icon"></span>
													<span class="nav-link-text"><?=esc_html($value->name)?></span>
												</span>
											</div>
										</a>
										<?php } else { ?>
										<span class="nav-link dropdown-indicator label-1 <?=$class?> text-uppercase">
											<div class="d-flex align-items-center">
												<a class="dropdown-indicator-icon-wrapper<?php echo ($mat==$value->term_id || $parent==$value->term_id) ? '':' collapsed'; ?>" href="#material-dropdown-<?=$value->term_id?>" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="material-dropdown-<?=$value->term_id?>"></a>
												<a class="nav-link-text" href="<?=esc_url($url)?>"><?=esc_html($value->name)?></a>
											</div>
										</span>
										<div class="parent-wrapper label-1">
											<ul class="nav parent collapse<?php echo ($mat==$value->term_id || $parent==$value->term_id) ? ' show':''; ?>" data-bs-parent="#navbarVerticalCollapse" id="material-dropdown-<?=$value->term_id?>">
												<?php
												foreach($children as $child) {
													$url = add_query_arg(['mat'=>$child], $current_url);
													$class = ($mat==$child)?'active':'';
													?>
													<li class="nav-item">
														<a class="nav-link <?=$class?>" href="<?=esc_url($url)?>">
															<div class="d-flex align-items-center">
																<span class="nav-link-text"><?=esc_html(get_term_field( 'name', $child, 'material' ))?></span>
															</div>
														</a>
													</li>
													<?php
												}
												?>
											</ul>
										</div>
										<?php } ?>
									</div>
									<?php
									// code...
								}
							}
						?>
					</li>

					<li class="nav-item">
						<hr class="navbar-vertical-line">
						<div class="navbar-vertical-label d-flex justify-content-between align-items-center">
							<span>Sản phẩm</span>
							<?php
							$all_url = remove_query_arg('pro', $current_url);
							?>
							<a class="filter-remove d-block me-2 px-2 pt-1 bg-secondary-subtle border border-dark-subtle rounded-1" data-tax="material" href="<?=esc_url($all_url)?>" title="Tất cả">Tất cả</a>
						</div>
						<hr class="navbar-vertical-line">
						<?php
							$queried = get_queried_object();
							$pro = isset($_GET['pro']) ? absint($_GET['pro']) : '';

							$products = get_terms([
								'taxonomy' => 'texture_product',
								'hide_empty' => false,
								//'fields' => 'ids'
							]);
							if($products) {
								
								foreach ($products as $key => $value) {
								$url = add_query_arg(['pro'=>$value->term_id], $current_url);
								$class = ($pro==$value->term_id)?'active':'';
								?>
								<div class="nav-item-wrapper">
									<a class="nav-link label-1 <?=$class?>" href="<?=esc_url($url)?>" role="button" data-bs-toggle="" aria-expanded="false">
										<div class="d-flex align-items-center">
											<span class="nav-link-text-wrapper">
												<span class="nav-link-icon"></span>
												<span class="nav-link-text"><?=esc_html($value->name)?></span>
											</span>
										</div>
									</a>
								</div>
								<?php
								// code...
								}
							}
						
						?>
					</li>

					<li class="nav-item">
						<hr class="navbar-vertical-line">
						<p class="navbar-vertical-label">Map mới đăng</p>
						<hr class="navbar-vertical-line">
						<?php
						$args = [
							'post_type' => 'texture',
							'posts_per_page' => 5,
							'post_status' => 'publish',
							'orderby' => 'date',
							'order' => 'DESC',
							'tax_query' => [
								'design_type' => [
									'taxonomy' => 'design_type',
									'field' => 'term_id',
									'terms' => [$queried->term_id]
								]
							]
						];

						if(isset($_GET['seg'])) {
							$args['tax_query']['segment'] = [
								'taxonomy' => 'segment',
								'field' => 'term_id',
								'terms' => [absint($_GET['seg'])]
							];
						}

						if(isset($_GET['mat'])) {
							$args['tax_query']['material'] = [
								'taxonomy' => 'material',
								'field' => 'term_id',
								'terms' => [absint($_GET['mat'])]
							];
						}

						$query = new \WP_Query($args);

						if($query->have_posts()) {
							while ($query->have_posts()) {
								$query->the_post();
								get_template_part( 'parts/texture-side-loop' );
							}

							wp_reset_postdata();
						}
						?>
					</li>
					<?php

					} // if(is_tax('design_type'))
					?>
				</ul>
			</div>
		</div>
		</nav>
		<?php
	}

	public static function navbar_top() {
		?>
		<nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
			<div class="collapse navbar-collapse justify-content-between">
				<div class="navbar-logo">
					<button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
						<span class="navbar-toggle-icon">
							<span class="toggle-line"></span>
						</span>
					</button>
					<a class="navbar-brand me-1 me-sm-3" href="<?php echo home_url(); ?>">
						<div class="d-flex align-items-center">
							<div class="d-flex align-items-center">
								<h5 class="logo-text ms-2 d-none d-sm-block"><?php bloginfo( 'name' ); ?></h5>
							</div>
						</div>
					</a>
				</div>

				<?php
				wp_nav_menu([
					'theme_location' => 'primary',
					'container' => false,
					'echo' => true,
					'fallback_cb' => '',
					'depth' => 1,
					'walker' => new \HomeViet\Walker_Primary_Menu(),
					'items_wrap' => '<ul class="navbar-nav navbar-nav-icons flex-row">%3$s</ul>',
				]);
				?>
				<div class="search-box navbar-top-search-box d-none d-lg-block" style="width:25rem;">
					<form class="position-relative" data-bs-toggle="search" data-bs-display="static" aria-expanded="false">
						<input class="form-control search-input fuzzy-search rounded-pill form-control-sm" type="search" placeholder="Search..." aria-label="Search">
					</form>
				</div>

				<ul class="navbar-nav navbar-nav-icons flex-row">
					<li class="nav-item d-lg-none"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchBoxModal">Search</a></li>
					<?php if(is_user_logged_in()) {

						$user = wp_get_current_user();
					?>
					<li class="nav-item dropdown">
						<a class="nav-link pe-0" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
							<!-- <span class="dashicons dashicons-admin-users"></span> -->
							<?php echo esc_html($user->display_name); ?>
						</a>
						<div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
							<div class="card position-relative border-0">
								<!-- <div class="card-body p-0">
									<div class="text-center pt-4 pb-3">
										<h6 class="mt-2 text-body-emphasis"><?php echo esc_html($user->display_name); ?></h6>
									</div>
								</div> -->
								<div class="overflow-auto scrollbar">
									<ul class="nav d-flex flex-column my-1 py-1">
										<li class="nav-item">
											<a class="nav-link px-3 d-block" href="<?php echo esc_url(get_edit_profile_url()); ?>"><span>Thông tin tài khoản</span></a>
										</li>
										<?php if(has_role('administrator')) { ?>
										<li class="nav-item">
											<a class="nav-link px-3 d-block" href="<?php echo esc_url(admin_url('edit.php?post_type=texture')); ?>"><span>Vào trang admin</span></a>
										</li>
										<?php } ?>
									</ul>
								</div>
								<div class="card-footer p-3 border-top border-translucent">
									<div class="px-3 text-center"><a class="btn btn-sm btn-secondary" href="<?php echo esc_url(wp_logout_url(fw_current_url())); ?>">Đăng xuất</a></div>
								</div>
							</div>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
		<?php
	}

	public static function site_content_open() {
		?>
		<div class="content">
		<?php
	}

	public static function site_content_close() {
		?>
		</div>
		<?php
	}

	public static function footer_html() {
		add_action('wp_footer', [__CLASS__, 'site_content_close'], 8);	
		add_action('wp_footer', [__CLASS__, 'site_main_close'], 9);
		// enqueue scripts 10
		// modal 50
		// custom code 100
	}

	public static function site_main_close() {
		?>
		</main>
		<?php
	}

	public static function modals() {
		?>
		<div class="toast-container position-fixed bottom-0 end-0 p-3">
			<div id="vote-toast" class="toast text-bg-info align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="d-flex">
					<div class="toast-body"></div>
					<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>

		<!-- Modal Texture Detail -->
		<!-- <div class="modal fade" id="texture-detail" tabindex="-1" aria-labelledby="texture-detail-label" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
					<h1 class="modal-title fs-5" id="texture-detail-label"></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body px-0">
					
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary texture-download" data-id="">Tải về</button>
					</div>
				</div>
			</div>
		</div> -->

		<div class="modal fade" id="searchBoxModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="true" data-phoenix-modal="data-phoenix-modal">
			<div class="modal-dialog">
				<div class="modal-content mt-15 rounded-pill">
					<div class="modal-body p-0">
						<div class="search-box navbar-top-search-box" data-list="{&quot;valueNames&quot;:[&quot;title&quot;]}" style="width: auto;">
							<form class="position-relative" data-bs-toggle="search" data-bs-display="static" aria-expanded="false"><input class="form-control search-input fuzzy-search rounded-pill form-control-lg" type="search" placeholder="Search..." aria-label="Search">

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function display_widgets() {
		?>
		<div class="site-footer-inner container-xl">
			<div class="row">
				<?php if(is_active_sidebar( 'footer-1' )) { ?>
				<div class="site-footer-col col-lg-4 py-3">
					<div class="col-inner"><?php dynamic_sidebar('footer-1'); ?></div>
				</div>
				<?php } ?>
				<?php if(is_active_sidebar( 'footer-2' )) { ?>
				<div class="site-footer-col col-lg-4 py-3">
					<div class="col-inner"><?php dynamic_sidebar('footer-2'); ?></div>
				</div>
				<?php } ?>
				<?php if(is_active_sidebar( 'footer-3' )) { ?>
				<div class="site-footer-col col-lg-4 py-3">
					<div class="col-inner"><?php dynamic_sidebar('footer-3'); ?></div>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	public static function footer_custom_scripts() {
		global $theme_setting;
		$custom_script = $theme_setting->get('footer_code', '');
		if(''!=$custom_script) {
			echo $custom_script;
		}

	}

	public static function body_open_custom_code() {
		global $theme_setting;
		$custom_script = $theme_setting->get('body_code', '');
		if(''!=$custom_script) {
			echo $custom_script;
		}
	}

	public static function noindex() {
		?>
		<meta name="robots" content="noindex, nofollow" />
		<?php
	}

	public static function head_youtube_scripts() {
		?>
		<script>
			// This code loads the IFrame Player API code asynchronously.
			var tag = document.createElement('script');
			tag.src = "https://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		 </script>
		<?php
	}

	public static function head_scripts() {
		global $theme_setting;
		?>
		<style type="text/css">
			.grecaptcha-badge {
				right: -999999px!important;
			}
			/* PART 1 - Before Lazy Load */
			img[data-lazyloaded]{
				opacity: 0;
			}
			/* PART 2 - Upon Lazy Load */
			img.litespeed-loaded{
				-webkit-transition: opacity .3s linear 0.1s;
				-moz-transition: opacity .3s linear 0.1s;
				transition: opacity .3s linear 0.1s;
				opacity: 1;
			}
			/*@media (min-width: 576px) {
				
			}*/
		</style>
		<script type="text/javascript">
			/*
			window.addEventListener('DOMContentLoaded', function(){
				const root = document.querySelector(':root');
				root.style.setProperty('--footer-buttons-fixed--height', document.getElementById('footer-buttons-fixed').clientHeight+'px');
				root.style.setProperty('--site-header--height', document.getElementById('site-header').clientHeight+'px');
				window.addEventListener('resize', function(){
					root.style.setProperty('--footer-buttons-fixed--height', document.getElementById('footer-buttons-fixed').clientHeight+'px');
					root.style.setProperty('--site-header--height', document.getElementById('site-header').clientHeight+'px');
				});
			});
			*/
		</script>
		<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
		<?php
		$custom_script = $theme_setting->get('head_code', '');
		if(''!=$custom_script) {
			echo $custom_script;
		}
	}
}