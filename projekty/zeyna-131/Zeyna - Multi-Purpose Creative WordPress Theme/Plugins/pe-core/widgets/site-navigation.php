<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeSiteNavigation extends Widget_Base
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
    return 'pesitenavigation';
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
    return __('Site Navigation', 'pe-core');
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
    return 'eicon-menu-toggle pe-widget';
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
    return ['pe-dynamic'];
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


    $registered = wp_get_nav_menus();
    $menus = [];

    if ($registered) {
      foreach ($registered as $menu) {

        $name = $menu->name;
        $id = $menu->term_id;

        $menus[$name] = $name;

      }
    }

    // Tab Title Control
    $this->start_controls_section(
      'section_tab_title',
      [
        'label' => __('Site Navigation', 'pe-core'),
      ]
    );

    $this->add_control(
      'menu_style',
      [
        'label' => esc_html__('Navigation Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'fullscreen',
        'options' => [

          'fullscreen' => esc_html__('Fullscreen', 'pe-core'),
          'popup' => esc_html__('Popup', 'pe-core'),
          'expand' => esc_html__('Expand', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'expand_element',
      [
        'label' => esc_html__('Expand Element', 'pe-core'),
        'placeholder' => esc_html__('Eg. #menuContainer', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'description' => esc_html__('You can enter a container id which will be expanded with the menu toggle.', 'pe-core'),
        'ai' => false,
        'condition' => [
          'menu_style' => 'expand'
        ],
      ]
    );

    $this->add_control(
      'expand_position',
      [
        'label' => esc_html__('Expand Position', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'right',
        'options' => [
          'right' => esc_html__('Right', 'pe-core'),
          'left' => esc_html__('Left', 'pe-core'),
          'full' => esc_html__('Full', 'pe-core'),
        ],
        'prefix_class' => 'expand--pos--',
        'render_type' => 'template',
        'condition' => [
          'menu_style' => 'expand'
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
        'label' => __('Select Menu Template', 'pe-core'),
        'description' => __('You can create your menu template via "Templates > Saved Templates > Add New Template" on your admin dashboard.', 'pe-core'),
        'label_block' => false,
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => $templates,
      ]
    );

    $this->add_responsive_control(
      'popup_position',
      [
        'label' => esc_html__('Popup Position', 'pe-core'),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => esc_html__('Left', 'pe-core'),
            'icon' => 'eicon-text-align-left',
          ],
          'right' => [
            'title' => esc_html__('Right', 'pe-core'),
            'icon' => 'eicon-text-align-right',
          ],
        ],
        'default' => is_rtl() ? 'left' : 'right',
        'toggle' => false,
        'prefix_class' => 'popup--pos--',
        'condition' => [
          'menu_style' => 'popup'
        ],
        'selectors' => [
          '{{WRAPPER}} .text-wrapper' => 'text-align: {{VALUE}};',
        ],
      ]
    );


    $this->add_control(
      'overlay_style',
      [
        'label' => esc_html__('Overlay Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'overlay',
        'options' => [
          'slide' => esc_html__('Slide', 'pe-core'),
          'blocks' => esc_html__('Blocks', 'pe-core'),
          'overlay' => esc_html__('Overlay', 'pe-core'),
          'fade' => esc_html__('Fade', 'pe-core'),
        ],
        'condition' => [
          'menu_style' => 'fullscreen'
        ]
      ]
    );


    $this->add_control(
      'slide_direction',
      [
        'label' => esc_html__('Slide Direction', 'elementor'),
        'type' => Controls_Manager::CHOOSE,
        'default' => 'down',
        'options' => [
          'left' => [
            'title' => esc_html__('Left', 'elementor'),
            'icon' => 'eicon-arrow-left',
          ],
          'up' => [
            'title' => esc_html__('Up', 'elementor'),
            'icon' => 'eicon-arrow-up',
          ],
          'down' => [
            'title' => esc_html__('Down', 'elementor'),
            'icon' => 'eicon-arrow-down',
          ],
          'right' => [
            'title' => esc_html__('Right', 'elementor'),
            'icon' => 'eicon-arrow-right',
          ],
        ],
        'toggle' => false,
        'condition' => [
          'menu_style' => 'fullscreen',
          'overlay_style' => 'slide',
        ],
      ]
    );

    $this->add_control(
      'blocks_count',
      [
        'label' => esc_html__('Blocks Count', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 20,
            'step' => 1,
          ],
        ],
        'default' => [
          'unit' => 'px',
          'size' => 4,
        ],
        'condition' => [
          'overlay_style' => 'blocks'
        ]
      ]
    );


    $this->add_control(
      'toggle_style',
      [
        'label' => esc_html__('Toggle Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'plus',
        'options' => [
          'hamburger' => esc_html__('Hamburger', 'pe-core'),
          'plus' => esc_html__('Plus', 'pe-core'),
          'text' => esc_html__('Text', 'pe-core'),
          'plus_text' => esc_html__('Plus + Text', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'hamburger_style',
      [
        'label' => esc_html__('Hamburger Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'simple',
        'prefix_class' => 'hamburger--style--',
        'options' => [
          'stylized' => esc_html__('Stylized', 'pe-core'),
          'simple' => esc_html__('Simple', 'pe-core'),
        ],
        'condition' => [
          'toggle_style' => 'hamburger'
        ]
      ]
    );

    $this->add_responsive_control(
      'toggle_size',
      [
        'label' => esc_html__('Size', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'vw'],
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
        ],
        'selectors' => [
          '{{WRAPPER}} .toggle-lines--wrap' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .tpt--icon' => 'font-size: {{SIZE}}{{UNIT}};height: auto;',
        ],
      ]
    );

    $this->add_responsive_control(
      'toggle_gap',
      [
        'label' => esc_html__('Gap', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'vw'],
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
        ],
        'selectors' => [
          '{{WRAPPER}} .menu--toggle:has(.toggle--text--wrapper)' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_control(
      'switch_header_on_open',
      [
        'label' => esc_html__('Switch Header on Open', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'return_value' => 'switch--header--on--open',
        'default' => '',
        'render_type' => 'template',
        'prefix_class' => '',
        'condition' => [
          'menu_style' => 'fullscreen'
        ]
      ]
    );

    $this->add_control(
      'open_text',
      [
        'label' => esc_html__('Open Text', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => esc_html__('Write menu open text here', 'pe-core'),
        'default' => esc_html__('MENU', 'pe-core'),
        'ai' => false,
      ]
    );

    $this->add_control(
      'close_text',
      [
        'label' => esc_html__('Close Text', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => esc_html__('Write menu close here', 'pe-core'),
        'default' => esc_html__('CLOSE', 'pe-core'),
        'ai' => false,
      ]
    );

    pe_text_hover_settings($this, 'toggle_text');
    pe_background_hover_settings($this, 'toggle_bg');


    $this->add_control(
      'nav_overlay',
      [
        'label' => esc_html__('Overlay?', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'return_value' => 'overlay',
        'default' => 'overlay',
      ]
    );

    $this->add_control(
      'overlay_has_backdrop',
      [
        'label' => esc_html__('Backdrop Filter', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'has--backdrop',
        'selectors' => [
          '{{WRAPPER}} .site--nav--overlay' => ' backdrop-filter: blur(var(--bgBlur));
            background-color: rgba(255, 255, 255, 0.2);
            --bgBlur: 10px;',
          '{{WRAPPER}} .site--nav--overlay.active' => 'opacity: 1;',
        ],
        'default' => '',
        'condition' => [
          'nav_overlay' => 'overlay',
        ],

      ]
    );

    $this->add_control(
      'overlay_color',
      [
        'label' => esc_html__('Overlay Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [

          '{{WRAPPER}} .site--nav--overlay' => 'background-color  : {{VALUE}};',
        ],
        'condition' => [
          'overlay_has_backdrop' => 'has--backdrop',
        ],
      ]
    );

    $this->add_responsive_control(
      'overlay_bg_backdrop_blur',
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
        'condition' => [
          'overlay_has_backdrop' => 'has--backdrop',
        ],
        'selectors' => [
          '{{WRAPPER}} .site--nav--overlay' => '--bgBlur: {{SIZE}}{{UNIT}};',
        ],
      ]
    );




    $this->add_control(
      'text_framed',
      [
        'label' => esc_html__('Framed?', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'return_value' => 'framed',
        'default' => 'framed',
        'condition' => [
          'toggle_style!' => 'plus'
        ]
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'blocks_animation_settings',
      [

        'label' => esc_html__('Blocks Animation', 'pe-core'),
        'condition' => [
          'overlay_style' => 'blocks'
        ]

      ]
    );


    $this->add_control(
      'blocks_duration',
      [
        'label' => esc_html__('Duration', 'pe-core'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 0.1,
        'step' => 0.1,
        'default' => 1.5
      ]
    );

    $this->add_control(
      'blocks_stagger_from',
      [
        'label' => esc_html__('Stagger From', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'random',
        'options' => [
          'random' => esc_html__('Random', 'pe-core'),
          'start' => esc_html__('Start', 'pe-core'),
          'center' => esc_html__('Center', 'pe-core'),
          'end' => esc_html__('End', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'blocks_stagger',
      [
        'label' => esc_html__('Stagger', 'pe-core'),
        'description' => esc_html__('Delay between animated elements (for multiple element animation types)', 'pe-core'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => -5,
        'max' => 5,
        'step' => 0.01,
        'default' => 0.1,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'additional',
      [

        'label' => esc_html__('Additonal Options', 'pe-core'),

      ]
    );

    $this->add_control(
      'hide_elements',
      [
        'label' => esc_html__('Hide Header Elements on Menu Open', 'pe-core'),
        'label_block' => true,
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => esc_html__('Enter target element class. Eg: .layout-switcher ', 'pe-core'),
        'description' => esc_html__('You can add classes to elements via "Advanced > CSS Classes" on widget options.', 'pe-core'),
        'ai' => false,
        'dynamic' => [
          'active' => false,
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

    objectStyles($this, 'menu--toggle', 'Toggle Button', '.menu--toggle');
    objectStyles($this, 'toggle-lines--wrap', 'Toggle Icon', '.toggle--icon--wrap , {{WRAPPER}} .tpt--icon.pe--styled--object');
    objectStyles($this, 'toggle--text--wrapper', 'Toggle Text', '.toggle--text--wrapper');
    pe_color_options($this);

  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $option = get_option('pe-redux');
    $style = $settings['menu_style'];
    $overlay = $settings['overlay_style'];
    $slideDir = $settings['slide_direction'];

    if ($style === 'popup' || $style === 'fullscreen' || $style === 'expand') {
      $expandElement = '';

      if ($style === 'expand' && !empty($settings['expand_element'])) {
        $expandElement = 'data-expand-element="' . $settings['expand_element'] . '"';
      }

      ?>

      <div <?php echo $expandElement ?>
        class="site--nav nav--<?php echo $style . ' overlay--' . $overlay . ' slide--' . $slideDir; ?>"
        data-hide-elements="<?php echo $settings['hide_elements'] ?>">


        <?php if ($settings['nav_overlay'] === 'overlay') { ?>

          <span hidden class="site--nav--overlay"></span>

        <?php } ?>

        <div class="menu--toggle--wrap pe--hover--trigger" data-id="<?php echo $this->get_id(); ?>">

          <?php if ($settings['toggle_style'] === 'plus' || $settings['toggle_style'] === 'hamburger') { ?>
            <div <?php echo pe_background_hover($this, 'toggle_bg') ?>
              class="menu--toggle pe--styled--object toggle--<?php echo $settings['toggle_style'] ?>" <?php echo pe_cursor($settings, $this); ?>>

              <div class="toggle--icon--wrap  pe--styled--object">
                <div class="toggle-lines--wrap">
                  <span class="toggle-line"></span>
                  <span class="toggle-line"></span>

                </div>
              </div>

              <?php if ($settings['open_text']) { ?>
                <div class="toggle--text--wrapper pe--styled--object">
                  <span class="open--text"><?php echo pe_text_hover($settings, $settings['open_text'], 'toggle_text') ?></span>
                  <span class="close--text"><?php echo pe_text_hover($settings, $settings['close_text'], 'toggle_text') ?></span>
                </div>
              <?php } ?>
            </div>
          <?php }
          if ($settings['toggle_style'] === 'text') { ?>

            <div <?php echo pe_background_hover($this, 'toggle_bg') ?>         <?php echo pe_cursor($settings, $this); ?>
              class="menu--toggle pe--styled--object toggle--text <?php echo $settings['text_framed'] ?>">

              <?php if ($settings['open_text']) { ?>
                <div class="toggle--text--wrapper pe--styled--object">

                  <span class="open--text"><?php echo pe_text_hover($settings, $settings['open_text'], 'toggle_text') ?></span>
                  <span class="close--text"><?php echo pe_text_hover($settings, $settings['close_text'], 'toggle_text') ?></span>
                </div>

              <?php } ?>

            </div>

          <?php } else if ($settings['toggle_style'] === 'plus_text') { ?>

              <div <?php echo pe_background_hover($this, 'toggle_bg') ?>
                class="menu--toggle pe--styled--object toggle--plus--text" <?php echo pe_cursor($settings, $this); ?>>

                <div class="tpt--icon pe--styled--object">

                  <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 -960 960 960" width="1em"
                    fill="var(--mainColor)">
                    <path d="M460-460H240v-40h220v-220h40v220h220v40H500v220h-40v-220Z" />
                  </svg>

                </div>

                <div class="toggle--text--wrapper pe--styled--object">
                  <span class="open--text"><?php echo pe_text_hover($settings, $settings['open_text'], 'toggle_text') ?></span>
                  <span class="close--text"><?php echo pe_text_hover($settings, $settings['close_text'], 'toggle_text') ?></span>
                </div>

              </div>

          <?php } ?>
        </div>

        <?php if ($overlay === 'blocks') {

          $this->add_render_attribute(
            'blocks_attributes',
            [
              'data-duration' => $settings['blocks_duration'],
              'data-stagger' => $settings['blocks_stagger'],
              'data-stagger-from' => $settings['blocks_stagger_from'],
            ]
          );

          $blocksAttributes = $this->get_render_attribute_string('blocks_attributes');

          ?>

          <div <?php echo $blocksAttributes ?> class="nav_overlay nav--blocks <?php echo 'blocks__' . $this->get_id() ?>">

            <?php
            if (class_exists("Redux")) {

              $option = get_option('pe-redux');

              $count = $settings['blocks_count']['size'];
              for ($i = 0; $i < $count; $i++) {
                echo '<span class="fullscreen--menu--block"  style="--index: ' . $i . '; --grid:' . $count . '"></span>';
              }

            }
            ?>

          </div>
        <?php } else if ($overlay === 'overlay') {

          echo '<span class="nav_overlay nav--overlay overlay__' . $this->get_id() . '"></span>';
          echo '<span class="nav_bg_opacity bg_op_' . $this->get_id() . '"></span>';

        } ?>

        <div class="site--menu <?php echo 'menu__' . $this->get_id() . ' menu--' . $style ?>">

          <?php

          echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($settings['select_template']);

          ?>

        </div>

      </div>

    <?php }



  }

}
