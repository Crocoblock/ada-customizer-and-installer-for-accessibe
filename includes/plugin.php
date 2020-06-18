<?php
namespace EIAB;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main file
 */
class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;

	}

	/**
	 * Initialize plugin parts
	 *
	 * @return void
	 */
	public function init_components() {

		require EIAB_PATH . 'includes/settings.php';

		$settings = new Settings();

		if ( is_admin() ) {
			require EIAB_PATH . 'includes/admin.php';
			new Admin( $settings );
		} else {
			require EIAB_PATH . 'includes/frontend.php';
			new Frontend( $settings );
		}

	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		add_action( 'after_setup_theme', array( $this, 'init_components' ), 0 );
	}

}

Plugin::instance();
