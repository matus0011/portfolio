<?php

namespace brandberry\Core;

/**
 * Enqueue.
 */
class Enqueue {

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		wp_register_style( 'wcf-custom-icons', BRANDBERRY_CSS . '/custom-icons.min.css', null, BRANDBERRY_VERSION );

		// ::::::::::::::::::::::::::::::::::::::::::
		if ( ! is_admin() ) {
			wp_enqueue_style( 'brandberry-fonts', brandberry_google_fonts_url( [
				'DM Sans:300,400;500,600,700,800,900',
				'PT Serif:400;500,600,700'
			] ), null, BRANDBERRY_VERSION );

			// Theme style
			wp_enqueue_style( 'wcf-custom-icons' );
			wp_enqueue_style( 'brandberry-style', BRANDBERRY_CSS . '/master.min.css', null, BRANDBERRY_VERSION );

			if ( class_exists( 'WooCommerce' ) ) {
				wp_enqueue_style( 'brandberry-woo', BRANDBERRY_CSS . '/woo.css', null, BRANDBERRY_VERSION );
			}
		}

		// javascripts
		// :::::::::::::::::::::::::::::::::::::::::::::::
		if ( ! is_admin() ) {
			wp_enqueue_script( 'brandberry-script', BRANDBERRY_JS . '/script.min.js', array( 'jquery' ), BRANDBERRY_VERSION, true );
			
			/* TREETHEMES */
			wp_enqueue_script( 'CustomEase', BRANDBERRY_JS . '/CustomEase.min.js', array( 'jquery' ), BRANDBERRY_VERSION, true );
			/* END TREETHEMES */
			
			$brandberry_data = apply_filters( 'brandberry/script/custom/data', [
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'cart_update_qty_change' => brandberry_option( 'cart_uwq_change', false ),
			] );
			wp_localize_script( 'brandberry-script', 'brandberry_obj', $brandberry_data );

			// Load WordPress Comment js
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}
	}
}
