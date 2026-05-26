<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeLayoutSwitcher extends Widget_Base
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
    return 'pelayoutswitcher';
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
    return __('Pe Layout Switcher', 'pe-core');
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
    return 'eicon-dual-button pe-widget';
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
    return ['pe-header'];
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
        'label' => __('Layout Switcher', 'pe-core'),
      ]
    );

    $this->add_control(
      'style',
      [
        'label' => esc_html__('Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'ls_switcher',
        'options' => [
          'ls_switcher' => esc_html__('Switcher', 'pe-core'),
          'ls_button' => esc_html__('Button', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'type',
      [
        'label' => esc_html__('Type', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'ls_text',
        'options' => [
          'ls_text' => esc_html__('Text', 'pe-core'),
          'ls_icon' => esc_html__('Icon', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'default_text',
      [
        'label' => esc_html__('Default Layout Text', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('DARK', 'pe-core'),
        'condition' => ['type' => 'ls_text']
      ]
    );

    $this->add_control(
      'switched_text',
      [
        'label' => esc_html__('Switched Layout Text', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('LIGHT', 'pe-core'),
        'condition' => ['type' => 'ls_text']
      ]
    );

    $this->add_control(
      'default_icon',
      [
        'type' => \Elementor\Controls_Manager::ICONS,
        'condition' => ['type' => 'ls_icon']
      ]
    );

    $this->add_control(
      'switched_icon',
      [
        'type' => \Elementor\Controls_Manager::ICONS,
        'condition' => ['type' => 'ls_icon']
      ]
    );

    $this->add_control(
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
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => esc_html__('Right', 'pe-core'),
            'icon' => 'eicon-text-align-right',
          ],
        ],
        'default' => 'center',
        'toggle' => true,
        'selectors' => [
          '{{WRAPPER}}' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'show_labels',
      [
        'label' => esc_html__('Show Labels', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'yes',
        'render_type' => 'template',
        'prefix_class' => 'show--labels--',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_brackets',
      [
        'label' => esc_html__('Show Brackets', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'yes',
        'render_type' => 'template',
        'prefix_class' => 'show--brackets--',
        'default' => '',
      ]
    );


    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'ls_typography',
        'selector' => '{{WRAPPER}} .pl--button ,{{WRAPPER}}  .pe-layout-switcher.ls_switcher .pl--switch:has(.pl--default) span:not(.pl--follower)',
      ]
    );


    $this->add_responsive_control(
      'switcher_border-radius',
      [
        'label' => esc_html__('Border Radius', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vh'],
        'selectors' => [
          '{{WRAPPER}} .pe-layout-switcher .pl--switch' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} .pe-layout-switcher .pl--switch .pl--follower' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
        'condition' => [
          'style' => 'ls_switcher'
        ]
      ]
    );


    $this->add_responsive_control(
      'switcher_width',
      [
        'label' => esc_html__('Width', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
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
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .pe-layout-switcher .pl--switch' => 'width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'switcher_height',
      [
        'label' => esc_html__('Height', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
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
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .pe-layout-switcher .pl--switch' => 'height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );


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



    $this->end_controls_section();

    pe_cursor_settings($this);
    pe_general_animation_settings($this);
    objectStyles($this, 'switcher', 'Switcher', '.pe--styled--object', true, false, true, false, false);

    pe_color_options($this);

  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $option = get_option('pe-redux');

    $animation = pe_general_animation($this);

    if ($settings['type'] === 'ls_text') {
      $default = $settings['default_text'];
      $switched = $settings['switched_text'];
    } else if ($settings['type'] === 'ls_icon') {

      if (!empty($settings['default_icon']['value'])) {
        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['default_icon'], ['aria-hidden' => 'true']);
        $default = ob_get_clean();
      } else {
        $default = file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/light_mode.svg');
      }

      if (!empty($settings['switched_icon']['value'])) {
        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['switched_icon'], ['aria-hidden' => 'true']);
        $switched = ob_get_clean();
      } else {
        $switched = file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/dark_mode.svg');
      }

    }

    ?>



    <div <?php echo $animation ?>     <?php echo pe_cursor($settings, $this) ?>
      class="pe-layout-switcher pe--styled--object <?php echo $settings['style'] ?>">

      <?php if ($settings['style'] == 'ls_switcher') { ?>

        <div class="pl--switch">
          <?php if ($settings['show_labels'] === 'yes') { ?>

            <span class="pl--switch--button pl--default active"><?php echo $default ?></span>
            <span class="pl--switch--button pl--switched"><?php echo $switched ?></span>

          <?php } ?>
          <span class="pl--follower"></span>
        </div>
      <?php } else if ($settings['style'] === 'ls_button') { ?>

          <div class="pl--button">

            <span class="pl--default"><?php echo $default ?></span>
            <span class="pl--switched"><?php echo $switched ?></span>

          </div>


      <?php } ?>

    </div>


    <?php
  }

}
