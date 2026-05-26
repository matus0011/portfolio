<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeShowcaseWEBGLGrid extends Widget_Base
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
        return 'peshowcasewebglgrid';
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
        return __('WEBGL Grid', 'pe-core');
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
        return 'eicon-posts-masonry pe-widget';
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
        return ['pe-showcase'];
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
            'content_section',
            [
                'label' => esc_html__('WEBGL Grid', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'grid_videos_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
                If you are using video project visuals, please make sure the videos are provided as HTML5 videos (streamed or self-hosted). The WebGL renderer does not support embedded videos such as Vimeo or YouTube. </div>",

            ]
        );

        zeyna_project_query_selection($this, false, false);




        $this->add_control(
            'show_cats',
            [
                'label' => __('Show Categories', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'cats--on',
                'default' => 'cats--on',
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'show_button',
            [
                'label' => __('Show Button', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'button--on',
                'default' => 'button--on',
                'frontend_available' => true,
            ]
        );



        $this->add_control(
            'cats_sperator',
            [
                'label' => esc_html__('Categories Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: /', 'pe-core'),
                'ai' => false,
                'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
                'condition' => [
                    'show_cats' => 'cats--on',
                ],
                'frontend_available' => true,
            ]
        );




        $this->add_control(
            'show_metas',
            [
                'label' => __('Show Metas', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'metas--on',
                'default' => 'true',
                'frontend_available' => true,
            ]
        );


        $metasRepeater = new \Elementor\Repeater();

        $metasRepeater->add_control(
            'select_meta',
            [
                'label' => __('Select Meta', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => get_project_meta_fields(),
                'frontend_available' => true,
            ]
        );

        $metasRepeater->add_control(
            'show_meta_label',
            [
                'label' => __('Show Label', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
                'frontend_available' => true,
            ]
        );


        $metasRepeater->add_control(
            'meta_seperator',
            [
                'label' => esc_html__('Seperator', 'pe-core'),
                'default' => esc_html__(':', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'condition' => ['show_meta_label' => 'true'],
            ]
        );


        $this->add_control(
            'metas_list',
            [
                'label' => esc_html__('Metas', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $metasRepeater->get_controls(),
                'title_field' => '{{{ select_meta }}}',
                'show_label' => false,
                'condition' => [
                    'show_metas' => 'metas--on',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'metas_seperator',
            [
                'label' => esc_html__('Metas Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: /', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
                'condition' => [
                    'show_metas' => 'metas--on',
                ],
                'frontend_available' => true,

            ]
        );


        $this->add_control(
            'show_acf_fields',
            [
                'label' => __('Show ACF Fields', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'acf--fields--on',
                'default' => 'true',
                'frontend_available' => true,
            ]
        );



        $acfFields = [];

        $fieldGroups = acf_get_field_groups([
            'post_type' => 'portfolio'
        ]);

        $excludeFields = [
            'header_behavior',
            'show_footer',
            'page_layout',
            'header_layout',
            'page_title',
            'media_type',
            'image',
            'video_provider',
            'video_id',
            'self_video',
            'hero_style',
            'hero_template',
            'gallery_project',
            'video_project',
            'ph_video_provider',
            'ph_video_id',
            'ph_self_video',
            'image_gallery',
            'video_cover',
            'ph_video_cover',
            'client',
            'excerpt',
            'project_metas',
        ];

        if (!empty($fieldGroups)) {
            foreach ($fieldGroups as $group) {

                $fields = acf_get_fields($group['key']);
                if (!empty($fields)) {
                    foreach ($fields as $field) {

                        if (!in_array($field['name'], $excludeFields)) {
                            $acfFields[$field['name']] = $field['label'];
                        }
                    }
                }
            }
        }

        $acfFieldsRepeater = new \Elementor\Repeater();

        $acfFieldsRepeater->add_control(
            'select_acf_field',
            [
                'label' => __('Select Field', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $acfFields,
                'frontend_available' => true,
            ]
        );

        $acfFieldsRepeater->add_control(
            'show_acf_label',
            [
                'label' => __('Show Label', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
                'frontend_available' => true,
            ]
        );


        $acfFieldsRepeater->add_control(
            'acf_seperator',
            [
                'label' => esc_html__('Seperator', 'pe-core'),
                'default' => esc_html__(':', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'condition' => ['show_acf_label' => 'true'],
            ]
        );


        $this->add_control(
            'acf_fields_list',
            [
                'label' => esc_html__('Metas', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $acfFieldsRepeater->get_controls(),
                'title_field' => '{{{ select_acf_field }}}',
                'show_label' => false,
                'condition' => [
                    'show_acf_fields' => 'acf--fields--on',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'acf_fields_seperator',
            [
                'label' => esc_html__('ACF Fields Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: /', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
                'condition' => [
                    'show_acf_fields' => 'acf--fields--on',
                ],
                'frontend_available' => true,

            ]
        );

        $this->add_control(
            'show_taxonomies',
            [
                'label' => __('Show Taxonomies', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'taxonomies--on',
                'default' => '',
                'frontend_available' => true,
            ]
        );

        $taxonomiesRepeater = new \Elementor\Repeater();

        $post_type = 'portfolio';
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $taxonomy_options = [];

        if (!empty($taxonomies) && !is_wp_error($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_options[$taxonomy->name] = $taxonomy->label;
            }
        }


        $taxonomiesRepeater->start_controls_tabs('select_taxonomy_tabs');

        $taxonomiesRepeater->start_controls_tab(
            'select_taxonomy_content',
            [
                'label' => esc_html__('Content', 'pe-core'),
            ]
        );

        $taxonomiesRepeater->add_control(
            'select_taxonomy',
            [
                'label' => __('Select Taxonomy', 'pe-core'),
                'label_block' => true,
                'description' => __('Select a taxonomy for this item.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $taxonomy_options,
                'frontend_available' => true,
            ]
        );


        $taxonomiesRepeater->end_controls_tab();

        $taxonomiesRepeater->start_controls_tab(
            'select_taxonomy_style',
            [
                'label' => esc_html__('Style', 'pe-core'),
            ]
        );

        flexOptions($taxonomiesRepeater, false, '{{CURRENT_ITEM}}', 'list', 'List', false);
        objectAbsolutePositioning($taxonomiesRepeater, '{{CURRENT_ITEM}}', 'list_pos', 'List', false);
        objectStyles($taxonomiesRepeater, 'list_items', 'Items', '{{CURRENT_ITEM}} .pe--styled--object', true, false, false, false, false);

        $taxonomiesRepeater->end_controls_tab();

        $taxonomiesRepeater->end_controls_tabs();


        $this->add_control(
            'taxonomies_list',
            [
                'label' => esc_html__('Taxonomies', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $taxonomiesRepeater->get_controls(),
                'title_field' => '{{{ select_taxonomy }}}',
                'show_label' => false,
                'condition' => [
                    'show_taxonomies' => 'taxonomies--on',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'link_behavior',
            [
                'label' => esc_html__('Project Link Behavior', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'open_external' => esc_html__('Use External URL', 'pe-core'),
                ],
                'frontend_available' => true,
            ]
        );


        $this->end_controls_section();

        pe_button_settings($this, false, ['show_button' => 'button--on'], 'project__button_', true, 'Project');

        $this->start_controls_section(
            'carousel_styles',
            [
                'label' => __('Carousel Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'carousel_images_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
                Please make sure that your project images use the same aspect ratio specified here (4:5 by default). Otherwise, since the WebGL renderer does not support image-fit properties, the images may appear distorted. </div>",

            ]
        );

        $this->add_responsive_control(
            'webgl_grid_columns',
            [
                'label' => esc_html__('Grid Columns', 'pe-core'),
                'description' => esc_html__('by px', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 12,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}}' => '--gridCols: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_width',
            [
                'label' => esc_html__('Images Width', 'pe-core'),
                'description' => esc_html__('by px', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}}' => '--imagesWidth: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_height',
            [
                'label' => esc_html__('Images Height', 'pe-core'),
                'description' => esc_html__('by px', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}}' => '--imagesHeight: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__('Spacings', 'pe-core'),
                'description' => esc_html__('by px', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}}' => '--gridGap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_type',
            [
                'label' => esc_html__('Title Size', 'pe-core'),
                'description' => esc_html__('This option will not change HTML tag of the element, this option only for typographic scaling.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'text-p' => [
                        'title' => esc_html__('P', 'pe-core'),
                        'icon' => ' eicon-editor-paragraph',
                    ],
                    'text-h1' => [
                        'title' => esc_html__('H1', 'pe-core'),
                        'icon' => ' eicon-editor-h1',
                    ],
                    'text-h2' => [
                        'title' => esc_html__('H2', 'pe-core'),
                        'icon' => ' eicon-editor-h2',
                    ],
                    'text-h3' => [
                        'title' => esc_html__('H3', 'pe-core'),
                        'icon' => ' eicon-editor-h3',
                    ],
                    'text-h4' => [
                        'title' => esc_html__('H4', 'pe-core'),
                        'icon' => ' eicon-editor-h4',
                    ],
                    'text-h5' => [
                        'title' => esc_html__('H5', 'pe-core'),
                        'icon' => ' eicon-editor-h5',
                    ],
                    'text-h6' => [
                        'title' => esc_html__('H6', 'pe-core'),
                        'icon' => ' eicon-editor-h6',
                    ]

                ],
                'default' => 'text-p',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .pe--webgl--project--title' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_responsive_control(
            'paragraph_size',
            [
                'label' => esc_html__('Paragraph Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'p-small' => [
                        'title' => esc_html__('Small', 'pe-core'),
                        'icon' => 'eicon-t-letter',
                    ],
                    'p-large' => [
                        'title' => esc_html__('Large', 'pe-core'),
                        'icon' => 'eicon-t-letter',
                    ],
                ],
                'default' => '',
                'toggle' => true,
                'condition' => ['text_type' => 'text-p'],
                'selectors' => [
                    '{{WRAPPER}} .pe--webgl--project--title' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_size',
            [
                'label' => esc_html__('Heading Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'md-title' => [
                        'title' => esc_html__('Medium', 'pe-core'),
                        'icon' => 'eicon-t-letter',
                    ],
                    'big-title' => [
                        'title' => esc_html__('Large', 'pe-core'),
                        'icon' => 'eicon-t-letter',
                    ],
                ],
                'default' => '',
                'toggle' => true,
                'condition' => ['text_type' => 'text-h1'],
                'selectors' => [
                    '{{WRAPPER}} .pe--webgl--project--title' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Title Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--webgl--project--title',
            ]
        );

        objectAbsolutePositioning($this, '.pe--webgl--grid--wrapper', 'titles', 'Titles');
        objectAbsolutePositioning($this, '.pe--webgl--grid--controls', 'controls', 'Controls');


        $this->end_controls_section();

        pe_button_style_settings($this, 'Project Button', 'project__button_', ['show_button' => 'button--on'], true, '');

        pe_color_options($this);
        pe_cursor_settings($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $classes = [];


        ob_start();

        \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);

        $icon = ob_get_clean();
        if ((isset($_GET['offset']))) {

            $offset = $_GET['offset'];

        } else {
            $offset = 0;
        }

        $args = array(
            'post_type' => 'portfolio',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'project-categories',
                    'field' => 'term_id'
                )
            )
        );

        ?>

        <div class="pe--webgl--grid">
            <div class="pe--webgl--grid--wrapper">
                <?php

                $loop = new \WP_Query(zeyna_project_query_args(($this)));

                while ($loop->have_posts()):
                    $loop->the_post();
                    $classes = 'cr--item carousel--item';
                    $id = get_the_id();

                    echo '<div class="pe--webgl--project barba--webgl--trigger webgl--project--' . get_the_ID() . '" data-id="' . get_the_ID() . '" data-url="' . get_the_permalink() . '">';
                    pe_project_image($id, false, false);
                    echo '<div class="pe--webgl--project--title">';
                    echo get_the_title($id);
                    echo '</div>';
                    echo '<div class="pe--webgl--project--meta" style="color: var(--secondaryColor)">';

                    if (isset($settings['show_cats']) && $settings['show_cats'] === 'cats--on') { ?>

                        <?php

                        $terms = get_the_terms($id, 'project-categories');

                        if ($terms) {
                            $term_names = array();
                            foreach ($terms as $key => $term) {
                                $key++;
                                echo '<span>' . $term->name . '</span>';
                                if (!empty($settings['cats_sperator']) && (count(($terms)) !== $key)) {
                                    echo '<span class="cats--seperator">' . $settings['cats_sperator'] . '</span>';
                                }
                            }
                        }

                        ?>


                    <?php }

                    if ($settings['show_acf_fields'] === 'acf--fields--on' && $settings['acf_fields_list']) {


                        foreach ($settings['acf_fields_list'] as $key => $meta) {
                            $key++;

                            if (get_field($meta['select_acf_field'])) {
                                if ($meta['show_acf_label']) {
                                    echo '<p class="project--meta meta--' . $meta['select_acf_field'] . '">' . '<span class="field--label">' . get_field_object($meta['select_acf_field'])['label'] . $meta['acf_seperator'] . '</span> '
                                        . '<span class="field--content">' . get_field($meta['select_acf_field']) . '</span>';
                                    echo '</p>';
                                    ;
                                } else {

                                    echo '<p class="project--meta meta--' . $meta['select_acf_field'] . '">' . get_field($meta['select_acf_field']);
                                    echo '</p>';

                                }

                                if (!empty($settings['acf_fields_seperator']) && (count(($settings['acf_fields_list'])) !== $key)) {
                                    echo '<span class="acf--fields--seperator">' . $settings['acf_fields_seperator'] . '</span>';
                                }

                            }


                        }
                    }


                    if ($settings['show_metas'] === 'metas--on' && $settings['metas_list'] && is_array($settings['metas_list'])) {

                        foreach ($settings['metas_list'] as $key => $sMeta) {
                            $key++;

                            $selectedMeta = $sMeta['select_meta'];

                            $project_metas = get_field('project_metas', $id);


                            if ($project_metas) {

                                foreach ($project_metas as $meta) {
                                    if (isset($meta['label']) && $meta['label'] === $selectedMeta) {
                                        if ($sMeta['show_meta_label']) {
                                            echo '<p class="project--meta meta--' . $meta['label'] . '">
                                    <span class="field--label">' . $meta['label'] . $sMeta['meta_seperator'] . '</span>' .
                                                ' <span class="field--content">' . $meta['content'] . '</span></p>';
                                        } else {
                                            echo '<p class="project--meta meta--' . $meta['label'] . '">' . $meta['content'] . '</p>';
                                        }
                                        if (!empty($settings['metas_seperator']) && (count(($settings['metas_list'])) !== $key)) {
                                            echo '<span class="metas--seperator">' . $settings['metas_seperator'] . '</span>';
                                        }
                                        break;
                                    }
                                }

                            }

                        }

                    }

                    if (isset($settings['show_taxonomies']) && $settings['show_taxonomies'] === 'taxonomies--on') { ?>

                        <div class="project--taxonomies--wrap">

                            <?php foreach ($settings['taxonomies_list'] as $tax) {

                                if (!empty($tax['select_taxonomy'])) {

                                    $taxonomy = get_taxonomy($tax['select_taxonomy']);

                                    $terms = get_the_terms($id, $tax['select_taxonomy']);
                                    if ($terms && $taxonomy) {

                                        echo '<div class="project--taxonomies--list  elementor-repeater-item-' . $tax['_id'] . ' taxonomy-' . $taxonomy->name . '">';

                                        foreach ($terms as $key => $term) {
                                            if (!is_a($term, 'WP_Term')) {
                                                continue;
                                            }
                                            echo '<p class="pe--styled--object project--taxonomy taxonomy--' . $term->term_id . '">';
                                            if (get_field('tag_image', $term)) {
                                                echo wp_get_attachment_image(get_field('tag_image', $term)['ID'], 'full');
                                            } else {
                                                echo $term->name;
                                            }
                                            echo '</p>';

                                        }
                                        echo '</div>';
                                    }
                                }




                            } ?>


                        </div>
                    <?php }

                    echo '</div>';

                    if ($settings['show_button'] === 'button--on') {

                        $attr = [
                            'href' => get_permalink($id),
                            'class' => 'pb--handle'
                        ];

                        pe_button_render($this, $attr, false, 'project__button_', false, false, 'project--button', true);
                    }
                    echo '</div>';
                endwhile;
                wp_reset_query();

                ?>
            </div>

            <div class="pe--webgl--grid--controls">

                <div class="pe--webgl--grid--controls--wrapper">
                    <div class="grid--switch grid--nav grid--item--prev">
                        <svg xmlns="http://www.w3.org/2000/svg" height="14px" viewBox="0 -960 960 960" width="14px"
                            fill="var(--mainColor)">
                            <path d="m294.92-450 227.85 227.85L480-180 180-480l300-300 42.77 42.15L294.92-510H780v60H294.92Z" />
                        </svg>
                    </div>
                    <div class="grid--switch grid--zoom" data-scale=1><span>ZOOM IN</span><span>ZOOM OUT</span></div>
                    <div class="grid--switch grid--nav grid--item--next">
                        <svg xmlns="http://www.w3.org/2000/svg" height="14px" viewBox="0 -960 960 960" width="14px"
                            fill="var(--mainColor)">
                            <path
                                d="M665.08-450H180v-60h485.08L437.23-737.85 480-780l300 300-300 300-42.77-42.15L665.08-450Z" />
                        </svg>
                    </div>
                </div>

            </div>

        </div>

    <?php }

}
