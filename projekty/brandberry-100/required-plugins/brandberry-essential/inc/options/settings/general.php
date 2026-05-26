<?php

// General Settings
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
    'id'    => 'general_tab',                     // Set a unique slug-like ID
    'title' => esc_html__('General Settings', 'brandberry'),
    'icon'  => 'fa fa-book',
));


// Full Site
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
    'parent' => 'general_tab',                        // The slug id of the parent section
    'icon'   => 'fa fa-book',
    'title'  => esc_html__('Global Options', 'brandberry'),
    'fields' => array(

        array(
            'type'    => 'subheading',
            'content' => esc_html__('Theme Customization via Elementor', 'brandberry'),
        ),
		
		array(
		    'type'     => 'callback',
		    'function' => 'brandberry_open_elementor_options',
		),

		
        array(
            'type'    => 'subheading',
            'content' => esc_html__('Other Options', 'brandberry'),
        ),
        
        
        array(
            'id'      => 'show_lines_pattern',
            'type'    => 'switcher',
            'title'   => 'Show Lines Pattern',
            'label'   => 'Turn ON to show the lines pattern under the page wrapper.',
            'default' => false,
        ),
        
		
        array(
            'id'      => 'theme_demo_activate',
            'type'    => 'switcher',
            'title'   => esc_html__('Activate Theme Demo Importer', 'brandberry'),
            'default' => true,
        ),
        
       
       array(
			'id'      => 'wcf_rtl_activator',
			'type'    => 'switcher',
			'title'   => esc_html__('Enable RTL (Frontend)', 'brandberry'),
			'default' => false,
		),
		
		
        
    )
));



// Full Site
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
    'parent' => 'general_tab',                        // The slug id of the parent section
    'icon'   => 'fa fa-book',
    'title'  => esc_html__('Style Default Templates', 'brandberry'),
    'fields' => array(

        array(
            'type'    => 'subheading',
            'content' => esc_html__('Body Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_body_color',
            'type'   => 'color',
            'title'  => esc_html__('Body Text Color', 'brandberry'),
            'desc'   => esc_html__('Set full site body color form here.', 'brandberry'),
            'output' => 'body p'
        ),

        array(
            'id'        => 'news_body_typo',
            'type'      => 'typography',
            'title'     => esc_html__('Body Text Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for Body', 'brandberry'),
            'output'    => 'body p'
        ),

        array(
            'type'    => 'subheading',
            'content' => esc_html__('Link Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_link_color',
            'type'   => 'color',
            'title'  => esc_html__('Link Text Color', 'brandberry'),
            'desc'   => esc_html__('Set full site Link color form here.', 'brandberry'),
            'output' => 'a.wcf-post-link'
        ),

        array(
            'id'     => 'news_link_hover_color',
            'type'   => 'color',
            'title'  => esc_html__('Link Text Hover Color', 'brandberry'),
            'desc'   => esc_html__('Set full site Link hover color form here.', 'brandberry'),
            'output' => '.wcf-post-link:hover'
        ),

        array(
            'id'        => 'news_link_typo',
            'type'      => 'typography',
            'title'     => esc_html__('Link Text Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for link', 'brandberry'),
            'output'    => 'a.wcf-post-link'
        ),

    )
));

// Full Site
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
    'parent' => 'general_tab',                        // The slug id of the parent section
    'icon'   => 'fa fa-book',
    'title'  => esc_html__('Global Typography', 'brandberry'),
    'fields' => array(

        array(
            'type'    => 'subheading',
            'content' => esc_html__('H1 Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_heading_h1_title_color',
            'type'   => 'color',
            'title'  => esc_html__('H1 Heading Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H1 color form here.', 'brandberry'),
            'output' => 'h1, h1.wcf-post-title'
        ),

        array(
            'id'     => 'news_heading_h1_title_color_hover',
            'type'   => 'color',
            'title'  => esc_html__('H1 Heading Hover Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H1 Hover color form here.', 'brandberry'),
            'output' => 'h1:hover'
        ),

        array(
            'id'        => 'h1_typo',
            'type'      => 'typography',
            'title'     => esc_html__('H1 Heading Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for all H1 Headers.', 'brandberry'),
            'output'    => 'body h1'
        ),

        array(
            'type'    => 'subheading',
            'content' => esc_html__('H2 Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_heading_h2_title_color',
            'type'   => 'color',
            'title'  => esc_html__('H2 Heading Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H2 color form here.', 'brandberry'),
            'output' => 'h2'
        ),

        array(
            'id'     => 'news_heading_h2_title_color_hover',
            'type'   => 'color',
            'title'  => esc_html__('H2 Heading Hover Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H2 Hover color form here.', 'brandberry'),
            'output' => 'h2:hover'
        ),

        array(
            'id'        => 'h2_typo',
            'type'      => 'typography',
            'title'     => esc_html__('H2 Heading Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for all H2 Heading.', 'brandberry'),
            'output'    => 'h2'
        ),

        array(
            'type'    => 'subheading',
            'content' => esc_html__('H3 Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_heading_h3_title_color',
            'type'   => 'color',
            'title'  => esc_html__('H3 Heading Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H3 color form here.', 'brandberry'),
            'output' => 'h3'
        ),

        array(
            'id'     => 'news_heading_h3_title_color_hover',
            'type'   => 'color',
            'title'  => esc_html__('H3 Heading Hover Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H3 Hover color form here.', 'brandberry'),
            'output' => 'h3:hover'
        ),

        array(
            'id'        => 'h3_typo',
            'type'      => 'typography',
            'title'     => esc_html__('H3 Heading Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for all H3 Heading.', 'brandberry'),
            'output'    => 'h3'
        ),

        array(
            'type'    => 'subheading',
            'content' => esc_html__('H4 Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_heading_h4_title_color',
            'type'   => 'color',
            'title'  => esc_html__('H4 Heading Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H4 color form here.', 'brandberry'),
            'output' => 'h4'
        ),

        array(
            'id'     => 'news_heading_h4_title_color_hover',
            'type'   => 'color',
            'title'  => esc_html__('H4 Heading Hover Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H4 Hover color form here.', 'brandberry'),
            'output' => 'h4:hover'
        ),

        array(
            'id'        => 'h4_typo',
            'type'      => 'typography',
            'title'     => esc_html__('H4 Heading Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for all H4 Heading.', 'brandberry'),
            'output'    => 'h4'
        ),

        array(
            'type'    => 'subheading',
            'content' => esc_html__('H5 Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_heading_h5_title_color',
            'type'   => 'color',
            'title'  => esc_html__('H5 Heading Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H5 color form here.', 'brandberry'),
            'output' => 'h5'
        ),

        array(
            'id'     => 'news_heading_h5_title_color_hover',
            'type'   => 'color',
            'title'  => esc_html__('H5 Heading Hover Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H5 Hover color form here.', 'brandberry'),
            'output' => 'h5:hover'
        ),

        array(
            'id'        => 'h5_typo',
            'type'      => 'typography',
            'title'     => esc_html__('H5 Heading Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for all H5 Heading.', 'brandberry'),
            'output'    => 'h5'
        ),

        array(
            'type'    => 'subheading',
            'content' => esc_html__('H6 Color & Typography', 'brandberry'),
        ),

        array(
            'id'     => 'news_heading_h6_title_color',
            'type'   => 'color',
            'title'  => esc_html__('H6 Heading Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H6 color form here.', 'brandberry'),
            'output' => 'h6'
        ),

        array(
            'id'     => 'news_heading_h6_title_color_hover',
            'type'   => 'color',
            'title'  => esc_html__('H6 Heading Hover Color', 'brandberry'),
            'desc'   => esc_html__('Set full site H6 Hover color form here.', 'brandberry'),
            'output' => 'h6:hover'
        ),

        array(
            'id'        => 'h6_typo',
            'type'      => 'typography',
            'title'     => esc_html__('H6 Heading Typography', 'brandberry'),
            'subtitle'  => esc_html__('These settings control the typography for all H6 Heading.', 'brandberry'),
            'output'    => 'h6'
        ),
    )
));




function brandberry_open_elementor_options( $field ) {

    if ( ! class_exists( 'Elementor\Plugin' ) ) {
        echo '<p>' . esc_html__( "To customize headers, please install Elementor plugin.", 'brandberry' ) . '</p>';
        return;
    }

    $site_settings_link = Brandberry_Elementor_Compatibility::get_site_settings_link();

    if ( ! $site_settings_link ) {
        echo '<p>' . esc_html__( "Elementor Site Settings are not available.", 'brandberry' ) . '</p>';
        return;
    }

    echo '<h6 class="global-options">' . esc_html__(
        'Brandberry uses Elementor for most customization options. You can set global fonts and colors, typography, site preloader, animated cursor, scroll-to-top button, scroll indicator, theme popups, layout, headers, footers, and Site Settings.',
        'brandberry'
    ) . '</h6>';

    echo '<a class="wcf-hf-btn" href="' . esc_url( $site_settings_link ) . '" target="_blank">';
    echo '<i class="csf-tab-icon fa fa-cog"></i> ' . esc_html__( "Open Elementor Site Settings", 'brandberry' );
    echo '</a>';

    echo '<p>' . esc_html__(
        "Elementor will open directly to Site Settings.",
        'brandberry'
    ) . '</p>';
}






