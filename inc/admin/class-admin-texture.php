<?php
namespace HomeViet\Admin;

class Texture {

	public static function custom_columns_value($column, $post_id) {
		global $texture;
		
		switch ($column) {
			case 'image':
				// $images = $texture->get('images', []);
				// if($images) {
				// 	echo wp_get_attachment_image( $images[0]['attachment_id'], 'thumbnail', false );
				// } else {
				// 	echo '<i>(No image)</i>';
				// }
				if(has_post_thumbnail( $texture->post )) {
					echo get_the_post_thumbnail( $texture->post, 'thumbnail' );
				} else {
					echo '<i>(No image)</i>';
				}
				break;
		}
	}

	public static function custom_columns_header($columns) {
		$cb = $columns['cb'];
		unset($columns['cb']);

		$new_columns = ['cb' => $cb];

		$new_columns['image'] = 'Ảnh';

		$columns = array_merge($new_columns, $columns);

		return $columns;

	}

	public static function meta_boxes() {
		// remove_meta_box(
		// 	'customerdiv' // ID
		// 	,   'texture'            // Screen, empty to support all post types
		// 	,   'side'      // Context
		// );

		add_meta_box(
            'content'     // Reusing just 'content' doesn't work.
        ,   'Mô tả thêm'    // Title
        ,   [__CLASS__, 'post_content_editor'] // Display function
        ,   'texture'              // Screen, we use all screens with meta boxes.
        ,   'normal'          // Context
        ,   'default'            // Priority
        );
	}

	public static function post_content_editor($post) {
		wp_editor( unescape($post->post_content), 'content2', [
			'tinymce' => true,
			'textarea_name' => 'content',
			'editor_height' => 500,
		] );
	}

	public static function ajax_change_texture_dimension() {
		$post_id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$val = isset($_REQUEST['val']) ? floatval($_REQUEST['val']) : '';
		$dimension = isset($_REQUEST['dimension']) ? sanitize_text_field($_REQUEST['dimension']) : '';

		$response = '';

		if(in_array($dimension, ['frontage', 'depth'])) {
			$response = fw_get_db_post_option($post_id, $dimension);

			if( check_ajax_referer('quick_edit_'.$post_id, 'nonce', false) && current_user_can('edit_post', $post_id) ) {
				
				fw_set_db_post_option($post_id, $dimension, $val);
				
				wp_cache_delete($post_id, 'posts');

				$response = $val;
			}
		}
		wp_send_json($response);
	}

	public static function taxonomy_parse_filter($query) {
		//modify the query only if it admin and main query.
		if( !(is_admin() AND $query->is_main_query()) ){ 
			return $query;
		}

		$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
	
		if($post_type=='texture') {
			$tax_query = [];

			$pc = isset($_GET['pc']) ? intval($_GET['pc']) : 0;
			if($pc!=0) {
				$tax_query['pc'] = ['taxonomy' => 'texture_cat'];
				if($pc>0) {
					$tax_query['pc']['field'] = 'term_id';
					$tax_query['pc']['terms'] = $pc;
				} else {
					$tax_query['pc']['operator'] = 'NOT EXISTS';
				}

			}

			if(!empty($tax_query)) {
				$query->set('tax_query', $tax_query);
			}
		}

		return $query;
	}

	public static function filter_by_taxonomy($post_type) {
		if ($post_type == 'texture') {
			wp_dropdown_categories(array(
				'show_option_all' => '- Hạng mục -',
				'show_option_none' => '- Chưa có -',
				'taxonomy'        => 'texture_cat',
				'name'            => 'pc',
				'orderby'         => 'name',
				'selected'        => isset($_GET['pc']) ? intval($_GET['pc']) : 0,
				'show_count'      => true,
				'hide_empty'      => true,
				'value_field'	  => 'term_id'
			));
		};
	}

	public static function ajax_change_texture_url_data_file() {
		$post_id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$url_data_file = isset($_REQUEST['url_data_file']) ? sanitize_url($_REQUEST['url_data_file']) : '';

		$response = fw_get_db_post_option($post_id, 'url_data_file');

		if( check_ajax_referer('quick_edit_'.$post_id, 'nonce', false) && current_user_can('edit_post', $post_id) ) {
			
			fw_set_db_post_option($post_id, 'url_data_file', $url_data_file);
			
			wp_cache_delete($post_id, 'posts');

			$response = $url_data_file;
		}
		wp_send_json($response);
	}

	public static function ajax_change_texture_combo() {
		$post_id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$combo = isset($_REQUEST['combo']) ? $_REQUEST['combo'] : '';

		$response = fw_get_db_post_option($post_id, 'combo');

		if( check_ajax_referer('quick_edit_'.$post_id, 'nonce', false) && current_user_can('edit_post', $post_id) ) {

			fw_set_db_post_option($post_id, 'combo', ($combo=='true')?'yes':'no');
			
			wp_cache_delete($post_id, 'posts');

			$response = fw_get_db_post_option($post_id, 'combo');
		}

		wp_send_json($response);
	}

	public static function ajax_change_texture_has_file() {
		$post_id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
		$has_file = isset($_REQUEST['has_file']) ? $_REQUEST['has_file'] : '';

		$response = fw_get_db_post_option($post_id, 'has_file');

		if( check_ajax_referer('quick_edit_'.$post_id, 'nonce', false) && current_user_can('edit_post', $post_id) ) {

			fw_set_db_post_option($post_id, 'has_file', ($has_file=='true')?'yes':'no');
			
			wp_cache_delete($post_id, 'posts');

			$response = fw_get_db_post_option($post_id, 'has_file');
		}

		wp_send_json($response);
	}

	public static function disable_months_dropdown($disabled, $post_type) {
		if($post_type=='texture') {
			$disabled = true;
		}
		return $disabled;
	}

	public static function delete_texture_images($post_id, $post) {
		if($post->post_type=='texture') {
			$texture = \HomeViet\Texture::get_instance($post_id);
			if($texture->get('images')) {
				if(function_exists('as_enqueue_async_action')) {
					$data = [];
					foreach ($texture->get('images') as $key => $value) {
						$data[] = $value['attachment_id'];
					}
					as_enqueue_async_action('delete_texture_images_process', [['images'=>$data]], 'texture');
				}
			}
		}
	}

	public static function save_texture($post_id, $post, $update) {
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		
		if ( wp_is_post_revision( $post_id ) ) return;

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if(!$update) {
			global $wpdb;
			$wpdb->update( $wpdb->posts, ['post_name' => 'texture-'.$post_id], ['ID' => $post_id] );
			
			wp_cache_delete( $post_id, 'posts' );
		}
	}

	public static function enqueue_scripts($hook) {
		global $post_type;

		if(($hook=='edit.php' || $hook=='post.php' || $hook=='post-new.php') && $post_type=='texture') {
			wp_enqueue_style('admin-texture', THEME_URI.'/assets/css/admin-texture.css', [], '');
			wp_enqueue_script('admin-texture', THEME_URI.'/assets/js/admin-texture.js', array('jquery'), '');
		}

	}
}