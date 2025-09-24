<?php
namespace HomeViet;

class Shortcode {

	public static function user_texture_rating_block($atts) {
		ob_start();
		if(is_user_logged_in()) {
			$texture = new \HomeViet\Texture($atts['id']);

			$user = wp_get_current_user();

			$ratings = $texture->get('ratings', []);
			if (!is_array($ratings)) $ratings = [];
			
			$rating = (isset($ratings[$user->ID])) ? absint($ratings[$user->ID]) : 0;
			?>
			<div class="texture-rating text-secondary d-flex justify-content-center flex-wrap mb-2" data-url="<?=esc_attr($_SERVER['REQUEST_URI'])?>" data-id="<?=$texture->id?>" data-rating="<?=$rating?>">
				<div class="order-last d-block w-100 text-center"><span class="star lh-1 star-none" data-value="0" data-bs-toggle="tooltip" data-bs-title="Hủy đánh giá sao">⮿</span></div>
				<?php
				for ($i = 1; $i <= 10; $i++) {
					?>
					<span class="star lh-1<?php echo ($i<=$rating) ? ' voted' :''; ?>" data-value="<?=$i?>" data-bs-toggle="tooltip" data-bs-title="<?=$i?>">★</span>
					<?php
				}
				?>
			</div>
			<?php
		}

		return ob_get_clean();
	}

	public static function user_info_block($atts) {
		ob_start();
		?>
		<ul class="navbar-nav navbar-nav-icons flex-row">
			<li class="nav-item d-lg-none"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchBoxModal">Tìm kiếm</a></li>
			<?php if(is_user_logged_in()) {

				$user = wp_get_current_user();
			?>
			<li class="nav-item">
				<a class="nav-link px-3 d-block" href="<?php echo esc_url(admin_url('edit.php?post_type=texture')); ?>"><span><?php echo esc_html($user->display_name); ?></span></a>
			</li>
			<!-- <li class="nav-item dropdown">
				<a class="nav-link pe-0" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
					<?php echo esc_html($user->display_name); ?>
				</a>
				<div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
					<div class="card position-relative border-0">
						<div class="overflow-auto scrollbar">
							<ul class="nav d-flex flex-column my-1 py-1">
								<li class="nav-item">
									<a class="nav-link px-3 d-block" href="<?php echo esc_url(get_edit_profile_url()); ?>"><span>Thông tin tài khoản</span></a>
								</li>
								<?php if(has_role('administrator')) { ?>
								<li class="nav-item">
									<a class="nav-link px-3 d-block" href="<?php echo esc_url(admin_url('edit.php?post_type=texture')); ?>" target="_blank"><span>Vào trang admin</span></a>
								</li>
								<?php } ?>
							</ul>
						</div>
						<div class="card-footer p-3 border-top border-translucent">
							<div class="px-3 text-center"><a class="btn btn-sm btn-secondary" href="<?php echo esc_url(wp_logout_url(fw_current_url())); ?>">Đăng xuất</a></div>
						</div>
					</div>
				</div>
			</li> -->
			<?php } else { ?>
			<li class="nav-item">
				<a class="nav-link px-3 d-block" href="<?php echo esc_url(wp_login_url(fw_current_url())); ?>"><span>Đăng nhập</span></a>
			</li>
			<?php } ?>
		</ul>
		<?php
		return ob_get_clean();
	}
}