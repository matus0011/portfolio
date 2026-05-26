<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class peClock extends Widget_Base
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
        return 'peclock';
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
        return __('Clock', 'pe-core');
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
        return 'eicon-clock-o pe-widget';
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
                'label' => __('Clock', 'pe-core'),
            ]
        );


        $this->add_control(
            'clock_format',
            [
                'label' => __('Time Format', 'pe-core'),
                'type' => Controls_Manager::SELECT,
                'default' => '24',
                'options' => [
                    '24' => '24-Hour',
                    '12' => '12-Hour (AM/PM)',
                ],
            ]
        );

        $this->add_control(
            'clock_mode',
            [
                'label' => __('Clock Mode', 'pe-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'local',
                'options' => [
                    'local' => 'Local Time (User)',
                    'timezone' => 'Specific Timezone',
                ],
            ]
        );

        $this->add_control(
            'clock_timezone',
            [
                'label' => __('Timezone', 'pe-core'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Europe/Istanbul',
                'description' => 'e.g. Europe/London, America/New_York. You can find a list of supported timezones <a target="_blank" href="https://www.php.net/manual/en/timezones.php">here</>',
                'condition' => [
                    'clock_mode' => 'timezone',
                ],
            ]
        );

        $this->add_control(
            'clock_prefix',
            [
                'label' => __('Prefix', 'pe-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'clock_suffix',
            [
                'label' => __('Suffix', 'pe-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'clock_typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--clock',
            ]
        );

        $this->end_controls_section();

        pe_general_animation_settings($this);

        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $format = $settings['clock_format'];
        $mode = $settings['clock_mode'];
        $timezone = $settings['clock_timezone'];

        $animation = pe_general_animation($this);

        ?>

        <div <?php echo $animation ?> class="pe--clock" data-format="<?php echo esc_attr($format); ?>"
            data-mode="<?php echo esc_attr($mode); ?>" data-timezone="<?php echo esc_attr($timezone); ?>">
            <?php if ($settings['clock_prefix']) {
                echo '<span>' . $settings['clock_prefix'] . '</span>';
            } ?>
            <span class="clock-display">00:00:00</span>
            <?php if ($settings['clock_suffix']) {
                echo '<span>' . $settings['clock_suffix'] . '</span>';
            } ?>
        </div>

        <?php

    }

}
