<?php

// Blog a top-tab
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
    'id'    => 'blog_tab',                     // Set a unique slug-like ID
    'title' => esc_html__('Blog', 'brandberry'),
    'icon'  => 'fas fa-archive',
));

// Blog
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
    'parent' => 'blog_tab',                        // The slug id of the parent section
    'icon'   => 'fas fa-archive',
    'title'  => esc_html__('General', 'brandberry'),
    'fields' => array(

        array(
            'type'    => 'content',
            'content' => 'These settings will work once using default blog layout.',
        ),

        array(
            'id'          => 'blog_sidebar',
            'type'        => 'select',
            'title'       => esc_html__('Blog Sidebar', 'brandberry'),
            'placeholder' => 'Select an option',
            'options'     => array(
                'blog-lg'       => esc_html__('No sidebar', 'brandberry'),
                'left-sidebar'  => esc_html__('Left Sidebar', 'brandberry'),
                'right-sidebar' => esc_html__('Right Sidebar', 'brandberry'),
            ),
            'default'    => 'left-sidebar',
        ),

        array(
            'id'      => 'blog_author',
            'type'    => 'switcher',
            'title'   => esc_html__('Blog Author', 'brandberry'),
            'default' => false,
        ),

        array(
            'id'      => 'blog_author_image',
            'type'    => 'switcher',
            'title'   => esc_html__('Blog Author image', 'brandberry'),
            'default' => false,
            'dependency' => array('blog_author', '==', 'true'),
        ),

        array(
            'id'      => 'blog_date',
            'type'    => 'switcher',
            'title'   => esc_html__('Blog Date', 'brandberry'),
            'default' => true,
        ),

        array(
            'id'      => 'blog_comment',
            'type'    => 'switcher',
            'title'   => esc_html__('Blog Comment', 'brandberry'),
            'default' => false,
        ),

        array(
            'id'      => 'blog_category',
            'type'    => 'switcher',
            'title'   => esc_html__('Blog Category', 'brandberry'),
            'default' => true,
        ),

        array(
            'id'      => 'blog_readmore',
            'type'    => 'switcher',
            'title'   => esc_html__('Blog Readmore', 'brandberry'),
            'default' => true,
        ),
        array(
            'id'      => 'blog_readmore_text',
            'type'    => 'text',
            'title'   => esc_html__('Blog Readmore Text', 'brandberry'),
            'default' => esc_html__('Read More', 'brandberry'),
            'dependency' => array('blog_readmore', '==', 'true'),
        ),

        array(
            'id'      => 'blog_readmore__icon',
            'type'    => 'media',
            'title'   => esc_html__('Readmore Icon', 'brandberry'),
            'library' => 'image',
            'dependency' => array('blog_readmore', '==', 'true'),
        ),

        array(
            'id'      => 'blog_post_nav',
            'type'    => 'switcher',
            'title'   => esc_html__('Blog Navigation', 'brandberry'),
            'default' => true,
        ),

        array(
            'id'         => 'blog_next_icon',
            'type'       => 'media',
            'title'      => esc_html__('Next Icon', 'brandberry'),
            'library'    => 'image',
            'dependency' => array('blog_post_nav', '==', 'true'),
        ),

        array(
            'id'         => 'blog_prev_icon',
            'type'       => 'media',
            'title'      => esc_html__('Prev Icon', 'brandberry'),
            'library'    => 'image',
            'dependency' => array('blog_post_nav', '==', 'true'),
        ),

        array(
            'id'          => 'blog_post_nav_alignment',
            'type'        => 'select',
            'title'       => esc_html__('Navigation Alignment', 'brandberry'),
            'placeholder' => 'Select an option',
            'options'     => array(
                'justify-content-start'  => esc_html__('Left', 'brandberry'),
                'justify-content-center' => esc_html__('Center', 'brandberry'),
                'justify-content-end'    => esc_html__('Right', 'brandberry'),
            ),
            'default'    => 'justify-content-start',
            'dependency' => array('blog_post_nav', '==', 'true'),
        ),

        array(
            'id'      => 'blog_excerpt_word',
            'type'    => 'number',
            'title'   => esc_html__('Blog Excerpt Word', 'brandberry'),
            'desc'    => esc_html__('Set the words that how many words you want to show in every blog post item.', 'brandberry'),
            'default' => '30',
        ),

    )
));

// Sidebar
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
    'parent' => 'blog_tab',                           // The slug id of the parent section  
    'title'  => esc_html__('Sidebar Style', 'brandberry'),
    'icon'   => 'fa fa-image',
    'fields' => array(
        array(
            'id'      => 'news__sidebars_bg',
            'type'    => 'background',
            'title'   => esc_html__('Sidebar Background', 'brandberry'),
            'desc'    => esc_html__('Upload a new background image to set the footer background.', 'brandberry'),
            'default' => array(
                'image'      => '',
                'repeat'     => 'no-repeat',
                'position'   => 'center center',
                'attachment' => 'scroll',
                'size'       => 'cover',

            ),
            'output' => '.default-sidebar__widget .widget,.default-sidebar__widget'
        ),

        array(
            'id'    => 'news__sidebars_padding_top',
            'type'  => 'slider',
            'title' => esc_html__('Sidebar Padding Top', 'brandberry'),
            'min'   => 0,
            'max'   => 200,
            'step'  => 1,
            'unit'  => 'px',

        ),
        array(
            'id'    => 'news__sidebars_padding_bottom',
            'type'  => 'slider',
            'title' => esc_html__('Sidebar Padding Bottom', 'brandberry'),
            'min'   => 0,
            'max'   => 200,
            'step'  => 1,
            'unit'  => 'px',

        ),

        array(
            'type'    => 'subheading',
            'content' => esc_html__('Text & Link Color', 'brandberry'),
        ),
        array(
            'id'     => 'news__sidebars_widget_title_color',
            'type'   => 'color',
            'title'  => esc_html__('Title Color', 'brandberry'),
            'desc'   => esc_html__('Set Sideabr widget title color form here.', 'brandberry'),
            'output' => '.default-sidebar__widget .widget .widget-title,.default-sidebar__widget .widget-title'
        ),
        array(
            'id'     => 'news__sidebars_widget_content_color',
            'type'   => 'color',
            'title'  => esc_html__('Content Color', 'brandberry'),
            'desc'   => esc_html__('Set footer widget content color form here.', 'brandberry'),
            'output' => '
                .default-sidebar__widget select, 
                .default-sidebar__widget .tagcloud a,
                .default-sidebar__widget ul li a,
                .rsswidget,
                .default-sidebar__widget,               
                .default-sidebar__widget .widget,               
                .default-sidebar__wrapper .widget_pages li a,
                .default-sidebar__wrapper .widget_meta li a, 
                .default-sidebar__wrapper .widget_nav_menu li a, 
                .default-sidebar__wrapper .widget_recent_entries li a,s
                .default-sidebar__widget ul li a,
                .default-sidebar__wrapper .widget_rss ul cite,
                .default-sidebar__wrapper .widget_recent_comments li a,
                .default-sidebar__wrapper .widget_rss ul a,
                .default-sidebar__wrapper .widget_rss .rssSummary,
                .default-sidebar__wrapper .widget_rss ul .rss-date,
                .default-sidebar__widget .widget ul li a.url'
        ),
        array(
            'id'     => 'sidebar_border_color',
            'type'   => 'border',
            'title'  => esc_html__('Border Color', 'brandberry'),
            'output' => '.default-sidebar__widget'
        ),
        array(
            'id'    => 'sidebar_widget_title_margin_top',
            'type'  => 'slider',
            'title' => esc_html__('Title Margin Top', 'brandberry'),
            'min'   => 0,
            'max'   => 200,
            'step'  => 1,
            'unit'  => 'px',

        ),
        array(
            'id'    => 'sidebar_widget_title_margin_bottom',
            'type'  => 'slider',
            'title' => esc_html__('Title Margin bottom', 'brandberry'),
            'min'   => 0,
            'max'   => 200,
            'step'  => 1,
            'unit'  => 'px',

        ),

        array(
            'id'     => 'sidebars_link_color',
            'type'   => 'color',
            'title'  => esc_html__('Sideber links color', 'brandberry'),
            'desc'   => esc_html__('Set the Sidebar area link color', 'brandberry'),
            'output' => '.default-sidebar__widget .single-blog-post a .default-sidebar__widget .tagcloud a, .default-sidebar__widget .widget a, .default-sidebar__widget .widget ul li a.url,.default-sidebar__widget .widget ul li a.rsswidget'
        ),

        array(
            'id'     => 'sidebar_link_hover',
            'type'   => 'color',
            'title'  => esc_html__('Sidebar links Hover color', 'brandberry'),
            'desc'   => esc_html__('Set the footer area link hover color', 'brandberry'),
            'output' => '.default-sidebar__widget .single-blog-post a:hover, .default-sidebar__widget .tagcloud a:hover,.default-sidebar__widget .widget a:hover, .default-sidebar__widget .widget ul li a.url:hover,.default-sidebar__widget .widget ul li a.rsswidget:hover'
        ),

    )
));
