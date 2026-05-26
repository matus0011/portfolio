<?php

	// Woo a top-tab
	CSF::createSection( BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
		'id'    => 'wcf_woo_tab',                     // Set a unique slug-like ID
		'title' => esc_html__( 'Shop', 'brandberry-essential' ),
		'icon'  => 'fas fa-archive',
	) );

	// Shop
	CSF::createSection( BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
		'parent' => 'wcf_woo_tab',                        // The slug id of the parent section
		'icon'   => 'fas fa-archive',
		'title'  => esc_html__( 'Shop', 'brandberry-essential' ),
		'fields' => array(
			
			array(
	            'type'    => 'subheading',
	            'content' => esc_html__('Woocommerce / Shop Options', 'brandberry'),
	        ),
			
			array(
				'id'          => 'wcf_woo_sidebar',
				'type'        => 'select',
				'title'       => esc_html__( 'Shop Sidebar', 'brandberry-essential' ),
				'placeholder' => 'Select an option',
				'options'     => array(
					'no-sidebar'    => esc_html__( 'No sidebar', 'brandberry-essential' ),
					'left-sidebar'  => esc_html__( 'Left Sidebar', 'brandberry-essential' ),
					'right-sidebar' => esc_html__( 'Right Sidebar', 'brandberry-essential' ),
				),
				'default'     => 'left-sidebar',
			),

			array(
				'id'          => 'wcf_woo_product_sidebar',
				'type'        => 'select',
				'title'       => esc_html__( 'Product Sidebar', 'brandberry-essential' ),
				'placeholder' => 'Select an option',
				'options'     => array(
					'no-sidebar'    => esc_html__( 'No sidebar', 'brandberry-essential' ),
					'left-sidebar'  => esc_html__( 'Left Sidebar', 'brandberry-essential' ),
					'right-sidebar' => esc_html__( 'Right Sidebar', 'brandberry-essential' ),
				),
				'default'     => 'no-sidebar',
			),

			array(
				'id'          => 'wcf_product_cols',
				'type'        => 'select',
				'title'       => esc_html__( 'Product Columns', 'brandberry-essential' ),
				'placeholder' => 'Select Columns',
				'options'     => array(
					'2' => esc_html__( '2', 'brandberry-essential' ),
					'3' => esc_html__( '3', 'brandberry-essential' ),
					'4' => esc_html__( '4', 'brandberry-essential' ),
				),
				'default'     => '3',
			),

			array(
				'id'          => 'wcf_product_tb_cols',
				'type'        => 'select',
				'title'       => esc_html__( 'Product Columns in Tablet', 'brandberry-essential' ),
				'placeholder' => 'Select Columns',
				'options'     => array(
					'1' => esc_html__( '1', 'brandberry-essential' ),
					'2' => esc_html__( '2', 'brandberry-essential' ),
					'3' => esc_html__( '3', 'brandberry-essential' ),
				),
				'default'     => '2',
			),

			array(
				'id'          => 'wcf_rel_product_cols',
				'type'        => 'select',
				'title'       => esc_html__( 'Related Product Show', 'brandberry-essential' ),
				'placeholder' => 'Select Columns',
				'options'     => array(
					'2' => esc_html__( '2', 'brandberry-essential' ),
					'3' => esc_html__( '3', 'brandberry-essential' ),
					'4' => esc_html__( '4', 'brandberry-essential' ),
					'5' => esc_html__( '5', 'brandberry-essential' ),
					'6' => esc_html__( '6', 'brandberry-essential' ),
				),
				'default'     => '4',
			),

			array(
				'id'          => 'wcf_shop_thumb_size',
				'type'        => 'select',
				'title'       => esc_html__( 'Image Size', 'brandberry-essential' ),
				'placeholder' => esc_html__( 'Select Product Thumbsize', 'brandberry-essential' ),
				'options'     => brandberry_get_image_sizes(),
				'default'     => 'full',
			),


		)
	) );


	// Sidebar
	CSF::createSection( BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
		'parent' => 'wcf_woo_tab',                        // The slug id of the parent section
		'icon'   => 'fas fa-archive',
		'title'  => esc_html__( 'Sidebar', 'brandberry-essential' ),
		'fields' => array(
			array(
				'id'     => 'wcf_s_title_color',
				'type'   => 'color',
				'title'  => esc_html__( 'Title Color', 'brandberry-essential' ),
				'output' => '.wcf-woo--title',
			),

			array(
				'id'          => 'wcf_s_title_border',
				'type'        => 'color',
				'title'       => esc_html__( 'Border Color', 'brandberry-essential' ),
				'output_mode' => 'border-color',
				'output'      => '.wcf-woo--title',
			),

			array(
				'id'          => 'wcf_s_widget_b_radius',
				'type'        => 'spacing',
				'title'       => 'Border Radius',
				'output_mode' => 'border-radius',
				'output'      => '.wcf-woo--widget',
			),

			array(
				'id'          => 'wcf_s_widget_bg',
				'type'        => 'color',
				'title'       => esc_html__( 'Background Color', 'brandberry-essential' ),
				'output_mode' => 'background-color',
				'output'      => '.wcf-woo--widget',
			),

		)
	) );


	// Cart
	CSF::createSection( BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
		'parent' => 'wcf_woo_tab',                        // The slug id of the parent section
		'icon'   => 'fas fa-archive',
		'title'  => esc_html__( 'Cart', 'brandberry-essential' ),
		'fields' => array(

			array(
				'id'    => 'cart_uwq_change',
				'type'  => 'switcher',
				'title' => 'Update Cart with Quantity',
			),

			array(
				'id'          => 'onsale_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Onsale Color', 'brandberry-essential' ),
				'output' => array( '.woocommerce ul.products li.product .onsale', '.single-product.woocommerce span.onsale' ),
			),

			array(
				'id'          => 'onsale_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Onsale Background Color', 'brandberry-essential' ),
				'output_mode' => 'background-color',
				'output' => array( '.woocommerce ul.products li.product .onsale', '.single-product.woocommerce span.onsale' ),
			),

		)
	) );


	// Message
	CSF::createSection( BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
		'parent' => 'wcf_woo_tab',                        // The slug id of the parent section
		'icon'   => 'fas fa-archive',
		'title'  => esc_html__( 'Error & Message', 'brandberry-essential' ),
		'fields' => array(
			array(
				'id'            => 'opt-tabbed-banner',
				'type'          => 'tabbed',
				'title'         => 'Style',
				'tabs'          => array(

					array(
						'title'     => esc_html__('Message','brandberry-essential'),
						'icon'      => '',
						'fields'    => array(
							array(
								'id'          => 'woo_msg_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Color', 'brandberry-essential' ),
								'output'      => '.woocommerce-message',
							),

							array(
								'id'          => 'woo_msg_b_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Border Color', 'brandberry-essential' ),
								'output_mode' => 'border-top-color',
								'output'      => '.woocommerce-message',
							),

							array(
								'id'          => 'woo_msg_icon_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Icon Color', 'brandberry-essential' ),
								'output'      => '.woocommerce-message::before',
							),

							array(
								'id'          => 'woo_msg_bg',
								'type'        => 'color',
								'title'       => esc_html__( 'Background Color', 'brandberry-essential' ),
								'output_mode' => 'background-color',
								'output'      => '.woocommerce-message',
							),

						)
					),

					array(
						'title'     => esc_html__('Info','brandberry-essential'),
						'icon'      => '',
						'fields'    => array(

							array(
								'id'          => 'woo_info_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Color', 'brandberry-essential' ),
								'output'      => '.woocommerce-info',
							),

							array(
								'id'          => 'woo_info_b_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Border Color', 'brandberry-essential' ),
								'output_mode' => 'border-top-color',
								'output'      => '.woocommerce-info',
							),

							array(
								'id'          => 'woo_info_icon_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Icon Color', 'brandberry-essential' ),
								'output'      => '.woocommerce-info::before',
							),

							array(
								'id'          => 'woo_info_msg_bg',
								'type'        => 'color',
								'title'       => esc_html__( 'Background Color', 'brandberry-essential' ),
								'output_mode' => 'background-color',
								'output'      => '.woocommerce-info',
							),

						)
					),

					array(
						'title'     => esc_html__('Error','brandberry-essential'),
						'icon'      => '',
						'fields'    => array(
							array(
								'id'          => 'woo_err_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Color', 'brandberry-essential' ),
								'output'      => '.woocommerce-error',
							),

							array(
								'id'          => 'woo_err_b_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Border Color', 'brandberry-essential' ),
								'output_mode' => 'border-top-color',
								'output'      => '.woocommerce-error',
							),

							array(
								'id'          => 'woo_err_icon_color',
								'type'        => 'color',
								'title'       => esc_html__( 'Icon Color', 'brandberry-essential' ),
								'output'      => '.woocommerce-error::before',
							),

							array(
								'id'          => 'woo_err_msg_bg',
								'type'        => 'color',
								'title'       => esc_html__( 'Background Color', 'brandberry-essential' ),
								'output_mode' => 'background-color',
								'output'      => '.woocommerce-error',
							),
						)
					),

				)
			),
		)
	) );