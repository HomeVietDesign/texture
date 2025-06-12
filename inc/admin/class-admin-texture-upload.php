<?php
namespace HomeViet\Admin;

class Texture_Upload {

	public static function admin_menu() {
		add_submenu_page( 'edit.php?post_type=texture', 'Tải lên map vật liệu', 'Tải lên', 'edit_posts', 'texture-upload', [__CLASS__, 'admin_texture_upload'] );
	}

	public static function admin_texture_upload() {

		?>
		<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<p></p>
			<div id="admin-texture-upload">
				<div id="texture-upload-terms">
					<?php
					wp_nonce_field( 'admin-texture-upload', 'upload_nonce' );

					wp_dropdown_categories([
						'taxonomy' => 'supplier',
						'name' => 'supplier',
						'id' => 'upload_to_supplier',
						'hide_empty' => false,
						'show_option_all' => '--Nhà cung cấp--',
						//'walker' => new \Walker_Supplier_Dropdown()
					]);

					wp_dropdown_categories([
						'taxonomy' => 'design_type',
						'name' => 'design_type',
						'id' => 'upload_to_design_type',
						'hide_empty' => false,
						'show_option_all' => '--Loại thiết kế--',
					]);

					wp_dropdown_categories([
						'taxonomy' => 'material',
						'name' => 'material',
						'id' => 'upload_to_material',
						'hide_empty' => false,
						'hierarchical' => true,
						'show_option_all' => '--Chất liệu--',
					]);

					wp_dropdown_categories([
						'taxonomy' => 'texture_product',
						'name' => 'texture_product',
						'id' => 'upload_to_texture_product',
						'hide_empty' => false,
						'show_option_all' => '--Loại sản phẩm--',
					]);

					wp_dropdown_categories([
						'taxonomy' => 'segment',
						'name' => 'segment',
						'id' => 'upload_to_segment',
						'hide_empty' => false,
						'show_option_all' => '--Phân khúc--',
					]);
					?>
				</div>
				<div id="texture-upload-dragandrop-handler">
					Kéo thả để tải lên
				</div>
				<div id="texture-upload-statusbars"></div>
				<br><br>
				<div id="texture-upload-status"></div>
			</div>
		</div>
		<?php
	}

	public static function ajax_admin_texture_upload() {
		if(current_user_can('edit_posts') && check_ajax_referer( 'admin-texture-upload', 'nonce', false )) {
			$supplier = isset($_POST['supplier']) ? absint($_POST['supplier']) : 0;
			$design_type = isset($_POST['design_type']) ? absint($_POST['design_type']) : 0;
			$material = isset($_POST['material']) ? absint($_POST['material']) : 0;
			$texture_product = isset($_POST['texture_product']) ? absint($_POST['texture_product']) : 0;
			$segment = isset($_POST['segment']) ? absint($_POST['segment']) : 0;

			$upload = isset($_FILES['image']) ? $_FILES['image'] : null;

			// tải lên file dự toán
			if ( ! function_exists( 'media_handle_upload' ) ) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				require_once(ABSPATH . "wp-admin" . '/includes/file.php');
				require_once(ABSPATH . "wp-admin" . '/includes/media.php');
			}

			$attachment_id = media_handle_upload( 'image', 0 );

			if ($upload['error']==0 && $attachment_id && ! is_array( $attachment_id ) ) {
				$path_parts = pathinfo($upload['full_path']);
				$insert_id = wp_insert_post([
					'post_title' => $path_parts['filename'],
					'post_type' => 'texture',
					'post_status' => 'publish',
				]);

				if(is_int($insert_id)) {
					$texture = \HomeViet\Texture::get_instance($insert_id);
					$texture->set('images',[['attachment_id'=>$attachment_id,'url'=>wp_get_attachment_url($attachment_id)]]);
					if($supplier>0) {
						wp_set_object_terms( $insert_id, [$supplier], 'supplier' );
					}
					if($design_type>0) {
						wp_set_object_terms( $insert_id, [$design_type], 'design_type' );
					}
					if($material>0) {
						wp_set_object_terms( $insert_id, [$material], 'material' );
					}
					if($texture_product>0) {
						wp_set_object_terms( $insert_id, [$texture_product], 'texture_product' );
					}
					if($segment>0) {
						wp_set_object_terms( $insert_id, [$segment], 'segment' );
					}
				}
			}
		}
		//debug_log($_FILES);

		exit;
	}

	public static function enqueue_scripts($hook) {
		//debug_log($hook);
		if(($hook=='texture_page_texture-upload')) {
			wp_enqueue_style( 'admin-texture-upload', THEME_URI.'/assets/css/admin-texture-upload.css', [], '' );
			wp_enqueue_script('admin-texture-upload', THEME_URI.'/assets/js/admin-texture-upload.js', array('jquery'), '');
		}
	}
}