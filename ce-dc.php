<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://codended.com
 * @since             1.0.0
 * @package           Ce_Dc
 *
 * @wordpress-plugin
 * Plugin Name:       Distance Calculator
 * Plugin URI:        http://codended.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Muhammad Junaid
 * Author URI:        http://codended.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ce-dc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CE_DC_VERSION', '1.0.0' );
define( 'CE_DC_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ce-dc-activator.php
 */
function activate_ce_dc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ce-dc-activator.php';
	Ce_Dc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ce-dc-deactivator.php
 */
function deactivate_ce_dc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ce-dc-deactivator.php';
	Ce_Dc_Deactivator::deactivate();
}

//register_activation_hook( __FILE__, 'activate_ce_dc' );
//register_deactivation_hook( __FILE__, 'deactivate_ce_dc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ce-dc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ce_dc() {

	$plugin = new Ce_Dc();
	$plugin->run();

}
run_ce_dc();
