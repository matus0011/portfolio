<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WP_Query;


if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeProjectField extends Widget_Base
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
        return 'peprojectfield';
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
        return __('Project Field', 'pe-core');
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
        return 'eicon-post-info pe-widget';
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
        return ['pe-dynamic'];
    }


    protected function get_project_meta_fields()
    {
        $projectMetas = [];

        $args = array(
            'post_type' => 'portfolio',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                $repeater = get_post_meta($post->ID, 'project_metas', true); // ACF kullanıyorsan bu yeterli

                if (is_array($repeater)) {
                    foreach ($repeater as $row) {
                        if (!empty($row['label'])) {
                            $label = $row['label'];
                            $projectMetas[$label] = $label; // hem key hem value aynı olabilir
                        }
                    }
                }
            }
        }

        $projectMetas = array_unique($projectMetas);
        return $projectMetas;
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


        // Tab Title Control
        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __('Project Fields', 'pe-core'),
            ]
        );

        $this->add_control(
            'field_type',
            [
                'label' => esc_html__('Field Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'title',
                'options' => [
                    'title' => esc_html__('Title', 'pe-core'),
                    'category' => esc_html__('Category', 'pe-core'),
                    'meta' => esc_html__('Meta', 'pe-core'),
                    'external_link' => esc_html__('External Link', 'pe-core'),
                    'excerpt' => esc_html__('Excerpt', 'pe-core'),
                    'taxonomy' => esc_html__('Taxonomy', 'pe-core'),
                    'acf_field' => esc_html__('ACF Fields', 'pe-core'),
                    'button' => esc_html__('Button', 'pe-core'),
                ],
                'label_block' => true,
            ]
        );

        pe_button_settings($this, false, ['field_type' => 'button'], 'project_button', false, 'Project Button');

        $this->add_control(
            'linked',
            [
                'label' => esc_html__('Linked?', 'pe-core'),
                'description' => esc_html__('Text will be linked to the project.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => ['field_type' => 'title'],

            ]
        );

        $projectFields = [];

        $groups = acf_get_field_groups(["post_type" => "portfolio"]);

        $exclude_names = [
            'hero_template',
            'header_behavior',
            'show_footer',
            'header_layout',
            'page_title',
            'hero_style',
            'gallery_project',
            'video_project',
            'ph_video_provider',
            'ph_video_id',
            'ph_self_video',
            'image_gallery',
            'media_type',
            'image',
            'video_provider',
            'video_id',
            'self_video',
            'page_layout',
            'video_cover',
            'ph_video_cover',
            'client',
            'excerpt',
            'project_metas',
            'project_gallery',
            'project_external_link_text',
            'external_link_label',
            'external_link_url',

        ];

        $projectFields = [];

        // ACF alanlarını ekle
        foreach ($groups as $group) {
            $fields = acf_get_fields($group);

            foreach ($fields as $field) {
                if (in_array($field['name'], $exclude_names, true)) {
                    continue;
                }

                $projectFields[$field['name']] = $field['label'];
            }
        }

        // Custom meta alanlarını ekle
        $meta_fields = get_option('portfolio_custom_metas', []);

        foreach ($meta_fields as $slug => $meta_field) {
            if (!empty($meta_field['label'])) {
                $projectFields[$slug] = $meta_field['label'];
            }
        }

        // Elementor SELECT kontrolü
        $this->add_control("select_acf_field", [
            "label" => esc_html__("Select ACF Field", "textdomain"),
            "type" => \Elementor\Controls_Manager::SELECT,
            "label_block" => true,
            "multiple" => false,
            "options" => $projectFields,
            'condition' => ['field_type' => 'acf_field'],
        ]);

        // Elementor SELECT kontrolü
        $this->add_control("project_fields", [
            "label" => esc_html__("Select Meta", "textdomain"),
            "type" => \Elementor\Controls_Manager::SELECT,
            "label_block" => true,
            "multiple" => false,
            "options" => $this->get_project_meta_fields(),
            'condition' => ['field_type' => 'meta'],
        ]);


        $post_type = 'portfolio';
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $taxonomy_options = [];

        if (!empty($taxonomies) && !is_wp_error($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_options[$taxonomy->name] = $taxonomy->label;
            }
        }

        $this->add_control(
            'select_taxonomy',
            [
                'label' => __('Select Taxonomy', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'multiple' => true,
                'options' => $taxonomy_options,
                'condition' => ['field_type' => 'taxonomy'],

            ]
        );


        $this->add_control(
            'show_label',
            [
                'label' => esc_html__('Show Label', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'condition' => ['field_type' => ['meta', 'acf_field']],
            ]
        );

        $this->add_control(
            'labels_block',
            [
                'label' => esc_html__('Labels Block', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'block',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .text-wrapper.zeyna--project--field span.field--label' => 'display: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'seperate_words',
            [
                'label' => esc_html__('Seperate Words', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'condition' => ['field_type' => ['meta']],
            ]
        );

        $this->add_control(
            'field_seperator',
            [
                'label' => esc_html__('Seperator', 'pe-core'),
                'default' => esc_html__(':', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'condition' => ['show_label' => 'true'],
            ]
        );
        $this->add_responsive_control(
            'text_type',
            [
                'label' => esc_html__('Text Size', 'pe-core'),
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
                    '{{WRAPPER}} .text-wrapper p' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
                    '{{WRAPPER}} .text-wrapper' => '--anim--letter--spacing: var(--{{VALUE}}-letter-spacing)',
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
                    '{{WRAPPER}} .text-wrapper p' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
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
                    '{{WRAPPER}} .text-wrapper p' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_control(
            'remove_breaks',
            [
                'label' => esc_html__('Remove Breaks on Mobile', 'pe-core'),
                'description' => esc_html__('On mobile screens "br" tags will be removed.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'hide-br-mobile',
                'default' => '',

            ]
        );

        $this->add_control(
            'remove_margins',
            [
                'label' => esc_html__('Remove Margins', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'no-margin',
                'default' => '',

            ]
        );

        $this->add_responsive_control(
            'text-indent',
            [
                'label' => esc_html__('Text Indent', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .text-wrapper' => '--indent: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'secondary_color',
            [
                'label' => esc_html__('Use Secondary Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'use--sec--color',
                'default' => '',

            ]
        );

        $this->add_control(
            '_use_secondary_font',
            [
                'label' => esc_html__('Use Secondary Font', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .text-wrapper > p ' => '
                    font-family: var(--sec_typo-font-family) !important;'
                ],


            ]
        );

        $this->add_control(
            'label_use_secondary_font',
            [
                'label' => esc_html__('Use Secondary Font (Label)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .text-wrapper .field--label ' => '
                        font-family: var(--sec_typo-font-family);
                        font-size: var(--sec_typo-font-size);
                        line-height: var(--sec_typo-line-height);
                        letter-spacing: var(--sec_typo-letter-spacing);
                        font-weight: var(--sec_typo-font-weight);
                   text-transform: var(--sec_typo-text-transform);',
                ],
                'condition' => ['show_label' => 'true'],
            ]
        );

        $this->add_control(
            'get_data',
            [
                'label' => esc_html__('Get Data From', 'pe-core'),
                'description' => esc_html__('You can select "Next/Prev project when creating project paginations." ', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'current',
                'options' => [
                    'current' => esc_html__('Current Project', 'pe-core'),
                    'next' => esc_html__('Next Project', 'pe-core'),
                    'prev' => esc_html__('Previous Project', 'pe-core'),

                ],
                'label_block' => false,
            ]
        );
        $this->end_controls_section();

        pe_button_style_settings($this, 'Project Button', 'project_button', ['field_type' => 'button']);

        pe_button_settings($this, false, ['field_type' => 'external_link'], 'external_button', true, 'External ');

        $this->start_controls_section(
            'insert_elements',
            [

                'label' => esc_html__('Insert Elements', 'pe-core'),

            ]
        );

        $this->add_control(
            'element_type',
            [
                'label' => esc_html__('Element Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'icon' => esc_html__('Icon', 'pe-core'),
                    'image' => esc_html__('Image', 'pe-core'),
                    'video' => esc_html__('Video', 'pe-core'),
                    'sup' => esc_html__('Sup-Text', 'pe-core'),
                ],
                'label_block' => true
            ]
        );

        $this->add_control(
            'insert_at',
            [
                'label' => esc_html__('Insert Element At:', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before', 'pe-core'),
                        'icon' => 'eicon-chevron-left',
                    ],
                    'after' => [
                        'title' => esc_html__('After', 'pe-core'),
                        'icon' => 'eicon-chevron-right',
                    ],

                ],
                'default' => 'after',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'element_text',
            [
                'label' => esc_html__('Sup-Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: SUP', 'pe-core'),
                'ai' => false,
                'condition' => ['element_type' => 'sup'],
            ]
        );

        $this->add_control(
            'element_icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ],
                    'fa-regular' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ],
                ],
                'condition' => ['element_type' => 'icon'],
            ]
        );

        $this->add_control(
            'element_image',
            [
                'label' => esc_html__('Image', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => ['element_type' => 'image'],
            ]
        );

        pe_video_settings($this, 'element_type', 'video');

        $this->add_responsive_control(
            'video_width',
            [
                'label' => esc_html__('Video Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.inner--video' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .inserted--ls--hold' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'video'],
            ]
        );

        $this->add_responsive_control(
            'video_height',
            [
                'label' => esc_html__('Video Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.inner--video' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .inserted--ls--hold' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'video'],
            ]
        );

        $this->add_control(
            'video-border-radius',
            [
                'label' => esc_html__('Video Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} span.inner--video , {{WRAPPER}} .inserted--ls--hold' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'video'],
            ]
        );

        $this->start_controls_tabs(
            'element_tabs'
        );

        $this->start_controls_tab(
            'element_style',
            [
                'label' => esc_html__('Style', 'pe-core'),
            ]
        );

        $this->add_control(
            'element_color',
            [
                'label' => esc_html__('Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => '--color: {{VALUE}}',
                ],
                'condition' => ['element_type' => 'icon'],
            ]
        );

        $this->add_control(
            'element_opposite_color',
            [
                'label' => esc_html__('Opposite Color', 'pe-core'),
                'description' => esc_html__('Recommended if you are using layout switcher in the page. This color will be used when the layout switched.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body.dark {{WRAPPER}} .inserted--element' => '--color: {{VALUE}}',
                ],
                'condition' => ['element_color!' => ''],
            ]
        );


        $this->add_responsive_control(
            'font_size',
            [
                'label' => esc_html__('Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'icon'],
            ]
        );

        $this->add_responsive_control(
            'image__width',
            [
                'label' => esc_html__('Image Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'image'],
            ]
        );

        $this->add_responsive_control(
            'image__height',
            [
                'label' => esc_html__('Image Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'image'],
            ]
        );

        $this->add_responsive_control(
            'border-radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'image'],
            ]
        );




        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .inserted--element',
                'condition' => ['element_type' => 'image'],
            ]
        );




        $this->add_control(
            'ls_default_behavior',
            [
                'label' => esc_html__('Default Behavior', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ls--default--none',
                'options' => [
                    'ls--default--none' => esc_html__('None', 'pe-core'),
                    'ls--default--revert' => esc_html__('Revert', 'pe-core'),
                    'ls--default--invert' => esc_html__('Invert', 'pe-core'),

                ],
                'label_block' => true
            ]
        );

        $this->add_control(
            'ls_behavior',
            [
                'label' => esc_html__('Layout Switch Behavior', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ls--none',
                'options' => [
                    'ls--none' => esc_html__('None', 'pe-core'),
                    'ls--revert' => esc_html__('Revert', 'pe-core'),
                    'ls--invert' => esc_html__('Invert', 'pe-core'),

                ],
                'label_block' => true
            ]
        );


        $this->add_control(
            'mobile_visibility',
            [
                'label' => esc_html__('Mobile Visibility', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'pe-core'),
                'label_off' => esc_html__('Show', 'pe-core'),
                'return_value' => 'hide--on--mobile',
                'default' => 'false',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'element__transform',
            [
                'label' => esc_html__('Transform', 'pe-core'),
            ]
        );


        $this->add_responsive_control(
            'element_rotate',
            [
                'label' => esc_html__('Rotate', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'condition' => ['motion' => 'none'],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => '--pe-rotate: {{SIZE}}deg;',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_scale',
            [
                'label' => esc_html__('Scale', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'condition' => ['motion' => 'none'],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => '--pe-scale: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_offset_x',
            [
                'label' => esc_html__('Offset X', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'condition' => ['motion' => 'none'],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => '--pe-translate-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_offset_y',
            [
                'label' => esc_html__('Offset Y', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'condition' => ['motion' => 'none'],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => '--pe-translate-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_margin',
            [
                'label' => esc_html__('Margin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_padding',
            [
                'label' => esc_html__('Padding', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'vertical_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'middle',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'vertical-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sup__typo',
                'label' => esc_html__('Sup Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .inserted--element',
                'condition' => ['element_type' => 'sup'],
            ]
        );


        $this->add_responsive_control(
            'sup_top_spacing',
            [
                'label' => esc_html__('Top Spacing', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
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
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'sup'],
            ]
        );

        $this->add_responsive_control(
            'sup_bottom_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
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
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .inserted--element' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['element_type' => 'sup'],
            ]
        );


        $this->end_controls_tab();


        $this->start_controls_tab(
            'element_motion',
            [
                'label' => esc_html__('Motion', 'pe-core'),
            ]
        );

        $this->add_control(
            'motion',
            [
                'label' => esc_html__('Motion Effects', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'me--rotate' => esc_html__('Rotate', 'pe-core'),
                    'me--flip-x' => esc_html__('Flip X', 'pe-core'),
                    'me--flip-y' => esc_html__('Flip Y', 'pe-core'),
                    'me--slide-left' => esc_html__('Slide Left', 'pe-core'),
                    'me--slide-right' => esc_html__('Slide Right', 'pe-core'),
                    'me--hearth-beat' => esc_html__('Heartbeat', 'pe-core'),

                ],
                'label_block' => true
            ]
        );


        $this->add_control(
            'zoom__in',
            [
                'label' => esc_html__('Pin on Scrol', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'inserted--pin',
                'default' => 'false',
                'description' => esc_html__('Only one pinned element can be used in same wrapper.', 'pe-core'),

            ]
        );

        $this->add_control(
            'zoom__pin__target',
            [
                'label' => esc_html__('Pin Target', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #heroContainer', 'pe-core'),
                'description' => esc_html__('A pin container needed for proper animation. (Parent container recommended)', 'pe-core'),
                'ai' => false,
                'condition' => ['zoom__in' => 'inserted--pin'],
            ]
        );

        $this->add_control(
            'motion_duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'step' => 0.1,
                'default' => 1,
                'condition' => ['motion!' => 'none'],
            ]
        );

        $this->add_control(
            'motion_repeat_delay',
            [
                'label' => esc_html__('Repeat Delay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.1,
                'default' => 0,
                'condition' => ['motion!' => 'none'],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();



        $this->end_controls_section();

        $this->start_controls_section(
            'additonal_options',
            [

                'label' => esc_html__('Additional Options', 'pe-core'),

            ]
        );

        $options = [];

        $products = get_posts([
            'post_type' => 'portfolio',
            'numberposts' => -1
        ]);

        foreach ($products as $product) {
            $options[$product->ID] = $product->post_title;
        }
        $this->add_control(
            'preview_product',
            [
                'label' => __('Preview Product', 'pe-core'),
                'label_block' => true,
                'description' => __('Select product to preview.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
            ]
        );

        $this->end_controls_section();

        pe_text_animation_settings($this);

        $this->start_controls_section(
            'style',
            [

                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        flexOptions($this, ['seperate_words' => 'true'], '.zeyna--project--field .field--content:has(.meta--seperate)', 'metas_flex', 'Metas', true, '.zeyna--project--field .field--content:has(.meta--seperate) > .meta--seperate');
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .text-wrapper p',
            ]
        );
      
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'labels_typography',
                'label' => esc_html__('Labels Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .text-wrapper .field--label',
                'condition' => ['show_label' => 'true'],
            ]
        );


        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'pe-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-text-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'pe-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justify', 'pe-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => is_rtl() ? 'right' : 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .text-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_align_last',
            [
                'label' => esc_html__('Justify Last Line?', 'pe-core'),
                'description' => esc_html__('On mobile screens "br" tags will be removed.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'justify-last',
                'default' => false,
                'condition' => ['alignment' => 'justify'],
            ]
        );


        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', 'custom', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        objectStyles($this, 'field_styles', 'Field', '.text-wrapper.pe--styled--object:not(.is--multiple)', false, false, false, false, true);

        $this->end_controls_section();

        objectStyles($this, 'seperated_metas', 'Meta', 'span.pe--styled--object.meta--seperate', true, ['seperate_words' => 'true'], true, false, false, false);

        $this->start_controls_section(
            'terms_styles',
            [

                'label' => esc_html__('Terms Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['field_type' => ['taxonomy']]
            ]
        );


        flexOptions($this, false, '.zeyna--project--field.is--multiple > *', 'terms_list', 'Terms List', true, '.zeyna--project--field.is--multiple > * > span');

        $cond = ['field_type' => ['taxonomy']];
        objectStyles($this, 'terms_', 'Terms', '.zeyna--project--field.is--multiple .pe--styled--object', true, $cond, false, false, false);


        $this->end_controls_section();
        pe_button_style_settings($this, 'External Button', 'external_button');

        pe_color_options($this);
        pe_cursor_settings($this, false, false);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();


        $this->add_render_attribute(
            'attributes',
            [
                'class' => [$settings['remove_margins'], $settings['remove_breaks'], $settings['text_align_last'], $settings['secondary_color']],
            ]
        );

        $text = 'dummy';
        $type = $settings['field_type'];
        $isMultiple = '';

        $loop = new \WP_Query([
            'post_type' => 'portfolio',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'order' => 'ASC',
            'post__in' => $settings['preview_product'] && \Elementor\Plugin::$instance->editor->is_edit_mode() ? array($settings['preview_product']) : [],
        ]);
        wp_reset_postdata();


        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            while ($loop->have_posts()):
                $loop->the_post();
                $id = get_the_ID();

            endwhile;
            wp_reset_query();

        } else {
            global $wp_query;

            $id = $wp_query->post->ID;
            $previous_post = get_previous_post();
            $next_post = get_next_post();

            if ($settings['get_data'] === 'next') {

                if (!$next_post) {
                    while ($loop->have_posts()):
                        $loop->the_post();
                        $id = get_the_ID();
                    endwhile;
                } else {
                    $id = $next_post->ID;
                }

            } else if ($settings['get_data'] === 'prev') {
                $id = $previous_post->ID;
            }

        }



        if ($type === 'title') {

            if ($settings['linked'] === 'yes') {
                $text = '<a class="barba--trigger" href="' . esc_url(get_the_permalink($id)) . '">' . esc_html(get_the_title($id)) . '</a>';
            } else {
                $text = get_the_title($id);
            }



        } else if ($type === 'excerpt') {

            $text = get_field('excerpt', $id);

        } else if ($type === 'external_link') {

            $attr = [
                'href' => get_field('external_link_url', $id),
                'target' => '_blank',
                'class' => 'pb--handle'
            ];

            $text = pe_button_render($this, $attr, false, 'external_button', get_field('external_link_label', $id), false);

        } else if ($type === 'category') {


            $terms = get_the_terms($id, 'project-categories');

            if ($terms) {

                $term_names = array();

                foreach ($terms as $term) {
                    $term_names[] = esc_html($term->name);
                }

                $text = implode(', ', $term_names);
            }

        } else if ($type === 'meta' && isset($settings["project_fields"])) {

            $project_metas = get_field('project_metas', $id);
            $text = '';

            if ($project_metas) {
                $found = false;

                foreach ($project_metas as $meta) {
                    $content = $settings['seperate_words'] === 'true'
                        ? array_map('trim', explode(",", $meta['content']))
                        : $meta['content'];

                    if (isset($meta['label']) && $meta['label'] === $settings["project_fields"]) {
                        if ($settings['show_label'] === 'true') {
                            $text .= '<span class="field--label">' . $meta['label'] . $settings['field_seperator'] . '</span>';
                        }

                        $text .= '<span class="field--content">';

                        if ($settings['seperate_words'] === 'true' && is_array($content)) {
                            foreach ($content as $item) {
                                $text .= "<span class='pe--styled--object meta--seperate'>{$item}</span>";
                            }
                        } else {
                            $text .= $content;
                        }

                        $text .= '</span>';

                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        $text = $settings["project_fields"];

                    } else {
                        $text = '';
                    }


                }
            } else {
                $text = $settings["project_fields"];
            }


        } else if ($type === 'taxonomy') {

            $text = '';

            $terms = get_the_terms($id, $settings['select_taxonomy']);
            if ($terms) {
                if (is_array($terms) && count($terms) > 1) {
                    $isMultiple = 'is--multiple';
                }
                ob_start();
                foreach ($terms as $key => $term) {
                    if (!is_a($term, 'WP_Term')) {
                        continue;
                    }

                    echo '<span class="field--content pe--styled--object">' . $term->name . '</span>';
                }
                $text = ob_get_clean();

            }

        } else if ($type === 'acf_field') {

            if ($settings['show_label'] === 'true') {
                $text = '<span class="field--label">' . get_field_object($settings['select_acf_field'])['label'] . $settings['field_seperator'] . '</span> '
                    . '<span class="field--content">' . get_field($settings['select_acf_field']) . '</span>';
            } else {
                $text = '<span class="field--content">' . get_field($settings['select_acf_field']) . '</span>';
                ;
            }


        }

        if ($text === '') {
            $this->add_render_attribute(
                '_wrapper',
                [
                    'class' => 'field--empty',
                ]
            );
        }
        ;

        $motionAttr = '';
        $motion = $settings['motion'] !== 'none' ? ' ' . $settings['motion'] : '';


        if ($settings['motion'] !== 'none') {
            $durr = $settings['motion_duration'];
            $dell = $settings['motion_repeat_delay'];

            $motionAttr = ' data-duration="' . $durr . '" data-delay="' . $dell . '"';
        }
        $createdElement = '';

        if ($settings['element_type'] !== 'none') {


            $elementType = $settings['element_type'];

            if ($elementType === 'icon') {

                ob_start();

                \Elementor\Icons_Manager::render_icon($settings['element_icon'], ['aria-hidden' => 'true']);

                $icon = ob_get_clean();

                $createdElement = '<span class="inserted--element inner--icon' . ' ' . $settings['mobile_visibility'] . ' ' . $motion . '" ' . $motionAttr . '>' . $icon . '</span>';

            } else if ($elementType === 'image') {

                $image = '<img src="' . $settings['element_image']['url'] . '">';

                $img = '<span data-zoom-pin="' . $settings['zoom__pin__target'] . '" class="inner--image ' . $settings['mobile_visibility'] . ' ' . $settings['zoom__in'] . ' ' . $settings['ls_behavior'] . ' ' . $settings['ls_default_behavior'] . 'inserted--element ' . $motion . '" ' . $motionAttr . '>' . $image . '</span>';
                $createdElement = $img;

                if ($settings['zoom__in']) {
                    $createdElement = '<span class="inserted--element inserted--ls--hold ' . '">' . $img . '</span>';

                }

            } else if ($elementType === 'video') {


                $video = '<span data-zoom-pin="' . $settings['zoom__pin__target'] . '" class="inner--video vid--no--ratio ' . $settings['mobile_visibility'] . ' ' . $settings['zoom__in'] . ' ' . $settings['ls_behavior'] . 'inserted--element ' . $motion . '" ' . $motionAttr . '>' . pe_video_render(false, $settings) . '</span>';

                $createdElement = $video;

                if ($settings['zoom__in']) {

                    $createdElement = '<span class="inserted--element inserted--ls--hold ' . '">' . $video . '</span>';

                }

            } else if ($elementType === 'sup') {

                $createdElement = '<span class="inserted--element inserted--sup--text  ' . $settings['mobile_visibility'] . ' ' . $motion . '" ' . $motionAttr . '>' . $settings['element_text'] . '</span>';
            }


        }

        if ($type === 'button') {

            $attr = [
                'href' => get_the_permalink($id),
                'class' => 'pb--handle',
                'data-id' => $id
            ];


            pe_button_render($this, $attr, false, 'project_button');
        } else {

            ?>

            <div class="text-wrapper zeyna--project--field <?php echo esc_attr($isMultiple) ?> pe--styled--object">

                <p <?php echo $this->get_render_attribute_string('attributes') ?><?php echo pe_text_animation($this) ?>>
                    <?php
                    if ($settings['insert_at'] === 'before') {
                        echo $createdElement;

                    } ?>
                    <?php echo $text; ?>

                    <?php
                    if ($settings['insert_at'] === 'after') {
                        echo $createdElement;

                    } ?>
                </p>
            </div>

            <?php

        }
    }

}
