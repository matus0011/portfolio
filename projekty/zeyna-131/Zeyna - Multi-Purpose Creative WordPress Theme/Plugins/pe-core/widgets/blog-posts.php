<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeBlogPosts extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'peblogposts';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Blog Posts', 'pe-core');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-posts-grid pe-widget';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['pe-content'];
    }


    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'widget_content',
            [
                'label' => __('Query Options', 'pe-core'),
            ]
        );

        $this->add_control(
            'posts_selection',
            [
                'label' => esc_html__('Selection', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'by--cat' => esc_html__('By Category', 'pe-core'),
                    'by--hand' => esc_html__('Manual Select', 'pe-core'),
                    'by--tag' => esc_html__('By Tag', 'pe-core'),
                    'all' => esc_html__('Get All Posts', 'pe-core'),
                ],

            ]
        );

        $repeaterPosts = [];

        $posts = get_posts([
            'post_type' => 'post',
            'numberposts' => -1
        ]);

        foreach ($posts as $post) {
            $repeaterPosts[$post->ID] = $post->post_title;
        }

        $postsRepeater = new \Elementor\Repeater();

        $postsRepeater->add_control(
            'select_post',
            [
                'label' => __('Select Post', 'pe-core'),
                'label_block' => true,
                'description' => __('Select post which will display in the slider.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $repeaterPosts,
            ]
        );

        $this->add_control(
            'posts_list',
            [
                'label' => esc_html__('Posts', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $postsRepeater->get_controls(),
                'show_label' => false,
                'condition' => [
                    'product_selection' => 'by--hand',

                ],
            ]
        );


        $postCats = array();

        $args = array(
            'hide_empty' => true,
            'taxonomy' => 'category'
        );

        $categories = get_categories($args);

        foreach ($categories as $key => $category) {
            $postCats[$category->term_id] = $category->name;
        }

        $this->add_control(
            'post_query_cats',
            [
                'label' => __('Categories', 'pe-core'),
                'description' => __('Select categories to display posts.', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $postCats,
                'condition' => [
                    'posts_selection' => 'by--cat',

                ],
            ]
        );

        $postTags = array();

        $args = array(
            'hide_empty' => true,
            'taxonomy' => 'tag'
        );

        $tags = get_categories($args);

        foreach ($tags as $key => $tag) {
            $postTags[$tag->term_id] = $tag->name;
        }

        $this->add_control(
            'post_query_tags',
            [
                'label' => __('Tags', 'pe-core'),
                'description' => __('Select tags to display posts.', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $postTags,
                'condition' => [
                    'posts_selection' => 'by--tag',

                ],
            ]
        );

        $this->add_control(
            'exclude_posts',
            [
                'label' => esc_html__('Exclude Posts', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $repeaterPosts,
                'multiple' => true,
                'condition' => [
                    'posts_selection' => ['by--cat', 'all'],

                ],
            ]
        );

        $this->add_control(
            'number_posts',
            [
                'label' => esc_html__('Posts Per View', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 999,
                'step' => 1,
                'render_type' => 'template',
                'default' => 10,
                'condition' => [
                    'posts_selection' => ['by--cat', 'all'],

                ],

            ]
        );

        $this->add_control(
            'posts_order_by',
            [
                'label' => esc_html__('Order By', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'ID' => esc_html__('ID', 'pe-core'),
                    'title' => esc_html__('Title', 'pe-core'),
                    'date' => esc_html__('Date', 'pe-core'),
                    'author' => esc_html__('Author', 'pe-core'),
                    'type' => esc_html__('Type', 'pe-core'),
                    'rand' => esc_html__('Random', 'pe-core'),
                ],
                'condition' => [
                    'posts_selection' => ['by--cat', 'all'],

                ],
            ]
        );

        $this->add_control(
            'posts_order',
            [
                'label' => esc_html__('Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => esc_html__('ASC', 'pe-core'),
                    'DESC' => esc_html__('DESC', 'pe-core')

                ],
                'condition' => [
                    'posts_selection' => ['by--cat', 'all'],

                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'posts_settings',
            [
                'label' => __('Posts Settings', 'pe-core'),
            ]
        );

        $this->add_control(
            'posts_style',
            [
                'label' => esc_html__('Posts Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'card' => esc_html__('Card', 'pe-core'),
                    'metro' => esc_html__('Metro', 'pe-core'),
                    'horizontal' => esc_html__('Horizontal', 'pe-core'),
                    'hidden-image' => esc_html__('Hidden Image', 'pe-core'),
                ],
                'frontend_available' => true

            ]
        );

        $this->add_control(
            'images_hover',
            [
                'label' => esc_html__('Images Hover', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'zoom-in',
                'prefix_class' => 'hover--',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'zoom-in' => esc_html__('Zoom-In', 'pe-core'),
                    'zoom-out' => esc_html__('Zoom-Out', 'pe-core'),
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'post_image_overlay',
            [
                'label' => esc_html__('Image Overlay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "metro--image--overlay",
                'default' => "metro--image--overlay",
                'prefix_class' => "",
                'condition' => [
                    'posts_style' => 'metro',
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'post_hidden_image_overlay',
            [
                'label' => esc_html__('Image Overlay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "hidden--image--overlay",
                'default' => "hidden--image--overlay",
                'prefix_class' => "",
                'condition' => [
                    'posts_style' => 'hidden-image',
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'post_thumbnail',
            [
                'label' => esc_html__('Thumbnail', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",
                'frontend_available' => true


            ]
        );

        $this->add_control(
            'post_date',
            [
                'label' => esc_html__('Date', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",
                'frontend_available' => true

            ]
        );

        $this->add_control(
            'post_cat',
            [
                'label' => esc_html__('Categories', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",
                'frontend_available' => true

            ]
        );

        $this->add_control(
            'post_excerpt',
            [
                'label' => esc_html__('Excerpt', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",
                'frontend_available' => true

            ]
        );

        $this->add_control(
            'post_author',
            [
                'label' => esc_html__('Author', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",
                'frontend_available' => true

            ]
        );

        $this->add_control(
            'post_button',
            [
                'label' => esc_html__('Read More Button', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",
                'frontend_available' => true
            ]
        );

        $this->end_controls_section();



        pe_button_settings($this, false, ['post_button' => 'show'], 'post_button', true, 'Post');

        $this->start_controls_section(
            'posts_controls_settings',
            [
                'label' => __('Controls ', 'pe-core'),
            ]
        );

        $this->add_control(
            'filters',
            [
                'label' => __('Filters', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'filters_style',
            [
                'label' => esc_html__('Filters Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'render_type' => 'template',
                'default' => 'block',
                'prefix_class' => 'filters--style--',
                'options' => [
                    'block' => esc_html__('Block', 'pe-core'),
                    'sidebar' => esc_html__('Sidebar', 'pe-core'),
                ],
                'condition' => [
                    'filters' => 'true',

                ]
            ]
        );

        $post_type = 'post';
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $taxonomy_options = [];

        if (!empty($taxonomies) && !is_wp_error($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_options[$taxonomy->name] = $taxonomy->label;
            }
        }

        $postFilters = new \Elementor\Repeater();

        $postFilters->add_control(
            'select_taxonomy',
            [
                'label' => __('Select Taxonomy', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'multiple' => true,
                'options' => $taxonomy_options,

            ]
        );


        $postFilters->start_controls_tabs(
            'filter_options_tabs'
        );

        $postFilters->start_controls_tab(
            'content_tab',
            [
                'label' => esc_html__('Content', 'pe-core'),
            ]
        );


        foreach ($taxonomies as $key => $tax) {

            $termsArray = [];
            $terms = get_terms([
                'taxonomy' => $tax->name,
                'hide_empty' => true,
            ]);

            if (!is_wp_error($terms) && !empty($terms)) {

                foreach ($terms as $term) {
                    $termsArray[$term->term_id] = $term->name;
                }
            }

            $postFilters->add_control(
                'select_' . $tax->name,
                [
                    'label' => __('Select ' . $tax->label, 'pe-core'),
                    'description' => __('Leave it empty if you want to display all.', 'pe-core'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $termsArray,
                    'condition' => [
                        'select_taxonomy' => $tax->name,
                    ],
                ]
            );
        }

        $postFilters->add_control(
            'show_label',
            [
                'label' => __('Show Label', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $postFilters->add_control(
            'show_all_button',
            [
                'label' => __('Show All Button', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'pe-core '),
                'label_off' => __('Hide', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );


        $postFilters->add_control(
            'show_all_text',
            [
                'label' => esc_html__('Show All Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('All ', 'pe-core'),
                'ai' => false,
                'condition' => [
                    'show_all_button' => 'true'
                ],
            ]
        );

        $postFilters->add_control(
            'show_counts',
            [
                'label' => __('Show Project Counts', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );


        $postFilters->add_control(
            'filter_style',
            [
                'label' => esc_html__('Filter Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'button',
                'options' => [
                    'button' => esc_html__('Button', 'pe-core'),
                    'checkbox' => esc_html__('Checkbox', 'pe-core'),
                    'dropdown' => esc_html__('Dropdown', 'pe-core'),
                ],
            ]
        );


        $postFilters->end_controls_tab();

        $postFilters->start_controls_tab(
            'styles_tab',
            [
                'label' => esc_html__('Styles', 'pe-core'),
            ]
        );

        $postFilters->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'labels_typography',
                'label' => esc_html__('Labels Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .taxonomy--label',
            ]
        );

        $postFilters->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'terms_typography',
                'label' => esc_html__('Terms Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .term-item',
            ]
        );

        flexOptions($postFilters, false, '{{CURRENT_ITEM}} ul.term--list', 'term_list', 'Terms', false);
        objectStyles($postFilters, 'items_styles', 'Items', '{{CURRENT_ITEM}} ul.term--list .term-item.pe--styled--object', false, false, false, false, false);


        $postFilters->end_controls_tab();

        $postFilters->end_controls_tabs();

        $this->add_control(
            'post_filters',
            [
                'label' => esc_html__('Filters', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $postFilters->get_controls(),
                'show_label' => false,
                'condition' => [
                    'filters' => 'true',
                ],
                'title_field' => '{{{ select_taxonomy }}}',
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'pagination',
            [
                'label' => esc_html__('Pagination', 'pe-core'),
            ]
        );

        $this->add_control(
            'pagination_style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'ajax-load-more' => esc_html__('AJAX Load More', 'pe-core'),
                    'infinite-scroll' => esc_html__('Infinite Scroll', 'pe-core'),
                    'paged' => esc_html__('Paged', 'pe-core'),
                ],

            ]
        );

        $this->add_control(
            'paged_style',
            [
                'label' => esc_html__('Prev/Next Styles', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => esc_html__('Text', 'pe-core'),
                    'icon' => esc_html__('Icon', 'pe-core'),
                ],
                'condition' => [
                    'pagination_style' => 'paged',

                ],

            ]
        );

        $this->add_control(
            'pagination_prev_text',
            [
                'label' => esc_html__('Prev Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Prev', 'pe-core'),
                'condition' => [
                    'pagination_style' => 'paged',
                    'paged_style' => 'text',
                ],
            ]
        );

        $this->add_control(
            'pagination_next_text',
            [
                'label' => esc_html__('Next Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Next', 'pe-core'),
                'condition' => [
                    'pagination_style' => 'paged',
                    'paged_style' => 'text',
                ],
            ]
        );

        $this->add_control(
            'pagination_prev_icon',
            [
                'label' => esc_html__('Prev Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_back',
                    'library' => 'material-design-icons',
                ],
                'condition' => [
                    'paged_style' => 'icon',
                    'pagination_style' => 'paged',
                ],

            ]
        );

        $this->add_control(
            'pagination_next_icon',
            [
                'label' => esc_html__('Next Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_forward',
                    'library' => 'material-design-icons',
                ],
                'condition' => [
                    'paged_style' => 'icon',
                    'pagination_style' => 'paged',
                ],

            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} .posts--pagination.pagination--paged',
                'condition' => [
                    'pagination_style' => 'paged',
                ],
            ]
        );


        flexOptions($this, ['pagination_style' => 'paged'], '.posts--pagination.pagination--paged', 'pagination', 'Pagination');

        $this->add_responsive_control(
            'pagination_margin',
            [
                'label' => esc_html__('Margin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .posts--pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination_style!' => 'none',
                ],
            ]
        );



        $cond = ['pagination_style' => 'ajax-load-more'];
        pe_button_settings($this, false, $cond, 'load_more_button', false, 'Load More');

        $this->end_controls_section();


        $this->start_controls_section(
            'post_styles',
            [

                'label' => esc_html__('Post Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_type',
            [
                'label' => esc_html__('Title Tag', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => esc_html__('H1', 'pe-core'),
                        'icon' => ' eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => esc_html__('H2', 'pe-core'),
                        'icon' => ' eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => esc_html__('H3', 'pe-core'),
                        'icon' => ' eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => esc_html__('H4', 'pe-core'),
                        'icon' => ' eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => esc_html__('H5', 'pe-core'),
                        'icon' => ' eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => esc_html__('H6', 'pe-core'),
                        'icon' => ' eicon-editor-h6',
                    ]

                ],
                'default' => 'h5',
                'toggle' => true,
                'frontend_available' => true
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'post_title_typography',
                'label' => esc_html__('Title Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .post-title.entry-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'post_metas_typography',
                'label' => esc_html__('Metas Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--post--meta',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'post_excerpt_typography',
                'label' => esc_html__('Excerpt Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--post--excerpt',
            ]
        );

        flexOptions($this, false, '.pe--single--post--wrapper', 'post', 'Post');
        flexOptions($this, false, '.pe--single--post--details', 'post_details', 'Details');

        $this->add_responsive_control(
            'post_images_height',
            [
                'label' => esc_html__('Images Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--image' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_images_width',
            [
                'label' => esc_html__('Images Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--image' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'posts_style' => 'horizontal',

                ],
            ]
        );

        $this->add_responsive_control(
            'post_details_width',
            [
                'label' => esc_html__('Details Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--details' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'posts_style' => 'horizontal',

                ],
            ]
        );

        $this->add_responsive_control(
            'post_details_height',
            [
                'label' => esc_html__('Details Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--details' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'posts_style' => 'hidden-image',

                ],
            ]
        );

        $this->add_control(
            'posts_has_bg',
            [
                'label' => esc_html__('Background', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'has--bg',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post::before' => 'content: ""',
                ],
            ]
        );

        $this->add_control(
            'posts_has_backdrop',
            [
                'label' => esc_html__('Backdrop Filter', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'has--backdrop',
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post::before' => 'content: ""; background-color: rgba(255, 255, 255, 0.2);',
                ],

                'default' => '',
            ]
        );


        $this->add_responsive_control(
            'posts_bg_backdrop_blur',
            [
                'label' => esc_html__('Bluriness', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'posts_has_backdrop' => 'has--backdrop',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post::before' => '--backdropBlur: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pe--single--post::before' => 'backdrop-filter: blur(var(--backdropBlur));',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'posts_border',
                'selector' => '{{WRAPPER}} .pe--single--post',
                'important' => true,
            ]
        );


        $this->add_responsive_control(
            'images_border-radius',
            [
                'label' => esc_html__('Border Radius (Images)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_border-radius',
            [
                'label' => esc_html__('Border Radius (Box)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post.psp--elementor' => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'post_padding',
            [
                'label' => esc_html__('Padding (Post)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post.psp--elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'post_details_padding',
            [
                'label' => esc_html__('Padding (Details)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important  ;',
                ],
            ]
        );

        $this->add_control(
            'project_order_items',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Order Items', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Ordered', 'pe-core'),
                'return_value' => 'post--items--ordered',
                'prefix_class' => '',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'post_title_order',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--title' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'post_metas_order',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--meta' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'post_excerpt_order',
            [
                'label' => esc_html__('Excerpt', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--excerpt' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'post_button_order',
            [
                'label' => esc_html__('Button', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--button' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->end_popover();


        $this->add_control(
            'metas_seperator',
            [
                'label' => esc_html__('Metas Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "metas--seperator",
                'default' => "metas--seperator",
                'prefix_class' => "",
            ]
        );

        objectStyles($this, 'details_wrap', 'Details Wrap', '.pe--single--post--details.pe--styled--object', true, false, false, false, false, true);
        objectStyles($this, 'metas_', 'Metas', '.pe--post--meta > .pe--styled--object', true, false, false, false, false, true);
        objectStyles($this, 'excerpt_', 'Excerpt', '.pe--post--excerpt', true, false, false, false, false, true);

        $this->end_controls_section();


        pe_button_style_settings($this, 'Post Button', 'post_button', ['post_button' => 'show'], true, '');

        pe_cursor_settings($this, false, true);
        pe_general_animation_settings($this);

        $this->start_controls_section(
            'posts_grid_styles',
            [

                'label' => esc_html__('Grid Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => esc_html__('Grid Columns', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 12,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--blog--posts--wrapper' => '--columns: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_columns_gap',
            [
                'label' => esc_html__('Columns Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--blog--posts--wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_rows_gap',
            [
                'label' => esc_html__('Rows Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--blog--posts--wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        pe_color_options($this);
        $this->start_controls_section(
            'products_additonal',
            [
                'label' => esc_html__('Additional Options', 'pe-core'),
            ]
        );

        $this->add_control(
            'is_related_query',
            [
                'label' => esc_html__('Is Related Posts', 'pe-core'),
                'description' => esc_html__('If you switch this on, the widget will be visible only on "Single Post" pages and all other query options will be overwritten by related posts query.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'prefix_class' => '',
                'default' => '',
            ]
        );

        $this->add_control(
            'include_current_post',
            [
                'label' => esc_html__('Include Current', 'pe-core'),

                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'prefix_class' => '',
                'default' => '',
                'condition' => [
                    'is_related_query' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        pe_button_style_settings($this, 'Load More Button', 'load_more_button', ['pagination_style' => 'ajax-load-more',]);

    }

    protected function render()
    {
        $settings = $this->get_settings();

        $classes = [];
        $taxQuery = [];

        $classes[] = 'pe--single--post inner--anim pe--styled--object grid--post--item psp--elementor post--' . $settings['posts_style'];


        if ($settings['posts_selection'] === 'by--hand') {
            $ids = [];
            foreach ($settings['posts_list'] as $key => $product) {
                $ids[] = $product['select_post'];
            }

            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 99,
                'post__in' => $ids,
                'orderby' => 'post__in',
            );

        } else if ($settings['posts_selection'] === 'by--brand' || $settings['posts_selection'] === 'by--tag' || $settings['posts_selection'] === 'by--cat' || $settings['posts_selection'] === 'all') {

            $excluded = is_array($settings['exclude_posts']) ? $settings['exclude_posts'] : [$settings['exclude_posts']];
            $cats = $settings['post_query_cats'];
            $tags = $settings['post_query_tags'];


            if ($settings['posts_selection'] === 'by--cat' && !empty($cats)) {
                $taxQuery[] = [
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $cats,
                ];
            }

            if ($settings['posts_selection'] === 'by--tag' && !empty($tags)) {
                $taxQuery[] = [
                    'taxonomy' => 'tag',
                    'field' => 'id',
                    'terms' => $tags,
                ];
            }

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $settings['number_posts'],
                'orderby' => $settings['posts_order_by'],
                'order' => $settings['posts_order'],
                'post__not_in' => $excluded,
                'tax_query' => $taxQuery,
                'paged' => $paged,
            );
        }

        if (isset($settings['is_related_query']) && $settings['is_related_query'] === 'yes' && is_single() && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {

            $post_id = get_the_ID();
            $terms_cat = wp_get_post_terms($post_id, 'category', array('fields' => 'ids'));
            $terms_tag = wp_get_post_terms($post_id, 'tag', array('fields' => 'ids'));

            $args = array(
                'post_type' => 'post',
                'posts_per_page' => $settings['number_posts'],
                'orderby' => $settings['posts_order_by'],
                'order' => $settings['posts_order'],
                'post__not_in' => $settings['include_current_post'] === 'yes' ? [] : array($post_id),
                'tax_query' => array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'terms' => $terms_cat,
                    ),
                    array(
                        'taxonomy' => 'tag',
                        'field' => 'term_id',
                        'terms' => $terms_tag,
                    ),
                ),
            );
        }

        $data_args = htmlspecialchars(json_encode($args), ENT_QUOTES, 'UTF-8');

        $loop = new \WP_Query($args);
        wp_reset_postdata();

        $cursor = pe_cursor($settings, $this);

        $curr_id = get_the_ID();

        ?>


        <div class="pe--blog--posts  anim-multiple" <?php echo pe_general_animation($this) ?>
            data-found="<?php echo esc_attr($loop->found_posts) ?>" data-query-args="<?php echo $data_args ?>">

            <div class="pe--blog--posts--controls inner--anim">

                <div class="pe--posts--filters <?php echo 'filters--' . esc_attr($settings['filters_style']) ?>">

                    <?php $filters = $settings['post_filters'];

                    foreach ($filters as $filter) {


                        $taxonomy = $filter['select_taxonomy'];
                        $selectTaxonomies = $filter['select_' . $taxonomy];

                        $terms = get_terms([
                            'taxonomy' => $taxonomy,
                            'hide_empty' => true,
                            'include' => !empty($selectTaxonomies) ? $selectTaxonomies : 'all',
                        ]);


                        if (!is_wp_error($terms) && !empty($terms)) {

                            echo '<div class="post--filter--group  elementor-repeater-item-' . $filter['_id'] . ' filters--style--' . $filter['filter_style'] . '">';
                            echo $filter['show_label'] ? '<p class="taxonomy--label">' . esc_html(get_taxonomy($taxonomy)->label) . '</p>' : '';
                            echo '<ul class="term--list term-list-' . esc_attr($taxonomy) . '">';

                            if ($filter['show_all_button'] === 'true') {
                                $count = $filter['show_counts'] === 'true' ? '<span class="term--count">(' . $loop->found_posts . ')</span>' : '';

                                echo '<li class="term-item pe--styled--object" data-term-id="all" data-taxonomy="' . esc_attr($taxonomy) . '">';
                                echo '<span class="term--item--inner">' . esc_html($filter['show_all_text']) . '</span>' . $count;
                                echo '</li>';

                            }

                            foreach ($terms as $term) {

                                $count = $filter['show_counts'] === 'true' ? '<span class="term--count">(' . $term->count . ')</span>' : '';


                                echo '<li class="term-item pe--styled--object" data-term-id="' . esc_attr($term->term_id) . '" data-taxonomy="' . esc_attr($taxonomy) . '">';
                                echo '<span class="term--item--inner">' . esc_html($term->name) . '</span>' . $count;
                                echo '</li>';
                            }

                            echo '</ul>';
                            echo '</div>';
                        }

                    }



                    ?>

                </div>

            </div>

            <div class="pe--blog--posts--wrapper">

                <?php while ($loop->have_posts()):
                    $loop->the_post();
                    $link = get_permalink();



                    if ($curr_id === get_the_ID()) {
                        $act = 'data-active="true"';
                    } else {
                        $act = '';
                    }

                    ?>

                    <article <?php echo $cursor;
                    echo $act ?> id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>

                        <?php if ($settings['posts_style'] === 'metro' || $settings['posts_style'] === 'card' || $settings['posts_style'] === 'hidden-image') {
                            echo '<a class="post--link--wrap" href="' . get_the_permalink() . '">';
                        } ?>

                        <div class="pe--single--post--wrapper">

                            <?php if ($settings['post_thumbnail'] === 'show') { ?>
                                <div class="pe--single--post--image">
                                    <?php pe_post_thumbnail(); ?>
                                </div>
                            <?php } ?>

                            <div class="pe--single--post--details pe--styled--object">

                                <div class="pe--post--meta">

                                    <?php if ($settings['post_cat'] === 'show') { ?>
                                        <div class="post--categories pe--styled--object"><?php
                                        if ('post' === get_post_type()) {

                                            $categories_list = get_the_category_list(esc_html__(', ', 'pe-core'));
                                            if ($categories_list) {
                                                printf('<span class="cat-links">' . esc_html__('%1$s', 'pe-core') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            }
                                        } ?>
                                        </div>
                                    <?php } ?>

                                    <?php if ($settings['post_date'] === 'show') { ?>
                                        <div class="post--date pe--styled--object">
                                            <?php pe_posted_on(); ?>
                                        </div>
                                    <?php } ?>

                                    <?php if ($settings['post_author'] === 'show') { ?>
                                        <div class="post--author pe--styled--object">
                                            <?php the_author(); ?>
                                        </div>
                                    <?php } ?>

                                </div>


                                <div class="pe--post--title">

                                    <?php echo '<a class="post--link--wrap" href="' . get_the_permalink() . '">';
                                    echo '<' . $settings['text_type'] . ' class="post-title entry-title">' . get_the_title() . '</' . $settings['text_type'] . '>';
                                    echo '</a>'; ?>
                                </div>

                                <?php if ($settings['post_excerpt'] === 'show') { ?>
                                    <div class="pe--post--excerpt pe--styled--object">
                                        <?php
                                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                            echo wp_trim_words(get_the_excerpt(), 20, '...');
                                        } else {
                                            the_excerpt();
                                        }
                                        ?>
                                    </div>
                                <?php } ?>

                                <?php if ($settings['post_button'] === 'show') { ?>
                                    <div class="pe--post--button">
                                        <?php
                                        $attr = [
                                            'href' => get_permalink(),
                                            'class' => 'pb--handle'
                                        ];
                                        pe_button_render($this, $attr, false, 'post_button', ''); ?>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>

                        <?php if ($settings['posts_style'] === 'metro' || $settings['posts_style'] === 'card' || $settings['posts_style'] === 'hidden-image') {
                            echo '</a>';
                        } ?>

                    </article>



                <?php endwhile;
                wp_reset_query(); ?>

            </div>


            <?php if ($settings['pagination_style'] !== 'none') { ?>

                <div class="posts--pagination pagination--<?php echo $settings['pagination_style'] ?>">

                    <?php if ($settings['pagination_style'] === 'ajax-load-more') { ?>
                        <div class="posts--load--more">
                            <?php pe_button_render($this, false, false, 'load_more_button'); ?>
                        </div>
                    <?php } else if ($settings['pagination_style'] === 'infinite-scroll') {

                        echo '<span class="ajax-infinite-scroll" hidden></span>';

                    } else if ($settings['pagination_style'] === 'paged') {

                        if ($settings['paged_style'] === 'text') {

                            $next = !empty($settings['pagination_next_text']) ? $settings['pagination_next_text'] : __('Next »');
                            $prev = !empty($settings['pagination_prev_text']) ? $settings['pagination_prev_text'] : __('« Prev');

                        } else {

                            $next = file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/arrow_forward.svg');
                            $prev = file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/arrow_back.svg');

                            if (!empty($settings['pagination_prev_icon']['value'])) {
                                ob_start();
                                \Elementor\Icons_Manager::render_icon($settings['pagination_prev_icon'], ['aria-hidden' => 'true']);
                                $prev = ob_get_clean();
                            }

                            if (!empty($settings['pagination_next_icon']['value'])) {
                                ob_start();
                                \Elementor\Icons_Manager::render_icon($settings['pagination_next_icon'], ['aria-hidden' => 'true']);
                                $next = ob_get_clean();
                            }

                        }

                        $paged = max(1, get_query_var('paged'));

                        $pagination = paginate_links(array(
                            'base' => get_pagenum_link(1) . '%_%',
                            'format' => 'page/%#%/',
                            'current' => $paged,
                            'total' => $loop->max_num_pages,
                            'prev_text' => $prev,
                            'next_text' => $next,
                            'mid_size' => 2,
                            'end_size' => 1,
                            'add_args' => false,
                            'add_fragment' => '',
                        ));

                        if ($pagination) {

                            $pagination = str_replace('<a', '<a class="pe--styled--object"', $pagination);
                            $pagination = str_replace('class="page-numbers current"', 'class="page-link pe--styled--object current"', $pagination);
                        }

                        echo $pagination;
                    } ?>


                </div>
            <?php } ?>

        </div>

        <?php
    }


}