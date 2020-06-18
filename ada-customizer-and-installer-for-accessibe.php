<?php
/**
 * Plugin Name: ADA customizer and installer for accessiBe
 * Plugin URI:
 * Description: The plugin helps to install and customize accessiBe — a web accessibility solution for ADA & WCAG compliance. After a user-friendly installation process, you will find accessiBe customization panel inside the WordPress dashboard. With its help, you can easily adjust accessiBe's interface — colors, position, font size, trigger icons etc. Before, you needed to make the customization process out of WordPress, inside the accessiBe solution. The plugin makes it possible to work with accessiBe and your website settings in one place — your WordPress dashboard.
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * Text Domain: ada-customizer-and-installer-for-accessibe
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'plugins_loaded', 'eaib_init' );

function eaib_init() {

	define( 'EIAB_VERSION', '1.0.0' );

	define( 'EIAB__FILE__', __FILE__ );
	define( 'EIAB_PLUGIN_BASE', plugin_basename( EIAB__FILE__ ) );
	define( 'EIAB_PATH', plugin_dir_path( EIAB__FILE__ ) );
	define( 'EIAB_URL', plugins_url( '/', EIAB__FILE__ ) );

	require EIAB_PATH . 'includes/plugin.php';

}
