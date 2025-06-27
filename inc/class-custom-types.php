<?php
namespace HomeViet;

class Custom_Types {

	public static function rewrite_texture_url ($post_link, $post ) {
		if ('texture' === $post->post_type) {
			$terms = get_the_terms($post->ID, 'material');
			if (!empty($terms) && !is_wp_error($terms)) {
				$material_slug = array_pop($terms)->slug;
				return str_replace('%material%', $material_slug, $post_link);
			} else {
				return str_replace('%material%', 'khong-chat-lieu', $post_link); // fallback
			}
		}
		return $post_link;
	}

	public static function _theme_filter_builder_supported_custom_type($result) {
		$texture = get_post_type_object( 'texture' );
		if($texture) {
			$result[$texture->name] = $texture->label;
		}
		return $result;
	}

	public static function hide_tags_from_quick_edit($show_in_quick_edit, $taxonomy_name, $post_type) {

		if( in_array($taxonomy_name, ['design_type', 'material']) && $post_type=='texture') {
			$show_in_quick_edit = false;
		}

		return $show_in_quick_edit;
	}

	public static function _theme_action_register_post_status() {
		register_post_status( 'cancel', array(
			'label'                     => 'Đã hủy',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Đã hủy (%s)', 'Đã hủy (%s)' ),
		) );
	}

	public static function _theme_action_register_custom_type() {
		$labels = array(
			'name'               => 'Map vật liệu',
			'singular_name'      => 'Map vật liệu',
			'add_new'            => 'Thêm mới Map vật liệu',
			'add_new_item'       => 'Thêm mới Map vật liệu',
			'edit_item'          => 'Sửa Map vật liệu',
			'new_item'           => 'Map vật liệu mới',
			'view_item'          => 'Xem Map vật liệu',
			'search_items'       => 'Tìm Map vật liệu',
			'not_found'          => 'Không có phần tử nào',
			'not_found_in_trash' => 'Không có phần tử nào trong Thùng rác',
			'parent_item_colon'  => 'Cấp trên:',
			'menu_name'          => 'Map vật liệu',
		);
	
		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-image',
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => true, // ẩn bài viết ở front-end
			'exclude_from_search' => false, // loại khỏi kết quả tìm kiếm
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => [
				//'slug'=>'texture'
				'slug' => '%material%',
        		'with_front' => false
			],
			'capability_type'     => 'post',
			//'map_meta_cap'		  => true,
			'supports'            => array(
				'title',
				'thumbnail',
				//'editor',
				//'excerpt',
				//'revisions',
				//'page-attributes',
			),
		);
	
		register_post_type( 'texture', $args );

	}

	public static function _theme_action_register_taxonomy() {
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => 'Nhà cung cấp',
			'singular_name'     => 'Nhà cung cấp',
			'search_items'      => 'Tìm Nhà cung cấp',
			'all_items'         => 'Tất cả Nhà cung cấp',
			'edit_item'         => 'Sửa Nhà cung cấp',
			'update_item'       => 'Cập nhật Nhà cung cấp',
			'add_new_item'      => 'Thêm Nhà cung cấp mới',
			'new_item_name'     => 'Nhà cung cấp mới',
			'menu_name'         => 'Nhà cung cấp',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => ['slug'=>'nha-cung-cap'],
			//'rewrite'           => false,
			'public' 			=> true,
			'show_in_menu' => false,
			'show_in_nav_menus' => true,
			'show_tagcloud' 	=> false,
		);
		register_taxonomy( 'supplier', 'texture', $args ); // our new 'format' taxonomy

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => 'Loại thiết kế',
			'singular_name'     => 'Loại thiết kế',
			'search_items'      => 'Tìm Loại thiết kế',
			'all_items'         => 'Tất cả Loại thiết kế',
			'edit_item'         => 'Sửa Loại thiết kế',
			'update_item'       => 'Cập nhật Loại thiết kế',
			'add_new_item'      => 'Thêm Loại thiết kế mới',
			'new_item_name'     => 'Loại thiết kế mới',
			'menu_name'         => 'Loại thiết kế',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => ['slug'=>'thiet-ke'],
			//'rewrite'           => false,
			'public' 			=> true,
			'show_in_nav_menus' => true,
			'show_tagcloud' 	=> false,
		);
		register_taxonomy( 'design_type', 'texture', $args ); // our new 'format' taxonomy

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => 'Chất liệu',
			'singular_name'     => 'Chất liệu',
			'search_items'      => 'Tìm Chất liệu',
			'all_items'         => 'Tất cả Chất liệu',
			'edit_item'         => 'Sửa Chất liệu',
			'update_item'       => 'Cập nhật Chất liệu',
			'add_new_item'      => 'Thêm Chất liệu mới',
			'new_item_name'     => 'Chất liệu mới',
			'menu_name'         => 'Chất liệu',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [
				'slug'=>'chat-lieu',
				'with_front' => false,
			],
			//'rewrite'           => false,
			'public' 			=> true,
			'show_in_nav_menus' => true,
			'show_tagcloud' 	=> false,
		);
		register_taxonomy( 'material', 'texture', $args ); // our new 'format' taxonomy

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => 'Loại sản phẩm',
			'singular_name'     => 'Loại sản phẩm',
			'search_items'      => 'Tìm Loại sản phẩm',
			'all_items'         => 'Tất cả Loại sản phẩm',
			'edit_item'         => 'Sửa Loại sản phẩm',
			'update_item'       => 'Cập nhật Loại sản phẩm',
			'add_new_item'      => 'Thêm Loại sản phẩm mới',
			'new_item_name'     => 'Loại sản phẩm mới',
			'menu_name'         => 'Loại sản phẩm',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => ['slug'=>'san-pham'],
			//'rewrite'           => true,
			'public' 			=> true,
			'show_in_nav_menus' => true,
			'show_tagcloud' 	=> false,
		);
		register_taxonomy( 'texture_product', 'texture', $args ); // our new 'format' taxonomy

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => 'Phân khúc',
			'singular_name'     => 'Phân khúc',
			'search_items'      => 'Tìm Phân khúc',
			'all_items'         => 'Tất cả Phân khúc',
			'edit_item'         => 'Sửa Phân khúc',
			'update_item'       => 'Cập nhật Phân khúc',
			'add_new_item'      => 'Thêm Phân khúc mới',
			'new_item_name'     => 'Phân khúc mới',
			'menu_name'         => 'Phân khúc',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => ['slug'=>'phan-khuc'],
			//'rewrite'           => false,
			'public' 			=> true,
			'show_in_nav_menus' => true,
			'show_tagcloud' 	=> false,
		);
		register_taxonomy( 'segment', 'texture', $args ); // our new 'format' taxonomy

	}

	public static function _theme_action_change_default() {
		global $wp_post_types, $wp_taxonomies;
		
		// Someone has changed this post type, always check for that!
		if( isset($wp_post_types['post']) ) {
			// $wp_post_types['post']->label = 'Vật liệu';
			// $wp_post_types['post']->labels->name               = 'Vật liệu';
			// $wp_post_types['post']->labels->singular_name      = 'Vật liệu';
			// $wp_post_types['post']->labels->add_new            = 'Thêm Vật liệu';
			// $wp_post_types['post']->labels->add_new_item       = 'Thêm mới Vật liệu';
			// $wp_post_types['post']->labels->all_items          = 'Tất cả Vật liệu';
			// $wp_post_types['post']->labels->edit_item          = 'Chỉnh sửa Vật liệu';
			// $wp_post_types['post']->labels->name_admin_bar     = 'Vật liệu';
			// $wp_post_types['post']->labels->menu_name          = 'Vật liệu';
			// $wp_post_types['post']->labels->new_item           = 'Vật liệu mới';
			// $wp_post_types['post']->labels->not_found          = 'Không có Vật liệu nào';
			// $wp_post_types['post']->labels->not_found_in_trash = 'Không có Vật liệu nào';
			// $wp_post_types['post']->labels->search_items       = 'Tìm Vật liệu';
			// $wp_post_types['post']->labels->view_item          = 'Xem Vật liệu';

			//debug_log($wp_post_types['post']);
		}

		if( isset($wp_taxonomies['category']) ) {
			// $wp_taxonomies['category']->label = 'Vị trí sử dụng';
			// $wp_taxonomies['category']->labels->name = 'Vị trí sử dụng';
			// $wp_taxonomies['category']->labels->singular_name = 'Vị trí sử dụng';
			// $wp_taxonomies['category']->labels->add_new = 'Thêm vị trí sử dụng';
			// $wp_taxonomies['category']->labels->add_new_item = 'Thêm vị trí sử dụng';
			// $wp_taxonomies['category']->labels->edit_item = 'Sửa vị trí sử dụng';
			// $wp_taxonomies['category']->labels->new_item = 'Vị trí sử dụng';
			// $wp_taxonomies['category']->labels->view_item = 'Xem vị trí sử dụng';
			// $wp_taxonomies['category']->labels->search_items = 'Tìm vị trí sử dụng';
			// $wp_taxonomies['category']->labels->not_found = 'Không có vị trí sử dụng nào được tìm thấy';
			// $wp_taxonomies['category']->labels->not_found_in_trash = 'Không có vị trí sử dụng nào trong thùng rác';
			// $wp_taxonomies['category']->labels->all_items = 'Tất cả vị trí sử dụng';
			// $wp_taxonomies['category']->labels->menu_name = 'Vị trí sử dụng';
			// $wp_taxonomies['category']->labels->name_admin_bar = 'Vị trí sử dụng';
			
			// $wp_taxonomies['category']->public = false;
			// $wp_taxonomies['category']->show_ui = false;
			// $wp_taxonomies['category']->show_in_nav_menus = false;
			// $wp_taxonomies['category']->rewrite = false;
		}

		if( isset($wp_taxonomies['post_tag']) ) {
			// $wp_taxonomies['post_tag']->public = false;
			// $wp_taxonomies['post_tag']->show_ui = false;
			// $wp_taxonomies['post_tag']->show_in_nav_menus = false;
			// $wp_taxonomies['post_tag']->rewrite = false;
		}
	}

	public static function _admin_action_rename_post_menu() {
		global $menu, $submenu;

		//debug_log($submenu);

		remove_menu_page( 'edit-comments.php' ); // ẩn menu Comments
		remove_menu_page( 'edit.php' ); // ẩn menu Blog posts
		remove_menu_page( 'fw-extensions' ); // ẩn menu Unyson
		remove_menu_page( 'separator1' );

		add_menu_page( 'Nhà cung cấp', 'Nhà cung cấp', 'manage_categories', 'edit-tags.php?taxonomy=supplier', null, 'dashicons-businessperson', 4 );
	}

	public static function list_suppliers($name, $category) {
		if($category && $category->taxonomy=='supplier') {
			$name = $category->description.' ( '.$category->name.' )';
		}

		return $name;
	}

	public static function admin_menu_highlight($parent_file) {
		global $pagenow, $taxonomy;

		//debug_log($taxonomy);

		if(($pagenow=='edit-tags.php' || $pagenow=='term.php') && $taxonomy=='supplier') {
			$parent_file = 'edit-tags.php?taxonomy=supplier';
		}

		// if ( $pagenow == 'post.php')
		// 	$parent_file = "post.php?post={$_REQUEST['post']}&action=edit";
		// elseif($pagenow == 'post-new.php')
		// 	$parent_file = "post-new.php?post_type={$_REQUEST['post_type']}";

		return $parent_file;
	}

	public static function _setup_loop_custom_type($post) {
		global $post, $texture;

		//$texture = new \HomeViet\Texture($post);
		$texture = \HomeViet\Texture::get_instance($post->ID);

	}

	public static function query_custom_postype($wp_query) {
		if(!is_admin() && $wp_query->is_main_query() ) {
			//$wp_query->set('post_type','texture');
			
			if(is_tax('design_type')) {
				$meta_query = [];

				if(is_user_logged_in()) {
					$user = wp_get_current_user();

					// $wp_query->set('meta_key','rating_'.$user->ID);
					// $wp_query->set('orderby','meta_value_num');
					// $wp_query->set('order','DESC');

					$meta_query = [
						'relation' => 'OR',
						'rating_1' => [
							'key' => 'rating_'.$user->ID,
							//'value' => 0, 
							'compare' => 'EXISTS',
							'type' => 'SIGNED'
						],
						'rating_2' => [
							'key' => 'rating_'.$user->ID,
							//'value' => '', 
							'compare' => 'NOT EXISTS',
							'type' => 'SIGNED'
						],
					];

				} else {
					$meta_query = [
						'relation' => 'OR',
						'rating_1' => [
							'key' => 'average_rating',
							//'value' => 0, 
							'compare' => 'EXISTS',
							'type' => 'SIGNED'
						],
						'rating_2' => [
							'key' => 'average_rating',
							//'value' => '', 
							'compare' => 'NOT EXISTS',
							'type' => 'SIGNED'
						],
					];
				}

				$tax_query = [];

				if(isset($_GET['seg'])) {
					$tax_query['segment'] = [
						'taxonomy' => 'segment',
						'field' => 'term_id',
						'terms' => [absint($_GET['seg'])]
					];
				}

				if(isset($_GET['mat'])) {
					$tax_query['material'] = [
						'taxonomy' => 'material',
						'field' => 'term_id',
						'terms' => [absint($_GET['mat'])]
					];
				}

				if(isset($_GET['pro'])) {
					$tax_query['texture_product'] = [
						'taxonomy' => 'texture_product',
						'field' => 'term_id',
						'terms' => [absint($_GET['pro'])]
					];
				}

				if(isset($_GET['sup'])) {
					$tax_query['supplier'] = [
						'taxonomy' => 'supplier',
						'field' => 'term_id',
						'terms' => [absint($_GET['sup'])]
					];
				}

				//debug_log($tax_query);
				if(!empty($meta_query)) {
					$wp_query->set('meta_query', $meta_query);
					$wp_query->set('orderby', ['rating_2'=>'DESC', 'date'=>'DESC']);
					//$wp_query->set('order', 'DESC');
				}

				if(!empty($tax_query)) {
					$wp_query->set('tax_query', $tax_query);
				}
				//debug_log($wp_query);
			} elseif (is_search()) {
				//debug_log($wp_query);
				$wp_query->set('post_type', 'texture');
			}
		}
	}

	public static function _setup_term_default_sort($pieces, $taxonomies, $args) {
		
		if(isset($taxonomies[0]) && 'texture_price' == $taxonomies[0] ) {

			$orderby = isset($_REQUEST['orderby']) ? trim(wp_unslash($_REQUEST['orderby'])) : 'name';
			$order   = isset($_REQUEST['order'])   ? trim(wp_unslash($_REQUEST['order']))   : 'ASC';

			if($orderby=='name') {
				$pieces['orderby'] = "ORDER BY name+0";
			}

			$pieces['order']   = $order;
		}

		return $pieces;
	}
}