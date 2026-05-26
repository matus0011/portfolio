<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeNumberCounter extends Widget_Base
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
        return 'penumbercounter';
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
        return __('Number Counter', 'pe-core');
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
        return 'eicon-number-field pe-widget';
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
                'label' => __('Number Counter', 'pe-core'),
            ]
        );


        $this->add_control(
            'counter_style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'basic',
                'render_type' => 'template',
                'prefix_class' => 'counter--',
                'options' => [
                    'basic' => esc_html__('Basic', 'pe-core'),
                    'animated' => esc_html__('Animated', 'pe-core'),
                    'multi' => esc_html__('Multi', 'pe-core'),
                ],
            ]
        );


        $this->add_control(
            'start_count',
            [
                'label' => esc_html__('Start Count', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 9999999,
                'step' => 1,
                'default' => 0,
                // 'condition' => ['counter_style!' => 'multi'],

            ]
        );


        $numbers = new \Elementor\Repeater();


        $numbers->add_control(
            'count',
            [
                'label' => esc_html__('Count', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 9999999,
                'step' => 1,
                'default' => 99,

            ]
        );



        $numbers->add_control(
            'counter_prefix',
            [
                'label' => esc_html__('Prefix', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('-', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display prefix.', 'pe-core'),
            ]
        );


        $numbers->add_control(
            'counter_suffix',
            [
                'label' => esc_html__('Suffix', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('+', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display suffix.', 'pe-core'),
            ]
        );

        $numbers->add_control(
            'counter_caption',
            [
                'label' => esc_html__('Caption', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display caption.', 'pe-core'),
            ]
        );


        $this->add_control(
            'multi_counters',
            [
                'label' => esc_html__('Customize Words.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $numbers->get_controls(),
                'title_field' => '{{{ count }}}',
                'prevent_empty' => false,
                'show_label' => false,
                'condition' => ['counter_style' => 'multi'],
            ]
        );


        $this->add_control(
            'end_count',
            [
                'label' => esc_html__('End Count', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 9999999,
                'step' => 1,
                'default' => 99,
                'condition' => ['counter_style!' => 'multi'],

            ]
        );



        $this->add_control(
            'unitize_numbers',
            [
                'label' => esc_html__('Unitize Numbers', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'condition' => ['counter_style!' => 'multi'],
            ]
        );


        $this->add_control(
            'counter_prefix',
            [
                'label' => esc_html__('Prefix', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('-', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display prefix.', 'pe-core'),
                'condition' => ['counter_style!' => 'multi'],
            ]
        );


        $this->add_control(
            'counter_suffix',
            [
                'label' => esc_html__('Suffix', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('+', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display suffix.', 'pe-core'),
                'condition' => ['counter_style!' => 'multi'],
            ]
        );

        $this->add_responsive_control(
            'digits_alignment',
            [
                'label' => esc_html__('Digits Alignment', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'pe-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'pe-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => is_rtl() ? 'end' : 'start',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} span.count--inner' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['counter_style!' => 'multi'],
            ]
        );

        $this->add_control(
            'counter_caption',
            [
                'label' => esc_html__('Caption', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display caption.', 'pe-core'),
                'condition' => ['counter_style!' => 'multi'],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_animations',
            [
                'label' => __('Animation', 'pe-core'),
            ]
        );

        $this->add_control(
            'counter_duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'description' => esc_html__('by ms)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 60000,
                'step' => 1,
                'default' => 2000,

            ]
        );

        $this->add_control(
            'repeat',
            [
                'label' => esc_html__('Repeat', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'counter--repeat',
                'default' => '',
                'render_type' => 'template',
                'prefix_class' => '',
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



        $this->end_controls_section();


        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .counter--numbers--wrap' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
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
                    '{{WRAPPER}} .counter--numbers--wrap' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
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
                    '{{WRAPPER}} .counter--numbers--wrap' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );


        $this->add_responsive_control(
            'counter_size',
            [
                'label' => esc_html__('Size', 'pe-core'),
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
                    '{{WRAPPER}} .pe--number--counter' => '--fontSize: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .pe--number--counter ' => '
                    font-family: var(--sec_typo-font-family);'
                ],


            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'exclude' => ['font_size'],
                'selector' => '{{WRAPPER}} .pe--number--counter',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'prefix_typography',
                'label' => esc_html__('Prefix Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--number--counter span.counter--fix.counter--prefix',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'suffix_typography',
                'label' => esc_html__('Suffix Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--number--counter span.counter--fix.counter--suffix',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'label' => esc_html__('Caption Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--number--counter .counter--caption p , {{WRAPPER}} .pe--number--counter  p.counter--caption',
            ]
        );


        $this->add_control(
            'caption_alignment',
            [
                'label' => esc_html__('Captions Alignment', 'pe-core'),
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
                ],
                'selectors' => [
                    '{{WRAPPER}}.counter--multi .counter--numbers--wrap p.counter--caption' => 'text-align: {{VALUE}};',
                ],
                'default' => 'center',
                'toggle' => false,
            ]
        );


        objectStyles($this, 'number_counter_', 'Counter', '.pe--number--counter.pe--styled--object', false, false, false, false, false);

        $this->add_control(
            'prefix_alignment',
            [
                'label' => esc_html__('Prefix Alignment', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.counter--fix.counter--prefix' => 'align-self: {{VALUE}};',
                ],
                'default' => 'center',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'suffix_alignment',
            [
                'label' => esc_html__('Suffix Alignment', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.counter--fix.counter--suffix' => 'align-self: {{VALUE}};',
                ],
                'default' => 'center',
                'toggle' => false,
            ]
        );

        flexOptions($this, false, '.pe--number--counter , {{WRAPPER}}.counter--multi .counter--numbers--wrap');


        $this->end_controls_section();


        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $start = $settings['start_count'];
        $end = $settings['end_count'];

        ?>


        <div class="pe--number--counter pe--styled--object" data-pin-target="<?php echo esc_attr($settings['pin_target']) ?>"
            data-pin="<?php echo esc_attr($settings['pin']) ?>" data-scrub="<?php echo esc_attr($settings['scrub']) ?>"
            data-start="<?php echo esc_attr($start) ?>" data-end="<?php echo esc_attr($end) ?>"
            data-duration="<?php echo esc_attr($settings['counter_duration']) ?>">

            <div class="counter--numbers--wrap">


                <?php if ($settings['counter_prefix']) {
                    echo '<span class="counter--fix counter--prefix">' . esc_html($settings['counter_prefix']) . '</span>';
                } ?>

                <?php if ($settings['counter_style'] === 'basic') {
                    echo '<span class="number--count">' . esc_html($start) . '</span>';
                } else if ($settings['counter_style'] === 'animated') {
                    $startArray = str_split($start);
                    $endArray = str_split($end);

                    if ($startArray[0] !== '0') {
                        echo '<style>';
                        foreach ($startArray as $key => $startDigit) {
                            $val = ($startDigit * 10) / 2;
                            echo '.elementor-element-' . $this->get_id() . ' .count--' . $key . '{transform: translateY(-' . $val . '%)}';
                        }
                        echo '</style>';
                    }

                    $longerArray = (count($startArray) > count($endArray)) ? $startArray : $endArray;
                    $totalDigits = count($longerArray);
                    ?>

                        <div class="number--count">
                        <?php foreach ($longerArray as $key => $count): ?>
                                <span class="count--inner count--<?php echo $key; ?>">
                                <?php for ($c = 0; $c < 10; $c++): ?>
                                        <span><?php echo $c; ?></span>
                                <?php endfor; ?>

                                <?php for ($c = 0; $c < 10; $c++): ?>
                                        <span><?php echo $c; ?></span>
                                <?php endfor; ?>
                                </span>

                                <?php
                                // sağdan sola her 3 basamakta nokta ekle
                                if ($settings['unitize_numbers'] === 'true' && (($totalDigits - $key - 1) % 3 == 0) && $key != ($totalDigits - 1)) {
                                    echo '<span class="count--dot">.</span>';
                                }
                                ?>
                        <?php endforeach; ?>
                        </div>



                <?php } else if ($settings['counter_style'] === 'multi') {

                    $counts = $settings['multi_counters'];

                    if (empty($counts)) {
                        return false;
                    }

                    $numbers = [];
                    foreach ($counts as $key => $count) {

                        $number = $count['count'];
                        $numbers[] = $number; ?>
                    <?php }
                    $numbers[] = $settings['start_count'];
                    ?>

                            <div class="number--count" data-numbers="<?php echo esc_attr(json_encode($numbers)); ?>">

                                <div class="counter--prefix">
                            <?php foreach ($counts as $key => $count) {
                                $prefix = $count['counter_prefix'];

                                if (!empty($prefix)) {
                                    echo '<span class="counter--prefix--inner prefix__' . esc_attr($key) . '">' . esc_html($prefix) . '</span>';
                                }
                            } ?>

                                </div>


                                <span class="number--hold"><?php echo esc_html($settings['start_count']) ?></span>

                                <div class="counter--suffix">
                            <?php foreach ($counts as $key => $count) {
                                $suffix = $count['counter_suffix'];

                                if (!empty($suffix)) {
                                    echo '<span class="counter--suffix--inner suffix__' . esc_attr($key) . '">' . esc_html($suffix) . '</span>';
                                }
                            } ?>

                                </div>
                            </div>

                            <p class="counter--caption">
                                <span class="counter--captions--wrap">
                            <?php foreach ($counts as $key => $count) {
                                $caption = $count['counter_caption'];

                                if (!empty($caption)) {
                                    echo '<span class="counter--capt--inner capt__' . esc_attr($key) . '">' . esc_html($caption) . '</span>';
                                }
                                ?>

                            <?php }
                            echo '<span class="counter--capt--inner">' . esc_html($counts[0]['counter_caption']) . '</span>';
                            ?>
                                </span>
                            </p>

                <?php } ?>

                <?php if ($settings['counter_suffix']) {
                    echo '<span class="counter--fix counter--suffix">' . esc_html($settings['counter_suffix']) . '</span>';
                } ?>
            </div>
            <?php if (!empty($settings['counter_caption'])) { ?>

                <div class="counter--caption">
                    <?php echo '<p>' . $settings['counter_caption'] . '</p>'; ?>
                </div>

            <?php } ?>

        </div>

        <?php
    }

}