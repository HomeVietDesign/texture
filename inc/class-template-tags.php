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
		add_action('wp_body_open', [__CLASS__, 'sidebar'], 20);
		add_action('wp_body_open', [__CLASS__, 'navbar_top'], 30);
		add_action('wp_body_open', [__CLASS__, 'site_content_open'], 40);
	}

	public static function sidebar() {
		if(is_tax('design_type')) {
			get_sidebar();
		}
		
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
								<h5 class="logo-text ms-2 d-none d-sm-block text-primary-emphasis"><?php bloginfo( 'name' ); ?></h5>
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
				<div class="search-box navbar-top-search-box d-none d-lg-block position-relative" style="width:25rem;">
					<form class="position-relative" action="<?=esc_url(home_url())?>" method="GET">
						<input class="form-control search-input rounded-pill form-control-sm" type="search" name="s" placeholder="Search..." aria-label="Search" value="<?php the_search_query(); ?>">
						<button type="submit" class="position-absolute btn btn-sm top-50 translate-middle-y end-0 border-0 me-1"><span class="dashicons dashicons-search"></span></button>
					</form>
				</div>

				<ul class="navbar-nav navbar-nav-icons flex-row">
					<li class="nav-item d-lg-none"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchBoxModal">Tìm kiếm</a></li>
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
					<?php } else { ?>
					<li class="nav-item">
						<a class="nav-link px-3 d-block" href="<?php echo esc_url(wp_login_url(fw_current_url())); ?>"><span>Đăng nhập</span></a>
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

	public static function right_sidebar() {
		if(is_singular( 'texture' )) {
			get_sidebar('right');
		}
	}

	public static function footer_html() {
		add_action('wp_footer', [__CLASS__, 'site_content_close'], 8);
		add_action('wp_footer', [__CLASS__, 'right_sidebar'], 8);
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

		<div class="modal fade" id="searchBoxModal">
			<div class="modal-dialog">
				<div class="modal-content mt-15 rounded-pill">
					<div class="modal-body p-0">
						<form class="position-relative" action="<?=esc_url(home_url())?>" method="GET">
							<input class="form-control search-input rounded-pill" type="search" name="s" placeholder="Search..." aria-label="Search" value="<?php the_search_query(); ?>">
							<button type="submit" class="position-absolute btn btn-sm top-50 translate-middle-y end-0 border-0 me-1"><span class="dashicons dashicons-search"></span></button>
						</form>
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