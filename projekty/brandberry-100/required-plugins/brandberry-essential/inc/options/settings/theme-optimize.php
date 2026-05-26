<?php 

CSF::createSection( BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
        'icon'   => 'fas fa-stethoscope',
        'title'  => esc_html__( 'Asset & Editor Optimize','brandberry-essential'),
        'fields' => array(
             
          
            array(
	            'id'      => 'disable_gsap_on_mobile',
	            'type'    => 'switcher',
	            'title'   => 'Disable GSAP on Mobile',
	            'label'   => 'Disable GSAP on mobile for better performance.',
	            'default' => true,
	        ),
	        
	        array(
			  'id'      => 'disable_gsap_on_elementor_editor',
			  'type'    => 'switcher',
			  'title'   => esc_html__('Disable GSAP in Elementor Editor', 'brandberry'),
			  'label'   => esc_html__('Improves Elementor editor speed by disabling GSAP libraries inside the editor preview (frontend not affected).', 'brandberry'),
			  'default' => false,
			),
			
			array(
			  'id'      => 'block_aa_fingerprint_editor',
			  'type'    => 'switcher',
			  'title'   => esc_html__('Block Animation Addons Fingerprint (Editor)', 'brandberry'),
			  'label'   => esc_html__('Blocks a remote fingerprint request that can slow down Elementor editor loading (frontend not affected).', 'brandberry'),
			  'default' => true,
			),
			
			array(
			  'id'      => 'cache_member_templates_editor',
			  'type'    => 'switcher',
			  'title'   => esc_html__('Cache Brandberry Template Library (Editor)', 'brandberry'),
			  'label'   => esc_html__('Caches template API responses to reduce repeated requests while editing.', 'brandberry'),
			  'default' => true,
			),
			
			
            
            array(
                'id'      => 'ondemand_contact_form_7',
                'type'    => 'switcher',
                'title'   => esc_html__( 'On Demand Contact form 7', 'brandberry-essential' ),
                'default' => true,              
            ), 
            
            array(
                'id'      => 'defer_js_and_css',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Theme JS Defer', 'brandberry-essential' ),
                'default' => true,              
            ), 
            
            
            array(
                'id'      => 'optimize_asset_enable',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Asset Optimize', 'brandberry-essential' ),
                'desc'    => esc_html__('Enable this option if your site uses HTTP/2','brandberry-essential'),
                'default' => false
            ),

            
            array(
                'id'         => 'optimize_minify_css',
                'type'       => 'switcher',
                'title'      => esc_html__( 'Minify css', 'brandberry-essential' ),
                'default'    => false,
                'dependency' => array( 'optimize_asset_enable', '==', 'true' ),
            ), 
        
        )
    ) ); 