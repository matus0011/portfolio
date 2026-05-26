<?php

namespace BrandBerry\TemplateLibrary\base;

use Elementor\Plugin;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

/**
 *  Template Library.
 *
 * @since 1.0
 */
class Templates_Lib {

	public static $source = null;
	
	public function register() {		
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'enqueue_editor_scripts' ] );
		add_action('elementor/preview/enqueue_styles', array(__CLASS__, 'preview_styles'));		
        add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
	}

	public static function preview_styles() {
        wp_enqueue_style( 'wcf-ready-templates-lib', esc_url( WCF_TEMPLATE_MODULE_URL . 'assets/css/editor.min.css' ), time(), true );
    }
	public static function enqueue_editor_scripts() {
		
		wp_enqueue_script(
			'wcf-ready-templates-lib',
			WCF_TEMPLATE_MODULE_URL . 'assets/js/templates-lib.min.js',
			[
				'jquery',			
				'elementor-editor'
			],
			time(),
			true
		);	
	}	

    	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action(
			'get_brandberry_theme_template_data',
			function ( $data ) {
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new \Exception( 'Access Denied' );
				}

				if ( ! empty( $data['editor_post_id'] ) ) {
					$editor_post_id = absint( $data['editor_post_id'] );

					if ( ! get_post( $editor_post_id ) ) {
						throw new \Exception( esc_html__( 'Post not found', 'animation-addons-for-elementor' ) );
					}

					\Elementor\Plugin::instance()->db->switch_to_post( $editor_post_id );
				}
		
				if ( empty( $data['template_id'] ) ) {
					throw new \Exception( esc_html__( 'Template id missing', 'animation-addons-for-elementor' ) );
				}

				$result = $this->get_template_data( $data );
			
				return $result;
			}
		);
	}

	private function get_template_data( array $args ) {
		$source = new Library_Source();
		$data   = $source->get_data( $args );       
		return $data;
	}
	
}




