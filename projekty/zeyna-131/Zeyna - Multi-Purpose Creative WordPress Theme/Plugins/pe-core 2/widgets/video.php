<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeVideo extends Widget_Base
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
        return 'pevideo';
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
        return __('Pe Video', 'pe-core');
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
        return 'eicon-youtube pe-widget';
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
                'label' => __('Video Settings', 'pe-core'),
            ]
        );

        $this->add_control(
            'video_provider',
            [
                'label' => esc_html__('Video Provider', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'self',
                'options' => [
                    'self' => esc_html__('Self', 'pe-core'),
                    'vimeo' => esc_html__('Vimeo', 'pe-core'),
                    'youtube' => esc_html__('Youtube', 'pe-core'),
                    'stream' => esc_html__('Stream', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'self_video',
            [
                'label' => esc_html__('Choose Video', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['video'],
                'condition' => [
                    'video_provider' => ['self']
                ]
            ]
        );

        $this->add_control(
            'stream_url',
            [
                'label' => esc_html__('Video Stream URL', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => esc_html__('Enter video URL here.', 'pe-core'),
                'condition' => [
                    'video_provider' => ['stream']
                ]
            ]
        );

        $this->add_control(
            'youtube_id',
            [
                'label' => esc_html__('Video ID', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter video id here.', 'pe-core'),
                'condition' => [
                    'video_provider' => ['youtube']
                ]
            ]
        );

        $this->add_control(
            'vimeo_id',
            [
                'label' => esc_html__('Video ID', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter video id here.', 'pe-core'),
                'condition' => [
                    'video_provider' => ['vimeo']
                ]
            ]
        );

        $this->add_control(
            'play_on_scroll',
            [
                'label' => esc_html__('Play on Scroll', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',

            ]
        );



        $this->add_control(
            'video_pin',
            [
                'label' => esc_html__('Pin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => 'false',
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );

        $this->add_control(
            'video_pin_spacer',
            [
                'label' => esc_html__('Pin Spacer', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => 'true',
                'condition' => [
                    'play_on_scroll' => 'true',
                    'video_pin' => 'true',
                ],
            ]
        );


        $this->add_control(
            'video_anim_pin_target',
            [
                'label' => esc_html__('Pin Target', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
                'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );


        $this->add_control(
            'video_start_references',
            [
                'label' => esc_html__('Start References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );

        $this->add_control(
            'video_references_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",
                'condition' => ['play_on_scroll' => 'true'],

            ]
        );

        $this->add_control(
            'video_item_ref_start',
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
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );

        $this->add_control(
            'video_window_ref_start',
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
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );

        $this->add_control(
            'video_end_references',
            [
                'label' => esc_html__('End References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );

        $this->add_control(
            'video_item_ref_end',
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
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );

        $this->add_control(
            'video_window_ref_end',
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
                'condition' => ['play_on_scroll' => 'true'],
            ]
        );



        $this->add_control(
            'controls',
            [
                'label' => esc_html__('Controls', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => ['play_on_scroll!' => 'true'],
            ]
        );


        $this->add_control(
            'select_controls',
            [
                'label' => esc_html__('Select Controls', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => [
                    'play' => esc_html__('Play', 'pe-core'),
                    'current-time' => esc_html__('Current Time', 'pe-core'),
                    'duration' => esc_html__('Duration', 'pe-core'),
                    'progress' => esc_html__('Progress Bar', 'pe-core'),
                    'mute' => esc_html__('Mute', 'pe-core'),
                    'volume' => esc_html__('Volume', 'pe-core'),
                    'captions' => esc_html__('Captions', 'pe-core'),
                    'settings' => esc_html__('Settings', 'pe-core'),
                    'pip' => esc_html__('PIP', 'pe-core'),
                    'airplay' => esc_html__('Airplay (Safari Only)', 'pe-core'),
                    'fullscreen' => esc_html__('Fullscreen', 'pe-core'),
                ],
                'default' => ['play', 'current-time', 'duration', 'progress', 'mute', 'volume', 'fullscreen'],
                'condition' => [
                    'controls' => ['true'],
                    'play_on_scroll!' => 'true'
                ]
            ]
        );



        $this->add_control(
            'video_style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'normal',
                'prefix_class' => 'video--',
                'options' => [
                    'normal' => esc_html__('Normal', 'pe-core'),
                    'button' => esc_html__('Button', 'pe-core'),
                ],
                'condition' => ['play_on_scroll!' => 'true'],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true'

                ],
            ]
        );

        $this->add_control(
            'autoplay_observer',
            [
                'label' => esc_html__('Observer', 'pe-core'),
                'description' => esc_html__('Normally, autoplay starts on window load. However, when the observer is enabled, video autoplay starts as soon as the video container approaches the viewport (or a tab/container trigger) and pauses when it leaves the visible area.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true',
                    'autoplay' => 'true',
                ],
            ]
        );

        $this->add_control(
            'word_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-info">	
	           <span>When autoplay is enabled, many browsers require the video to be "muted" for it to autoplay properly.</div>',
                'condition' => [
                    'autoplay' => 'true',
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true'
                ],

            ]
        );

        $this->add_control(
            'muted',
            [
                'label' => esc_html__('Muted', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true'
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true'

                ],
            ]
        );

        $this->add_control(
            'lightbox',
            [
                'label' => esc_html__('Play in Lightbox', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'controls' => ['true'],
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true'
                ]
            ]
        );

        $this->add_control(
            'play_button',
            [
                'label' => esc_html__('Play Button', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__('Icon', 'pe-core'),
                    'text' => esc_html__('Text', 'pe-core'),
                    'button' => esc_html__('Button', 'pe-core'),
                    'none' => esc_html__('None', 'pe-core'),
                ],
                'condition' => [
                    'controls' => ['true'],
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true'
                ]
            ]
        );


        $this->add_control(
            'play_text',
            [
                'label' => esc_html__('Play Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('PLAY', 'pe-core'),
                'condition' => [
                    'play_button' => ['text'],
                    'controls' => ['true'],
                    'video_style!' => 'button',
                    'play_on_scroll!' => 'true'
                ],

            ]
        );

        $this->add_control(
            'video_poster',
            [
                'label' => esc_html__('Video Poster', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => ['video_style!' => 'button'],
            ]
        );

        $this->add_control(
            'poster_image',
            [
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'video_poster' => ['true'],
                    'video_style!' => 'button'
                ],
            ]
        );

        pe_button_settings($this, false, ['video_style' => 'button'], 'video_button', false, 'Button');

        $this->end_controls_section();

        pe_button_settings($this, false, ['play_button' => 'button'], 'play_button', true);

        pe_button_style_settings($this, 'Play', 'play_button', ['play_button' => 'button']);
        pe_button_style_settings($this, 'Button', 'video_button', ['video_style' => 'button']);

        $this->start_controls_section(
            'Style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'player_skin',
            [
                'label' => esc_html__('Player Skin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'global',
                'options' => [
                    'global' => esc_html__('Use Global', 'pe-core'),
                    'skin--simple' => esc_html__('Simple', 'pe-core'),
                    'skin--rounded' => esc_html__('Rounded', 'pe-core'),
                    'skin--minimal' => esc_html__('Minimal', 'pe-core'),
                ],
            ]
        );


        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['vh', 'vw', 'px', '%'],
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'vw' => [
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
                    '{{WRAPPER}} .pe-video' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'custom_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['vw', 'vh', 'px', '%'],
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe-video' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pe-video.pe-stream video' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'player_main_color',
            [
                'label' => esc_html__('Main Skin Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe-video' => '--plyr-color-main: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_secondary_color',
            [
                'label' => esc_html__('Secondary Skin Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe-video' => '--plyr-color-secondary: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_progress_bg',
            [
                'label' => esc_html__('Progress Buffered Background', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe-video' => '--plyr-video-progress-buffered-background: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'controls_spacing',
            [
                'label' => esc_html__('Controls Spacing', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe-video' => '--plyr-control-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'controls_font-size',
            [
                'label' => esc_html__('Controls Font Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe-video' => '--plyr-font-size-small: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .pe-video' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'player_border',
                'selector' => '{{WRAPPER}} .pe-video',
                'important' => true
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .pe-video',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'play_text_typography',
                'label' => esc_html__('Play Text Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--play',
                'condition' => [
                    'play_button' => ['text'],
                    'controls' => ['true']
                ],
            ]
        );

        objectStyles($this, 'play_button', 'Play/Pause Button', '.pe--large--play.pe--styled--object', true, false, false, false, true, true);
        pe_background_hover_settings($this);

        $this->end_controls_section();
        pe_color_options($this);
        pe_cursor_settings($this);
        pe_general_animation_settings($this);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $skin = $settings['player_skin'];
        $provider = $settings['video_provider'];
        $video_id = '';

        if ($provider === 'youtube') {
            $video_id = $settings['youtube_id'];
        }

        if ($provider === 'vimeo') {
            $video_id = $settings['vimeo_id'];
        }

        $controls = [];
        if ($settings['select_controls']) {
            foreach ($settings['select_controls'] as $control) {
                array_push($controls, $control);
            }
        }

        if ($settings['play_on_scroll'] === 'true') {

            $scrollOpt = '{' .
                'id=' . $this->get_id() . '' .
                ';pin=' . $settings['video_pin'] . '' .
                ';pinSpacer=' . $settings['video_pin_spacer'] . '' .
                ';pinTarget=' . $settings['video_anim_pin_target'] . '' .
                ';item_ref_start=' . $settings['video_item_ref_start'] . '' .
                ';window_ref_start=' . $settings['video_window_ref_start'] . '' .
                ';item_ref_end=' . $settings['video_item_ref_end'] . '' .
                ';window_ref_end=' . $settings['video_window_ref_end'] . '' .
                '}';
        } else {
            $scrollOpt = false;
        }

        $this->add_render_attribute(
            'parent_attributes',
            [
                'class' => ['pe-video', 'pe-' . $provider, $skin],
                'data-controls' => implode(',', $controls),
                'data-autoplay' => $settings['autoplay'],
                'data-observer' => $settings['autoplay_observer'],
                'data-muted' => $settings['muted'],
                'data-loop' => $settings['loop'],
                'data-lightbox' => $settings['lightbox'],
                'data-play-on-scroll' => $settings['play_on_scroll'],
                'data-scroll' => $scrollOpt
            ]
        );

        $this->add_render_attribute(
            'embed_attributes',
            [
                'class' => ['p-video'],
                'data-plyr-provider' => $provider,
                'data-plyr-embed-id' => $video_id,
            ]
        );

        ?>


        <!-- Video -->
        <div <?php echo $this->get_render_attribute_string('parent_attributes') ?>         <?php echo pe_general_animation($this) ?>>

            <?php if ($settings['lightbox'] === 'true' || $settings['video_style'] === 'button') { ?>
                <div class="pe--lightbox--close x-icon">

                    <div class="pl--close--icon">
                        <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../assets/img/close.svg'); ?>">
                    </div>

                </div>
            <?php }

            if ($settings['video_style'] === 'button') {
                pe_button_render($this, false, false, 'video_button', '', false, false, false);
            }

            if ($settings['controls'] === 'true') {

                if ($settings['play_button'] !== 'none') {

                    if ($settings['play_button'] === 'icon') { ?>

                        <div class="pe--large--play pe--styled--object icons" <?php echo pe_background_hover($this) ?>>

                            <div class="pe--play">

                                <svg xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" viewBox="0 -960 960 960" width="24">
                                    <path d="M320-200v-560l440 280-440 280Z" />
                                </svg>

                            </div>

                        </div>

                    <?php } else if ($settings['play_button'] === 'button') { ?>

                            <div class="pe--large--play">

                            <?php pe_button_render($this, false, false, 'play_button'); ?>

                            </div>
                    <?php } else { ?>

                            <div class="pe--large--play pe--styled--object texts">
                                <div class="pe--play">
                                <?php echo esc_html($settings['play_text']); ?>
                                </div>
                            </div>

                    <?php }
                }
            } ?>

            <?php if ($settings['video_poster'] === 'true') { ?>

                <div class="pe--video--poster">
                    <?php
                    echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'full', 'poster_image');
                    ?>
                </div>

            <?php } ?>

            <?php if ($provider === 'self' || $provider === 'stream') {

                $url = '';
                if ($provider === 'self') {
                    $url = $settings['self_video']['url'];
                } else if ($provider === 'stream') {
                    $url = $settings['stream_url'];
                }
                ?>

                <video class="p-video" playsinline loop autoplay>
                    <source src="<?php echo esc_url($url) ?>">
                </video>


            <?php } else { ?>

                <div <?php echo $this->get_render_attribute_string('embed_attributes') ?>></div>

            <?php } ?>


        </div>

        <div class="video-pin-spacer"></div>
        <!--/ Video -->

        <?php if ($settings['lightbox'] === 'true') { ?>
            <div class="pe--lightbox--hold"></div>
        <?php }

        // if ($settings['play_on_scroll'] === 'true' && $settings['video_pin'] === 'true') {
        //     echo '<div class="video-pin-spacer"></div>';
        // }
        ;


    }

}
