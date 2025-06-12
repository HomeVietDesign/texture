<?php
namespace HomeViet\Admin;

class Supplier {

	public static function enqueue_scripts($hook) {
		global $taxonomy;
		if(($hook=='edit-tags.php' || $hook=='term.php') && $taxonomy=='supplier') {
			wp_enqueue_style( 'manage-supplier', THEME_URI.'/assets/css/manage-supplier.css', [], '' );
			wp_enqueue_script('manage-supplier', THEME_URI.'/assets/js/manage-supplier.js', array('jquery'), '');
		}
	}

	public static function change_columns_links($term_links, $taxonomy, $terms) {
		if($taxonomy=='supplier') {
			foreach ( $term_links as $key => &$link) {
				$link = preg_replace('/(<a[^>]*>)([^<]*)(<\/a>)/', "$1".esc_html($terms[$key]->description.' ( '.$terms[$key]->name.' )')."$3", $link);
			}
		}

		return $term_links;
	}

	public static function change_check_list($args) {
		if($args['taxonomy']=='supplier') {
			$args['walker'] = new \Walker_Supplier_Checklist();
		}

		return $args;
	}

	public static function auto_slug($term_id) {
		global $wpdb;
		//$wpdb->update( $wpdb->terms, ['slug' => date('Ymd-His', current_time( 'U' ))], ['term_id' => $term_id] );
		$wpdb->update( $wpdb->terms, ['slug' => 't'.$term_id], ['term_id' => $term_id] );
		wp_cache_delete( $term_id, 'terms' );
	}

	public static function manage_edit_column_header($columns) {
		if(isset($columns['slug'])) {
			$columns['slug'] = 'URL';
		}

		if(isset($columns['name'])) {
			$columns['name'] = 'Số điện thoại';
		}

		if(isset($columns['description'])) {
			$columns['description'] = 'Tên gọi';
		}

		//$columns['term_id'] = 'ID';
		//$columns['order'] = 'STT';

		if(isset($columns['posts'])) {
			$columns['posts'] = 'Đếm';
		}
		return $columns;
	}

	public static function manage_edit_columns_value($row, $column_name, $term_id) {
		$nonce = wp_create_nonce('quick_edit_'.$term_id);

		switch ($column_name) {
			case 'order':
				echo intval(get_term_meta($term_id, 'order', true));
				break;
			case 'term_id':
				echo esc_html($term_id);
				break;
		}
	}
}