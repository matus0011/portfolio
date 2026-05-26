<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeTestimonials extends Widget_Base
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
        return 'petestimonials';
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
        return __('Testimonials', 'pe-core');
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
        return 'eicon-testimonial-carousel pe-widget';
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




        // Tab Title Control
        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __('Testimonials', 'pe-core'),
            ]
        );
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'avatar',
            [
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'testimonial_name',
            [
                'label' => esc_html__('Name', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('John Doe', 'pe-core'),
            ]
        );

        $repeater->add_control(
            'testimonial_title',
            [
                'label' => esc_html__('Sub-Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Marketing Director', 'pe-core'),
            ]
        );


        $repeater->add_control(
            'testimonial_text',
            [
                'label' => esc_html__('Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('List Content', 'pe-core'),
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla consequat egestas nisi. Vestibulum malesuada fermentum nibh. Donec venenatis, neque et pellentesque efficitur, lectus est preti.', 'pe-core'),

            ]
        );


        $this->add_control(
            'testimonials',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'testimonial_name' => esc_html__('John Doe', 'pe-core'),
                        'testimonial_title' => esc_html__('Marketing Director', 'pe-core'),
                        'testimonial_text' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla consequat egestas nisi. Vestibulum malesuada fermentum nibh. Donec venenatis, neque et pellentesque efficitur, lectus est preti.', 'pe-core'),
                    ],
                    [
                        'testimonial_name' => esc_html__('John Doe', 'pe-core'),
                        'testimonial_title' => esc_html__('Marketing Director', 'pe-core'),
                        'testimonial_text' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla consequat egestas nisi. Vestibulum malesuada fermentum nibh. Donec venenatis, neque et pellentesque efficitur, lectus est preti.', 'pe-core'),
                    ],
                    [
                        'testimonial_name' => esc_html__('John Doe', 'pe-core'),
                        'testimonial_title' => esc_html__('Marketing Director', 'pe-core'),
                        'testimonial_text' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla consequat egestas nisi. Vestibulum malesuada fermentum nibh. Donec venenatis, neque et pellentesque efficitur, lectus est preti.', 'pe-core'),
                    ],
                ],
                'title_field' => '{{{ testimonial_name }}}',
            ]
        );


        pe_slider_settings($this, false);

        $this->end_controls_section();

        pe_cursor_settings($this, true);

        $this->start_controls_section(
            'section_animate',
            [
                'label' => __('Animations', 'pe-core'),
            ]
        );

        $this->add_control(
            'select_animation',
            [
                'label' => esc_html__('Select Animation', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'has-anim anim-multiple fadeIn' => esc_html__('Fade In', 'pe-core'),
                    'has-anim anim-multiple fadeUp' => esc_html__('Fade Up', 'pe-core'),
                    'has-anim anim-multiple fadeDown' => esc_html__('Fade Down', 'pe-core'),
                    'has-anim anim-multiple fadeLeft' => esc_html__('Fade Left', 'pe-core'),
                    'has-anim anim-multiple fadeRight' => esc_html__('Fade Right', 'pe-core'),
                    'has-anim anim-multiple scaleIn' => esc_html__('Scale In', 'pe-core'),
                    'has-anim anim-multiple slideUp' => esc_html__('Slide Up', 'pe-core'),
                    'has-anim anim-multiple slideLeft' => esc_html__('Slide Left', 'pe-core'),
                    'has-anim anim-multiple slideRight' => esc_html__('Slide Right', 'pe-core'),
                    'has-anim anim-multiple maskUp' => esc_html__('Mask Up', 'pe-core'),
                    'has-anim anim-multiple maskDown' => esc_html__('Mask Down', 'pe-core'),
                    'has-anim anim-multiple maskLeft' => esc_html__('Mask Left', 'pe-core'),
                    'has-anim anim-multiple maskRight' => esc_html__('Mask Right', 'pe-core'),

                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'more_options',
            [
                'label' => esc_html__('Animation Options', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'animation_options_tabs'
        );

        $this->start_controls_tab(
            'basic_tab',
            [
                'label' => esc_html__('Basic', 'pe-core'),
            ]
        );

        $this->add_control(
            'duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'step' => 0.1,
                'default' => 1.5
            ]
        );

        $this->add_control(
            'delay',
            [
                'label' => esc_html__('Delay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.1,
                'default' => 0
            ]
        );

        $this->add_control(
            'stagger',
            [
                'label' => esc_html__('Stagger', 'pe-core'),
                'description' => esc_html__('Delay between animated elements (for multiple element animation types)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'default' => 0.1,
            ]
        );


        $this->add_control(
            'scrub',
            [
                'label' => esc_html__('Scrub', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
            ]
        );

        $this->add_control(
            'pin',
            [
                'label' => esc_html__('Pin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'description' => esc_html__('Animation will be pinned to window during animation.', 'pe-core'),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'advanced_tab',
            [
                'label' => esc_html__('Advanced', 'pe-core'),
            ]
        );

        $this->add_control(
            'show_markers',
            [
                'label' => esc_html__('Markers', 'pe-core'),
                'description' => esc_html__('Shows (only in editor) animation start and end points and adjust them.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'default' => 'false',
                'return_value' => 'true',

            ]
        );

        $this->add_control(
            'anim_start',
            [
                'label' => esc_html__('Animation Start Point', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'description' => esc_html__('Animation will be triggered when the element enters the desired point of the view.', 'pe-core'),
                'options' => [
                    'top top' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'top bottom' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top bottom',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'start_offset',
            [
                'label' => esc_html__('Start Offset', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
            ]
        );

        $this->add_control(
            'anim_end',
            [
                'label' => esc_html__('Animation End Point', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'description' => esc_html__('Animation will be end when the element enters the desired point of the view. (For scrubbed/pinned animations)', 'pe-core'),
                'options' => [
                    'bottom bottom' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'center center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                ],
                'default' => 'bottom bottom',
                'toggle' => false,
            ]
        );


        $this->add_control(
            'end_offset',
            [
                'label' => esc_html__('End Offset', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
            ]
        );


        $this->add_control(
            'pin_target',
            [
                'label' => esc_html__('Pin Target', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
                'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),

            ]
        );

        $this->add_control(
            'animate_out',
            [
                'label' => esc_html__('Animate Out', 'pe-core'),
                'description' => esc_html__('Animation will be played backwards when leaving from viewport. (For scrubbed/pinned animations)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'default' => 'false',
                'return_value' => 'true',

            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();



        $this->start_controls_section(
            'Style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        pe_slider_style_settings($this);


        $this->end_controls_section();


        $this->start_controls_section(
            'items_styles',
            [
                'label' => esc_html__('Items Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        flexOptions($this, false, '.pe-testimonial', 'test_item', 'Items');


        objectStyles($this, 'testimonial_', 'Items', '.pe-testimonial.pe--styled--object', false, false, false, false, false, false);


        flexOptions($this, false, '.pt-head', 'testimonial_head', 'Meta Box');

        objectStyles($this, 'metaox_', 'Meta Box', '.pt-head.pe--styled--object', false, false, false, false, true, true);


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__('Name Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pt-det'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Work Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pt-det span'
            ]
        );


        $this->add_responsive_control(
            'text_type',
            [
                'label' => esc_html__('Content Size', 'pe-core'),
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
                'default' => 'text-h6',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .pe-testimonial .pt-content.pe--styled--object p' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
                ],
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
                    '{{WRAPPER}} .pe-testimonial .pt-content.pe--styled--object p'  => '
                    font-family: var(--sec_typo-font-family) !important;',
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
                    '{{WRAPPER}} .pe-testimonial .pt-content.pe--styled--object p' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
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
                    '{{WRAPPER}} .pe-testimonial .pt-content.pe--styled--object p' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__('Content Typography'),
                'selector' => '{{WRAPPER}} .pe-testimonial .pt-content.pe--styled--object p'
            ]
        );


        $this->add_control(
            'avatar_width',
            [
                'label' => esc_html__('Avatar Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 25
                    ]
                ],
                'default' => [
                    'size' => 50,
                    'unit' => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .pt-avatar' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'avatar_height',
            [
                'label' => esc_html__('Avatar Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 25
                    ]
                ],
                'default' => [
                    'size' => 50,
                    'unit' => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .pt-avatar' => 'height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'avatar_border_radius',
            [
                'label' => esc_html__('Avatar Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_unit' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .pt-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );

        objectStyles($this, 'content_', 'Content', '.pt-content.pe--styled--object', false, false, false, false, true, true);



        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'avatar_images',
                'exclude' => [],
                'include' => [],
                'default' => 'thumbnail',
            ]
        );



        $this->end_controls_section();

        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $arr = [];

        foreach ($settings['testimonials'] as $testimonial) {

            ob_start();
            ?>
            <div class="pe-testimonial pe--styled--object">
                <div class="pt-head pe--styled--object">
                    <?php if ($testimonial['avatar']['url']) { ?>

                        <div class="pt-avatar">
                            <?php echo wp_get_attachment_image($testimonial['avatar']['id'], $settings['avatar_images_size']) ?>
                        </div>

                    <?php } ?>
                    <div class="pt-det">
                        <?php echo esc_html($testimonial['testimonial_name']); ?>
                        <span class="pt-title"><?php echo esc_html($testimonial['testimonial_title']); ?></span>
                    </div>
                </div>
                <div class="pt-content pe--styled--object">
                    <p><?php echo esc_html($testimonial['testimonial_text']); ?></p>
                </div>
            </div>
            <?php

            $html = ob_get_clean();
            $arr[] = $html;

        } ?>


        <?php

        echo pe_slider_render($this, $arr);
    }

}
