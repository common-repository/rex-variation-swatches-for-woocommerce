<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/includes
 * @author     RexTheme <sakib@coderex.co>
 */
class Variation_Swatches_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Variation_Swatches_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	protected static $instance = null;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Extra attribute types
	 *
	 * @var array
	 */
	public $types = array();

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'VARIATION_SWATCHES_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = VARIATION_SWATCHES_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'rex-variation-swatches-for-woocommerce';
		$this->types = array(
			'color' => esc_html__( 'Color', 'rexvs' ),
			'image' => esc_html__( 'Image', 'rexvs' ),
			'label' => esc_html__( 'Label', 'rexvs' ),
		);
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Variation_Swatches_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Variation_Swatches_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - REX_Variation_Swatches_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - REX_Variation_Swatches_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rex-variation-swatches-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rex-variation-swatches-for-woocommerce-i18n.php';

		/**
		* The class responsible for defining all actions that occur in the product area.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rex-variation-swatches-for-woocommerce-admin-product.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rex-variation-swatches-for-woocommerce-admin.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area pages.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rex-variation-swatches-for-woocommerce-admin-pages.php';

		/**
		 * The class responsible for defining all JQuery Ajax.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rex-variation-swatches-for-woocommerce-admin-ajax.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rex-variation-swatches-for-woocommerce-public.php';




		$this->loader = new Variation_Swatches_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Variation_Swatches_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Variation_Swatches_For_Woocommerce_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new REX_Variation_Swatches_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_page = new REX_REX_Variation_Swatches_For_Woocommerce_Admin_Pages();
		$plugin_admin_ajax = new REX_REX_Variation_Swatches_For_Woocommerce_Admin_Ajax();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin_page, 'rexvs_add_admin_pages' );
		$this->loader->add_action( 'wp_ajax_rexvs_settings_submit', $plugin_admin_ajax, 'rexvs_settings_submit' );

		$this->loader->add_action( 'wp_ajax_rexvs_custom_attr_submit', $plugin_admin_ajax, 'rexvs_custom_attr_submit' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'init_attribute_hooks');
		$this->loader->add_action( 'rexvs_product_attribute_field', $plugin_admin, 'attribute_fields', 10, 3 );
		$this->loader->add_filter( 'product_attributes_type_selector', $this, 'add_attribute_types');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$is_premium = get_option( 'rexvs_is_premium' ) ? get_option( 'rexvs_is_premium' ) : '';

		$plugin_public = new REX_Variation_Swatches_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_dropdown_variation_attribute_options_html', $plugin_public, 'get_swatch_html', 100, 2 );
		$this->loader->add_action( 'rexvs_swatch_html', $plugin_public, 'swatch_html', 5, 4 );

		if ($is_premium === 'yes') {
			$this->loader->add_action( 'rexvs_custom_swatch_html', $plugin_public, 'custom_swatch_html', 6, 4 );
		}

		$this->loader->add_filter( 'product_attributes_type_selector', $this, 'add_attribute_types');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Variation_Swatches_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve attribute type.
	 *
	 * @since     1.0.0
	 * @return    array    attribute types.
	 */
	public function add_attribute_types( $types ) {
		$types = array_merge( $types, $this->types );
		return $types;
	}

	public function get_tax_attribute( $taxonomy ) {

		global $wpdb;
		$attr = substr( $taxonomy, 3 );
		$attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attr'" );
		return $attr;
	}
}

if ( ! function_exists( 'rex_variation_master' ) ) {
	/**
	 * Main instance of plugin
	 *
	 * @return Variation_Swatches_For_Woocommerce
	 */
	function rex_variation_master() {
		return Variation_Swatches_For_Woocommerce::instance();
	}
}
