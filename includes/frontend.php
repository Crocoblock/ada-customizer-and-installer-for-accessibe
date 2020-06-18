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
			echo $settings['installationScript'];
			return;
		}

		$config = array(
			'statementLink'    => $settings['statementLink'],
			'feedbackLink'     => $settings['feedbackLink'],
			'footerHtml'       => $settings['footerHtml'],
			'hideMobile'       => $settings['hideMobile'],
			'hideTrigger'      => $settings['hideTrigger'],
			'language'         => $settings['language'],
			'position'         => $settings['position'],
			'leadColor'        => $settings['leadColor'],
			'triggerColor'     => $settings['triggerColor'],
			'triggerRadius'    => $this->number_to_percent( $settings['triggerRadius'] ),
			'triggerPositionX' => $settings['triggerPositionX'],
			'triggerPositionY' => $settings['triggerPositionY'],
			'triggerIcon'      => $settings['triggerIcon'],
			'triggerSize'      => $settings['triggerSize'],
			'triggerOffsetX'   => $settings['triggerOffsetX'],
			'triggerOffsetY'   => $settings['triggerOffsetY'],
			'mobile'           => array(
				'triggerSize'      => $settings['mobileTriggerSize'],
				'triggerPositionX' => $settings['mobileTriggerPositionX'],
				'triggerPositionY' => $settings['mobileTriggerPositionY'],
				'triggerOffsetX'   => $settings['mobileTriggerOffsetX'],
				'triggerOffsetY'   => $settings['mobileTriggerOffsetY'],
				'triggerRadius'    => $this->number_to_percent( $settings['mobileTriggerRadius'] ),
			),
		);

		echo "<script>window.acsbJSConfig = " . json_encode( $config ) . "</script>";
		echo "<script>(function(document, tag) { var script = document.createElement(tag); var element = document.getElementsByTagName('body')[0]; script.src = 'https://acsbap.com/apps/app/assets/js/acsb.js'; script.async = true; script.defer = true; (typeof element === 'undefined' ? document.getElementsByTagName('html')[0] : element).appendChild(script); script.onload = function() { acsbJS.init(window.acsbJSConfig); };}(document, 'script'));</script>";

	}

}
