<?php
namespace EIAB;

/**
 * Plugin settings class
 */
class Settings {

	private $key      = 'eiab_settings';
	private $settings = null;
	private $defaults = array(
		'enabled'                => true,
		'manualMode'             => false,
		'installationScript'     => '',
		'statementLink'          => '',
		'feedbackLink'           => '',
		'footerHtml'             => '',
		'hideMobile'             => false,
		'hideTrigger'            => false,
		'language'               => 'en',
		'position'               => 'left',
		'leadColor'              => '#146ff8',
		'triggerColor'           => '#146ff8',
		'triggerRadius'          => 50,
		'triggerPositionX'       => 'left',
		'triggerPositionY'       => 'bottom',
		'triggerIcon'            => 'default',
		'triggerSize'            => 'medium',
		'triggerOffsetX'         => 20,
		'triggerOffsetY'         => 20,
		'mobileTriggerSize'      => 'small',
		'mobileTriggerPositionX' => 'left',
		'mobileTriggerPositionY' => 'center',
		'mobileTriggerOffsetX'   => 0,
		'mobileTriggerOffsetY'   => 0,
		'mobileTriggerRadius'    => 0,
	);

	/**
	 * Get plugin settings
	 *
	 * @return array
	 */
	public function get( $setting = null ) {

		if ( null === $this->settings ) {
			$this->settings = get_option( $this->key, array() );

			if ( empty( $this->settings ) ) {
				$this->settings = $this->defaults;
			}

		}

		if ( ! $setting ) {
			return $this->settings;
		}

		$default = ! empty( $this->defaults[ $setting ] ) ? $this->defaults[ $setting ] : false;

		return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

	}

	/**
	 * Update setting
	 *
	 * @param  [type] $setting [description]
	 * @param  [type] $value   [description]
	 * @param  [type] $force   [description]
	 * @return [type]          [description]
	 */
	public function update( $setting = '', $value = null, $force = true ) {

		$this->get();
		$this->settings[ $setting ] = $value;

		if ( $force ) {
			$this->save();
		}

	}

	/**
	 * Update settings in the DB
	 *
	 * @return [type] [description]
	 */
	public function save() {
		$this->get();
		update_option( $this->key, $this->settings, true );
	}

	/**
	 * Update all settings from array
	 *
	 * @param  array  $settings [description]
	 * @return [type]           [description]
	 */
	public function update_all( $settings = array() ) {

		foreach ( $this->defaults as $key => $default_value ) {
			$value = isset( $settings[ $key ] ) ? $this->sanitize_val( $settings[ $key ], $key ) : $default_value;
			$this->update( $key, $value, false );
		}

		$this->save();

	}

	/**
	 * Returns allowed settings list
	 *
	 * @return [type] [description]
	 */
	public function settings_list() {
		return array_keys( $this->defaults );
	}

	/**
	 * Sanitize key
	 *
	 * @return [type] [description]
	 */
	public function sanitize_val( $value, $key ) {

		switch ( $key ) {

			case 'enabled':
			case 'hideMobile':
			case 'hideTrigger':
			case 'manualMode':
				$value = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
				break;

			case 'triggerRadius':
			case 'triggerOffsetX':
			case 'triggerOffsetY':
			case 'mobileTriggerOffsetX':
			case 'mobileTriggerOffsetY':
			case 'mobileTriggerRadius':
				$value = absint( $value );
				break;

			case 'installationScript':
				$value = wp_unslash( $this->remove_untrusted_code( $value ) );
				break;

			case 'triggerColor':
				$value = sanitize_hex_color( $value );
				break;

			default:
				$value = wp_kses_post( $value );
				break;
		}

		return $value;

	}

	/**
	 * Returns an empty string if untrusted code is found in the string
	 *
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function remove_untrusted_code( $value ) {

		$untrusted_pieces = array(
			'alert',
			'eval',
			'cookie',
		);

		foreach ( $untrusted_pieces as $piece ) {
			if ( false !== strpos( $value, $piece ) ) {
				return '';
			}
		}

		return $value;

	}

}
