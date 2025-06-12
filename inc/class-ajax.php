<?php
namespace HomeViet;

class Ajax {

	public static function texture_download() {
		//$user = wp_get_current_user();
		$id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$texture = \HomeViet\Texture::get_instance($id);
		if ($texture->id) {
			$upload_dir = wp_get_upload_dir();

			$image_url = $texture->get_image_src();
			//debug_log($image_url);

			if( !file_exists($upload_dir['basedir'].'/download/') ) {
				wp_mkdir_p($upload_dir['basedir'].'/download/');
			}
			
			$save_path = $upload_dir['basedir'].'/download/'.basename($image_url);

			if(strpos($image_url, home_url())===false) {
				// If the function it's not available, require it.
				if ( ! function_exists( 'download_url' ) ) {
					require_once ABSPATH . 'wp-admin/includes/file.php';
				}

				// Now you can use it!
				$tmp_file = download_url( $image_url );

				if(is_wp_error($tmp_file)) {
					echo 'Download failure: '.$image_url;
				} else {
					// Copies the file to the final destination and deletes temporary file.
					@copy( $tmp_file, $save_path );
					@unlink( $tmp_file );
				}
			} else {
				if($texture->get_image_file()) {
					@copy( $texture->get_image_file(), $save_path );	
				}
			}
			
			if(file_exists($save_path)) {
				// Thiết lập header để trình duyệt tải file về
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . basename($save_path) . '"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($save_path));
				ob_clean();
				flush();
				readfile($save_path);
				ignore_user_abort(true);
				unlink($save_path);

				exit;
			} else {
				echo 'File không tồn tại.';
			}
		}
		exit;
	}

	public static function texture_rating() {
		$user = wp_get_current_user();
		$id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$url = isset($_REQUEST['url']) ? home_url($_REQUEST['url']) : '';
		$rating = isset($_REQUEST['rating']) ? absint($_REQUEST['rating']) : 0;
		$texture = \HomeViet\Texture::get_instance($id);

		$response = false;

		if ($texture->id && $rating >= 0 && $rating <= 10) {
			$ratings = $texture->get('ratings', []);
			if (!is_array($ratings)) $ratings = [];

			if($rating>0) {
				$ratings[$user->ID] = $rating;
				update_post_meta( $texture->id, 'rating_'.$user->ID, $rating );
			} else {
				if(isset($ratings[$user->ID])) unset($ratings[$user->ID]);
				delete_post_meta( $texture->id, 'rating_'.$user->ID );
			}
			
			$texture->set('ratings', $ratings);

			if(!empty($ratings)) {
				$avg = array_sum($ratings) / count($ratings);
				update_post_meta($texture->id, 'average_rating', $avg);
			} else {
				delete_post_meta( $texture->id, 'average_rating' );
			}

			wp_cache_delete($texture->id, 'posts');
			$texture->refresh();

			do_action( 'litespeed_purge_post', $texture->id );
			do_action( 'litespeed_purge_url', $url );
			do_action( 'litespeed_purge_url', home_url('/') );

			$design_types = get_the_terms( $texture->post, 'design_type' );
			if($design_types) {
				foreach ($design_types as $key => $value) {
					$term_link = get_term_link( $value, 'design_type' );
					if($term_link!=$url) do_action( 'litespeed_purge_url', $term_link );
				}
			}

			//do_action( 'litespeed_purge_all' );
			
			$response = true;
			
		}

		wp_send_json( $response );
	}

	public static function texture_detail() {
		$id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$texture = \HomeViet\Texture::get_instance($id);
		if($texture->id) {
			if($texture->get('images')) {
			?>
			<div class="texture-detail px-3">
				<?php echo wp_get_the_content( $texture->post->post_content ); ?>
			</div>
			<?php
			}
		}
		exit;
	}

	public static function texture_detail_old() {
		$id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$texture = \HomeViet\Texture::get_instance($id);
		if($texture->id) {
			if($texture->get('images')) {
			?>
			<div class="texture-detail">
				<div class="info px-3 mb-3 d-flex justify-content-center">
					<div class="border p-2 bg-light">
						<div class="code my-1 d-flex justify-content-between align-items-end">
							<span class="d-block me-2">Mã vật liệu:</span>
							<span class="d-block flex-grow-1 border-bottom me-2" style="margin-bottom: 5px; min-width: 60px;"></span>
							<strong><?=esc_html($texture->post->post_title)?></strong>
						</div>
						<div class="design_type my-1 d-flex justify-content-between align-items-end">
							<span class="d-block me-2">Loại thiết kế:</span>
							<span class="d-block flex-grow-1 border-bottom me-2" style="margin-bottom: 5px; min-width: 60px;"></span>
							<strong><?php the_terms( $id, 'design_type', '', ', ', '' ); ?></strong>
						</div>
						<?php
						$segments = get_the_terms( $texture->post, 'segment' );
						if($segments) {
							echo '<div class="segment my-1 d-flex justify-content-between align-items-end">';
							echo '<span class="d-block me-2">Phân khúc:</span>';
							echo '<span class="d-block flex-grow-1 border-bottom me-2" style="margin-bottom: 5px; min-width: 60px;"></span>';
							echo '<strong>';
							foreach($segments as $key => $value) {
								if($key>0) echo ', ';
								echo esc_html($value->name);
							}
							echo '</strong>';
							echo '</div>';
						}

						$materials = get_the_terms( $texture->post, 'material' );
						if($materials) {
							echo '<div class="material my-1 d-flex justify-content-between align-items-end">';
							echo '<span class="d-block me-2">Chất liệu:</span>';
							echo '<span class="d-block flex-grow-1 border-bottom me-2" style="margin-bottom: 5px; min-width: 60px;"></span>';
							echo '<strong>';
							foreach($materials as $key => $value) {
								if($key>0) echo ', ';
								echo esc_html($value->name);
							}
							echo '</strong>';
							echo '</div>';
						}
						?>
						<div class="code my-1 d-flex justify-content-between align-items-end">
							<span class="d-block me-2">Ngày đăng:</span>
							<span class="d-block flex-grow-1 border-bottom me-2" style="margin-bottom: 5px; min-width: 60px;"></span>
							<strong><?=esc_html(get_the_time( 'd/m/Y', $texture->post ))?></strong>
						</div>
					</div>
				</div>

				<div class="description px-3 mb-3">
				<?php
					//$content = fw_ext_page_builder_get_post_content($texture->post);
					echo wp_get_the_content( $texture->post->post_content );
				?>
				</div>
				<?php
					/*
					foreach ($texture->get('images') as $key => $value) {
						//$src = wp_get_attachment_url( $value['attachment_id'] );
						$src_full = wp_get_attachment_image_src( $value['attachment_id'], 'full' );
						?>
						<div class="px-3 mb-3 text-center">
							<?php
							echo wp_get_attachment_image($value['attachment_id'], 'large', false, []);
							?>	
						</div>
						<?php
					}
					*/
				?>
			</div>
			<?php
			}
		}
		exit;
	}
	
	public static function logout_post_password() {
		$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
		if($url) wp_remote_request($url, ['method'=>'PURGE']);
		exit;
	}

}
