<?php
namespace BrandberryEssentialApp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Plugin as ElementorPlugin;

if ( ! defined('ABSPATH') ) { exit; }

/**
 * Brandberry Offcanvas (dependency-free)
 * Inspired by Element Pack's Offcanvas widget, but does not rely on UIkit.
 */
class Offcanvas extends Widget_Base {

  public function get_name() { return 'brandberry-offcanvas'; }
  public function get_title() { return esc_html__('Offcanvas', 'brandberry-essential'); }
  public function get_icon() { return 'eicon-menu-bar'; }
  public function get_categories() { return ['brandberry']; }
  public function get_keywords() { return ['offcanvas', 'menu', 'drawer', 'panel']; }

  public function get_style_depends() { return ['brandberry-offcanvas']; }
  public function get_script_depends() { return ['brandberry-offcanvas']; }

  protected function register_controls() {

    // -------------------- Layout / Source --------------------
    $this->start_controls_section('section_content_layout', [
      'label' => esc_html__('Layout', 'brandberry-essential'),
    ]);

    $this->add_control('layout', [
      'label' => esc_html__('Layout', 'brandberry-essential'),
      'type' => Controls_Manager::SELECT,
      'default' => 'default',
      'options' => [
        'default' => esc_html__('Default', 'brandberry-essential'),
        'custom'  => esc_html__('Custom Link', 'brandberry-essential'),
      ],
    ]);

    $this->add_control('offcanvas_custom_selector', [
      'label' => esc_html__('Offcanvas Selector', 'brandberry-essential'),
      'type' => Controls_Manager::TEXT,
      'default' => '#bb-custom-offcanvas',
      'description' => __('Set your offcanvas selector here. Example: <b>.custom-link</b> or <b>#customLink</b>. Clicking that element will open this offcanvas.', 'brandberry-essential'),
      'condition' => ['layout' => 'custom'],
    ]);

    $this->add_control('source', [
      'label' => esc_html__('Select Source', 'brandberry-essential'),
      'type' => Controls_Manager::SELECT,
      'default' => 'sidebar',
      'options' => [
        'sidebar'   => esc_html__('Sidebar', 'brandberry-essential'),
        'elementor' => esc_html__('Elementor Template', 'brandberry-essential'),
        'html'      => esc_html__('Custom HTML', 'brandberry-essential'),
      ],
    ]);

    $this->add_control('template_id', [
      'label' => esc_html__('Select Template', 'brandberry-essential'),
      'type' => Controls_Manager::SELECT2,
      'options' => $this->get_elementor_templates(),
      'label_block' => true,
      'condition' => ['source' => 'elementor'],
    ]);

    $this->add_control('sidebars', [
      'label' => esc_html__('Choose Sidebar', 'brandberry-essential'),
      'type' => Controls_Manager::SELECT,
      'default' => '',
      'options' => $this->get_registered_sidebars(),
      'label_block' => true,
      'condition' => ['source' => 'sidebar'],
    ]);

    $this->add_control('custom_html', [
      'label' => esc_html__('Custom HTML', 'brandberry-essential'),
      'type' => Controls_Manager::WYSIWYG,
      'dynamic' => ['active' => true],
      'default' => '',
      'condition' => ['source' => 'html'],
    ]);

    $this->add_control('custom_content_before_switcher', [
      'label' => esc_html__('Custom Content Before', 'brandberry-essential'),
      'type' => Controls_Manager::SWITCHER,
    ]);

    $this->add_control('custom_content_after_switcher', [
      'label' => esc_html__('Custom Content After', 'brandberry-essential'),
      'type' => Controls_Manager::SWITCHER,
    ]);

    $this->add_control('offcanvas_overlay', [
      'label' => esc_html__('Overlay', 'brandberry-essential'),
      'type' => Controls_Manager::SWITCHER,
      'default' => 'yes',
      'return_value' => 'yes',
    ]);

    $this->add_control('offcanvas_animations', [
      'label' => esc_html__('Animations', 'brandberry-essential'),
      'type' => Controls_Manager::SELECT,
      'default' => 'slide',
      'options' => [
        'slide'  => esc_html__('Slide', 'brandberry-essential'),
        'push'   => esc_html__('Push', 'brandberry-essential'),
        'reveal' => esc_html__('Reveal', 'brandberry-essential'),
        'none'   => esc_html__('None', 'brandberry-essential'),
      ],
    ]);

    $this->add_control('offcanvas_flip', [
      'label' => esc_html__('Flip', 'brandberry-essential'),
      'type' => Controls_Manager::SWITCHER,
      'return_value' => 'right',
      'default' => '',
      'description' => esc_html__('When enabled, opens from the opposite side.', 'brandberry-essential'),
    ]);

    $this->add_control('offcanvas_close_button', [
      'label' => esc_html__('Close Button', 'brandberry-essential'),
      'type' => Controls_Manager::SWITCHER,
      'default' => 'yes',
      'return_value' => 'yes',
      'separator' => 'before',
    ]);

    $this->add_control('offcanvas_close_button_text', [
      'label' => esc_html__('Close Button Text', 'brandberry-essential'),
      'type' => Controls_Manager::TEXT,
      'dynamic' => ['active' => true],
      'placeholder' => esc_html__('Close', 'brandberry-essential'),
      'condition' => ['offcanvas_close_button' => 'yes'],
    ]);

    $this->add_responsive_control('button_close_icon_align', [
      'label' => esc_html__('Close Button Align', 'brandberry-essential'),
      'type' => Controls_Manager::CHOOSE,
      'options' => [
        'left' => [ 'title' => esc_html__('Left', 'brandberry-essential'), 'icon' => 'eicon-text-align-left' ],
        'center' => [ 'title' => esc_html__('Center', 'brandberry-essential'), 'icon' => 'eicon-text-align-center' ],
        'right' => [ 'title' => esc_html__('Right', 'brandberry-essential'), 'icon' => 'eicon-text-align-right' ],
      ],
      'selectors_dictionary' => [
        'left' => 'left: 10px; right: auto;',
        'center' => 'left: 50%; right: auto; transform: translateX(-50%);',
        'right' => 'right: 10px; left: auto;',
      ],
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas .bbe-offcanvas-close' => '{{VALUE}}',
      ],
      'condition' => ['offcanvas_close_button' => 'yes'],
    ]);

    $this->add_control('offcanvas_bg_close', [
      'label' => esc_html__('Close on Click Background', 'brandberry-essential'),
      'type' => Controls_Manager::SWITCHER,
      'default' => 'yes',
      'return_value' => 'yes',
    ]);

    $this->add_control('offcanvas_esc_close', [
      'label' => esc_html__('Close on Press ESC', 'brandberry-essential'),
      'type' => Controls_Manager::SWITCHER,
      'default' => 'yes',
      'return_value' => 'yes',
    ]);

    $this->add_control('position', [
      'label' => esc_html__('Position', 'brandberry-essential'),
      'type' => Controls_Manager::SELECT,
      'default' => 'left',
      'options' => [
        'left' => esc_html__('Left', 'brandberry-essential'),
        'right' => esc_html__('Right', 'brandberry-essential'),
      ],
      'separator' => 'before',
    ]);

    $this->add_responsive_control('offcanvas_width', [
      'label' => esc_html__('Width', 'brandberry-essential'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', 'vw', '%'],
      'range' => [
        'px' => ['min' => 240, 'max' => 1200],
        'vw' => ['min' => 10, 'max' => 100],
        '%'  => ['min' => 10, 'max' => 100],
      ],
      'default' => ['unit' => 'px', 'size' => 360],
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas' => '--bbe-oc-width: {{SIZE}}{{UNIT}};',
      ],
    ]);

    $this->add_responsive_control('offcanvas_height', [
      'label' => esc_html__('Height', 'brandberry-essential'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', 'vh'],
      'range' => [
        'px' => ['min' => 200, 'max' => 1400],
        'vh' => ['min' => 10, 'max' => 100],
      ],
      'default' => ['unit' => 'vh', 'size' => 100],
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas' => '--bbe-oc-height: {{SIZE}}{{UNIT}};',
      ],
    ]);

    $this->end_controls_section();

    // -------------------- Custom content before/after --------------------
    $this->start_controls_section('section_content_custom_before', [
      'label' => esc_html__('Custom Content Before', 'brandberry-essential'),
      'condition' => ['custom_content_before_switcher' => 'yes'],
    ]);
    $this->add_control('custom_content_before', [
      'label' => esc_html__('Custom Content Before', 'brandberry-essential'),
      'type' => Controls_Manager::WYSIWYG,
      'dynamic' => ['active' => true],
      'default' => esc_html__('This is your custom content for before of your offcanvas.', 'brandberry-essential'),
    ]);
    $this->end_controls_section();

    $this->start_controls_section('section_content_custom_after', [
      'label' => esc_html__('Custom Content After', 'brandberry-essential'),
      'condition' => ['custom_content_after_switcher' => 'yes'],
    ]);
    $this->add_control('custom_content_after', [
      'label' => esc_html__('Custom Content After', 'brandberry-essential'),
      'type' => Controls_Manager::WYSIWYG,
      'dynamic' => ['active' => true],
      'default' => esc_html__('This is your custom content for after of your offcanvas.', 'brandberry-essential'),
    ]);
    $this->end_controls_section();

    // -------------------- Button (default layout) --------------------
    $this->start_controls_section('section_content_offcanvas_button', [
      'label' => esc_html__('Button', 'brandberry-essential'),
      'condition' => ['layout' => 'default'],
    ]);

    $this->add_control('button_text', [
      'label' => esc_html__('Button Text', 'brandberry-essential'),
      'type' => Controls_Manager::TEXT,
      'dynamic' => ['active' => true],
      'default' => esc_html__('Offcanvas', 'brandberry-essential'),
    ]);

    $this->add_control('offcanvas_button_icon', [
      'label' => esc_html__('Icon', 'brandberry-essential'),
      'type' => Controls_Manager::ICONS,
      'default' => [ 'value' => 'fas fa-bars', 'library' => 'fa-solid' ],
      'skin' => 'inline',
    ]);

    $this->add_control('button_icon_align', [
      'label' => esc_html__('Icon Position', 'brandberry-essential'),
      'type' => Controls_Manager::SELECT,
      'default' => 'left',
      'options' => [
        'left' => esc_html__('Before', 'brandberry-essential'),
        'right' => esc_html__('After', 'brandberry-essential'),
      ],
    ]);

    $this->add_responsive_control('button_align', [
      'label' => esc_html__('Button Alignment', 'brandberry-essential'),
      'type' => Controls_Manager::CHOOSE,
      'options' => [
        'left' => [ 'title' => esc_html__('Left', 'brandberry-essential'), 'icon' => 'eicon-text-align-left' ],
        'center' => [ 'title' => esc_html__('Center', 'brandberry-essential'), 'icon' => 'eicon-text-align-center' ],
        'right' => [ 'title' => esc_html__('Right', 'brandberry-essential'), 'icon' => 'eicon-text-align-right' ],
        'justify' => [ 'title' => esc_html__('Justified', 'brandberry-essential'), 'icon' => 'eicon-text-align-justify' ],
      ],
      'prefix_class' => 'elementor%s-align-',
      'default' => 'left',
    ]);

    $this->add_control('button_offset_toggle', [
      'label' => esc_html__('Offset', 'brandberry-essential'),
      'type' => Controls_Manager::POPOVER_TOGGLE,
      'label_off' => esc_html__('None', 'brandberry-essential'),
      'label_on' => esc_html__('Custom', 'brandberry-essential'),
      'return_value' => 'yes',
    ]);

    $this->start_popover();

    $this->add_responsive_control('button_offset', [
      'label' => esc_html__('Horizontal Offset', 'brandberry-essential'),
      'type' => Controls_Manager::SLIDER,
      'range' => [ 'px' => [ 'min' => -300, 'max' => 300, 'step' => 1 ] ],
      'condition' => [ 'button_offset_toggle' => 'yes' ],
      'selectors' => [ '{{WRAPPER}} .bbe-offcanvas' => '--bbe-oc-h-offset: {{SIZE}}px;' ],
    ]);

    $this->add_responsive_control('button_vertical_offset', [
      'label' => esc_html__('Vertical Offset', 'brandberry-essential'),
      'type' => Controls_Manager::SLIDER,
      'range' => [ 'px' => [ 'min' => -300, 'max' => 300, 'step' => 1 ] ],
      'condition' => [ 'button_offset_toggle' => 'yes' ],
      'selectors' => [ '{{WRAPPER}} .bbe-offcanvas' => '--bbe-oc-v-offset: {{SIZE}}px;' ],
    ]);

    $this->add_responsive_control('button_rotate', [
      'label' => esc_html__('Rotate', 'brandberry-essential'),
      'type' => Controls_Manager::SLIDER,
      'range' => [ 'deg' => [ 'min' => -180, 'max' => 180, 'step' => 1 ] ],
      'size_units' => ['deg'],
      'condition' => [ 'button_offset_toggle' => 'yes' ],
      'selectors' => [ '{{WRAPPER}} .bbe-offcanvas' => '--bbe-oc-rotate: {{SIZE}}deg;' ],
    ]);

    $this->end_popover();

    $this->end_controls_section();

    // -------------------- Style: Button --------------------
    $this->start_controls_section('section_style_button', [
      'label' => esc_html__('Button', 'brandberry-essential'),
      'tab' => Controls_Manager::TAB_STYLE,
      'condition' => ['layout' => 'default'],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'button_typography',
      'selector' => '{{WRAPPER}} .bbe-offcanvas-button',
    ]);

    $this->add_responsive_control('button_padding', [
      'label' => esc_html__('Padding', 'brandberry-essential'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', 'em'],
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ],
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
      'name' => 'button_border',
      'selector' => '{{WRAPPER}} .bbe-offcanvas-button',
    ]);

    $this->add_control('button_radius', [
      'label' => esc_html__('Border Radius', 'brandberry-essential'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%'],
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ],
    ]);

    $this->start_controls_tabs('tabs_button_colors');

    $this->start_controls_tab('tab_button_normal', [
      'label' => esc_html__('Normal', 'brandberry-essential'),
    ]);

    $this->add_control('button_text_color', [
      'label' => esc_html__('Text Color', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-button' => 'color: {{VALUE}};',
        '{{WRAPPER}} .bbe-offcanvas-button svg' => 'fill: {{VALUE}};',
      ],
    ]);

    $this->add_control('button_bg_color', [
      'label' => esc_html__('Background', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-button' => 'background-color: {{VALUE}};',
      ],
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('tab_button_hover', [
      'label' => esc_html__('Hover', 'brandberry-essential'),
    ]);

    $this->add_control('button_text_color_hover', [
      'label' => esc_html__('Text Color', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-button:hover' => 'color: {{VALUE}};',
        '{{WRAPPER}} .bbe-offcanvas-button:hover svg' => 'fill: {{VALUE}};',
      ],
    ]);

    $this->add_control('button_bg_color_hover', [
      'label' => esc_html__('Background', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-button:hover' => 'background-color: {{VALUE}};',
      ],
    ]);

    $this->end_controls_tab();
    $this->end_controls_tabs();

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
      'name' => 'button_shadow',
      'selector' => '{{WRAPPER}} .bbe-offcanvas-button',
    ]);

    $this->end_controls_section();

    // -------------------- Style: Offcanvas panel --------------------
    $this->start_controls_section('section_style_panel', [
      'label' => esc_html__('Offcanvas', 'brandberry-essential'),
      'tab' => Controls_Manager::TAB_STYLE,
    ]);

    $this->add_control('panel_bg', [
      'label' => esc_html__('Background', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-bar' => 'background: {{VALUE}};',
      ],
    ]);

    $this->add_responsive_control('panel_padding', [
      'label' => esc_html__('Padding', 'brandberry-essential'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', 'em'],
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ],
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
      'name' => 'panel_border',
      'selector' => '{{WRAPPER}} .bbe-offcanvas-bar',
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
      'name' => 'panel_shadow',
      'selector' => '{{WRAPPER}} .bbe-offcanvas-bar',
    ]);

    $this->end_controls_section();

    // -------------------- Style: Overlay --------------------
    $this->start_controls_section('section_style_overlay', [
      'label' => esc_html__('Overlay', 'brandberry-essential'),
      'tab' => Controls_Manager::TAB_STYLE,
      'condition' => ['offcanvas_overlay' => 'yes'],
    ]);

    $this->add_control('overlay_color', [
      'label' => esc_html__('Overlay Color', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas' => '--bbe-oc-overlay: {{VALUE}};',
      ],
    ]);

    $this->end_controls_section();

    // -------------------- Style: Close button --------------------
    $this->start_controls_section('section_style_close', [
      'label' => esc_html__('Close Button', 'brandberry-essential'),
      'tab' => Controls_Manager::TAB_STYLE,
      'condition' => ['offcanvas_close_button' => 'yes'],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'close_typography',
      'selector' => '{{WRAPPER}} .bbe-offcanvas-close',
    ]);

    $this->add_control('close_color', [
      'label' => esc_html__('Color', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-close' => 'color: {{VALUE}};',
      ],
    ]);

    $this->add_control('close_bg', [
      'label' => esc_html__('Background', 'brandberry-essential'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .bbe-offcanvas-close' => 'background-color: {{VALUE}};',
      ],
    ]);

    $this->end_controls_section();
  }

  private function get_elementor_templates(): array {
    $opts = ['' => esc_html__('— Select —', 'brandberry-essential')];
    $posts = get_posts([
      'post_type' => 'elementor_library',
      'posts_per_page' => 300,
      'orderby' => 'title',
      'order' => 'ASC',
    ]);
    foreach ($posts as $p) {
      $opts[(string) $p->ID] = $p->post_title;
    }
    return $opts;
  }

  private function get_registered_sidebars(): array {
    global $wp_registered_sidebars;
    $opts = ['' => esc_html__('— Select —', 'brandberry-essential')];
    if (is_array($wp_registered_sidebars)) {
      foreach ($wp_registered_sidebars as $id => $sb) {
        $opts[$id] = $sb['name'] ?? $id;
      }
    }
    return $opts;
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    $layout = $settings['layout'] ?? 'default';
    $id = ($layout === 'custom' && ! empty($settings['offcanvas_custom_selector']))
      ? $settings['offcanvas_custom_selector']
      : 'bbe-offcanvas-' . $this->get_id();

    // If user provided selector like ".x" we still need a real DOM id for our wrapper.
    $dom_id = 'bbe-offcanvas-' . $this->get_id();

    $position = $settings['position'] ?? 'left';
    $mode = $settings['offcanvas_animations'] ?? 'slide';

    // Flip means "open from the opposite side".
    if (($settings['offcanvas_flip'] ?? '') === 'right') {
      $position = ($position === 'left') ? 'right' : 'left';
    }

    $data = [
      'id' => $dom_id,
      'layout' => $layout,
      'custom_selector' => ($layout === 'custom') ? ($settings['offcanvas_custom_selector'] ?? '') : '',
      'mode' => $mode,
      'overlay' => (($settings['offcanvas_overlay'] ?? '') === 'yes'),
      'bg_close' => (($settings['offcanvas_bg_close'] ?? '') === 'yes'),
      'esc_close' => (($settings['offcanvas_esc_close'] ?? '') === 'yes'),
      'position' => $position,
    ];

    $this->add_render_attribute('wrap', 'class', 'bbe-offcanvas');
    $this->add_render_attribute('wrap', 'id', $dom_id);
    // Frontend JS reads settings from data-bbe-settings.
    $this->add_render_attribute('wrap', 'data-bbe-settings', wp_json_encode($data));
    // Back-compat (older builds).
    $this->add_render_attribute('wrap', 'data-settings', wp_json_encode($data));
    $this->add_render_attribute('wrap', 'data-mode', $mode);
    $this->add_render_attribute('wrap', 'data-position', $position);

    // Button (default layout)
    if ($layout === 'default') {
      $this->render_button($settings, $dom_id);
    }

    echo '<div ' . $this->get_render_attribute_string('wrap') . '>';

    // Overlay
    echo '<div class="bbe-offcanvas-overlay" aria-hidden="true"></div>';

    echo '<div class="bbe-offcanvas-bar" role="dialog" aria-modal="true" aria-label="' . esc_attr($this->get_title()) . '">';

    if (($settings['offcanvas_close_button'] ?? 'yes') === 'yes') {
      echo '<button class="bbe-offcanvas-close" type="button" aria-label="' . esc_attr__('Close', 'brandberry-essential') . '">';
      if (! empty($settings['offcanvas_close_button_text'])) {
        echo '<span>' . wp_kses_post($settings['offcanvas_close_button_text']) . '</span>';
      }
      echo '</button>';
    }

    echo '<div class="bbe-offcanvas-content">';

    if (($settings['custom_content_before_switcher'] ?? '') === 'yes' && ! empty($settings['custom_content_before'])) {
      echo '<div class="bbe-offcanvas-custom-content-before widget">' . wp_kses_post($settings['custom_content_before']) . '</div>';
    }

    $this->render_inner_content($settings);

    if (($settings['custom_content_after_switcher'] ?? '') === 'yes' && ! empty($settings['custom_content_after'])) {
      echo '<div class="bbe-offcanvas-custom-content-after widget">' . wp_kses_post($settings['custom_content_after']) . '</div>';
    }

    echo '</div>'; // content
    echo '</div>'; // bar
    echo '</div>'; // wrap
  }

  protected function render_button(array $settings, string $target_id) {
    $text = $settings['button_text'] ?? esc_html__('Offcanvas', 'brandberry-essential');

    $this->add_render_attribute('button', 'class', ['bbe-offcanvas-button', 'elementor-button']);
    $this->add_render_attribute('button', 'href', '#');
    // A simple explicit target attribute for our frontend JS.
    $this->add_render_attribute('button', 'data-bbe-target', '#' . esc_attr($target_id));
    // Back-compat attribute (older builds).
    $this->add_render_attribute('button', 'data-bbe-toggle', 'target: #' . esc_attr($target_id));
    $this->add_render_attribute('button', 'aria-controls', esc_attr($target_id));
    $this->add_render_attribute('button', 'role', 'button');

    $icon_html = '';
    if (! empty($settings['offcanvas_button_icon']['value'])) {
      ob_start();
      Icons_Manager::render_icon($settings['offcanvas_button_icon'], ['aria-hidden' => 'true']);
      $icon_html = ob_get_clean();
    }

    $icon_first = (($settings['button_icon_align'] ?? 'left') === 'left');

    echo '<div class="bbe-offcanvas-button-wrapper">';
    echo '<a ' . $this->get_render_attribute_string('button') . '>';
    if ($icon_first) echo '<span class="bbe-offcanvas-button-icon">' . $icon_html . '</span>';
    if (! empty($text)) echo '<span class="bbe-offcanvas-button-text">' . wp_kses_post($text) . '</span>';
    if (! $icon_first) echo '<span class="bbe-offcanvas-button-icon">' . $icon_html . '</span>';
    echo '</a>';
    echo '</div>';
  }

  protected function render_inner_content(array $settings) {
    $source = $settings['source'] ?? 'sidebar';

    if ($source === 'sidebar' && ! empty($settings['sidebars'])) {
      dynamic_sidebar($settings['sidebars']);
      return;
    }

    if ($source === 'elementor' && ! empty($settings['template_id'])) {
      echo ElementorPlugin::$instance->frontend->get_builder_content_for_display((int) $settings['template_id']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      return;
    }

    if ($source === 'html' && ! empty($settings['custom_html'])) {
      echo wp_kses_post($settings['custom_html']);
      return;
    }

    // Fallback message
    echo '<div class="bbe-alert" role="alert">' . esc_html__('You did not select any content. Please add content from the widget settings.', 'brandberry-essential') . '</div>';
  }
}
