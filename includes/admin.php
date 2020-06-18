<?php
namespace EIAB;

/**
 * Admin related actions
 */
class Admin {

	private $page_title;
	private $menu_title;
	private $capability;
	private $menu_slug;

	private $settings;

	public function __construct( $settings ) {

		$this->settings = $settings;

		$this->page_title = __( 'AccessiBe Installation Settings', 'ada-customizer-and-installer-for-accessibe' );
		$this->menu_title = __( 'AccessiBe', 'ada-customizer-and-installer-for-accessibe' );
		$this->capability = 'manage_options';
		$this->menu_slug  = 'accessibe-integration';

		add_action( 'init', array( $this, 'lang' ) );
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_' . $this->menu_slug . '-settings', array( $this, 'save_settings' ) );

		add_filter( 'plugin_action_links_' . EIAB_PLUGIN_BASE, array( $this, 'plugins_page_settings_link' ) );

	}

	public function plugins_page_settings_link( $links ) {

		$url = add_query_arg(
			array( 'page' => $this->menu_slug ),
			esc_url( admin_url( 'options-general.php' ) )
		);

		$link = sprintf(
			'<a href="%2$s">%1$s<a/>',
			__( 'Install & Customize', 'ada-customizer-and-installer-for-accessibe' ),
			$url
		);
		$links = array_merge( array( $link ), $links );
		return $links;
	}

	/**
	 * Loads the translation files.
	 */
	public function lang() {
		load_plugin_textdomain( 'ada-customizer-and-installer-for-accessibe', false, EIAB_PATH . 'languages' );
	}

	/**
	 * Save settings handler
	 *
	 * @return [type] [description]
	 */
	public function save_settings() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => 'Access denied',
				)
			);
		}

		$nonce = ! empty( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : false;

		if ( ! $nonce || ! wp_verify_nonce( $nonce, $this->menu_slug ) ) {
			wp_send_json_error(
				array(
					'message' => 'The link is expired',
				)
			);
		}

		$settings = ! empty( $_REQUEST['settings'] ) ? $_REQUEST['settings'] : false;

		if ( empty( $settings ) ) {
			wp_send_json_error(
				array(
					'message' => 'Incorrect request data',
				)
			);
		}

		$this->settings->update_all( $settings );
		wp_send_json_success();

	}

	/**
	 * Enqueuse plugin assets
	 *
	 * @return [type] [description]
	 */
	public function enqueue_assets( $hook ) {

		if ( 'settings_page_' . $this->menu_slug !== $hook ) {
			return;
		}

		wp_enqueue_script(
			$this->menu_slug,
			EIAB_URL . 'assets/js/admin.js',
			array( 'wp-util', 'wp-components', 'wp-element', 'wp-editor' ),
			EIAB_VERSION . time(),
			true
		);

		wp_localize_script( $this->menu_slug, 'EIABData', array(
			'_id'        => $this->menu_slug,
			'_nonce'     => wp_create_nonce( $this->menu_slug ),
			'settings'   => $this->settings->get(),
			'lang'       => $this->get_lang(),
			'hPositions' => $this->get_h_positions(),
			'vPositions' => $this->get_v_positions(),
			'sizes'      => $this->get_sizes(),
		) );

		wp_enqueue_style(
			$this->menu_slug,
			EIAB_URL . 'assets/css/admin.css',
			array( 'wp-components' ),
			EIAB_VERSION . time()
		);

	}

	/**
	 * Get available language options
	 *
	 * @return [type] [description]
	 */
	public function get_lang() {
		return array(
			array(
				'value' => 'en',
				'label' => 'English',
			),
			array(
				'value' => 'es',
				'label' => 'Español',
			),
			array(
				'value' => 'fr',
				'label' => 'Français',
			),
			array(
				'value' => 'de',
				'label' => 'Deutsche',
			),
			array(
				'value' => 'it',
				'label' => 'Italiano',
			),
			array(
				'value' => 'pt',
				'label' => 'Português',
			),
			array(
				'value' => 'nl',
				'label' => 'Nederlands',
			),
			array(
				'value' => 'jp',
				'label' => '日本語',
			),
			array(
				'value' => 'tw',
				'label' => '台灣',
			),
			array(
				'value' => 'ct',
				'label' => '中文',
			),
			array(
				'value' => 'he',
				'label' => 'עברית',
			),
			array(
				'value' => 'ru',
				'label' => 'Русский',
			),
			array(
				'value' => 'ar',
				'label' => 'الإمارات العربية المتحدة',
			),
			array(
				'value' => 'ar',
				'label' => 'عربى',
			),
		);
	}

	/**
	 * Get available horizontal position options
	 *
	 * @return [type] [description]
	 */
	public function get_h_positions() {
		return array(
			array(
				'value' => 'left',
				'label' => 'Left',
			),
			array(
				'value' => 'right',
				'label' => 'Right',
			),
		);
	}

	/**
	 * Get available horizontal position options
	 *
	 * @return [type] [description]
	 */
	public function get_v_positions() {
		return array(
			array(
				'value' => 'top',
				'label' => 'Top',
			),
			array(
				'value' => 'center',
				'label' => 'Center',
			),
			array(
				'value' => 'bottom',
				'label' => 'Bottom',
			),
		);
	}

	/**
	 * Get available horizontal position options
	 *
	 * @return [type] [description]
	 */
	public function get_sizes() {
		return array(
			array(
				'value' => 'small',
				'label' => 'Small',
			),
			array(
				'value' => 'medium',
				'label' => 'Medium',
			),
			array(
				'value' => 'big',
				'label' => 'Big',
			),
		);
	}

	/**
	 * Register menu page
	 * @return [type] [description]
	 */
	public function register_page() {
		add_options_page(
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render menu page
	 *
	 * @return [type] [description]
	 */
	public function render_page() {
		echo '<div class="wrap">';
		echo '<h1 class="wp-heading-inline">' . $this->page_title . '</h1>';
		echo '<div id="' . $this->menu_slug . '"></div>';
		echo '</div>';
	}

}
