<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeNavMenu extends Widget_Base
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
    return 'penavmenu';
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
    return __('Nav Menu', 'pe-core');
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
    return 'eicon-nav-menu pe-widget';
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
        'label' => __('Nav Menu', 'pe-core'),
      ]
    );

    $this->add_control(
      'select_menu',
      [
        'label' => esc_html__('Select Menu', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => $menus,
      ]
    );

    $this->add_control(
      'menu_style',
      [
        'label' => esc_html__('Menu Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'menu--vertical',
        'render_type' => 'template',
        'prefix_class' => '',
        'options' => [
          'menu--vertical' => esc_html__('Vertical', 'pe-core'),
          'menu--horizontal' => esc_html__('Horizontal', 'pe-core'),
          'menu--toggled' => esc_html__('Toggled', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'menu_open_text',
      [
        'label' => esc_html__('Open Text', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('MENU', 'pe-core'),
        'ai' => false,
        'condition' => [
          'menu_style' => 'menu--toggled',
        ],
      ]
    );

    $this->add_control(
      'menu_close_text',
      [
        'label' => esc_html__('Close Text', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('CLOSE', 'pe-core'),
        'ai' => false,
        'condition' => [
          'menu_style' => 'menu--toggled',
        ],
      ]
    );

    $this->add_control(
      'toggle_icon_style',
      [
        'label' => esc_html__('Toggle Icon', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'hamburger',
        'options' => [
          'none' => esc_html__('None', 'pe-core'),
          'hamburger' => esc_html__('Hamburger', 'pe-core'),
          'plus' => esc_html__('Plus', 'pe-core'),
          'custom' => esc_html__('Custom', 'pe-core'),
        ],
        'condition' => [
          'menu_style' => 'menu--toggled',
        ],
      ]
    );

    $this->add_control(
      'toggle_open_icon',
      [
        'label' => esc_html__('Open Icon', 'pe-core'),
        'type' => \Elementor\Controls_Manager::ICONS,
        'condition' => [
          'menu_style' => 'menu--toggled',
          'toggle_icon_style' => 'custom',
        ],
        'skin' => 'inline'
      ]
    );

    $this->add_control(
      'toggle_close_icon',
      [
        'label' => esc_html__('Close Icon', 'pe-core'),
        'type' => \Elementor\Controls_Manager::ICONS,
        'condition' => [
          'menu_style' => 'menu--toggled',
          'toggle_icon_style' => 'custom',
        ],
        'skin' => 'inline'
      ]
    );




    $this->add_control(
      'indexed',
      [
        'label' => esc_html__('Items Index', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => " menu--indexed",
        'default' => false,
      ]
    );

    $this->add_control(
      'items_seperator',
      [
        'label' => esc_html__('Seperator', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => "menu--items--seperator",
        'prefix_class' => "",
        'default' => '',
        'condition' => ['menu_style' => 'menu--horizontal'],
      ]
    );

    $this->add_control(
      'cats_sperator',
      [
        'label' => esc_html__('Seperator', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => esc_html__('Eg: /', 'pe-core'),
        'condition' => [
          'items_seperator' => 'menu--items--seperator',
        ],
        'selectors' => [
          '{{WRAPPER}}  .main-navigation>ul>li a::after' => 'content: "{{VALUE}}";',
        ],
      ]
    );


    $this->add_control(
      'sub_style',
      [
        'label' => esc_html__('Sub-Parent Behavior', 'pe-core'),
        'description' => esc_html__('If you select "toggle and parent"; Menu items which are parenting a sub-menu will not be directed to the link inserted for it, It will open sub-menu when it clicked. ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'st--only',
        'options' => [
          'st--all' => esc_html__('Toggle and Parent', 'pe-core'),
          'st--only' => esc_html__('Toggle Only', 'pe-core'),
        ],
      ]
    );



    $this->add_control(
      'sub_menu_style',
      [
        'label' => esc_html__('Sub-Menu Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'prefix_class' => 'sub--style--',
        'default' => 'default',
        'render_type' => 'template',
        'options' => [
          'default' => esc_html__('Default', 'pe-core'),
          'expand' => esc_html__('Expand', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'sub_behavior',
      [
        'label' => esc_html__('Sub-Menu Reveal Behavior', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'prefix_class' => 'sub--behavior--',
        'default' => 'hover',
        'render_type' => 'template',
        'options' => [
          'hover' => esc_html__('Hover', 'pe-core'),
          'click' => esc_html__('Click', 'pe-core'),
        ],
        'condition' => ['sub_menu_style' => ['default']],
      ]
    );


    $this->add_control(
      'menu_hover',
      [
        'label' => esc_html__('Hover Effect', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'none',
        'render_type' => 'template',
        'prefix_class' => 'hover--',
        'options' => [
          'none' => esc_html__('None', 'pe-core'),
          'underline' => esc_html__('Underline', 'pe-core'),
          'chars-up' => esc_html__('Chars Up', 'pe-core'),
          'words-up' => esc_html__('Words Up', 'pe-core'),
          'dot' => esc_html__('Dot', 'pe-core'),
          'opacity' => esc_html__('Opacity', 'pe-core'),
          'spacer' => esc_html__('Spacer', 'pe-core'),
          'background-follower' => esc_html__('Background Follower', 'pe-core'),
          'font-swap' => esc_html__('Font Swap', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'active_item_style',
      [
        'label' => esc_html__('Active Item Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'none',
        'render_type' => 'template',
        'prefix_class' => 'active--',
        'options' => [
          'none' => esc_html__('None', 'pe-core'),
          'underline' => esc_html__('Underline', 'pe-core'),
          'dot' => esc_html__('Dot', 'pe-core'),
          'brackets' => esc_html__('Brackets', 'pe-core'),
          'opacity' => esc_html__('Opacity', 'pe-core'),
          'spacer' => esc_html__('Spacer', 'pe-core'),
          'background-follower' => esc_html__('Background Follower', 'pe-core'),
          'font-swap' => esc_html__('Font Swap', 'pe-core'),
        ],
        'condition' => ['menu_hover!' => ['dot', 'underline', 'spacer', 'font-swap', 'background-follower']],
      ]
    );

    $this->add_responsive_control(
      'wrap--overflows',
      [
        'label' => esc_html__('Wrap Overflows', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => "wrap--overflows",
        'prefix_class' => "",
        'default' => '',
      ]
    );

    $this->add_control(
      'has_bg',
      [
        'label' => esc_html__('Background', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'has--bg',
        'prefix_class' => '',
        'default' => '',
      ]
    );

    $this->add_control(
      'has_bg_color',
      [
        'label' => esc_html__('Background Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .pe--account--pop--button::after' => 'background-color: {{VALUE}}',
        ],
        'condition' => ['menu_style' => 'horizontal'],
      ]
    );

    $this->add_responsive_control(
      'has_border_radius',
      [
        'label' => esc_html__('Border Radius', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
        'selectors' => [
          '{{WRAPPER}} .pe--account--pop--button::after' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
        ],
        'condition' => ['menu_style' => 'horizontal'],
      ]
    );


    $this->add_control(
      'has_padding',
      [
        'label' => esc_html__('Padding', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'default' => [
          'top' => 0,
          'right' => 5,
          'bottom' => 0,
          'left' => 5,
          'unit' => 'px',
          'isLinked' => false,
        ],
        'selectors' => [
          '{{WRAPPER}} .pe--account--pop--button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
        'condition' => ['menu_style' => 'horizontal'],
      ]
    );

    $this->add_responsive_control(
      'menu_items_alignment',
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
        ],
        'default' => is_rtl() ? 'right' : 'left',
        'toggle' => true,
        'prefix_class' => 'menu--items--align--',
        'selectors' => [
          '{{WRAPPER}} ul.menu' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'sub_toggle_style',
      [
        'label' => esc_html__('Sub-Toggle Style', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'plus',
        'options' => [
          'plus' => esc_html__('Plus', 'pe-core'),
          'chevron' => esc_html__('Chevron', 'pe-core'),
          'arrow' => esc_html__('Arrow', 'pe-core'),
          'custom' => esc_html__('Custom', 'pe-core'),
        ],
      ]
    );

    $this->add_control(
      'st_custom_icon',
      [
        'label' => esc_html__('Toggle Icon', 'pe-core'),
        'type' => \Elementor\Controls_Manager::ICONS,
        'condition' => ['sub_toggle_style' => 'custom'],
      ]
    );

    $this->add_responsive_control(
      'st_toggle_position',
      [
        'label' => esc_html__('Icon Position', 'pe-core'),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
          'row-reverse' => [
            'title' => esc_html__('Left', 'pe-core'),
            'icon' => 'eicon-h-align-left',
          ],
          'row' => [
            'title' => esc_html__('Right', 'pe-core'),
            'icon' => 'eicon-h-align-right',
          ],
        ],
        'default' => 'row',
        'prefix_class' => 'st--icon--',
        'toggle' => false,
        'selectors' => [
          '{{WRAPPER}} .menu-item.zeyna-has-children, {{WRAPPER}} .menu-item-has-children' => 'flex-direction: {{VALUE}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'is_one_page',
      [
        'label' => esc_html__('One Page Navigation?', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'nav--one--page',
        'prefix_class' => '',
        'default' => '',
        'render_type' => 'template',
      ]
    );


    $this->end_controls_section();

    pe_text_animation_settings($this, true);

    $this->start_controls_section(
      'style',
      [
        'label' => esc_html__('Styling', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    flexOptions($this, false, '.menu.main-menu', 'menu_', 'Menu', true, '.menu-item');

    $this->add_control(
      'dot_color',
      [
        'label' => esc_html__('Dot Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .active--dot ul.menu>li.current-menu-item>a::before, .hover--dot ul.menu>li.current-menu-item>a::before, .hover--dot ul.menu>li>a:hover::before' => 'background-color: {{VALUE}}',
        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'menu_hover',
              'operator' => '===',
              'value' => 'dot',
            ],
            [
              'name' => 'active_item_style',
              'operator' => '===',
              'value' => 'dot',
            ],

          ],
        ],
      ]
    );


    $this->add_control(
      'underline_color',
      [
        'label' => esc_html__('Underline Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} ul.menu>li.current-menu-item::after, .hover--underline ul.menu>li::after, .hover--dot ul.menu>li.current-menu-item>a::before, .hover--dot ul.menu>li>a:hover::before' => 'background-color: {{VALUE}}',
        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'menu_hover',
              'operator' => '===',
              'value' => 'underline',
            ],
            [
              'name' => 'active_item_style',
              'operator' => '===',
              'value' => 'underline',
            ],

          ],
        ],
      ]
    );

    $this->add_control(
      'follower_color',
      [
        'label' => esc_html__('Follower Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .main-navigation ul::after' => 'background-color: {{VALUE}}',
        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'menu_hover',
              'operator' => '===',
              'value' => 'background-follower',
            ],
            [
              'name' => 'active_item_style',
              'operator' => '===',
              'value' => 'background-follower',
            ],

          ],
        ],
      ]
    );

    $this->add_control(
      'active_item_color',
      [
        'label' => esc_html__('Active Item Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .main-navigation ul li.current-menu-item a' => 'color: {{VALUE}} !important',
          '{{WRAPPER}} .main-navigation ul li a:hover' => 'color: {{VALUE}} !important',
        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'menu_hover',
              'operator' => '===',
              'value' => 'background-follower',
            ],
            [
              'name' => 'active_item_style',
              'operator' => '===',
              'value' => 'background-follower',
            ],

          ],
        ],
      ]
    );


    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'parents_typography',
        'label' => esc_html__('Parent Items Typography', 'pe-core'),
        'selector' => '{{WRAPPER}} ul.menu.main-menu > li',
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'items_hover_typography',
        'label' => esc_html__('Items Hover Typography', 'pe-core'),
        'selector' => '{{WRAPPER}} ul.menu>li.current-menu-item>a, {{WRAPPER}} ul.menu>li>a:hover',
        'condition' => [
          'menu_hover' => 'font-swap',
          'hover_use_secondary_font!' => 'true'
        ],
      ]
    );


    $this->add_control(
      'hover_use_secondary_font',
      [
        'label' => esc_html__('Use Secondary Font at Hover', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'true',
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} ul.menu>li.current-menu-item>a' => '
              font-family: var(--sec_typo-font-family);
              font-size: var(--sec_typo-font-size);
              line-height: var(--sec_typo-line-height);
              letter-spacing: var(--sec_typo-letter-spacing);
              font-weight: var(--sec_typo-font-weight);
         text-transform: var(--sec_typo-text-transform);',
          '{{WRAPPER}} ul.menu>li>a:hover' => '
              font-family: var(--sec_typo-font-family);
              font-size: var(--sec_typo-font-size);
              line-height: var(--sec_typo-line-height);
              letter-spacing: var(--sec_typo-letter-spacing);
              font-weight: var(--sec_typo-font-weight);
         text-transform: var(--sec_typo-text-transform);',
        ],
        'condition' => ['menu_hover' => 'font-swap'],
      ]
    );

    $this->add_control(
      'nav_menu_item_hover_color',
      [
        'label' => esc_html__('Hover/Active Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}}.hover--font-swap ul.menu>li.current-menu-item>a,
          {{WRAPPER}}.active--font-swap ul.menu>li.current-menu-item>a,
          {{WRAPPER}}.hover--font-swap ul.menu>li>a:hover ' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'hover_use_secondary_font' => 'true',
          'menu_hover' => 'font-swap'
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'subs_typography',
        'label' => esc_html__('Sub-Menu Items Typography', 'pe-core'),
        'selector' => '{{WRAPPER}} ul.menu ul.sub-menu li',
      ]
    );

    $this->add_responsive_control(
      'items_spacing',
      [
        'label' => esc_html__('Items Spacing', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'vw', 'em'],
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
          '{{WRAPPER}} .main-navigation ul' => '--gap: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}}.menu--items--seperator .main-navigation>ul>li::before' => 'margin-right: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    objectStyles($this, 'nav_menu_', 'Menu', 'ul.main-menu', false, false, false, true, false);
    objectStyles($this, '_menu_items', 'Menu Items', '.menu-item a.pe--styled--object', false, false, false, false, false, true);

    $this->add_responsive_control(
      'follower_radius',
      [
        'label' => esc_html__('Follower Radius', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
        'selectors' => [
          '{{WRAPPER}}.hover--background-follower .main-navigation ul::after' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
        ],
        'condition' => ['menu_hover' => 'background-follower'],
      ]
    );

    $this->add_responsive_control(
      'underline_height',
      [
        'label' => esc_html__('Underline Height', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['em', 'px'],
        'range' => [
          'px' => [
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
          '{{WRAPPER}} ul.menu>li.current-menu-item::after' => 'height: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} ul.menu>li::after' => 'height: {{SIZE}}{{UNIT}};',

        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'menu_hover',
              'operator' => '===',
              'value' => 'underline',
            ],
            [
              'name' => 'active_item_style',
              'operator' => '===',
              'value' => 'underline',
            ],

          ],
        ],
      ]
    );

    $this->add_responsive_control(
      'underline_width',
      [
        'label' => esc_html__('Underline Width', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['em', 'px', '%'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'em' => [
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
          '{{WRAPPER}} ul.menu>li.current-menu-item::after' => 'width: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} ul.menu>li:hover::after' => 'width: {{SIZE}}{{UNIT}};',

        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'menu_hover',
              'operator' => '===',
              'value' => 'underline',
            ],
            [
              'name' => 'active_item_style',
              'operator' => '===',
              'value' => 'underline',
            ],

          ],
        ],
      ]
    );

    $this->add_responsive_control(
      'underline_pos_y',
      [
        'label' => esc_html__('Underline Position', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['em', 'px', '%'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'em' => [
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
          '{{WRAPPER}} ul.menu>li.current-menu-item::after' => 'bottom: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} ul.menu>li::after' => 'bottom: {{SIZE}}{{UNIT}};',

        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'menu_hover',
              'operator' => '===',
              'value' => 'underline',
            ],
            [
              'name' => 'active_item_style',
              'operator' => '===',
              'value' => 'underline',
            ],

          ],
        ],
      ]
    );



    $this->add_control(
      'nav_menu_has_backdrop',
      [
        'label' => esc_html__('Backdrop Filter', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'nav--menu--has--backdrop',
        'prefix_class' => '',
        'default' => '',
      ]
    );

    $this->add_control(
      'nav_menu_background_color',
      [
        'label' => esc_html__('Background Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} ul.main-menu' => 'background-color: {{VALUE}}',
        ],
        'condition' => [
          'nav_menu_has_backdrop' => 'nav--menu--has--backdrop',
        ],
      ]
    );

    $this->add_responsive_control(
      'nav_menu_bg_backdrop_blur',
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
          'nav_menu_has_backdrop' => 'nav--menu--has--backdrop',
        ],
        'selectors' => [
          '{{WRAPPER}} ul.main-menu' => '--backdropBlur: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'toggle_styles',
      [
        'label' => esc_html__('Menu Toggle Styles', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'menu_toggle_typo',
        'label' => esc_html__('Toggle Typography', 'pe-core'),
        'selector' => '{{WRAPPER}} .pe--menu--toggle--text.pe--styled--object',
      ]
    );

    objectStyles($this, 'menu_toggle', 'Menu Toggle', '.pe--menu--toggle.pe--styled--object', false, [
      'menu_style' =>
        'menu--toggled'
    ], false, false, true, false);

    objectStyles($this, 'menu_toggle_icon', 'Icon', '.pe--menu--toggle--icon.pe--styled--object', false, ['toggle_icon_style!' => 'none'], false, false, true, true);
    objectStyles($this, 'menu_toggle_text', 'Text', '.pe--menu--toggle--text.pe--styled--object', true, false, false, false, true, true);

    $this->end_controls_section();


    pe_color_options($this);


  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $menuClasses = 'menu main-menu ' . $settings['menu_style'] . ' ' . $settings['indexed'];

    $stStyle = $settings['sub_toggle_style'];

    if ($stStyle === 'plus') {

      $subToggle = '<span class="sub--toggle st--plus"><span class="toggle--line"></span><span class="toggle--line"></span></span>';

    } else if ($stStyle === 'chevron') {

      $svgPath = plugin_dir_path(__FILE__) . '../assets/img/chevron_down.svg';
      $subToggle = '<span class="sub--toggle st--chevron">' . file_get_contents($svgPath) . '</span>';

    } else if ($stStyle === 'arrow') {

      $svgPath = plugin_dir_path(__FILE__) . '../assets/img/arrow_forward.svg';
      $subToggle = '<span class="sub--toggle st--arrow">' . file_get_contents($svgPath) . '</span>';

    } else if ($stStyle === 'custom') {

      ob_start();
      \Elementor\Icons_Manager::render_icon($settings['st_custom_icon'], ['aria-hidden' => 'true']);
      $icon = ob_get_clean();

      $subToggle = '<span class="sub--toggle st--custom">' . $icon . '</span>';

    }

    echo '<nav ' . pe_text_animation($this) . ' id="site-navigation" class="text--anim--multi main-navigation ' . $settings['sub_style'] . '" data-sub-toggle="' . esc_attr($subToggle) . '">';

    if ($settings['menu_style'] === 'menu--toggled') { ?>

      <div class="pe--menu--toggle pe--styled--object">

        <?php if ($settings['toggle_icon_style'] !== 'none') { ?>

          <div class="pe--menu--toggle--icon pe--styled--object <?php echo 'toggle--' . $settings['toggle_icon_style'] ?>">

            <?php if ($settings['toggle_icon_style'] !== 'none' && $settings['toggle_icon_style'] !== 'custom') {

              echo '<span></span><span></span><span></span>';

            } else if ($settings['toggle_icon_style'] === 'custom') {

              ob_start();
              \Elementor\Icons_Manager::render_icon($settings['toggle_open_icon'], ['aria-hidden' => 'true']);
              $openIcon = ob_get_clean();

              ob_start();
              \Elementor\Icons_Manager::render_icon($settings['toggle_close_icon'], ['aria-hidden' => 'true']);
              $closeIcon = ob_get_clean();

              echo '  <span class="toggle--open--icon">' . $openIcon . '</span>';
              echo '  <span class="toggle--close--icon">' . $closeIcon . '</span>';
            } ?>

          </div>
        <?php } ?>

        <?php if (!empty($settings['menu_open_text'])) { ?>
          <div class="pe--menu--toggle--text pe--styled--object">
            <span class="toggle--open--text"><?php echo $settings['menu_open_text'] ?></span>
            <span class="toggle--close--text"><?php echo $settings['menu_close_text'] ?></span>
          </div>
        <?php } ?>

      </div>

    <?php }

    wp_nav_menu(
      array(
        'theme_location' => '',
        'menu' => $settings['select_menu'],
        'container' => false,
        'menu_class' => $menuClasses,
      )
    );

    echo '</nav>';

  }

}
