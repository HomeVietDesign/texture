<?php
namespace HomeViet\Admin;

class Main {
	use \HomeViet\Singleton;

	protected function __construct() {
		$this->includes();
		$this->hooks_page();
		$this->hooks_texture();
		$this->hooks_design_type();
		$this->hooks_material();
		$this->hooks_texture_product();
		$this->hooks_segment();
		$this->hooks_supplier();
		$this->hooks_texture_upload();
	}

	private function hooks_texture_upload() {
		if(is_admin()) {
			add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Texture_Upload', 'enqueue_scripts'] );
			add_action( 'admin_menu', ['\HomeViet\Admin\Texture_Upload', 'admin_menu' ] );
		}

		add_action('wp_ajax_admin_texture_upload', ['\HomeViet\Admin\Texture_Upload', 'ajax_admin_texture_upload' ] );
	}
	
	private function hooks_supplier() {
		add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Supplier', 'enqueue_scripts'] );
		//add_action( 'created_supplier', ['\HomeViet\Admin\Supplier', 'auto_slug'] );
		add_action( 'manage_edit-supplier_columns', ['\HomeViet\Admin\Supplier', 'manage_edit_column_header'] );
		add_action( 'manage_supplier_custom_column', ['\HomeViet\Admin\Supplier', 'manage_edit_columns_value'], 15, 3 );

		add_filter( 'wp_terms_checklist_args', ['\HomeViet\Admin\Supplier', 'change_check_list'], 10, 1 );
		add_filter( 'post_column_taxonomy_links', ['\HomeViet\Admin\Supplier', 'change_columns_links'], 10, 3 );
	}

	private function hooks_segment() {
		add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Segment', 'enqueue_scripts'] );
		//add_action( 'created_segment', ['\HomeViet\Admin\Segment', 'auto_slug'] );
		add_action( 'manage_edit-segment_columns', ['\HomeViet\Admin\Segment', 'manage_edit_column_header'] );
		add_action( 'manage_segment_custom_column', ['\HomeViet\Admin\Segment', 'manage_edit_columns_value'], 15, 3 );
	}

	private function hooks_texture_product() {
		add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Texture_Product', 'enqueue_scripts'] );
		//add_action( 'created_texture_product', ['\HomeViet\Admin\Texture_Product', 'auto_slug'] );
		add_action( 'manage_edit-texture_product_columns', ['\HomeViet\Admin\Texture_Product', 'manage_edit_column_header'] );
		add_action( 'manage_texture_product_custom_column', ['\HomeViet\Admin\Texture_Product', 'manage_edit_columns_value'], 15, 3 );
	}

	private function hooks_material() {
		add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Material', 'enqueue_scripts'] );
		//add_action( 'created_material', ['\HomeViet\Admin\Material', 'auto_slug'] );
		add_action( 'manage_edit-material_columns', ['\HomeViet\Admin\Material', 'manage_edit_column_header'] );
		add_action( 'manage_material_custom_column', ['\HomeViet\Admin\Material', 'manage_edit_columns_value'], 15, 3 );
	}

	private function hooks_design_type() {
		add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Design_Type', 'enqueue_scripts'] );
		//add_action( 'created_design_type', ['\HomeViet\Admin\Design_Type', 'auto_slug'] );
		add_action( 'manage_edit-design_type_columns', ['\HomeViet\Admin\Design_Type', 'manage_edit_column_header'] );
		add_action( 'manage_design_type_custom_column', ['\HomeViet\Admin\Design_Type', 'manage_edit_columns_value'], 15, 3 );
	}

	private function hooks_texture() {
		add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Texture', 'enqueue_scripts'] );
		add_action( 'save_post_texture', ['\HomeViet\Admin\Texture', 'save_texture'], 15, 3 );
		add_action( 'before_delete_post', ['\HomeViet\Admin\Texture', 'delete_texture_images'], 15, 2 );

		add_filter( 'manage_texture_posts_columns', ['\HomeViet\Admin\Texture', 'custom_columns_header'] );
		add_action( 'manage_texture_posts_custom_column', ['\HomeViet\Admin\Texture', 'custom_columns_value'], 2, 2 );
	}

	private function hooks_page() {
		//add_action( 'admin_enqueue_scripts', ['\HomeViet\Admin\Page', 'enqueue_scripts'] );
		add_action( 'save_post_page', ['\HomeViet\Admin\Page', 'save_page'], 15, 3 );
	}

	private function includes() {
		require_once THEME_DIR.'/inc/admin/class-walker-supplier-checklist.php';
		//require_once THEME_DIR.'/inc/admin/class-walker-supplier-dropdown.php';

		include_once THEME_DIR.'/inc/admin/class-admin-page.php';
		include_once THEME_DIR.'/inc/admin/class-admin-texture.php';
		include_once THEME_DIR.'/inc/admin/class-admin-design_type.php';
		include_once THEME_DIR.'/inc/admin/class-admin-material.php';
		include_once THEME_DIR.'/inc/admin/class-admin-texture_product.php';
		include_once THEME_DIR.'/inc/admin/class-admin-segment.php';
		include_once THEME_DIR.'/inc/admin/class-admin-supplier.php';

		require_once THEME_DIR.'/inc/admin/class-admin-texture-upload.php';
		
	}

}
Main::get_instance();