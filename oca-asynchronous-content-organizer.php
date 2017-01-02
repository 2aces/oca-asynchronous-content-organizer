<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://2aces.com.br
 * @since             1.0.0
 * @package           Oca_Asynchronous_Content_Organizer
 *
 * @wordpress-plugin
 * Plugin Name:       OCA - Asynchronous Content Organizer
 * Plugin URI:        https://2aces.com.br/plugins/oca/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            2Aces Conteúdo
 * Author URI:        https://2aces.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oca-asynchronous-content-organizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-oca-asynchronous-content-organizer-activator.php
 */
function activate_oca_asynchronous_content_organizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oca-asynchronous-content-organizer-activator.php';
	Oca_Asynchronous_Content_Organizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-oca-asynchronous-content-organizer-deactivator.php
 */
function deactivate_oca_asynchronous_content_organizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oca-asynchronous-content-organizer-deactivator.php';
	Oca_Asynchronous_Content_Organizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oca_asynchronous_content_organizer' );
register_deactivation_hook( __FILE__, 'deactivate_oca_asynchronous_content_organizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-oca-asynchronous-content-organizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_oca_asynchronous_content_organizer() {

	$plugin = new Oca_Asynchronous_Content_Organizer();
	$plugin->run();

}
run_oca_asynchronous_content_organizer();
