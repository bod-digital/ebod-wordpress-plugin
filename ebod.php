<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://bod.digital
 * @since             1.0.0
 * @package           Ebod
 *
 * @wordpress-plugin
 * Plugin Name:       EBOD Tracking
 * Plugin URI:        https://github.com/bod-digital/ebod-wordpress-plugin
 * Description:       EBOD Tracking for WooCommerce orders.
 * Version:           1.1.0
 * Author:            ebodbot
 * Author URI:        https://bod.digital
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eboddigital
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.2 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EBOD_VERSION', '1.2.0' );
define( 'EBOD_API_VERSION', 'v1' );
define( 'EBOD_TOKEN_VALIDITY_DAYS', '180' );
define( 'EBOD_BASE_URL', 'https://e.bod.digital' );

/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'ebod_is_woocommerce_activated' ) ) {
    function ebod_is_woocommerce_activated() {
        //if ( class_exists( 'woocommerce' ) ) { 
        if( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')) ) ) {
			return true; 
		} else { 
			return false; 
		}
    }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ebod-activator.php
 */
function ebod_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ebod-activator.php';
	Ebod_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ebod-deactivator.php
 */
function ebod_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ebod-deactivator.php';
	Ebod_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ebod_activate' );
register_deactivation_hook( __FILE__, 'ebod_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ebod.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function ebod_run() {
	$plugin = new Ebod();
	$plugin->run();
}
ebod_run();
