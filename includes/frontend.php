<?php
namespace EIAB;

/**
 * Front-end related actions
 */
class Frontend {

	private $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
		if ( $this->settings->get( 'enabled' ) ) {
			add_action( 'wp_footer', array( $this, 'init_script' ), 999 );
		}
	}

	/**
	 * Add percent sign to number if value greter than 0
	 *
	 * @param  int $value [description]
	 * @return [type]        [description]
	 */
	public function number_to_percent( $value ) {
		$value = absint( $value );
		return ( 0 < $value ) ? $value . '%' : $value;
	}

	/**
	 * Initialize AccessiBe script
	 *
	 * @return [type] [description]
	 */
	public function init_script() {

		$settings = $this->settings->get();

		if ( ! empty( $settings['manualMode'] ) && ! empty( $settings['installationScript'] ) ) {
			echo $this->settings->remove_untrusted_code( $settings['installationScript'] );
			return;
		}

		$config = array(
			'statementLink'    => $this->settings->sanitize_val( $settings['statementLink'], 'statementLink' ),
			'feedbackLink'     => $this->settings->sanitize_val( $settings['feedbackLink'], 'feedbackLink' ),
			'footerHtml'       => $this->settings->sanitize_val( $settings['footerHtml'], 'footerHtml' ),
			'hideMobile'       => $this->settings->sanitize_val( $settings['hideMobile'], 'hideMobile' ),
			'hideTrigger'      => $this->settings->sanitize_val( $settings['hideTrigger'], 'hideTrigger' ),
			'language'         => $this->settings->sanitize_val( $settings['language'], 'language' ),
			'position'         => $this->settings->sanitize_val( $settings['position'], 'position' ),
			'leadColor'        => $this->settings->sanitize_val( $settings['leadColor'], 'leadColor' ),
			'triggerColor'     => $this->settings->sanitize_val( $settings['triggerColor'], 'triggerColor' ),
			'triggerRadius'    => $this->number_to_percent( $settings['triggerRadius'] ),
			'triggerPositionX' => $this->settings->sanitize_val( $settings['triggerPositionX'], 'triggerPositionX' ),
			'triggerPositionY' => $this->settings->sanitize_val( $settings['triggerPositionY'], 'triggerPositionY' ),
			'triggerIcon'      => $this->settings->sanitize_val( $settings['triggerIcon'], 'triggerIcon' ),
			'triggerSize'      => $this->settings->sanitize_val( $settings['triggerSize'], 'triggerSize' ),
			'triggerOffsetX'   => $this->settings->sanitize_val( $settings['triggerOffsetX'], 'triggerOffsetX' ),
			'triggerOffsetY'   => $this->settings->sanitize_val( $settings['triggerOffsetY'], 'triggerOffsetY' ),
			'mobile'           => array(
				'triggerSize'      => $this->settings->sanitize_val( $settings['mobileTriggerSize'], 'mobileTriggerSize' ),
				'triggerPositionX' => $this->settings->sanitize_val( $settings['mobileTriggerPositionX'], 'mobileTriggerPositionX' ),
				'triggerPositionY' => $this->settings->sanitize_val( $settings['mobileTriggerPositionY'], 'mobileTriggerPositionY' ),
				'triggerOffsetX'   => $this->settings->sanitize_val( $settings['mobileTriggerOffsetX'], 'mobileTriggerOffsetX' ),
				'triggerOffsetY'   => $this->settings->sanitize_val( $settings['mobileTriggerOffsetY'], 'mobileTriggerOffsetY' ),
				'triggerRadius'    => $this->number_to_percent( $settings['mobileTriggerRadius'] ),
			),
		);

		echo "<script>window.acsbJSConfig = " . json_encode( $config ) . "</script>";
		echo "<script>(function(document, tag) { var script = document.createElement(tag); var element = document.getElementsByTagName('body')[0]; script.src = 'https://acsbap.com/apps/app/assets/js/acsb.js'; script.async = true; script.defer = true; (typeof element === 'undefined' ? document.getElementsByTagName('html')[0] : element).appendChild(script); script.onload = function() { acsbJS.init(window.acsbJSConfig); };}(document, 'script'));</script>";

	}

}
