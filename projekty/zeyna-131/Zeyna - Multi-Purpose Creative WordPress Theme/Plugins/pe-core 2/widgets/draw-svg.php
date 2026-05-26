<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class peDrawSVG extends Widget_Base
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
        return 'pedrawsvg';
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
        return __('Draw SVG', 'pe-core');
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
        return 'eicon-svg pe-widget';
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
                'label' => __('Draw SVG', 'pe-core'),
            ]
        );

        $this->add_control(
            'svg_draw_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-info">	
	           <span>This widget only animates the strokes of SVG elements. If you upload an SVG that contains filled elements, a stroke will be automatically added.</span></div>',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__('SVG', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
            ]
        );

        $this->add_responsive_control(
            'svg_width',
            [
                'label' => esc_html__('SVG Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--draw--svg' => 'width: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'drawing_type',
            [
                'label' => esc_html__('Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'autoplay',
                'render_type' => 'template',
                'prefix_class' => 'draw--',
                'options' => [
                    'scroll' => esc_html__('Follow Scroll', 'pe-core'),
                    'autoplay' => esc_html__('Autoplay', 'pe-core'),
                    'hover' => esc_html__('Hover', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'draw_loop',
            [
                'label' => esc_html__('Loop', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'prefix_class' => '',
                'render_type' => 'template',
                'return_value' => 'drawing--loop',
                'default' => '',
                'condition' => ['drawing_type!' => 'scroll'],
            ]
        );

        $this->add_control(
            'draw_loop_reverse',
            [
                'label' => esc_html__('Loop Reverse', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'prefix_class' => '',
                'render_type' => 'template',
                'return_value' => 'drawing--loop--reverse',
                'default' => '',
                'condition' => [
                    'drawing_type' => ['autoplay', 'hover'],
                    'draw_loop' => 'drawing--loop',
                ],
            ]
        );

        $this->add_control(
            'draw_duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'description' => esc_html__('Seconds', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'step' => 0.1,
                'default' => 5,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'draw_delay',
            [
                'label' => esc_html__('Delay', 'pe-core'),
                'description' => esc_html__('Seconds', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'step' => 0.1,
                'default' => 0,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'draw_stagger',
            [
                'label' => esc_html__('Stagger', 'pe-core'),
                'description' => esc_html__('Seconds', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.1,
                'default' => 0,
                'render_type' => 'template',
            ]
        );


        $this->add_control(
            'svg_references_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
                   This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );

        $this->add_control(
            'svg_item_ref_start',
            [
                'label' => esc_html__('Item Reference Point', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'toggle' => false,
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );

        $this->add_control(
            'svg_window_ref_start',
            [
                'label' => esc_html__('Window Reference Point', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'bottom',
                'toggle' => false,
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );

        $this->add_control(
            'svg_ref_start_offset',
            [
                'label' => esc_html__('Start Offset', 'pe-core'),
                'description' => esc_html__('An offset value (px) which will be added to pinning start position.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 10000,
                'step' => 1,
                'default' => 0,
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );

        $this->add_control(
            'svg_end_references',
            [
                'label' => esc_html__('End References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );


        $this->add_control(
            'svg_item_ref_end',
            [
                'label' => esc_html__('Item Reference Point', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'bottom',
                'toggle' => false,
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );

        $this->add_control(
            'svg_window_ref_end',
            [
                'label' => esc_html__('Window Reference Point', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'toggle' => false,
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );

        $this->add_control(
            'svg_ref_end_offset',
            [
                'label' => esc_html__('End Offset', 'pe-core'),
                'description' => esc_html__('An offset value (px) which will be added to pinning end position. ', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 10000,
                'step' => 1,
                'default' => 0,
                'condition' => ['drawing_type' => 'scroll'],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'svg_styles',
            [
                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'svg_blend_mode',
            [
                'label' => esc_html__('SVG Blend', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'normal' => esc_html__('Normal', 'pe-core'),
                    'multiply' => esc_html__('Multiply', 'pe-core'),
                    'color' => esc_html__('Color', 'pe-core'),
                    'color-burn' => esc_html__('Color Burn', 'pe-core'),
                    'darken' => esc_html__('Darken', 'pe-core'),
                    'difference' => esc_html__('Difference', 'pe-core'),
                    'hard-light' => esc_html__('Hard Light', 'pe-core'),
                    'screen' => esc_html__('Screen', 'pe-core'),
                    'overlay' => esc_html__('Overlay', 'pe-core'),
                ],
                'default' => 'normal',
                'selectors' => [
                    '{{WRAPPER}} ' => 'mix-blend-mode: {{VALUE}}',
                ],
                'label_block' => false
            ]
        );

        $this->add_control(
            'svg_stroke_color',
            [
                'label' => esc_html__('Stroke Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe--draw--svg .has--theme--stroke:not(:has(.has--theme--stroke))' => 'stroke: {{VALUE}} !important',
                    '{{WRAPPER}} g , 
                    {{WRAPPER}} circle, 
                    {{WRAPPER}} path , 
                    {{WRAPPER}} polygon,  
                    {{WRAPPER}} ellipse,  
                    {{WRAPPER}} line,  
                    {{WRAPPER}} rect' => 'stroke: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_responsive_control(
            'svg_stroke_width',
            [
                'label' => esc_html__('Stroke Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} g , 
                    {{WRAPPER}} circle, 
                    {{WRAPPER}} path , 
                    {{WRAPPER}} polygon,  
                    {{WRAPPER}} ellipse,  
                    {{WRAPPER}} line,  
                    {{WRAPPER}} rect' => 'stroke-width: {{SIZE}}{{UNIT}} !important',
                ],

            ]
        );

        $this->end_controls_section();



        // pe_cursor_settings($this);


        objectStyles($this, 'icon_', 'Icon', '.pe--styled--object', false);

        pe_color_options($this);


    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();

        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
        $icon = ob_get_clean();

        $scrollRefs = '';
        if ($settings['drawing_type'] === 'scroll') {

            $this->add_render_attribute(
                'scroll_refs',
                [
                    'data-start' => $settings['svg_item_ref_start'] . '+=' . $settings['svg_ref_start_offset'] . ' ' . $settings['svg_window_ref_start'],
                    'data-end' => $settings['svg_item_ref_end'] . '+=' . $settings['svg_ref_end_offset'] . ' ' . $settings['svg_window_ref_end'],
                ]
            );

            $scrollRefs = $this->get_render_attribute_string('scroll_refs');

        }


        ?>


        <div <?php echo $scrollRefs ?> data-stagger="<?php echo esc_attr($settings['draw_stagger']) ?>"
            data-delay="<?php echo esc_attr($settings['draw_delay']) ?>"
            data-duration="<?php echo esc_attr($settings['draw_duration']) ?>" class="pe--draw--svg">
            <?php echo $icon ?>
        </div>

        <?php
    }

}
