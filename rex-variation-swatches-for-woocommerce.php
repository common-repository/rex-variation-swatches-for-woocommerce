<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rextheme.com
 * @since             1.0.0
 * @package           Variation_Swatches_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Variation Swatches for WooCommerce
 * Plugin URI:        https://rextheme.com/variation-swatches-for-woocommerce/
 * Description:       View your variable products with more attractive look
 * Version:           1.4.6
 * Author:            RexTheme
 * Author URI:        https://rextheme.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rexvs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VARIATION_SWATCHES_FOR_WOOCOMMERCE_VERSION', '1.4.6' );
define( "REXVS_PLUGIN_DIR_URL", plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-variation-swatches-for-woocommerce-activator.php
 */
function activate_variation_swatches_for_woocommerce()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rex-variation-swatches-for-woocommerce-activator.php';
	Variation_Swatches_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-variation-swatches-for-woocommerce-deactivator.php
 */
function deactivate_variation_swatches_for_woocommerce()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rex-variation-swatches-for-woocommerce-deactivator.php';
	Variation_Swatches_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_variation_swatches_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_variation_swatches_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rex-variation-swatches-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_variation_swatches_for_woocommerce()
{

	require_once plugin_dir_path( __FILE__ ) . 'admin/class-rexvs-dependency-check.php';
	$plugin = new Variation_Swatches_For_Woocommerce();
	$plugin->run();
	new Rexvs_Dependency_Check( 'woocommerce/woocommerce.php', __FILE__, '4.0.0', 'rexvs' );
}

run_variation_swatches_for_woocommerce();

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_rex_variation_swatches_for_woocommerce() {
	if ( !class_exists( 'Appsero\Client' ) ) {
		require_once __DIR__ . '/appsero/src/Client.php';
	}

	$client = new Appsero\Client( '98ddab8b-9ad5-47b2-974a-27b51d3f3ae4', 'Variation Swatches for WooCommerce', __FILE__ );

	// Active insights
	$client->insights()->init();
}

appsero_init_tracker_rex_variation_swatches_for_woocommerce();

/**
 * woocommerce_before_variations_form
 *
 */
function action_woocommerce_before_variations_form()
{
	echo '<div class="rexvs-variations">
			<p class="variation_notice" style="display:none;">No variation available</p>
		';
}

add_action( 'woocommerce_before_variations_form', 'action_woocommerce_before_variations_form', 10, 0 );


/**
 * woocommerce_after_variations_form
 *
 */
function action_woocommerce_after_variations_form()
{
	echo '</div>';
}

add_action( 'woocommerce_after_variations_form', 'action_woocommerce_after_variations_form', 10, 0 );
