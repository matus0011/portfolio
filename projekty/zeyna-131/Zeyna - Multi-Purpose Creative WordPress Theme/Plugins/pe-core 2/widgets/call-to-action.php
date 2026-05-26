<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeCallToAction extends Widget_Base
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
        return 'pecalltoaction';
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
        return __('Call To Action', 'pe-core');
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
        return 'eicon-call-to-action pe-widget';
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
                'label' => __('Call to Action', 'pe-core'),
            ]
        );


        $repeater = new \Elementor\Repeater();

        $repeater->start_controls_tabs('cta_element_tabs');

        $repeater->start_controls_tab(
            'element_content',
            [
                'label' => esc_html__('Content', 'pe-core'),
            ]
        );

        $option = get_option('pe-redux');
        $lottie = [];

        if ($option['pe_lotties'] == true) {
            $lottie = ['lottie' => esc_html__('Lottie', 'pe-core'),];
        }


        $repeater->add_control(
            'cta_select_element',
            [
                'label' => esc_html__('Select Element', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'title',
                'options' => [
                    'title' => esc_html__('Title', 'pe-core'),
                    'text' => esc_html__('Text', 'pe-core'),
                    'icon' => esc_html__('Icon', 'pe-core'),
                    'button' => esc_html__('Button', 'pe-core'),
                    'image' => esc_html__('Image', 'pe-core'),
                    ...$lottie
                ],
            ]
        );


        $repeater->add_control(
            'input_type',
            [
                'label' => esc_html__('Input', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'url',
                'options' => [
                    'url' => esc_html__('URL', 'pe-core'),
                    'upload' => esc_html__('Upload', 'pe-core'),
                ],
                'condition' => ['cta_select_element' => 'lottie'],
            ]
        );

        $repeater->add_control(
            'lottie_file',
            [
                'label' => esc_html__('Upload JSON', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://lottie.host/cfad902b-bab5-447c-8ba0-deb0bd1f3536/CDRHoL0tIA.lottie',
                ],
                'media_types' => ['application/json', 'json', 'lottie', 'application'],
                'condition' => [
                    'input_type' => 'upload',
                    'cta_select_element' => 'lottie'
                ],
            ]
        );

  

        $repeater->add_control(
            'lottie_url',
            [
                'label' => esc_html__('Lottie URL', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'https://lottie.host/272b60dd-462d-42a3-8ed6-fec4143633d6/X4FxBascRI.json',
                'ai' => false,
                'condition' => [
                    'input_type' => 'url',
                    'cta_select_element' => 'lottie'
                ],
            ]
        );

        $repeater->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'autoplay',
                'prefix_class' => '',
                'default' => 'autoplay',
                'render_type' => 'template',
                'condition' => ['cta_select_element' => 'lottie'],
            ]
        );
        $repeater->add_control(
            'speed',
            [
                'label' => esc_html__('Speed', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [''],
                'range' => [
                    '' => [
                        'min' => 1,
                        'max' => 3,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '',
                    'size' => 1
                ],
                'condition' => ['cta_select_element' => 'lottie'],
            ]
        );


        $repeater->add_control(
            'cta_title',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('Call to action.', 'pe-core'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => ['cta_select_element' => 'title'],
            ]
        );

        $repeater->add_control(
            'cta_text',
            [
                'label' => esc_html__('Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'label_block' => true,
                'placeholder' => esc_html__('Lorem ipsum .', 'pe-core'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => ['cta_select_element' => 'text'],
            ]
        );


        $repeater->add_control(
            'cta_icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_outward',
                    'library' => 'material-design-icons',
                ],
                'condition' => ['cta_select_element' => 'icon'],
            ]
        );

        $repeater->add_control(
            'cta_image',
            [
                'label' => esc_html__('Image', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => ['cta_select_element' => 'image'],
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'cta_image_size',
                'exclude' => [],
                'include' => [],
                'default' => 'medium',
                'condition' => ['cta_select_element' => 'image'],
            ]
        );


        pe_button_settings($repeater, true, ['cta_select_element' => 'button'], 'cta_button', false, 'Button');


        $repeater->add_control(
            'cta_element_visibility',
            [
                'label' => esc_html__('Visibility', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Visible', 'pe-core'),
                    'show-on-hover' => esc_html__('Show on Hover', 'pe-core'),
                    'hide-on-hover' => esc_html__('Hide on Hover', 'pe-core'),
                ],
            ]
        );

        pe_text_hover_settings($repeater, 'cta_text', ['cta_select_element' => ['text', 'title']]);
        pe_icon_hover_settings($repeater, 'cta_icon', ['cta_select_element' => ['icon']]);
        pe_image_hover_settings($repeater, 'cta_image', ['cta_select_element' => 'image'], '.cta--element');

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'element_style',
            [
                'label' => esc_html__('Style', 'pe-core'),
            ]
        );

        $repeater->add_responsive_control(
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
                'prefix_class' => 'text--align--',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $repeater->add_control(
            'title_tag',
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
                'default' => 'h6',
                'toggle' => false,
                'label_block' => true,
                'frontend_available' => true,
                'condition' => ['cta_select_element' => 'title'],
            ]
        );

        $repeater->add_control(
            'element_use_secondary_font',
            [
                'label' => esc_html__('Use Secondary Font', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' => '
                font-family: var(--sec_typo-font-family);
                font-size: var(--sec_typo-font-size);
                line-height: var(--sec_typo-line-height);
                letter-spacing: var(--sec_typo-letter-spacing);
                font-weight: var(--sec_typo-font-weight);
           text-transform: var(--sec_typo-text-transform);',
                ],

            ]
        );

        $repeater->add_control(
            'secondary_color',
            [
                'label' => esc_html__('Use Secondary Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: var(--secondaryColor);--color: var(--secondaryColor)',
                ],

            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'element_typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} p , {{WRAPPER}} {{CURRENT_ITEM}} > *',
                'condition' => ['cta_select_element' => ['title', 'text', 'icon']],
            ]
        );

        $repeater->add_responsive_control(
            'element_image_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'rem', 'em', 'custom'],
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
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
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
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['cta_select_element' => ['image']],
            ]
        );

        $repeater->add_responsive_control(
            'element_image_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'rem', 'em', 'custom'],
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
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
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
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'min-height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['cta_select_element' => ['image']],
            ]
        );

        $repeater->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
                ],
                'condition' => ['cta_select_element' => ['image']],
            ]
        );

        $repeater->add_control(
            'svg_fill',
            [
                'label' => esc_html__('Fill with site colors? (SVG)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'icon--fill--yes',
                'default' => '',
                'condition' => ['cta_select_element' => ['icon']],
            ]
        );


        pe_button_style_settings($repeater, 'Button', 'cta_button', ['cta_select_element' => 'button'], false, '{{CURRENT_ITEM}} .pe--button');

        objectStyles($repeater, 'element_', 'Element', '.pe--styled--object{{CURRENT_ITEM}}', false, false, false, false, true);

        pe_background_hover_settings($repeater, 'cta_element', false);

        objectAbsolutePositioning($repeater, '{{CURRENT_ITEM}}', 'item_', 'Element', false);



        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();



        $this->add_control(
            'cta_elements',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'cta_select_element' => 'title',
                        'cta_title' => esc_html__('Call To Action.', 'pe-core'),
                    ],
                    [
                        'cta_select_element' => 'text',
                        'cta_text' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis justo nisl, ultricies vel est at, iaculis pulvinar elit. Donec tortor magna, molestie eget sodales a, porttitor quis odio.', 'pe-core'),
                    ],
                    [
                        'cta_select_element' => 'icon',
                    ],
                ],
                'title_field' => '{{{ cta_select_element }}}: {{{ cta_title }}}  {{{ cta_text }}} ',
            ]
        );


        $this->add_control(
            'interaction',
            [
                'label' => esc_html__('Interaction', 'pe-core'),
                'description' => esc_html__('Keep it "None" if you have added a "button" element in CTA; otherwise the links may conflict and cause issues on design due to HTML markup rules', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'link' => esc_html__('Link', 'pe-core'),
                    'to-page-post' => esc_html__('To Page/Post', 'pe-core'),
                    'pe--scroll--button' => esc_html__('Scroll To', 'pe-core'),
                    'open-popup' => esc_html__('Open Popup', 'pe-core'),
                ],

            ]
        );

        $templates = [];

        $templates = get_posts([
            'post_type' => 'elementor_library',
            'numberposts' => -1
        ]);

        foreach ($templates as $template) {
            $templates[$template->ID] = $template->post_title;
        }


        $this->add_control(
            'select_template',
            [
                'label' => __('Select Template', 'pe-core'),
                'description' => __('You can create your template via "Templates > Saved Templates > Add New Template" on your admin dashboard.', 'pe-core'),
                'label_block' => false,
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $templates,
                'condition' => ['interaction' => 'open-popup'],

            ]
        );



        popupOptions($this, ['interaction' => 'open-popup']);


        $this->add_control(
            'select_page',
            [
                'label' => esc_html__('Select Page / Post', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'groups' => get_grouped_page_options(),
                'condition' => ['interaction' => 'to-page-post'],
            ]
        );


        $this->add_control(
            'scroll_target',
            [
                'label' => esc_html__('Scroll To', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #aboutContainer', 'pe-core'),
                'description' => esc_html__('Enter a target ID or exact number of desired scroll position ("0" for scrolling top)', 'pe-core'),
                'condition' => ['interaction' => 'pe--scroll--button'],
            ]
        );

        $this->add_control(
            'scroll_duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'step' => 0.1,
                'default' => 1,
                'description' => esc_html__('Seconds', 'pe-core'),
                'condition' => ['interaction' => 'pe--scroll--button'],
            ]
        );

        $this->add_control(
            'cta_link',
            [
                'label' => esc_html__('Link', 'pe-core'),
                'type' => \Elementor\Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow', 'custom_attributes'],
                'default' => [
                    'is_external' => false,
                    'nofollow' => true,
                    // 'custom_attributes' => '',
                ],
                'label_block' => false,
                'description' => esc_html__('Leave it empty if you do not want to display link', 'pe-core'),
                'condition' => ['interaction' => 'link'],
            ]
        );



        $this->add_control(
            'background',
            [
                'label' => esc_html__('Background Image/Video', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'background_type',
            [
                'label' => esc_html__('Background Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__('Image', 'pe-core'),
                    'video' => esc_html__('Video', 'pe-core'),
                ],
                'condition' => [
                    'background' => 'true',
                ],

            ]
        );


        pe_video_settings($this , 'background_type' , 'video' , 'cta_bg' , false);


        $this->add_control(
            'bg_show_hover',
            [
                'label' => esc_html__('Background Reveal on Hover', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'bg--show--on--hover',
                'default' => '',
                'condition' => [
                    'background' => 'true',
                ],
            ]
        );


        $this->add_control(
            'background_hover',
            [
                'label' => esc_html__('Background Hover', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'reveal' => esc_html__('Reveal', 'pe-core'),
                    'zoom-in' => esc_html__('Zoom - In', 'pe-core'),
                    'zoom-out' => esc_html__('Zoom - Out', 'pe-core'),
                    'filter-in' => esc_html__('Filter - In', 'pe-core'),
                    'filter-out' => esc_html__('Filter - Out', 'pe-core'),
                    'overlay-in' => esc_html__('Overlay - In', 'pe-core'),
                    'overlay-out' => esc_html__('Overlay - Out', 'pe-core'),
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'hover_css_filters',
                'selector' => '{{WRAPPER}} div[data-image-hover=filter-out]>img, {{WRAPPER}} div.pe--hover--trigger:hover div[data-image-hover=filter-in]>img,
                {{WRAPPER}} div[data-image-hover=filter-in]:not(.has--trigger):hover>img',
                'condition' => [
                    'background_hover' => ['filter-in', 'filter-out'],
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'label' => esc_html__('Overlay', 'pe-core'),
                'name' => 'hover_overlay_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} div[data-image-hover=overlay-out]::before , {{WRAPPER}} div[data-image-hover=overlay-in]::before',
                'condition' => [
                    'background_hover' => ['overlay-in', 'overlay-out'],
                ]
            ]
        );


        $this->add_control(
            'hover_overlay_blend_mode',
            [
                'label' => esc_html__('Overlay Blend', 'pe-core'),
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
                'default' => 'multiply',
                'condition' => [
                    'background_hover' => ['overlay-in', 'overlay-out'],
                ],
                'selectors' => [
                    '{{WRAPPER}} div[data-image-hover=overlay-out]::before' => 'mix-blend-mode: {{VALUE}}',
                    '{{WRAPPER}} div[data-image-hover=overlay-in]::before' => 'mix-blend-mode: {{VALUE}}'
                ],
                'label_block' => false
            ]
        );

        $this->add_control(
            'switch_on_enter',
            [
                'label' => esc_html__('Switch Layout on Enter', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'color--change--true',
                'default' => '',
                'prefix_class' => '',
                'condition' => [
                    'background' => 'true',
                ],
            ]
        );

        $this->add_control(
            'cta_bg_image',
            [
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'background_type' => 'image',
                    'background' => 'true',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'cta_bg_image_size',
                'exclude' => ['custom'],
                'include' => [],
                'default' => 'large',
            ]
        );

        image_overlay_opt($this, '.cta--background', 'background_', ['background' => 'true']);



        $this->end_controls_section();

        $this->start_controls_section(
            'styles',
            [

                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'rem', 'em', 'custom'],
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
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
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
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'rem', 'em', 'custom'],
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
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
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
                    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        objectStyles($this, 'box', 'Box', '.pe--call--to--action.pe--styled--object', false, false, false, false, false);

        $this->add_control(
            'corner_borders',
            [
                'label' => esc_html__('Corner Borders', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'border--corners',
                'default' => '',
                'prefix_class' => '',
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'corner_borders_size',
            [
                'label' => esc_html__('Borders Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'corner_borders' => 'border--corners'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--borderSize: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'corner_borders_width',
            [
                'label' => esc_html__('Borders Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'corner_borders' => 'border--corners'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--borderWidth: {{SIZE}}{{UNIT}}',
                ],
            ]
        );


        flexOptions($this, false, '.pe--cta--wrapper', 'box', 'Box', false, '.cta--element');

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_styles_sec',
            [
                'label' => esc_html__('Popup Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['interaction' => 'open-popup'],
            ]
        );

        popupStyles($this, ['interaction' => 'open-popup']);
        $this->end_controls_section();


        pe_text_animation_settings($this);
        pe_cursor_settings($this);
        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $interaction = $settings['interaction'];
        $link = '';

        if ($interaction === 'link') {
            if (!empty($settings['cta_link']['url'])) {
                $this->add_link_attributes('cta_link', $settings['cta_link']);
            }

            $link = $this->get_render_attribute_string('cta_link');

        } else if ($interaction === 'to-page-post') {
            $link = 'href="' . get_the_permalink($settings['select_page']) . '"';
        }


        //Scroll Button Attributes
        $this->add_render_attribute(
            'scroll_attributes',
            [
                'data-scroll-to' => $settings['scroll_target'],
                'data-scroll-duration' => $settings['scroll_duration'],
            ]
        );

        $scrollAttributes = $settings['interaction'] === 'pe--scroll--button' ? $this->get_render_attribute_string('scroll_attributes') : '';
        $anim = pe_text_animation($this);

        ?>

        <div class="pe--call--to--action pe--hover--trigger pe--styled--object <?php echo $settings['interaction'] ?>" <?php echo $scrollAttributes ?>>

            <?php if ($settings['background'] === 'true') { ?>

                <div data-image-hover="<?php echo $settings['background_hover'] ?>"
                    class="cta--background has--trigger <?php echo $settings['bg_show_hover'] ?>">

                    <?php if ($settings['background_type'] === 'image') {
                        echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'cta_bg_image_size', 'cta_bg_image');
                    } else {
                        echo pe_video_render($this, false , false , 'cta_bg');
                    }
                    ?>
                </div>

            <?php } ?>

            <?php if ($interaction !== 'none' && $interaction !== 'open-popup') { ?>

                <a <?php echo pe_cursor($settings, $this) . ' ' . $link; ?>>

                <?php } ?>


                <div <?php echo $interaction === 'open-popup' ? pe_cursor($settings, $this) : '' ?>
                    class="pe--cta--wrapper <?php echo $interaction === 'open-popup' ? 'pe--pop--button' : '' ?>">

                    <?php

                    $elements = $settings['cta_elements'];

                    if (!empty($elements)) {

                        foreach ($elements as $key => $element) {
                            $type = $element['cta_select_element'];

                            ?>

                            <div <?php echo pe_image_hover($element, 'cta_image') . pe_background_hover($element, 'cta_element') ?>
                                class="cta--element pe--styled--object element--<?php echo $type . ' elementor-repeater-item-' . $element['_id'] . ' ' . $element['cta_element_visibility'] . ' ' . $element['svg_fill'] ?>">

                                <?php

                                if ($type === 'title') {

                                    echo '<' . $element['title_tag'] . '>';
                                    echo pe_text_hover($element, $element['cta_title'], 'cta_text');
                                    echo '</' . $element['title_tag'] . '>';

                                } else if ($type === 'text') {

                                    echo '<p>' . pe_text_hover($element, $element['cta_text'], 'cta_text') . '</p>';

                                } else if ($type === 'icon') {

                                    ob_start();
                                    \Elementor\Icons_Manager::render_icon($element['cta_icon'], ['aria-hidden' => 'true']);
                                    $icon = ob_get_clean();
                                    // echo '<div class="pe-icon">' . $icon . '</div>';
                                    echo '<div class="pe-icon">' . pe_icon_hover($element, $icon, 'cta_icon') . '</div>';


                                } else if ($type === 'image') {

                                    echo wp_get_attachment_image($element['cta_image']['id'], $element['cta_image_size_size']);

                                } else if ($type === 'button') {

                                    pe_button_render($element, false, false, 'cta_button', false, true, ' button-elementor-repeater-item-' . $element['_id']);

                                } else if ($type === 'lottie') {

                                    $file = '';
                                    if ($element['input_type'] === 'url') {
                                        $file = $element['lottie_url'];
                                    } else {
                                        $file = $element['lottie_file']['url'];
                                    }
                                        $attributes = $element['autoplay'] . ' loop';
                                   ?>

                                                            <dotlottie-player src="<?php echo esc_url($file) ?>" background="transparent"
                                                                speed="<?php echo $element['speed']['size'] ?>" direction="1"
                                                                playMode="normal" <?php echo $attributes; ?>>
                                                            </dotlottie-player>

                                <?php } ?>


                            </div>
                        <?php }
                    }

                    ?>
                </div>

                <?php if ($interaction !== 'none' && $interaction !== 'open-popup') { ?>
                </a>
            <?php } ?>


        </div>


        <?php if ($settings['interaction'] === 'open-popup') { ?>

            <?php if ($settings['back_overlay'] === 'true') { ?>
                <span class="pop--overlay"></span>
            <?php } ?>


            <div class="pe--styled--popup">

                <span class="pop--close">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path
                            d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                    </svg>
                </span>

                <div class="zeyna--popup--template" data-lenis-prevent>

                    <?php

                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($settings['select_template']);

                    ?>

                </div>
            </div>

        <?php } ?>

        <?php
    }

}