<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class peSplineLoader extends Widget_Base
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
        return 'pesplineloader';
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
        return __('Spline Loader (Beta)', 'pe-core');
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
        return 'pe-icon-spline pe-widget';
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
        return ['zeyna-content'];
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
                'label' => __('Spline Loader (Beta)', 'pe-core'),
            ]
        );

        $this->add_control(
            'beta_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
                   Since the widget in it's initial <b>beta version;</b> Elementor editor doesn't supports animating Spline on editor so create/customize and preview your animations on front-end of the page. <a target='_blank' href='https://spline.design'>Spline Editor</a></div>",
            ]
        );


        $this->add_control(
            'spline_url',
            [
                'label' => __('Spline File', 'pe-core'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => "https://prod.spline.design/6Wq1Q7YGyM-iab9i/scene.splinecode",
                'ai' => "false",
                'description' => __('You can create/customize and share Spline files on <a target="_blank" href="https://spline.design">Spline Editor</a>', 'pe-core'),
            ]
        );

        $this->add_control(
            'scene_background',
            [
                'label' => esc_html__('Background', 'pe-core'),
                'description' => esc_html__('If you switch this to "yes"; default scene background will be overwritten.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'default' => '',
                'return_value' => 'true',

            ]
        );

        $this->add_control(
            'edit_mode',
            [
                'label' => esc_html__('Edit Mode', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'default' => '',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'animations',
            [
                'label' => esc_html__('Animations', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'default' => '',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'animations_json',
            [
                'label' => esc_html__('Animations JSON', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'json',
                'rows' => 10,
                'ai' => false,
            ]
        );


        $this->add_control(
            'object_preferences_json',
            [
                'label' => esc_html__('Preferences JSON', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'json',
                'rows' => 10,
                'ai' => false,
            ]
        );


        $this->add_control(
            'scene_background_color',
            [
                'label' => esc_html__('Background Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'scene_background' => 'true',
                ],
            ]
        );

        $this->add_responsive_control(
            'scene_width',
            [
                'label' => esc_html__('Scene Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'em', 'rem', 'custom'],
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
                    'vw' => [
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
                    '{{WRAPPER}} .pe--spline--loader canvas.spline--canvas' => 'width: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} spline-viewer' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'scene_height',
            [
                'label' => esc_html__('Scene Height', 'pe-core'),
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
                    '{{WRAPPER}} .pe--spline--loader canvas.spline--canvas' => 'height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} spline-viewer' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );



        $this->end_controls_section();

        pe_general_animation_settings($this);

        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $file = $settings['spline_url'];

        $sceneSettings = '{ 
            "background" : ' . ($settings['scene_background'] === "true" ? '"' . $settings['scene_background_color'] . '"' : '""') . ' 
            ,"editMode" : ' . ($settings['edit_mode'] === "true" ? '"true"' : '""') . ' 
            ,"animations" : ' . ($settings['animations'] === "true" ? '"true"' : '""') . ' 
          
        }';

        $animationsJSON = !empty($settings['animations_json']) ? $settings['animations_json'] : "[]";
        $objectJSON = !empty($settings['object_preferences_json']) ? $settings['object_preferences_json'] : "[]";

        

        ?>

        <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) { ?>

            <script type="module" src="https://unpkg.com/@splinetool/viewer@1.10.88/build/spline-viewer.js"></script>
            <spline-viewer style="width: 100vw;height: 100vh" url="<?php echo esc_url($file) ?>"></spline-viewer>

        <?php } else { ?>

            <div data-object-preferences='<?php echo $objectJSON ?>' data-animations='<?php echo $animationsJSON ?>'
                data-settings='<?php echo $sceneSettings ?>' class="pe--spline--loader" data-object="<?php echo $file ?>">
                <canvas class="spline--canvas"></canvas>
            </div>

            <!-- <div data-lenis-prevent id="animation--gui"></div> -->

        <?php } ?>

        <?php

    }

}
