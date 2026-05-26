<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class peInfoSequence extends Widget_Base
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
        return 'peinfosequence';
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
        return __('Infosequence', 'pe-core');
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
        return 'eicon-animation pe-widget';
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

    protected function get_content_render($info, $i, $settings)
    {
        ob_start();

        $active = $i == 0 ? 'active' : ''; ?>

        <div class="seq--info--content info--content-<?php echo esc_attr($i . ' ' . $active) ?> pe--styled--object">

            <?php if ($info['add_icon'] === 'yes') {

                ob_start();
                \Elementor\Icons_Manager::render_icon($info['info_icon'], ['aria-hidden' => 'true']);
                $infoIcon = ob_get_clean();
                echo '<div class="seq--info--icon">' . $infoIcon . '</div>';
            }
            ?>

            <p class="seq--info--title <?php echo $settings['title_text_type'] ?>"><?php echo $info['info_title'] ?></p>
            <p class="seq--info--text"><?php echo $info['info_text'] ?></p>

            <?php
            if ($info['add_image'] === 'yes') {

                echo '<div class="seq--info--image">' .
                    wp_get_attachment_image($info['info_image']['id'], $settings['image_size'])
                    . '</div>';
            }

            ?>
        </div>
        <?php
        return ob_get_clean();
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


        // Tab Title Controlx
        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __('Infosequence', 'pe-core'),
            ]
        );

        $this->add_control(
            'sequence_type',
            [
                'label' => esc_html__('Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'circle ',
                'options' => [
                    'circle' => esc_html__('Circle', 'pe-core'),
                    'timeline' => esc_html__('Timeline', 'pe-core'),
                    'graphic' => esc_html__('Graphic', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'circle_behavior',
            [
                'label' => esc_html__('Behavior', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'click ',
                'options' => [
                    'click' => esc_html__('Click', 'pe-core'),
                    'rotate' => esc_html__('Rotate', 'pe-core'),
                    'draw' => esc_html__('Draw', 'pe-core'),
                ],
                'condition' => ['sequence_type' => 'circle'],
            ]
        );

        $this->add_control(
            'open_multiple',
            [
                'label' => esc_html__('Open Multiple?', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'open--multiple',
                'prefix_class' => '',
                'default' => '',
                'render_type' => 'template',
                'condition' => ['circle_behavior' => 'click'],
            ]
        );


        $repeater = new \Elementor\Repeater();

        $repeater->start_controls_tabs(
            'info_tabs',
            [

            ]

        );


        $repeater->start_controls_tab(
            'info_content_tab',
            [
                'label' => esc_html__('Content', 'pe-core'),
            ]
        );

        $repeater->add_control(
            'info_main_type',
            [
                'label' => esc_html__('Point Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => esc_html__('Text', 'pe-core'),
                    'icon' => esc_html__('Icon', 'pe-core'),
                ],
            ]
        );


        $repeater->add_control(
            'info_main',
            [
                'label' => esc_html__('Point Text', 'pe-core'),
                'default' => esc_html__('1', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'condition' => ['info_main_type' => 'text'],
            ]
        );

        $repeater->add_control(
            'info_main_icon',
            [
                'label' => esc_html__('Point Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_downward',
                    'library' => 'material-design-icons',
                ],
                'condition' => ['info_main_type' => 'icon'],
            ]
        );

        $repeater->add_control(
            'info_title',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'default' => esc_html__('Lorem ipsum', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
            ]
        );


        $repeater->add_control(
            'info_text',
            [
                'label' => esc_html__('Text', 'pe-core'),
                'default' => esc_html__('Proin ut ipsum eu sem pretium tempor et id elit. Nam accumsan, erat a tempor tristique, ipsum lacus egestas eros, in mattis leo mauris vitae lorem.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'ai' => false,
            ]
        );

        $repeater->add_control(
            'add_icon',
            [
                'label' => esc_html__('Add Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $repeater->add_control(
            'add_image',
            [
                'label' => esc_html__('Add Image', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $repeater->add_control(
            'info_icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_downward',
                    'library' => 'material-design-icons',
                ],
                'condition' => ['add_icon' => 'yes'],
            ]
        );

        $repeater->add_control(
            'info_image',
            [
                'label' => esc_html__('Info Image', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => ['add_image' => 'yes',]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'info_position_tab',
            [
                'label' => esc_html__('Position', 'pe-core'),
            ]
        );


        $repeater->add_responsive_control(
            'info_transform_x',
            [
                'label' => esc_html__('Transform X', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
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
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--transformX: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'info_transform_y',
            [
                'label' => esc_html__('Transform Y', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
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
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--transformY: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();



        $this->add_control(
            'infos',
            [
                'label' => esc_html__('Infos', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ info_main }}}',
                'show_label' => false,
            ]
        );


        $this->add_responsive_control(
            'circle_size',
            [
                'label' => esc_html__('Circle Size', 'pe-core'),
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
                    '{{WRAPPER}} .pe--infosequence .seq--circle' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['sequence_type' => 'circle'],

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
                'default' => '',
                'render_type' => 'template',
                'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
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
                'default' => '',
                'render_type' => 'template',
                'description' => esc_html__('Animation will be pinned to window during animation.', 'pe-core'),
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );


        $this->add_control(
            'pin_target',
            [
                'label' => esc_html__('Pin Target', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
                'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),
                'condition' => ['pin' => 'true'],
            ]
        );


        $this->add_control(
            'seq_references_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
                   This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );

        $this->add_control(
            'seq_item_ref_start',
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
                'default' => 'center',
                'toggle' => false,
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );

        $this->add_control(
            'seq_window_ref_start',
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
                'default' => 'center',
                'toggle' => false,
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );

        $this->add_control(
            'seq_ref_start_offset',
            [
                'label' => esc_html__('Start Offset', 'pe-core'),
                'description' => esc_html__('An offset value (px) which will be added to pinning start position.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 10000,
                'step' => 1,
                'default' => 0,
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );

        $this->add_control(
            'seq_end_references',
            [
                'label' => esc_html__('End References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );


        $this->add_control(
            'seq_item_ref_end',
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
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );

        $this->add_control(
            'seq_window_ref_end',
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
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );

        $this->add_control(
            'seq_ref_end_offset',
            [
                'label' => esc_html__('End Offset', 'pe-core'),
                'description' => esc_html__('An offset value (px) which will be added to pinning end position. ', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 10000,
                'step' => 1,
                'default' => 1000,
                'condition' => ['circle_behavior' => ['draw', 'rotate']],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'seq_styles',
            [
                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'exclude' => [],
                'include' => [],
                'default' => 'medium_large',
            ]
        );

        $this->add_control(
            'seq_stroke_color',
            [
                'label' => esc_html__('Stroke Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe--infosequence .seq--circle circle' => 'stroke: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_responsive_control(
            'seq_stroke_width',
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
                    '{{WRAPPER}} .pe--infosequence .seq--circle circle' => 'stroke-width: {{SIZE}}{{UNIT}} !important',
                ],
            ]
        );
      
        $this->add_responsive_control(
            'icons_size',
            [
                'label' => esc_html__('Icons Size', 'pe-core'),
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
                    '{{WRAPPER}} .pe--infosequence .seq--info--content .seq--info--icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'main_typography',
                'label' => esc_html__('Points Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .seq--info--main.pe--styled--object',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Titles Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .seq--info--title',
            ]
        );

        $this->add_control(
            'title_text_type',
            [
                'label' => esc_html__('Title Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
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
                'toggle' => true,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Texts Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .seq--info--text',
            ]
        );

        objectAbsolutePositioning($this, '.pe--infosequence[data-behavior="rotate"] .seq--info--content', 'center_content', 'Centered Content', ['circle_behavior' => ['rotate']]);


        $this->add_responsive_control(
            'contents_max_width',
            [
                'label' => esc_html__('Contents Max Width', 'pe-core'),
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
                    '{{WRAPPER}} .pe--infosequence .seq--info--content' => 'width: {{SIZE}}{{UNIT}} !important;max-width: {{SIZE}}{{UNIT}} !important',
                ],
            ]
        );

        $this->end_controls_section();



        // pe_cursor_settings($this);


        objectStyles($this, 'icon_', 'Points', '.seq--info--main.pe--styled--object', false);

        objectStyles($this, 'content_', 'Content', '.seq--info--content.pe--styled--object', false);



        pe_color_options($this);


    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $infos = $settings['infos'];
        $length = count($infos);

        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
        $icon = ob_get_clean();

        $scrollRefs = '';
        if ($settings['circle_behavior'] === 'draw' || $settings['circle_behavior'] === 'rotate') {

            $this->add_render_attribute(
                'scroll_refs',
                [
                    'data-start' => $settings['seq_item_ref_start'] . '+=' . $settings['seq_ref_start_offset'] . ' ' . $settings['seq_window_ref_start'],
                    'data-end' => $settings['seq_item_ref_end'] . '+=' . $settings['seq_ref_end_offset'] . ' ' . $settings['seq_window_ref_end'],
                    'data-scrub' => $settings['scrub'],
                    'data-pin' => $settings['pin'],
                    'data-pin-target' => $settings['pin_target'],
                ]
            );
            $scrollRefs = $this->get_render_attribute_string('scroll_refs');
        }

        $this->add_render_attribute(
            'attributes',
            [
                'data-type' => $settings['sequence_type'],
                'data-behavior' => $settings['circle_behavior'],
            ]
        );

        $attributes = $this->get_render_attribute_string('attributes');
        ?>


        <div <?php echo $scrollRefs . $attributes ?> class="pe--infosequence">

            <svg class="seq--circle" viewBox="0 0 180 180">
                <circle id="seq-circle" cx="90" cy="90" r="89" />
            </svg>

            <div class="infos--wrapper" style="--length: <?php echo $length; ?>">

                <?php foreach ($infos as $key => $info) {
                    $i = $key;
                    $active = $i == 0 && ($settings['circle_behavior'] === 'click' || $settings['circle_behavior'] === 'rotate') ? 'active' : '';
                    $pos = $i < $length / 2 ? 'right' : 'left';

                    ?>
                    <div data-index="<?php echo esc_attr($i) ?>"
                        class="seq--info info-<?php echo esc_attr($i . ' ' . $active . ' pos--' . $pos . ' elementor-repeater-item-' . $info['_id']) ?>"
                        style="--i: <?php echo $i ?>">
                        <div class="seq--info--main pe--styled--object"><?php
                        if ($info['info_main_type'] === 'icon') {
                            ob_start();
                            \Elementor\Icons_Manager::render_icon($info['info_main_icon'], ['aria-hidden' => 'true']);
                            $icon = ob_get_clean();
                            echo $icon;
                        } else {
                            echo $info['info_main'];
                        }

                        ?></div>

                        <?php if ($settings['circle_behavior'] !== 'rotate') {
                            echo $this->get_content_render($info, $i, $settings);
                        }
                        ?>
                    </div>
                <?php } ?>

            </div>

            <?php if ($settings['circle_behavior'] === 'rotate') {

                foreach ($infos as $key => $info) {

                    $i = $key;
                    $active = $i == 0 && ($settings['circle_behavior'] === 'click' || $settings['circle_behavior'] === 'rotate') ? 'active' : '';

                    echo $this->get_content_render($info, $i, $settings);
                }
            } ?>

        </div>


        <?php
    }

}
