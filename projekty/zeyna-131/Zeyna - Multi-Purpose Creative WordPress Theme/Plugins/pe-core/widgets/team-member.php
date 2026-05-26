<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeTeamMember extends Widget_Base
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
        return 'peteammember';
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
        return __('Team Member', 'pe-core');
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
        return 'eicon-person pe-widget';
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
                'label' => __('Team Member', 'pe-core'),
            ]
        );


        $this->add_control(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'classic',
                'options' => [
                    'classic' => esc_html__('Classic', 'pe-core'),
                    'metro' => esc_html__('Metro', 'pe-core'),
                    'card' => esc_html__('Card', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Member Image', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $this->add_control(
            'team_member_name',
            [
                'label' => esc_html__('Name - Surname', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => esc_html__('John Doe', 'pe-core'),
            ]
        );

        $this->add_control(
            'team_member_title',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => esc_html__('Market Analyst', 'pe-core'),
            ]
        );

        $this->add_control(
            'team_member_content',
            [
                'label' => esc_html__('Content', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'ai' => false,
                'placeholder' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla consequat egestas nisi. Vestibulum malesuada fermentum nibh. Donec venenatis, neque et pellentesque efficitur, lectus est preti.', 'pe-core'),
                'rows' => 10,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'select_platform',
            [
                'label' => esc_html__('Select Platform', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'facebook',
                'options' => [
                    'facebook' => esc_html__('Facebook', 'pe-core'),
                    'whatsapp' => esc_html__('WhatsApp', 'pe-core'),
                    'x-twitter' => esc_html__('X (Twitter)', 'pe-core'),
                    'telegram' => esc_html__('Telegram', 'pe-core'),
                    'pinterest' => esc_html__('Pinterest', 'pe-core'),
                    'linkedin' => esc_html__('LinkedIn', 'pe-core'),
                    'behance' => esc_html__('Behance', 'pe-core'),
                    'dribble' => esc_html__('Dribble', 'pe-core'),
                    'instagram' => esc_html__('Instagram', 'pe-core'),
                    'medium' => esc_html__('Medium', 'pe-core'),
                    'pateron' => esc_html__('Patreon', 'pe-core'),
                    'tiktok' => esc_html__('Tiktok', 'pe-core'),
                    'vimeo' => esc_html__('Vimeo', 'pe-core'),
                    'youtube' => esc_html__('YouTube', 'pe-core'),
                    'custom' => esc_html__('Custom', 'pe-core'),
                ],
                'label_block' => false,
            ]
        );


        $repeater->add_control(
            'social_label',
            [
                'label' => esc_html__('Label', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => esc_html__('Eg. Instagram', 'pe-core'),
            ]
        );

        $repeater->add_control(
            'social_url',
            [
                'label' => esc_html__('Enter URL', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => esc_html__('Eg. https://instagram.com/zeyna', 'pe-core'),
            ]
        );


        $repeater->add_control(
            'platform_custom_icon',
            [
                'label' => esc_html__('Custom Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'description' => esc_html__('Leave it empty if you want to display default icon.', 'pe-core'),
                'condition' => [
                    'select_platform' => 'custom',
                ]
            ]
        );

        $this->add_control(
            'team_member_socials',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'socials_style',
            [
                'label' => __('Socials Style', 'pe-core'),
                'label_block' => false,
                'default' => 'icons',
                'type' => \Elementor\Controls_Manager::SELECT,

                'options' => [
                    'icons' => esc_html__('Icons', 'pe-core'),
                    'texts' => esc_html__('Texts', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'socials_behavior',
            [
                'label' => __('Socials Behavior', 'pe-core'),
                'label_block' => false,
                'default' => 'visible',
                'render_type' => 'template',
                'prefix_class' => 'socials--behavior--',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'visible' => esc_html__('Visible', 'pe-core'),
                    'hover' => esc_html__('On-Hover', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'content_style',
            [
                'label' => __('Content Style', 'pe-core'),
                'label_block' => false,
                'default' => 'visible',
                'prefix_class' => 'member--content--',
                'render_type' => 'template',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'visible' => esc_html__('Visible', 'pe-core'),
                    'box' => esc_html__('Box', 'pe-core'),
                    'popup' => esc_html__('Popup', 'pe-core'),
                ],
            ]
        );

        popupOptions($this, ['content_style' => 'popup']);

        $this->add_control(
            'show_in_popup',
            [
                'label' => esc_html__('Show in popup ', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'render_type' => 'template',
                'default' => ['image', 'title', 'socials'],
                'options' => [
                    'image' => esc_html__('Image', 'pe-core'),
                    'name' => esc_html__('Name', 'pe-core'),
                    'socials' => esc_html__('Socials', 'pe-core'),
                ],
                'multiple' => true,
            ]
        );

        $this->add_control(
            'content_toggle',
            [
                'label' => esc_html__('Content Toggle', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'content--toggle',
                'prefix_class' => '',
                'default' => '',
                'render_type' => 'template',
                'condition' => [
                    'content_style!' => 'visible',
                ]
            ]
        );

        $this->add_control(
            'custom_toggle_icon',
            [
                'label' => esc_html__('Custom Toggle Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => '',
                'condition' => [
                    'content_toggle' => 'content--toggle',
                ]
            ]
        );

        $this->add_control(
            'toggle_icon',
            [
                'label' => esc_html__('Toggle Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'custom_toggle_icon' => 'true',
                ]
            ]
        );


        pe_image_hover_settings($this);


        $this->end_controls_section();

        $this->start_controls_section(
            'member_styles',
            [
                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__('Name Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .team--member--name',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .team--member--title',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__('Content Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .team--member--content',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'socials_typography',
                'label' => esc_html__('Socials Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .team--member--socials',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'toggle_typography',
                'label' => esc_html__('Toggle Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .member--toggle',
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['vw', 'px', '%', 'rem', 'custom'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--team--member' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__('Image Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['vh', 'px', '%', 'rem', 'custom'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--team--member .team--member--image:not(.team--member--pop--image)' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'details_height',
            [
                'label' => esc_html__('Details Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['vh', 'px', '%', 'rem', 'custom'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--team--member .team--member--details' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pe--team--member .team--member--labels' => 'height: 100%;',
                ],
            ]
        );

        flexOptions($this, false, '.team--member--labels', 'details_', 'Details', true, false);

        // pe_flex_options($this, '.team--member--wraper', 'box', 'Box');
        // pe_flex_options($this, '.team--member--details', 'content', 'Content');
        // pe_flex_options($this, '.team--member--labels', 'labels', 'Labels');
        // pe_flex_options($this, '.team--member--socials', 'socials', 'Socials');
        flexOptions($this, ['content_style' => 'popup'], '.team--member--pop--wrap', 'pop_content', 'Popup Content');

        flexOptions($this, false, '.pe--team--member .team--member--wraper', 'box', 'Box', true, false);

        $this->add_responsive_control(
            'box_border-radius',
            [
                'label' => esc_html__('Border Radius (Box)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .pe--team--member' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .pe--team--member',
            ]
        );

        $this->add_control(
            '_has_backdrop',
            [
                'label' => esc_html__('Backdrop Filter', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'has--backdrop',
                'default' => '',

            ]
        );


        $this->add_responsive_control(
            '_bg_backdrop_blur',
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
                'condition' => ['_has_backdrop' => 'has--backdrop'],
                'selectors' => [
                    '{{WRAPPER}} .pe--team--member' => '--backdropBlur: {{SIZE}}{{UNIT}};backdrop-filter: blur(var(--backdropBlur));',
                ],
            ]
        );



        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__('Padding (Box)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'selectors' => [
                    '{{WRAPPER}} .pe--team--member' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border-radius',
            [
                'label' => esc_html__('Border Radius (Image)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .team--member--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding (Content)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'selectors' => [
                    '{{WRAPPER}} .team--member--details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        objectAbsolutePositioning($this, 'span.member--toggle', 'member_toggle', 'Toggle');
        objectAbsolutePositioning($this, 'ul.team--member--socials', 'member_socials', 'Socials');
        objectAbsolutePositioning($this, '.team--member--content', 'box_content', 'Box Content');


        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .team--member--image',
            ]
        );

        $this->add_control(
            'socials_color',
            [
                'label' => esc_html__('Socials Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .team--member--socials' => '--mainColor: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'popup_styles_sec',
            [
                'label' => esc_html__('Popup Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['content_style' => 'popup'],
            ]
        );

        popupStyles($this, ['content_style' => 'popup']);
        $this->end_controls_section();

        $this->start_controls_section(
            'cursor_interactions',
            [
                'label' => __('Cursor Interactions', 'pe-core'),
            ]
        );

        $this->add_control(
            'cursor_type',
            [
                'label' => esc_html__('Interaction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'text' => esc_html__('Text', 'pe-core'),
                    'icon' => esc_html__('Icon', 'pe-core'),
                    'none' => esc_html__('None', 'pe-core'),

                ],

            ]
        );

        $this->add_control(
            'cursor_icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'description' => esc_html__('Only Material Icons allowed, do not select Font Awesome icons.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => ['cursor_type' => 'icon'],
            ]
        );

        $this->add_control(
            'cursor_text',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => ['cursor_type' => 'text'],
            ]
        );


        $this->end_controls_section();

        pe_image_animation_settings($this);
        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $icons = [
            'facebook' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/fb.svg'),
            'x-twitter' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/x.svg'),
            'linkedin' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/linkedin.svg'),
            'pinterest' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/pinterest.svg'),
            'whatsapp' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/wp.svg'),
            'telegram' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/telegram.svg'),
            'behance' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/behance.svg'),
            'dribble' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/dribble.svg'),
            'instagram' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/instagram.svg'),
            'medium' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/medium.svg'),
            'pateron' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/pateron.svg'),
            'tiktok' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/tiktok.svg'),
            'vimeo' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/vimeo.svg'),
            'youtube' => file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/youtube.svg'),
        ];

        $socials = $settings['team_member_socials'];
        $socials_style = $settings['socials_style'];


        ob_start();
        if (!empty($socials)) {
            echo '<ul class="team--member--socials">';

            foreach ($socials as $social) { ?>

                <li class="member--social">
                    <a target="_blank" href="<?php echo esc_url($social['social_url']) ?>">

                        <?php
                        if ($socials_style === 'icons') {
                            if ($social['select_platform'] === 'custom') {
                                ob_start();
                                \Elementor\Icons_Manager::render_icon($social['platform_custom_icon'], ['aria-hidden' => 'true']);
                                $icon = ob_get_clean();
                            } else {
                                $icon = $icons[$social['select_platform']];
                            }

                            echo '<span class="social--icon">' . $icon . '</span>';
                        } else {
                            echo '<span class="social--label">' . esc_html($social['social_label']) . '</span>';
                        }

                        ?>

                    </a>
                </li>

            <?php }

            echo '</ul>';
        }

        $socialsRender = ob_get_clean();

        ?>

        <div class="pe--team--member <?php echo $settings['style'] ?>">

            <div class="team--member--wraper">

                <?php if ($settings['content_toggle'] === 'content--toggle') {

                    if ($settings['custom_toggle_icon'] === 'true') {
                        ob_start();
                        \Elementor\Icons_Manager::render_icon($settings['toggle_icon'], ['aria-hidden' => 'true']);
                        $icon = ob_get_clean();

                    } else {
                        $svgPath = get_template_directory() . '/assets/img/add.svg';
                        $icon = file_get_contents($svgPath);
                    }

                    echo '<span class="member--toggle pe--pop--button pe--styled--object pe--styled--object">' . $icon . '</span>';
                } ?>

                <div <?php echo pe_cursor($settings, $this) . ' ' . pe_image_hover($this); ?> class="team--member--image">

                    <?php
                    $alt = isset($settings['image']['alt']) ? 'alt="' . $settings['image']['alt'] . '"' : '';
                    echo '<img ' . $alt . ' src="' . $settings['image']['url'] . '">'; ?>
                </div>

                <div class="team--member--details">

                    <div class="team--member--labels">
                        <h6 class="team--member--name"><?php echo esc_html($settings['team_member_name']) ?></h6>
                        <p class="team--member--title"><?php echo esc_html($settings['team_member_title']) ?></p>

                    </div>

                    <?php if ($settings['content_style'] === 'popup') { ?>
                        <div class="zeyna--member--popup pe--styled--popup">

                            <span class="pop--close">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path
                                        d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                                </svg>
                            </span>

                            <div class="team--member--pop--wrap">

                                <?php $showinPop = $settings['show_in_popup'];

                                if (in_array('image', $showinPop)) { ?>

                                    <div class="team--member--image team--member--pop--image">

                                        <?php
                                        $alt = isset($settings['image']['alt']) ? 'alt="' . $settings['image']['alt'] . '"' : '';

                                        echo '<img ' . $alt . ' src="' . $settings['image']['url'] . '">'; ?>

                                    </div>

                                <?php }

                                if (in_array('name', $showinPop)) { ?>

                                    <div class="team--member--labels">
                                        <h6 class="team--member--name"><?php echo esc_html($settings['team_member_name']) ?></h6>
                                        <p class="team--member--title"><?php echo esc_html($settings['team_member_title']) ?></p>

                                    </div>

                                <?php } ?>


                                <p class="team--member--content">
                                    <?php echo $settings['team_member_content']; ?>
                                </p>

                                <?php if (in_array('socials', $showinPop) && !empty($socials)) {
                                    echo $socialsRender;
                                } ?>


                            </div>


                        </div>
                        <span class="pop--overlay"></span>

                    <?php } else { ?>

                        <p class="team--member--content">
                            <?php echo $settings['team_member_content']; ?>
                        </p>

                    <?php } ?>



                    <?php if (!empty($socials)) {
                        echo $socialsRender;
                    } ?>


                </div>


            </div>

        </div>



        <?php
    }

}
