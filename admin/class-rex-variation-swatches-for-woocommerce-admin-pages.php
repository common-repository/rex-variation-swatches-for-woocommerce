<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/admin
 */

class REX_REX_Variation_Swatches_For_Woocommerce_Admin_Pages {

	/**
	 * Admin page setup is specified in this area.
	 */
	function rexvs_add_admin_pages() {

		add_menu_page( 'Swatches', 'Swatches', 'manage_options', 'rexvs', array( $this, 'rexvs_admin_form'),REXVS_PLUGIN_DIR_URL. '/images/icon.png' , 50);

		add_submenu_page( 'rexvs', 'Swatches', 'Swatches','manage_options', 'rexvs', array( $this, 'rexvs_admin_form'));
		
		// add_submenu_page( 'woocommerce', 'Swatches', 'Swatches','manage_options', 'rexvs', array( $this, 'rexvs_admin_form'));
	}

	function rexvs_admin_form()
	{
		if ( apply_filters( 'is_rexvs_premium', false ) ) {
			require_once ABSPATH . 'wp-content/plugins/rexvs-pro/admin/partials/rexvs_pro_settings.php';
		}else {
			require_once plugin_dir_path( __FILE__ ) . '/partials/rexvs_settings.php';
		}
	}
}
