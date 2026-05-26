<?php
/**
 * Redux Repeater Extension Class
 *
 * @package Redux
 * @author  Dovy Paukstys & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extenzeyna_Repeater
 *
 * @version 4.3.13
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extenzeyna_Repeater' ) ) {


	/**
	 * Class Redux_Extenzeyna_Repeater
	 */
	class Redux_Extenzeyna_Repeater extends Redux_Extenzeyna_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.3.13';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extenzeyna_name = 'Repeater';

		/**
		 * Class Constructor. Defines the args for the extensions class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       object $redux Parent settings.
		 *
		 * @return      void
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'repeater' );
		}
	}
}

class_alias( 'Redux_Extenzeyna_Repeater', 'ReduxFramework_Extenzeyna_repeater' );
