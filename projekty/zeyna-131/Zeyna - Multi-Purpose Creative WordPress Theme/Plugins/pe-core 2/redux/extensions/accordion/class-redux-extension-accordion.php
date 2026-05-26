<?php
/**
 * Redux Accordion Extension Class
 *
 * @package Redux
 * @author Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extenzeyna_Accordion
 * @version 4.3.16
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extenzeyna_Accordion' ) ) {

	/**
	 * Main ReduxFramework_Extenzeyna_Accordion extension class
	 *
	 * @since       1.0.0
	 */
	class Redux_Extenzeyna_Accordion extends Redux_Extenzeyna_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.3.16';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extenzeyna_name = 'Accordion';

		/**
		 * Class Constructor. Defines the args for the extension class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       ReduxFramework $redux Parent settings.
		 *
		 * @return      void
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'accordion' );
		}
	}
}
