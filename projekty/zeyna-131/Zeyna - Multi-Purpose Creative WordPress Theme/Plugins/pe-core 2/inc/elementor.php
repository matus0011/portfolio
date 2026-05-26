<?php

function container_colors($element, $section_id, $args)
{

	if (('section' === $element->get_name() || 'container' === $element->get_name()) && 'section_background' === $section_id) {

		$element->start_controls_section(
			'custom_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'label' => esc_html__('Container Colors', 'pe-core'),
			]
		);

		$element->add_control(
			'container_layout',
			[
				'label' => 'Container Layout',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => 'Default',
					'layout--switched' => 'Switched',
				],
				'default' => '',
				'prefix_class' => '',
				'condition' => ['switch_on_enter!' => 'switch_on_enter'],
			]
		);


		$element->add_control(
			'switch_on_enter',
			[
				'label' => esc_html__('Switch Layout on Enter', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'switch_on_enter',
				'prefix_class' => '',
				'default' => false,
				'condition' => ['container_layout!' => 'layout--switched'],
			]
		);



		$element->start_controls_tabs(
			'element_tabs'
		);

		$element->start_controls_tab(
			'colors_default',
			[
				'label' => esc_html__('Default', 'pe-core'),
			]
		);

		$element->add_control(
			'main_color',
			[
				'label' => esc_html__('Main Texts Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} , {{WRAPPER}} .e-con' => '--mainColor: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'secondary_color',
			[
				'label' => esc_html__('Secondary Texts Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} , {{WRAPPER}} .e-con' => '--secondaryColor: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'lines_color',
			[
				'label' => esc_html__('Lines Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} , {{WRAPPER}} .e-con' => '--linesColor: {{VALUE}}',
				],
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'colors_switched',
			[
				'label' => esc_html__('Switched', 'pe-core'),
			]
		);

		$element->add_control(
			'switched_main_color',
			[
				'label' => esc_html__('Main Texts Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body.layout--switched {{WRAPPER}} , .header--switched {{WRAPPER}} ,body.layout--switched {{WRAPPER}} .e-con , .header--switched {{WRAPPER}} .e-con' => '--mainColor: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'switched_secondary_color',
			[
				'label' => esc_html__('Secondary Texts Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body.layout--switched {{WRAPPER}} , .header--switched {{WRAPPER}} ,body.layout--switched {{WRAPPER}} .e-con , .header--switched {{WRAPPER}} .e-con' => '--secondaryColor: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'switched_lines_color',
			[
				'label' => esc_html__('Lines Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body.layout--switched {{WRAPPER}} , .header--switched {{WRAPPER}} ,body.layout--switched {{WRAPPER}} .e-con , .header--switched {{WRAPPER}} .e-con' => '--linesColor: {{VALUE}}',
				],
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();




	}

}
add_action('elementor/element/before_section_start', 'container_colors', 10, 4);

function convert_containers($element, $section_id, $args)
{

	if (('container' === $element->get_name()) && 'section_layout_additional_options' === $section_id) {

		$element->start_controls_section(
			'convert_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
				'label' => esc_html__('Convert Container', 'pe-core'),
				'condition' => ['container_type' => 'flex'],

			]
		);

		$element->add_control(
			'container_refresh_behavior_convert',
			[
				'label' => esc_html__('Animation Mode', 'pe-core'),
				'description' => esc_html__('(For editor only). Since the heavy animations causes performance drops on Elementor editor you could pause animation when editing animated elements.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'con--mode--play' => [
						'title' => esc_html__('Play Mode', 'pe-core'),
						'icon' => 'eicon-play-o',
					],
					'con--mode--edit' => [
						'title' => esc_html__('Edit Mode', 'pe-core'),
						'icon' => 'eicon-library-edit',
					],
				],
				'default' => 'con--mode--edit',
				'prefix_class' => '',
				'render_type' => 'template',
				'toggle' => true,
				'condition' => [
					'convert_container!' => '',
				],
			]
		);


		$element->add_control(
			'convert_container',
			[
				'label' => 'Convert Container',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => 'None',
					'convert--layered' => 'Layered',
					'convert--carousel' => 'Carousel',
					'convert--curved' => 'Curved',
				],
				'default' => '',
				// 'render_type' => 'template',
				'prefix_class' => '',
			]
		);

		$element->add_control(
			'curved_items_pos',
			[
				'label' => 'Items Position',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'curved--items--left' => 'Left',
					'curved--items--center' => 'Center',
					'curved--items--right' => 'Right',
				],
				'default' => 'curved--items--left',
				// 'render_type' => 'template',
				'prefix_class' => '',
				'condition' => ['convert_container' => 'convert--curved'],
			]
		);

		$element->add_control(
			'curved_css',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'fit-content',
				'condition' => ['convert_container' => 'convert--curved'],
				'selectors' => [
					'{{WRAPPER}}' => 'width: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			'curved_angle',
			[
				'label' => esc_html__('Angle', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'deg' => [
						'min' => 10,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 30,
				],
				'condition' => ['convert_container' => 'convert--curved'],
				'selectors' => [
					'{{WRAPPER}}' => '--angle: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);


		$element->add_control(
			'y_adjust',
			[
				'label' => esc_html__('Y Adjust', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'condition' => ['convert_container' => 'convert--curved'],
				'selectors' => [
					'{{WRAPPER}}' => '--yVal: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$element->add_responsive_control(
			'items_width',
			[
				'label' => esc_html__('Items Width', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
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
					'rem' => [
						'min' => 0,
						'max' => 100,
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
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--itemsWidth: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} > .elementor-element' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => ['convert_container' => 'convert--curved'],
			]
		);


		$element->add_control(
			'container_carousel_id',
			[
				'label' => esc_html__('Carousel ID', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('An id will required if the carousel controls from other widgets will be used.', 'pe-core'),
				'ai' => false,
				'prefix_class' => '',
				'condition' => ['convert_container' => 'convert--carousel'],
			]
		);


		$element->add_control(
			'container_carousel_behavior',
			[
				'label' => esc_html__('Carousel Behavior', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'cr--drag',
				'options' => [
					'cr--drag' => esc_html__('Drag', 'pe-core'),
					'cr--scroll' => esc_html__('Scroll', 'pe-core'),
					'cr--looped--autoplay' => esc_html__('Looped Autoplay', 'pe-core'),
				],
				'prefix_class' => '',
				'render_type' => 'template',
				'condition' => ['convert_container' => 'convert--carousel'],
			]
		);

		$element->add_control(
			'container_carousel_mobile_drag',
			[
				'label' => esc_html__('Drag at mobile?', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'cr--drag--mobile',
				'prefix_class' => '',
				'default' => '',
				'render_type' => 'template',
				'condition' => [
					'convert_container' => 'convert--carousel',
					'container_carousel_behavior' => 'cr--scroll',
				],
			]
		);

		$element->add_control(
			'fade_edges',
			[
				'label' => esc_html__('Fade Edges?', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'fade--edges',
				'prefix_class' => '',
				'default' => '',
				'render_type' => 'template',
				'condition' => [
					'convert_container' => 'convert--carousel',
				],
			]
		);

		$element->add_control(
			'convert_direction',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'converted--vertical',
				'condition' => [
					'flex_direction' => 'column',
					'convert_container' => 'convert--carousel'
				],
				'prefix_class' => '',
			]
		);

		$element->add_responsive_control(
			'vertical_convert_parent_height',
			[
				'label' => esc_html__('Parent Height', 'pe-core'),
				'description' => esc_html__('*Required: This adjusts the parent container height.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'vh'],
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
					'vh' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 50,
				],
				'selectors' => [
					'.elementor-element.e-con:has(>.elementor-element.elementor-element-{{ID}})' => 'height: {{SIZE}}{{UNIT}};overflow: hidden;',
				],
				// 'render_type' => 'template',
				'condition' => [
					'flex_direction' => 'column',
					'convert_container' => 'convert--carousel'
				],
			]
		);

		$element->add_control(
			'looped_direction',
			[
				'label' => esc_html__('Direction', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'loop--up',
				'options' => [
					'loop--up' => esc_html__('Up', 'pe-core'),
					'loop--down' => esc_html__('Down', 'pe-core'),
				],
				'prefix_class' => '',
				'render_type' => 'template',
				'condition' => [
					'flex_direction' => 'column',
					'convert_container' => 'convert--carousel'
				],
			]
		);

		$element->add_control(
			'loop_speed',
			[
				'label' => esc_html__('Loop Speed (Seconds)', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 20,
				'selectors' => [
					'{{WRAPPER}}' => '--loopSpeed: {{VALUE}};',
				],
				'render_type' => 'template',
				'condition' => [
					'convert_container' => 'convert--carousel',
					'container_carousel_behavior' => 'cr--looped--autoplay',
				],
			]
		);

		$element->add_control(
			'loop_speed_up',
			[
				'label' => esc_html__('Speed up on Scroll', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'loop--speed--up',
				'prefix_class' => '',
				'default' => false,
				'render_type' => 'template',
				'condition' => [
					'convert_container' => 'convert--carousel',
					'container_carousel_behavior' => 'cr--looped--autoplay',
				],
			]
		);

		$element->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__('Pause on Hover', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'loop--pause--on--hover',
				'prefix_class' => '',
				'default' => false,
				'render_type' => 'template',
				'condition' => [
					'convert_container' => 'convert--carousel',
					'container_carousel_behavior' => 'cr--looped--autoplay',
				],
			]
		);



		$element->add_responsive_control(
			'container_carousel_start',
			[
				'label' => esc_html__('Carousel Start Position', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'vw'],
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
					'vw' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--carouselStart: {{SIZE}}{{UNIT}};',
				],
				// 'render_type' => 'template',
				'condition' => ['container_carousel_behavior' => 'cr--scroll'],
			]
		);

		$element->add_control(
			'highligh_active_item',
			[
				'label' => esc_html__('Highlight Active', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'container--highlight--active',
				'prefix_class' => '',
				'default' => false,
				'condition' => ['convert_container' => 'convert--carousel'],
			]
		);

		$element->add_control(
			'container_carousel_trigger',
			[
				'label' => esc_html__('Carousel Trigger', 'pe-core'),
				'placeholder' => esc_html__('Eg. #worksContainer', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Normally the carousel pin itself but in some cases, a custom trigger may required.', 'pe-core'),
				'ai' => false,
				'prefix_class' => 'carousel_trigger_',
				'condition' => ['container_carousel_behavior' => 'cr--scroll'],
			]
		);

		$element->add_control(
			'scroll_speed',
			[
				'label' => esc_html__('Scroll Speed', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'step' => 100,
				'default' => 1000,
				'prefix_class' => 'layered_speed_',
				'condition' => ['convert_container' => 'convert--layered'],
			]
		);

		$element->add_control(
			'pin_target',
			[
				'label' => esc_html__('Pin Target', 'pe-core'),
				'placeholder' => esc_html__('Eg. #worksContainer', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Normally the container pin itself but in some cases, a custom trigger may required.', 'pe-core'),
				'ai' => false,
				'prefix_class' => 'layered_target_',
				'condition' => ['convert_container' => 'convert--layered'],
			]
		);

		$element->add_control(
			'layered_accordion',
			[
				'label' => esc_html__('Accordion Layers', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'accordion--layered',
				'prefix_class' => '',
				'default' => false,
				'condition' => ['convert_container' => 'convert--layered'],
			]
		);
		$element->add_responsive_control(
			'accordion_layered_spacings',
			[
				'label' => esc_html__('Spacings', 'pe-core'),
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
					'background_type' => ['color']
				],
				'selectors' => [
					'{{WRAPPER}}' => '--accordionLayerSpacing: {{SIZE}};',
				],
				'condition' => [
					'convert_container' => 'convert--layered',
					'layered_accordion' => 'accordion--layered'
				],
			]
		);

		$element->add_control(
			'layered_out_animation',
			[
				'label' => esc_html__('Out Animation', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'layered_out_anim',
				'prefix_class' => '',
				'default' => false,
				'condition' => ['convert_container' => 'convert--layered'],
			]
		);

		$element->add_control(
			'layered_out_anim',
			[
				'label' => esc_html__('Out', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'layer--out--fade-simple',
				'options' => [
					'layer--out--fade-simple' => esc_html__('Simple', 'pe-core'),
					'layer--out--fade-up' => esc_html__('Fade Up', 'pe-core'),
					'layer--out--fade-out' => esc_html__('Fade Out', 'pe-core'),
					'layer--out--fade-left' => esc_html__('Fade Left', 'pe-core'),
					'layer--out--fade-right' => esc_html__('Fade Right', 'pe-core'),
					'layer--out--slide-up' => esc_html__('Slide Up', 'pe-core'),
					'layer--out--slide-left' => esc_html__('Slide Left', 'pe-core'),
					'layer--out--slide-right' => esc_html__('Slide Right', 'pe-core'),
				],
				'prefix_class' => '',
				'label_block' => false,
				'condition' => [
					'convert_container' => 'convert--layered',
					'layered_out_animation' => 'layered_out_anim'
				],
			]
		);


		$element->add_control(
			'cursor_drag',
			[
				'label' => esc_html__('Cursor Drag Interaction', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'cursor_drag',
				'prefix_class' => '',
				'default' => false,
				'condition' => ['convert_container' => 'convert--carousel'],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'container_tab_title',
			[
				'label' => esc_html__('Title', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Title', 'pe-core'),
				'label_block' => true,
			]
		);



		$element->end_controls_section();



		$element->start_controls_section(
			'cursor_interactions',
			[
				'label' => __('Cursor Interactions', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'cursor_type',
			[
				'label' => esc_html__('Interaction', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__('None', 'pe-core'),
					'default' => esc_html__('Default', 'pe-core'),
					'text' => esc_html__('Text', 'pe-core'),
					'icon' => esc_html__('Icon', 'pe-core'),
				],

			]
		);

		$element->add_control(
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

		$element->add_control(
			'cursor_text',
			[
				'label' => esc_html__('Text', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => ['cursor_type' => 'text'],
			]
		);


		$element->end_controls_section();

		$element->start_controls_section(
			'container_behaviors',
			[
				'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
				'label' => esc_html__('Container Behaviors', 'pe-core'),

			]
		);

		$element->add_control(
			'container_refresh_behavior',
			[
				'label' => esc_html__('Animation Mode', 'pe-core'),
				'description' => esc_html__('(For editor only). Since the heavy animations causes performance drops on Elementor editor you could pause animation when editing animated elements.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'con--mode--play' => [
						'title' => esc_html__('Play Mode', 'pe-core'),
						'icon' => 'eicon-play-o',
					],
					'con--mode--edit' => [
						'title' => esc_html__('Edit Mode', 'pe-core'),
						'icon' => 'eicon-library-edit',
					],
				],
				'default' => 'con--mode--edit',
				'prefix_class' => '',
				'render_type' => 'template',
				'toggle' => true,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'backward_container',
							'operator' => '===',
							'value' => 'backward__container',
						],
						[
							'name' => 'backward_container',
							'operator' => '===',
							'value' => 'backward__container',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);


		$element->add_control(
			'build_notice',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-danger">	
				   <span>The animation is disabled at the editor view due to performance reasons. Can be viewed only at front-end of the page. </span></div>',
				'condition' => ['build_on_scroll' => 'build--on--scroll'],
			]
		);

		$element->add_control(
			'build_on_scroll',
			[
				'label' => esc_html__('Build Grid on Scroll', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'build--on--scroll',
				'prefix_class' => '',
				'default' => false,
				'condition' => [],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '!==',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '!==',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '!==',
							'value' => 'grid--stacked',
						],
						[
							'name' => 'backward_container',
							'operator' => '!==',
							'value' => 'backward__container',
						],
						[
							'name' => 'pin_container',
							'operator' => '!==',
							'value' => 'true',
						],
						[
							'name' => 'container_type',
							'operator' => '===',
							'value' => 'grid',
						],
					],
				],
			]
		);

		$element->add_control(
			'build_pin',
			[
				'label' => esc_html__('Pin', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'build--pin',
				'prefix_class' => '',
				'default' => 'build--pin',
				'condition' => [
					'container_type' => 'grid',
					'build_on_scroll' => 'build--on--scroll',

				],
			]
		);


		$element->add_control(
			'build_type',
			[
				'label' => esc_html__('Build Type', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide-up',
				'options' => [
					'slide-up' => esc_html__('Slide Up', 'pe-core'),
					'slide-down' => esc_html__('Slide Down', 'pe-core'),
					'slide-left' => esc_html__('Slide Left', 'pe-core'),
					'slide-right' => esc_html__('Slide Right', 'pe-core'),
					'scale-up' => esc_html__('Scale Up', 'pe-core'),
					'fade' => esc_html__('Simple Fade', 'pe-core'),
				],
				'prefix_class' => 'build_type_',
				'label_block' => false,
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);


		$element->add_control(
			'build_on_scroll_target',
			[
				'label' => esc_html__('Parent Target', 'pe-core'),
				'placeholder' => esc_html__('Eg. #worksContainer', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('A parent container is required to make it work.', 'pe-core'),
				'ai' => false,
				'prefix_class' => 'build_pin_container_',
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);

		$element->add_control(
			'animate_first_item',
			[
				'label' => esc_html__('Animate First Item', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'animate--first--item',
				'prefix_class' => '',
				'default' => false,
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);
		$element->add_control(
			'animate_inners',
			[
				'label' => esc_html__('Animate Inner Elements', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'animate--inners',
				'prefix_class' => '',
				'default' => false,
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);

		$element->add_control(
			'build_rotation',
			[
				'label' => esc_html__('Rotation', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'rotate--items',
				'prefix_class' => '',
				'default' => false,
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);

		$element->add_control(
			'stagger_from',
			[
				'label' => esc_html__('Start From', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'start',
				'options' => [
					'start' => esc_html__('Start', 'pe-core'),
					'center' => esc_html__('Center', 'pe-core'),
					'end' => esc_html__('End', 'pe-core'),
				],
				'prefix_class' => 'stagger_from_',
				'label_block' => false,
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);

		$element->add_control(
			'build_stagger',
			[
				'label' => esc_html__('Stagger', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 10,
				'step' => 0.1,
				'prefix_class' => 'build_stagger_',
				'default' => 0.5,
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);

		$element->add_control(
			'build_speed',
			[
				'label' => esc_html__('Building Speed', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 20000,
				'step' => 100,
				'prefix_class' => 'build_speed_',
				'default' => 1000,
				'condition' => [
					'build_on_scroll' => 'build--on--scroll',
					'container_type' => 'grid'
				],
			]
		);


		$element->add_control(
			'stacked_grid',
			[
				'label' => esc_html__('Stacked Grid', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'grid--stacked',
				'prefix_class' => '',
				'default' => false,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '!==',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '!==',
							'value' => 'stack--inners',
						],
						[
							'name' => 'backward_container',
							'operator' => '!==',
							'value' => 'backward__container',
						],
						[
							'name' => 'pin_container',
							'operator' => '!==',
							'value' => 'true',
						],
						[
							'name' => 'container_type',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'build_on_scroll',
							'operator' => '!==',
							'value' => 'build--on--scroll',
						],
					],
				],
			]
		);

		$element->add_control(
			'grid_stack_animation',
			[
				'label' => esc_html__('Type', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'grid--stack--expand',
				'render_type' => 'template',
				'options' => [
					'grid--stack--expand' => esc_html__('Expand', 'pe-core'),
					'grid--stack--collapse' => esc_html__('Collapse', 'pe-core'),
				],
				'prefix_class' => '',
				'label_block' => false,
				'condition' => [
					'container_type' => 'grid',
					'stacked_grid' => 'grid--stacked'
				],
			]
		);

		$element->add_control(
			'parallax_container',
			[
				'label' => esc_html__('Parallax Container', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'parallax__container',
				'render_type' => 'template',
				'prefix_class' => '',
				'default' => false,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '!==',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '!==',
							'value' => 'stack--inners',
						],
						[
							'name' => 'backward_container',
							'operator' => '!==',
							'value' => 'backward__container',
						],
						[
							'name' => 'pin_container',
							'operator' => '!==',
							'value' => 'true',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '!==',
							'value' => 'grid--stacked',
						],
						[
							'name' => 'build_on_scroll',
							'operator' => '!==',
							'value' => 'build--on--scroll',
						],
					],
				],
			]
		);

		$element->add_control(
			'mobile_parallax',
			[
				'label' => esc_html__('Mobile Parallax', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'mobile__parallax',
				'render_type' => 'template',
				'prefix_class' => '',
				'default' => false,
				'condition' => [
					'parallax_container' => 'parallax__container',
				],
			]
		);

		$element->add_control(
			'parallax_direction',
			[
				'label' => esc_html__('Parallax Direction', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'up',
				'render_type' => 'template',
				'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
				'options' => [
					'left' => esc_html__('Left', 'pe-core'),
					'right' => esc_html__('Right', 'pe-core'),
					'up' => esc_html__('Up', 'pe-core'),
					'down' => esc_html__('Down', 'pe-core'),
				],
				'prefix_class' => 'parallax_direction_',
				'label_block' => true,
				'condition' => ['parallax_container' => 'parallax__container'],
			]
		);

		$element->add_control(
			'parallax_strength',
			[
				'label' => esc_html__('Parallax Strength', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 500,
				'step' => 1,
				'render_type' => 'template',
				'prefix_class' => 'parallax_strength_',
				'default' => 10,
				'condition' => ['parallax_container' => 'parallax__container'],
			]
		);

		$element->add_control(
			'backward_container',
			[
				'label' => esc_html__('Backward Container', 'pe-core'),
				'description' => esc_html__('The next container will come above this container at the end.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'backward__container',
				// 'render_type' => 'template',
				'prefix_class' => '',
				'default' => false,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '!==',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '!==',
							'value' => 'stack--inners',
						],
						[
							'name' => 'pin_container',
							'operator' => '!==',
							'value' => 'true',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '!==',
							'value' => 'grid--stacked',
						],
						[
							'name' => 'build_on_scroll',
							'operator' => '!==',
							'value' => 'build--on--scroll',
						],
					],
				],
			]
		);

		$element->add_control(
			'backward_fade',
			[
				'label' => esc_html__('Backward Fade', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'backward__fade',
				'prefix_class' => '',
				'default' => false,
				'condition' => ['backward_container' => 'backward__container'],
			]
		);

		$element->add_control(
			'backward_start_pos',
			[
				'label' => esc_html__('Bakcward Start', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bottom',
				'render_type' => 'template',
				'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
				'options' => [
					'top' => esc_html__('Top', 'pe-core'),
					'bottom' => esc_html__('Bottom', 'pe-core'),
				],
				'prefix_class' => 'backward-start-',
				'label_block' => false,
				'condition' => ['backward_container' => 'backward__container'],
			]
		);



		$element->add_control(
			'backward_mobile',
			[
				'label' => esc_html__('Backward at Mobile', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'yes',
				'prefix_class' => 'backward__mobile-',
				'default' => 'no',
				'condition' => ['backward_container' => 'backward__container'],
			]
		);

		$element->add_control(
			'pin_container',
			[
				'label' => esc_html__('Pin Container', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'prefix_class' => 'pinned_',
				'render_type' => 'template',
				'default' => false,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '!==',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '!==',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '!==',
							'value' => 'grid--stacked',
						],
						[
							'name' => 'backward_container',
							'operator' => '!==',
							'value' => 'backward__container',
						],
						[
							'name' => 'build_on_scroll',
							'operator' => '!==',
							'value' => 'build--on--scroll',
						],
					],
				],
			]
		);

		$element->add_control(
			'highlight_inners',
			[
				'label' => esc_html__('Highlight Inners', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'description' => esc_html__('Inner elements of this container will be highlighted on scroll.', 'pe-core'),
				'return_value' => 'highlight--inners',
				// 'render_type' => 'template',
				'prefix_class' => '',
				'default' => '',
				'condition' => ['pin_container' => 'true'],
			]
		);

		$element->add_control(
			'reveal_inners',
			[
				'label' => esc_html__('Reveal Inners', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'reveal--inners',
				// 'render_type' => 'template',
				'prefix_class' => '',
				'default' => '',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'stack_inners',
							'operator' => '!==',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '!==',
							'value' => 'grid--stacked',
						],
						[
							'name' => 'backward_container',
							'operator' => '!==',
							'value' => 'backward__container',
						],
						[
							'name' => 'build_on_scroll',
							'operator' => '!==',
							'value' => 'build--on--scroll',
						],
						[
							'name' => 'pin_container',
							'operator' => '!==',
							'value' => 'true',
						],
					],
				],
			]
		);


		$element->add_control(
			'reveal_animation',
			[
				'label' => esc_html__('Animation', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__('Fade', 'pe-core'),
					'scale' => esc_html__('Scale', 'pe-core'),
					'parallax' => esc_html__('Parallax', 'pe-core'),
					'slide' => esc_html__('Slide', 'pe-core'),
					'items' => esc_html__('Items Anim', 'pe-core'),
				],
				'prefix_class' => 'reveal--anim--',
				'label_block' => false,
				'condition' => [
					'reveal_inners' => 'reveal--inners',
				],
			]
		);

		$element->add_control(
			'reveal_items_anim_notice',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-info">	
				   <span>Please make sure to select the animation options for inner elements from the widget settings panel; otherwise, you may experience display issues.</span></div>',
				'condition' => [
					'reveal_inners' => 'reveal--inners',
					'reveal_animation' => 'items',
				],
			]
		);


		$element->add_control(
			'active_first',
			[
				'label' => esc_html__('First Item Active', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'reveal--first--active',
				'prefix_class' => '',
				'default' => '',
				'condition' => [
					'reveal_inners' => 'reveal--inners',
				],
			]
		);

		$element->add_control(
			'stagger_sub_elements',
			[
				'label' => esc_html__('Staggered Sub Elements', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'reveal--subs--staggered',
				'prefix_class' => '',
				'default' => '',
				'condition' => [
					'reveal_inners' => 'reveal--inners',
				],
			]
		);


		$element->add_control(
			'stack_inners',
			[
				'label' => esc_html__('Stack Inners', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'stack--inners',
				'prefix_class' => '',
				'default' => '',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '!==',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '!==',
							'value' => 'grid--stacked',
						],
						[
							'name' => 'backward_container',
							'operator' => '!==',
							'value' => 'backward__container',
						],
						[
							'name' => 'pin_container',
							'operator' => '!==',
							'value' => 'true',
						],
						[
							'name' => 'build_on_scroll',
							'operator' => '!==',
							'value' => 'build--on--scroll',
						],
					],
				],
			]
		);

		$element->add_control(
			'stack_type',
			[
				'label' => esc_html__('Stack Type', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'collect',
				'options' => [
					'collect' => esc_html__('Collect', 'pe-core'),
					'scatter' => esc_html__('Scatter', 'pe-core'),
				],
				'prefix_class' => 'stack--type--',
				'label_block' => false,
				// 'render_type' => 'template',
				'condition' => [
					'stack_inners' => 'stack--inners',
				],
			]
		);

		$element->add_control(
			'stack_rotate',
			[
				'label' => esc_html__('Rotate Items', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'cont--stack--rotate',
				'prefix_class' => '',
				'default' => '',
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'stack_offset',
			[
				'label' => esc_html__('Offset Between', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'cont--stack--offset',
				'prefix_class' => '',
				'default' => '',
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'reveal_speed',
			[
				'label' => esc_html__('Speed', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'step' => 100,
				'default' => 1000,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
					],
				],
			]
		);

		$element->add_control(
			'cont_anim_pin',
			[
				'label' => esc_html__('Pin', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'cont--behavior--pin',
				'prefix_class' => '',
				'default' => '',
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'cont_anim_scrub',
			[
				'label' => esc_html__('Scrub', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'cont--behavior--scrub',
				'prefix_class' => '',
				'default' => '',
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'pin_container_target',
			[
				'label' => esc_html__('Events Target', 'pe-core'),
				'placeholder' => esc_html__('Eg. #worksContainer', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Leave it empty if you want to use container as trigger.', 'pe-core'),
				'ai' => false,
				'prefix_class' => 'pin_container_',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'ct_pin_mobile',
			[
				'label' => esc_html__('Pin Mobile', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => '',
				// 'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'container_pin_header',
			[
				'label' => esc_html__('Disable Header Pinning', 'pe-core'),
				'description' => esc_html__('Normally the pin keeps header until completed if it starts on top of the page, you can disable header pin setting this option to "yes".', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => '',
				// 'render_type' => 'template',
				'prefix_class' => 'header--pin--disabled--',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);


		$element->add_control(
			'ct_element_start_references',
			[
				'label' => esc_html__('Start References', 'pe-core'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'ct_element_references_notice',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
				   This references below are adjusts the pinning start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; pinning will start when item's top edge enters the window's bottom edge.</b></div>",
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],


			]
		);

		$element->add_control(
			'ct_element_start_offset',
			[
				'label' => esc_html__('Start Offset', 'pe-core'),
				'description' => esc_html__('An offset value (px) which will be added to pinning start position. Usefull if you are using a fixed,/sticky header.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -1000,
				'max' => 1000,
				'step' => 1,
				'default' => 0,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'ct_element_item_ref_start',
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
				// 'render_type' => 'template',
				'default' => 'center',
				'toggle' => false,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'ct_element_window_ref_start',
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
				// 'render_type' => 'template',
				'default' => 'center',
				'toggle' => false,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'ct_element_end_references',
			[
				'label' => esc_html__('End References', 'pe-core'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'ct_element_item_ref_end',
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
				// 'render_type' => 'template',
				'default' => 'bottom',
				'toggle' => false,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'ct_element_window_ref_end',
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
				// 'render_type' => 'template',
				'default' => 'top',
				'toggle' => false,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);

		$element->add_control(
			'pin_container_end_trigger',
			[
				'label' => esc_html__('End Trigger', 'pe-core'),
				'placeholder' => esc_html__('Eg. #nextContainer', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => false,
				'prefix_class' => 'pin_container_end_trigger_',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pin_container',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'reveal_inners',
							'operator' => '===',
							'value' => 'reveal--inners',
						],
						[
							'name' => 'stack_inners',
							'operator' => '===',
							'value' => 'stack--inners',
						],
						[
							'name' => 'stacked_grid',
							'operator' => '===',
							'value' => 'grid--stacked',
						],
					],
				],
			]
		);


		$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

		foreach ($active_breakpoints as $breakpoint) {
			$name = $breakpoint->get_name();
			$label = $breakpoint->get_label();
			$value = $breakpoint->get_value();
			$direction = $breakpoint->get_direction();

			$element->add_control(
				'pin_disable_enable_' . $name,
				[
					'label' => sprintf(__('Disable for %s', 'pe-core'), $label),
					'description' => sprintf(__('%dpx %s-width', 'pe-core'), $value, $direction),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __('Yes', 'pe-core'),
					'label_off' => __('No', 'pe-core'),
					'return_value' => 'yes',
					// 'render_type' => 'template',
					'default' => $name === 'mobile' ? 'yes' : '',
					'prefix_class' => 'pin-disabled-' . $name . '-',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
							[
								'name' => 'pin_container',
								'operator' => '===',
								'value' => 'true',
							],
							[
								'name' => 'reveal_inners',
								'operator' => '===',
								'value' => 'reveal--inners',
							],
							[
								'name' => 'stack_inners',
								'operator' => '===',
								'value' => 'stack--inners',
							],
							[
								'name' => 'stacked_grid',
								'operator' => '===',
								'value' => 'grid--stacked',
							],
						],
					],
				]
			);

		}

		$element->end_controls_section();


	}

}
add_action('elementor/element/before_section_start', 'convert_containers', 10, 4);


function container_animations($element, $section_id, $args)
{
	if (('section' === $element->get_name() || 'container' === $element->get_name()) && 'section_layout_additional_options' === $section_id) {
		pe_general_animation_settings($element, \Elementor\Controls_Manager::TAB_LAYOUT, true);
		pe_loader_transition_animations($element);
	}

}
add_action('elementor/element/before_section_start', 'container_animations', 10, 3);

function container_notice($element, $section_id, $args)
{
	if (('container' === $element->get_name()) && 'section_layout_container' === $section_id) {

		$element->add_control(
			'converted_notice',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-danger">	
				   <span>This container has been converted. You can view converting preferences via <u>"Convert Container"</u> section below.</span></div>',
				'condition' => ['convert_container!' => ''],
			]
		);

	}

	if (('container' === $element->get_name()) && 'section_layout_additional_options' === $section_id) {

		$element->add_control(
			'container_title',
			[
				'label' => esc_html__('Container Title', 'pe-core'),
				'label_block' => true,
				'render_type' => 'template',
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter container title here.', 'pe-core'),
				'description' => esc_html__('If this container is a sub-element of a container that has been converted to an accordion or tab, a title must be entered.', 'pe-core'),
				'ai' => false

			]
		);

	}
}
add_action('elementor/element/after_section_start', 'container_notice', 10, 4);

function widget_header_visibility($element, $section_id, $args)
{
	if (('container' !== $element->get_name()) && '_section_style' === $section_id) {

		$element->add_control(
			'widget_header_visibility',
			[
				'label' => esc_html__('Widget Visibility (for Headers)', 'pe-core'),
				'description' => esc_html__('Affective only if the container used in an header template.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'label_block' => true,
				'prefix_class' => '',
				'options' => [
					'' => esc_html__('Default', 'pe-core'),
					'wd--show--sticky' => esc_html__('When heeader stkicked/fixed.', 'pe-core'),
					'wd--show--on--top' => esc_html__('When header on top.', 'pe-core'),
				],
			]
		);

		$element->add_control(
			'get_widget_state',
			[
				'label' => esc_html__('Get State on Move', 'pe-core'),
				'description' => esc_html__('Usefull if you want to adjust element position on header move.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'element--get--state',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'widget_header_visibility' => ''
				]
			]
		);

		$element->add_control(
			'disable_visibility_at_mobile',
			[
				'label' => esc_html__('Disable Visiblity at Mobile', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'vis--disabled--at--mobile',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'widget_header_visibility!' => ''
				]
			]
		);

	}

}
add_action('elementor/element/before_section_end', 'widget_header_visibility', 10, 4);


function widget_outside_curve($element, $section_id, $args)
{
	if (('container' !== $element->get_name()) && '_section_background' === $section_id) {

		$element->add_control(
			'outside_curve',
			[
				'label' => esc_html__('Outside Curve', 'pe-core'),
				'description' => esc_html__('For "classic" background type only.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'outside--curved',
				'default' => '',
				'render_type' => 'template',
				'prefix_class' => '',
				'condition' => [
					'_background_background' => ['classic']
				]

			]
		);

		$element->add_control(
			'top_curve_pos',
			[
				'label' => __('Top Curve Position', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'right',
				'prefix_class' => 'top--curve--',
				'options' => [
					'right' => __('Right-Top', 'zeyna'),
					'left' => __('Left-Top', 'zeyna'),
					'right-right' => __('Right-Right', 'zeyna'),
					'left-left' => __('Left-Left', 'zeyna'),
				],
				'condition' => [
					'outside_curve' => ['outside--curved']
				],
			]
		);

		$element->add_control(
			'bottom_curve_pos',
			[
				'label' => __('Bottom Curve Position', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'prefix_class' => 'bottom--curve--',
				'options' => [
					'right' => __('Right-Right', 'zeyna'),
					'left' => __('Left-Left', 'zeyna'),
					'right-bottom' => __('Right-Bottom', 'zeyna'),
					'left-left' => __('Left-Bottom', 'zeyna'),
				],
				'condition' => [
					'outside_curve' => ['outside--curved']
				],
			]
		);


		$element->add_responsive_control(
			'outside_curve_size',
			[
				'label' => esc_html__('Outside Curve Size', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'render_type' => 'template',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'condition' => [
					'outside_curve' => ['outside--curved']
				],
				'selectors' => [
					'{{WRAPPER}}' => '--outsideCurveWidth: {{SIZE}}{{UNIT}};',
				],
			]
		);

		//     $widget->add_control(
		//         $style_prefix . '_outside_curve_color',
		//         [
		//             'label' => esc_html__('Curve Color', 'pe-core'),
		//             'type' => \Elementor\Controls_Manager::COLOR,
		//             'render_type' => 'template',
		//             'selectors' => [
		//                 '{{WRAPPER}}' => '--outsideCurveColor: {{VALUE}}',
		//             ],
		//             'condition' => [
		// 				$style_prefix . '_outside_curve' => ['outside--curved']
		// 			],
		//         ]
		//     );




	}

}
add_action('elementor/element/before_section_end', 'widget_outside_curve', 10, 4);

function widget_animations($element, $section_id, $args)
{
	if (('container' !== $element->get_name()) && '_section_style' === $section_id) {
		$element->add_responsive_control(
			'mobile_widget_pos',
			[
				'label' => esc_html__('Position', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__('Default', 'pe-core'),
					'static' => esc_html__('Static', 'pe-core'),
					'relative' => esc_html__('Relative', 'pe-core'),
				],
				'default' => '',
				'condition' => [
					'_position' => ['absolute', 'fixed'],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'position: {{VALUE}} !important',
				],
				'label_block' => false
			]
		);
	}

	if (('container' !== $element->get_name()) && 'section_effects' === $section_id) {


		$element->add_control(
			'pe_widget_animations',
			[
				'label' => esc_html__('Zeyna Animations', 'pe-core'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$element->add_control(
			'parallax_widget',
			[
				'label' => esc_html__('Parallax Widget', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'parallax__widget',
				'render_type' => 'template',
				'prefix_class' => '',
				'default' => false,
			]
		);

		$element->add_control(
			'widget_parallax_direction',
			[
				'label' => esc_html__('Parallax Direction', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'up',
				'render_type' => 'template',
				'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
				'options' => [
					'left' => esc_html__('Left', 'pe-core'),
					'right' => esc_html__('Right', 'pe-core'),
					'up' => esc_html__('Up', 'pe-core'),
					'down' => esc_html__('Down', 'pe-core'),
				],
				'prefix_class' => 'parallax_direction_',
				'label_block' => true,
				'condition' => ['parallax_widget' => 'parallax__widget'],
			]
		);

		$element->add_control(
			'widget_parallax_strength',
			[
				'label' => esc_html__('Parallax Strength', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 1000,
				'step' => 1,
				'render_type' => 'template',
				'prefix_class' => 'parallax_strength_',
				'default' => 10,
				'condition' => ['parallax_widget' => 'parallax__widget'],
			]
		);

		pe_general_animation_settings($element, false, false, false, false, 'widget_');

		$element->add_control(
			'pe_widget_elementor_animations',
			[
				'label' => esc_html__('Elementor Animations', 'pe-core'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

	}

}
add_action('elementor/element/after_section_start', 'widget_animations', 10, 4);

function widget_blend($element, $section_id, $args)
{
	if (('container' !== $element->get_name()) && '_section_style' === $section_id) {

		$element->add_control(
			'widget_blend_mode',
			[
				'label' => esc_html__('Blend Mode', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__('Normal', 'pe-core'),
					'multiply' => esc_html__('Multiply', 'pe-core'),
					'screen' => esc_html__('Screen', 'pe-core'),
					'overlay' => esc_html__('Overlay', 'pe-core'),
					'darken' => esc_html__('Darken', 'pe-core'),
					'lighten' => esc_html__('Lighten', 'pe-core'),
					'color-dodge' => esc_html__('Color Dodge', 'pe-core'),
					'saturation' => esc_html__('Saturation', 'pe-core'),
					'color' => esc_html__('Color', 'pe-core'),
					'luminosity' => esc_html__('Luminosity', 'pe-core'),
				],
				'selectors' => [
					'{{WRAPPER}}' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

	}
}
add_action('elementor/element/before_section_end', 'widget_blend', 10, 4);



function widget_animation_attributes($element)
{
	if ($element->get_settings('widget_select_animation') && $element->get_settings('widget_select_animation') !== 'none') {

		$element->add_render_attribute(
			'_wrapper',
			pe_general_animation($element, 'widget_')
		);
	}

}
add_action('elementor/frontend/widget/before_render', 'widget_animation_attributes');

function container_additional_settings($element, $section_id, $args)
{

	if ('container' === $element->get_name() && 'section_layout_additional_options' === $section_id) {

		$element->add_control(
			'nav_visibility',
			[
				'label' => esc_html__('Show Container:', 'pe-core'),
				'description' => esc_html__('Affective only if the container used in an header template.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'prefix_class' => '',
				'options' => [
					'' => esc_html__('Always', 'pe-core'),
					'wd--show--sticky' => esc_html__('When heeader stkicked/fixed.', 'pe-core'),
					'wd--show--on--top' => esc_html__('When header on top.', 'pe-core'),
				],
			]
		);

		$element->add_control(
			'get_container_state',
			[
				'label' => esc_html__('Get State on Move', 'pe-core'),
				'description' => esc_html__('Usefull if you want to adjust element position on header move.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'element--get--state',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'nav_visibility' => ''
				]
			]
		);

		$element->add_control(
			'disable_visibility_at_mobile',
			[
				'label' => esc_html__('Disable Visiblity at Mobile', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'vis--disabled--at--mobile',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'nav_visibility!' => ''
				]
			]
		);

		$element->add_control(
			'pointer_events',
			[
				'label' => esc_html__('Pointer Events', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'prefix_class' => 'container--pointer--events--',
				'options' => [
					'' => esc_html__('Auto', 'pe-core'),
					'all' => esc_html__('All', 'pe-core'),
					'none' => esc_html__('None', 'pe-core'),
				],
			]
		);

		$element->add_control(
			'blend_container',
			[
				'label' => esc_html__('Blend Container', 'pe-core'),
				'description' => esc_html__('Mix blend mode "difference" will be applied to the container.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'container--blended',
				'default' => '',
				'prefix_class' => '',
			]
		);

		$element->add_control(
			'scrollable_container',
			[
				'label' => esc_html__('Scrollable Container', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'scrollable--container',
				'default' => '',
				'prefix_class' => '',
			]
		);

		$element->add_responsive_control(
			'scrollable_cont_height',
			[
				'label' => esc_html__('Parent Max Height', 'pe-core'),
				'description' => esc_html__('*Required: This adjusts the parent container height.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'vh'],
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
					'vh' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'max-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'scrollable_container' => 'scrollable--container'
				],
			]
		);

		$element->add_control(
			'container_hover_trigger',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'return_value' => 'pe--hover--trigger',
				'default' => 'pe--hover--trigger',
				'prefix_class' => '',
				'condition' => [
					'html_tag' => 'a'
				]
			]
		);


		$element->add_responsive_control(
			'mobile_container_pos',
			[
				'label' => esc_html__('Position', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__('Default', 'pe-core'),
					'static' => esc_html__('Static', 'pe-core'),
					'relative' => esc_html__('Relative', 'pe-core'),
				],
				'default' => '',
				'condition' => [
					'position' => ['absolute', 'fixed'],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'position: {{VALUE}}',
				],
				'label_block' => false
			]
		);



	}


}
add_action('elementor/element/after_section_start', 'container_additional_settings', 10, 3);


function container_link_opt($element, $section_id, $args)
{

	if ('container' === $element->get_name() && 'section_layout_additional_options' === $section_id) {

		$element->add_control(
			'is_dynamic_link',
			[
				'label' => esc_html__('Is Dynamic Link?', 'pe-core'),
				'description' => esc_html__('For Post navigations', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => '',
				'condition' => [
					'html_tag' => 'a'
				]
			]
		);

		$element->add_control(
			'dynamic_target',
			[
				'label' => 'Link to;',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'next_post' => 'Next Post',
					'prev_post' => 'Previous Post',
				],
				'default' => 'next_post',
				'render_type' => 'template',
				'condition' => [
					'html_tag' => 'a',
					'is_dynamic_link' => 'true'
				],
			]
		);

		$element->add_control(
			'cont_select_page',
			[
				'label' => esc_html__('Select Page / Post', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'groups' => get_grouped_page_options(),
				'condition' => [
					'html_tag' => 'a',
					'is_dynamic_link!' => 'true'

				],
			]
		);


	}

}
add_action('elementor/element/before_section_end', 'container_link_opt', 10, 3);

function container_background_settings($element, $section_id, $args)
{

	if ('container' === $element->get_name() && 'section_background' === $section_id) {

		$element->add_control(
			'pe_background_sec',
			[
				'label' => esc_html__('Theme Backgrounds', 'pe-core'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$element->add_control(
			'background_type',
			[
				'label' => 'Background Type',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => 'None',
					'color' => 'Color',
					'gradient' => 'Gradient',
					'video' => 'Video',
					'image' => 'Image',
					'customcss' => 'Custom CSS',
				],
				'default' => '',
				'render_type' => 'template',
			]
		);

		$element->add_control(
			'bg-color-prefix',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'bg--color',
				'prefix_class' => '',
				'condition' => [
					'background_type' => ['color']
				]
			]
		);

		$element->add_control(
			'custom_bg_code',
			[
				'label' => esc_html__('CSS', 'pe-core'),
				'description' => __('No selectors required. You can create your own custom backgrounds on <a href="https://csshero.org/mesher/" target="_blank">this</a> generator.', 'pe-core'),
				'default' => "background-color:hsla(59,0%,0%,1);
				background-image:
				radial-gradient(at 0% 0%, hsla(6,66%,26%,1) 0px, transparent 50%),
				radial-gradient(at 100% 0%, hsla(6,66%,26%,1) 0px, transparent 50%);",
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 4,
				'condition' => [
					'background_type' => ['customcss']
				]
			]
		);


		$element->add_control(
			'video_provider',
			[
				'label' => 'Video Provider',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'self' => 'Self',
					'vimeo' => 'Vimeo',
					'youtube' => 'Youtube',
					'stream' => 'Stream'
				],
				'default' => 'self',
				'prefix_class' => '',
				'condition' => [
					'background_type' => ['video']
				]
			]
		);

		$element->add_control(
			'stream_url',
			[
				'label' => esc_html__('Stream URL', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => false,
				'placeholder' => esc_html__('Enter video URL here.', 'pe-core'),
				'condition' => [
					'video_provider' => ['stream'],
					'background_type' => ['video']
				]
			]
		);

		$element->add_control(
			'sec_video',
			[
				'label' => esc_html__('Choose Video', 'pe-core'),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'media_types' => ['mp4'],

				'condition' => [
					'video_provider' => ['self'],
					'background_type' => ['video']
				]
			]
		);

		$element->add_control(
			'youtube_id',
			[
				'label' => esc_html__('Video ID', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Youtube video ID', 'pe-core'),
				'condition' => [
					'video_provider' => ['youtube'],
					'background_type' => ['video']

				]

			]
		);

		$element->add_control(
			'vimeo_id',
			[
				'label' => esc_html__('Video ID', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Vimeo video ID', 'pe-core'),
				'condition' => [
					'video_provider' => ['vimeo'],
					'background_type' => ['video']
				]

			]
		);

		$element->add_control(
			'video_poster',
			[
				'label' => esc_html__('Video Poster', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => 'false',
				'condition' => [

					'background_type' => ['video']
				]
			]
		);

		$element->add_control(
			'poster_image',
			[
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'video_poster' => ['true'],
				],
			]
		);



		$element->add_control(
			'cont_background_image',
			[
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'background_type' => 'image',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'cont_background_image_size', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => ['custom'],
				'include' => [],
				'default' => 'large',
				'condition' => [
					'background_type' => 'image',
				],
			]
		);

		$element->add_control(
			'bg_behavior',
			[
				'label' => 'Background Behavior',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'static' => 'Static',
					'parallax' => 'Parallax',
					'zoom-in' => 'Zoom In',
					'zoom-out' => 'Zoom Out',
				],
				'default' => 'static',
				'render_type' => 'template',
				'prefix_class' => 'bg--behavior--',
				'condition' => [
					'background_type' => ['image']
				]
			]
		);

		$element->add_control(
			'transparent_bg',
			[
				'label' => esc_html__('Transparent Image', 'pe-core'),
				'description' => esc_html__('Switch this "On" if you uploaded an image with alpha channel (such as PNG, WEBP..)', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => 'false',
				'prefix_class' => 'bg_transparent_',
				'condition' => [
					'background_type' => ['image']
				]

			]
		);

		$element->add_control(
			'pin_bg',
			[
				'label' => esc_html__('Pin background', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => 'false',
				'render_type' => 'template',
				'prefix_class' => 'bg_pinned_',
				'condition' => [
					'background_type' => ['image', 'video'],
					'bg_behavior' => 'static',
				]

			]
		);

		$element->add_control(
			'fixed_bg',
			[
				'label' => esc_html__('Fixed Background', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => 'false',
				'render_type' => 'template',
				'prefix_class' => 'bg_fixed_',
				'condition' => [
					'background_type' => ['image', 'video'],
					'bg_behavior' => 'static',
				]

			]
		);


		$element->add_control(
			'fixed_bg_strength',
			[
				'label' => esc_html__('Fixed Strength', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [''],
				'range' => [
					'' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'fixed_bg' => 'true',
				]
			]
		);

		$element->add_control(
			'cont_background_overlay',
			[
				'label' => esc_html__('Background Overlay', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => 'false',
				'render_type' => 'template',
				'prefix_class' => 'bg__overlay__',
				'condition' => [
					'background_type' => ['image', 'video'],
				]

			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'bg_overlay_background',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .cont--bg--overlay',
				'condition' => [
					'background_type' => ['image', 'video'],
					'cont_background_overlay' => 'true',
				]
			]
		);

		// $element->add_control(
		// 	'bg_overlay_color',
		// 	[
		// 		'label' => esc_html__('Overlay Color', 'pe-core'),
		// 		'type' => \Elementor\Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .cont--bg--overlay' => 'background-color: {{VALUE}}',
		// 		],
		// 		'condition' => [
		// 			'background_type' => ['image', 'video'],
		// 			'cont_background_overlay' => 'true',
		// 		]
		// 	]
		// );

		$element->add_control(
			'bg_overlay_blend_mode',
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
				'default' => 'normal',
				'condition' => [
					'background_type' => ['image', 'video'],
					'cont_background_overlay' => 'true',
				],
				'selectors' => [
					'{{WRAPPER}} .cont--bg--overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'label_block' => false
			]
		);


		$element->add_responsive_control(
			'bg_position',
			[
				'label' => esc_html__('Background Position', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__('Top', 'pe-core'),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__('Middle', 'pe-core'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__('Bottom', 'pe-core'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'toggle' => true,
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .container--bg .cont--bg--wrap img' => 'object-position: {{VALUE}};',
				],
				'condition' => [
					'background_type' => ['image']
				]
			]
		);



		$element->start_controls_tabs(
			'bg_color_Tabs'
		);

		$element->start_controls_tab(
			'bg_default',
			[
				'label' => esc_html__('Default', 'pe-core'),
				'condition' => [
					'background_type' => ['color']
				]
			]
		);

		$element->add_control(
			'main_background',
			[
				'label' => esc_html__('Main Background', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--mainBackground: {{VALUE}} !important',
					'.fade--overlay--for--{{ID}}' => '--mainBackground: {{VALUE}} !important',
				],
				'condition' => [
					'background_type' => ['color']
				]

			]
		);

		$element->add_control(
			'secondary_background',
			[
				'label' => esc_html__('Secondary Background', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--secondaryBackground: {{VALUE}} !important',
				],
				'condition' => [
					'background_type' => ['color']
				]

			]
		);


		$element->end_controls_tab();

		$element->start_controls_tab(
			'bg_switched',
			[
				'label' => esc_html__('Switched', 'pe-core'),
				'condition' => [
					'background_type' => ['color']
				]
			]
		);

		$element->add_control(
			'switched_main_background',
			[
				'label' => esc_html__('Main Background', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.header--switched {{WRAPPER}} , .header--switched {{WRAPPER}} .e-con' => '--mainBackground: {{VALUE}} !important',
					'body.layout--switched {{WRAPPER}}' => '--mainBackground: {{VALUE}} !important',
					'body.layout--switched .reverse__' . $element->get_id() => '--mainBackground: {{VALUE}} !important',
				],
				'condition' => [
					'background_type' => ['color']
				]

			]
		);

		$element->add_control(
			'switched_secondary_background',
			[
				'label' => esc_html__('Secondary Background', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.header--switched {{WRAPPER}} , .header--switched {{WRAPPER}} .e-con' => '--secondaryBackground: {{VALUE}} !important',
					'body.layout--switched {{WRAPPER}}' => '--secondaryBackground: {{VALUE}} !important',
				],
				'condition' => [
					'background_type' => ['color']
				]

			]
		);


		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->add_control(
			'bg_backdrop',
			[
				'label' => esc_html__('Backdrop Filter', 'pe-core'),
				'description' => esc_html__('For "classic" background type only.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => 'false',
				'render_type' => 'template',
				'prefix_class' => 'bg_backdrop_',
				'condition' => [
					'background_type' => ['color']
				]
			]
		);

		$element->add_responsive_control(
			'bg_backdrop_blur',
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
					'background_type' => 'color',
					'bg_backdrop' => 'true',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--backdropBlur: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'curved_bg',
			[
				'label' => esc_html__('Curved Background', 'pe-core'),
				'description' => esc_html__('For "classic" background type only.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => 'false',
				'render_type' => 'template',
				'prefix_class' => 'curved_',
				'condition' => [
					'background_type' => ['color']
				]

			]
		);

		$element->add_responsive_control(
			'curves',
			[
				'label' => esc_html__('Curves', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__('Top', 'pe-core'),
						'icon' => 'eicon-v-align-top',
					],
					'both' => [
						'title' => esc_html__('Both', 'pe-core'),
						'icon' => 'eicon-justify-space-between-v'
					],
					'bottom' => [
						'title' => esc_html__('Bottom', 'pe-core'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'both',
				'condition' => [
					'curved_bg' => ['true']
				]
			]
		);

		$element->add_responsive_control(
			'curve',
			[
				'label' => esc_html__('Curve Size', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 75,
				],
				'condition' => [
					'curved_bg' => ['true']
				],
				'selectors' => [
					'{{WRAPPER}} .bg--reverse-layer' => '--curveWidth: {{SIZE}}{{UNIT}};--curveHeight: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'outside_curve',
			[
				'label' => esc_html__('Outside Curve', 'pe-core'),
				'description' => esc_html__('For "classic" background type only.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'outside--curved',
				'default' => '',
				'render_type' => 'template',
				'prefix_class' => '',
				'condition' => [
					'background_type' => ['color']
				]

			]
		);

		$element->add_control(
			'top_curve_pos',
			[
				'label' => __('Top Curve Position', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'right',
				'prefix_class' => 'top--curve--',
				'options' => [
					'right' => __('Right-Top', 'zeyna'),
					'left' => __('Left-Top', 'zeyna'),
					'right-right' => __('Right-Right', 'zeyna'),
					'left-left' => __('Left-Left', 'zeyna'),
				],
				'condition' => [
					'outside_curve' => ['outside--curved']
				],
			]
		);

		$element->add_control(
			'bottom_curve_pos',
			[
				'label' => __('Bottom Curve Position', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'prefix_class' => 'bottom--curve--',
				'options' => [
					'right' => __('Right-Right', 'zeyna'),
					'left' => __('Left-Left', 'zeyna'),
					'right-bottom' => __('Right-Bottom', 'zeyna'),
					'left-left' => __('Left-Bottom', 'zeyna'),
				],
				'condition' => [
					'outside_curve' => ['outside--curved']
				],
			]
		);

		$element->add_responsive_control(
			'outside_curve_size',
			[
				'label' => esc_html__('Outside Curve Size', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'render_type' => 'template',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'condition' => [
					'outside_curve' => ['outside--curved']
				],
				'selectors' => [
					'{{WRAPPER}}' => '--outsideCurveWidth: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'bg_fade_edges',
			[
				'label' => esc_html__('Fade Edges', 'pe-core'),
				'description' => esc_html__('For "classic" background type only.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'bg--fade--edges',
				'default' => '',
				'render_type' => 'template',
				'prefix_class' => '',
				'condition' => [
					'background_type' => ['color']
				]

			]
		);

		$element->add_responsive_control(
			'bg_fade_pos',
			[
				'label' => esc_html__('Edges', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'fade--edges--top' => [
						'title' => esc_html__('Top', 'pe-core'),
						'icon' => 'eicon-v-align-top',
					],
					'fade--edges--both' => [
						'title' => esc_html__('Both', 'pe-core'),
						'icon' => 'eicon-justify-space-between-v'
					],
					'fade--edges--bottom' => [
						'title' => esc_html__('Bottom', 'pe-core'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'fade--edges--both',
				'prefix_class' => '',
				'condition' => [
					'bg_fade_edges' => ['bg--fade--edges']
				]
			]
		);
		$element->add_responsive_control(
			'bg_fade_height',
			[
				'label' => esc_html__('Fade Height', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh', 'rem'],
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
					'rem' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'bg_fade_edges' => ['bg--fade--edges']
				],
				'selectors' => [
					'{{WRAPPER}}' => '--fadeEdgeHeight: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'animate_fade_bg',
			[
				'label' => esc_html__('Animate Fade', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'bg--fade--animate',
				'default' => '',
				'render_type' => 'template',
				'prefix_class' => '',
				'condition' => [
					'background_type' => ['color'],
					'bg_fade_edges' => ['bg--fade--edges'],
				]

			]
		);



		$element->add_control(
			'gradient_type',
			[
				'label' => __('Gradient Type', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'linear',
				'options' => [
					'linear' => __('Linear', 'zeyna'),
					'radial' => __('Radial', 'zeyna'),
				],
				'condition' => [
					'background_type' => ['gradient']
				]
			]
		);

		$element->add_control(
			'color_1',
			[
				'label' => __('Color 1', 'zeyna'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ff0000',
				'condition' => [
					'background_type' => ['gradient']
				]
			]
		);

		$element->add_control(
			'color_1_location',
			[
				'label' => __('Color 1 Location', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'condition' => [
					'background_type' => ['gradient']
				]
			]
		);

		$element->add_control(
			'color_2',
			[
				'label' => __('Color 2', 'zeyna'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#0000ff',
				'condition' => [
					'background_type' => ['gradient']
				]
			]
		);

		$element->add_control(
			'color_2_location',
			[
				'label' => __('Color 2 Location', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'condition' => [
					'background_type' => ['gradient']
				]
			]
		);

		$element->add_control(
			'gradient_angle',
			[
				'label' => __('Gradient Angle', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 45,
				],
				'condition' => [
					'background_type' => ['gradient'],
					'gradient_type' => 'linear',
				]
			]
		);

		$element->add_control(
			'gradient_position',
			[
				'label' => __('Radial Gradient Position', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'top' => __('Top', 'zeyna'),
					'bottom' => __('Bottom', 'zeyna'),
					'center' => __('Center', 'zeyna'),
					'left' => __('Left', 'zeyna'),
					'right' => __('Right', 'zeyna'),
					'top left' => __('Top Left', 'zeyna'),
					'top right' => __('Top Right', 'zeyna'),
					'bottom left' => __('Bottom Left', 'zeyna'),
					'bottom right' => __('Bottom Right', 'zeyna'),
				],
				'default' => 'center',
				'condition' => [
					'background_type' => ['gradient'],
					'gradient_type' => 'radial',
				]
			]
		);

		$element->add_control(
			'animated_gradient',
			[
				'label' => __('Animated Gradient?', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'zeyna'),
				'label_off' => __('No', 'zeyna'),
				'default' => 'no',
				'render_type' => 'template',
				'return_value' => 'animated--gradient',
				'prefix_class' => '',
				'condition' => [
					'background_type' => ['gradient']
				]
			]
		);

		$element->add_control(
			'gradient_animation_duration',
			[
				'label' => esc_html__('Animation Speed', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 5,
				'render_type' => 'template',
				'prefix_class' => 'gradient_animation_duration_',
				'condition' => [
					'background_type' => ['gradient'],
					'animated_gradient' => 'animated--gradient',
				]
			]
		);


		$element->add_control(
			'color_3',
			[
				'label' => __('Color 3 (Animated Gradient)', 'zeyna'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#00ff00',
				'condition' => [
					'background_type' => ['gradient'],
					'animated_gradient' => 'animated--gradient',
				]
			]
		);

		$element->add_control(
			'color_3_location',
			[
				'label' => __('Color 3 Location (Animated Gradient)', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'condition' => [
					'background_type' => ['gradient'],
					'animated_gradient' => 'animated--gradient', // Sadece animasyonlu gradient için
				]
			]
		);

		$element->add_control(
			'color_4',
			[
				'label' => __('Color 4 (Animated Gradient)', 'zeyna'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ff00ff',
				'condition' => [
					'background_type' => ['gradient'],
					'animated_gradient' => 'animated--gradient', // Sadece animasyonlu gradient için
				]
			]
		);

		$element->add_control(
			'color_4_location',
			[
				'label' => __('Color 4 Location (Animated Gradient)', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'condition' => [
					'background_type' => ['gradient'],
					'animated_gradient' => 'animated--gradient', // Sadece animasyonlu gradient için
				]
			]
		);

		$element->add_control(
			'gradient_angle_animated',
			[
				'label' => __('Gradient Angle (Animated Gradient)', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 45,
				],
				'condition' => [
					'background_type' => ['gradient'],
					'animated_gradient' => 'animated--gradient',
					'gradient_type' => 'linear', // Yalnızca linear için göster
				]
			]
		);

		$element->add_control(
			'gradient_position_animated',
			[
				'label' => __('Radial Gradient Position (Animated Gradient)', 'zeyna'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'top' => __('Top', 'zeyna'),
					'bottom' => __('Bottom', 'zeyna'),
					'center' => __('Center', 'zeyna'),
					'left' => __('Left', 'zeyna'),
					'right' => __('Right', 'zeyna'),
					'top left' => __('Top Left', 'zeyna'),
					'top right' => __('Top Right', 'zeyna'),
					'bottom left' => __('Bottom Left', 'zeyna'),
					'bottom right' => __('Bottom Right', 'zeyna'),
				],
				'default' => 'center',
				'condition' => [
					'background_type' => ['gradient'],
					'animated_gradient' => 'animated--gradient',
					'gradient_type' => 'radial', // Yalnızca radial için göster
				]
			]
		);

		$element->add_control(
			'adjust_margins',
			[
				'label' => esc_html__('Adjust Margins', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'adjust--margins',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'curved_bg' => ['true']
				],
			]
		);

		$element->add_control(
			'background_notice',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           <span>If you use Elementor's default background settings for a background adjustment, you won't be able to use some theme features for this container. For example, curved backgrounds, color changes in layout switch, etc</span></div>",
				'condition' => [
					'background_background' => ['classic', 'gradient', 'video', 'slideshow'],
				],

			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'cont_bg_filters',
				'selector' => '{{WRAPPER}} .container--bg',
				'condition' => [
					'background_type!' => ['none']
				]
			]
		);

		$element->add_control(
			'elementor_bg_notice',
			[
				'label' => esc_html__('Elementor Backgrounds', 'pe-core'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

	}

	if ('section_border' === $section_id) {

		$element->add_control(
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

		$element->add_responsive_control(
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

		$element->add_responsive_control(
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


	}

	if ('container' === $element->get_name() && 'section_border' === $section_id) {

		$element->add_control(
			'animate_radius',
			[
				'label' => esc_html__('Animate Radius', 'pe-core'),
				'description' => esc_html__('For "classic" background type only.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'animate--radius',
				'default' => 'no',
				'prefix_class' => '',
				'render_type' => 'template',

			]
		);


	}

	if ('container' === $element->get_name() && 'section_border' === $section_id) {

		$element->add_control(
			'integared_width',
			[
				'label' => esc_html__('Intergrate Width', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'yes',
				'default' => 'no',
				'render_type' => 'template',

			]
		);


	}




}
add_action('elementor/element/after_section_start', 'container_background_settings', 10, 3);

function container_mask_settings($element, $section_id, $args)
{

	if ('container' === $element->get_name() && 'section_border' === $section_id) {

		pe_svg_mask_settings($element, false, 'container_', '');

		$element->add_control(
			'cont_fade_edges',
			[
				'label' => esc_html__('Fade Edges', 'pe-core'),
				'description' => esc_html__('For "classic" background type only.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'cont--fade--edges',
				'default' => '',
				'render_type' => 'template',
				'prefix_class' => '',
			]
		);

		$element->add_responsive_control(
			'cont_fade_pos',
			[
				'label' => esc_html__('Edges', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'cont--fade--edges--top' => [
						'title' => esc_html__('Top', 'pe-core'),
						'icon' => 'eicon-v-align-top',
					],
					'cont--fade--edges--both' => [
						'title' => esc_html__('Both', 'pe-core'),
						'icon' => 'eicon-justify-space-between-v'
					],
					'cont--fade--edges--bottom' => [
						'title' => esc_html__('Bottom', 'pe-core'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'fade--edges--both',
				'prefix_class' => '',
				'condition' => [
					'cont_fade_edges' => ['cont--fade--edges']
				]
			]
		);
		$element->add_responsive_control(
			'cont_fade_height',
			[
				'label' => esc_html__('Fade Height', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh', 'rem'],
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
					'rem' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'cont_fade_edges' => ['cont--fade--edges']
				],
				'selectors' => [
					'{{WRAPPER}}' => '--fadeEdgeHeight: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'cont_fade_color',
			[
				'label' => esc_html__('Fade Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'cont_fade_edges' => ['cont--fade--edges']
				],
				'selectors' => [
					'{{WRAPPER}}' => '--fadeColor: {{VALUE}};',
				],
			]
		);

	}

}

add_action('elementor/element/before_section_end', 'container_mask_settings', 10, 3);

function container_attributes($element)
{

	if ($element->get_settings('integared_width') === 'yes') {

		$element->add_render_attribute(
			'_wrapper',
			[
				'class' => 'integared--width',

			]
		);

	}

	if ($element->get_settings('scrollable_container') === 'scrollable--container') {

		$element->add_render_attribute(
			'_wrapper',
			[
				'data-lenis-prevent' => true

			]
		);

	}

	if ($element->get_settings('convert_container') === 'convert--carousel') {

		$id = !empty($element->get_settings('container_carousel_id')) ? ' carousel_id_' . $element->get_settings('container_carousel_id') : '';

		$element->add_render_attribute(
			'_wrapper',
			[
				'class' => $element->get_settings('container_carousel_behavior') . $id,
				'data-carousel-id' => $element->get_settings('container_carousel_id') ? $element->get_settings('container_carousel_id') : 'cr--' . $element->get_id(),
				'data-trigger' => $element->get_settings('container_carousel_trigger')

			]
		);

	}

	if ($element->get_settings('cursor_type') !== 'none') {

		ob_start();

		\Elementor\Icons_Manager::render_icon($element->get_settings('cursor_icon'), ['aria-hidden' => 'true']);

		$cursorIcon = ob_get_clean();

		$element->add_render_attribute(
			'_wrapper',
			[
				'data-cursor' => "true",
				'data-cursor-type' => $element->get_settings('cursor_type'),
				'data-cursor-text' => $element->get_settings('cursor_text'),
				'data-cursor-icon' => $cursorIcon,

			]
		);


	}

	if ($element->get_settings('select_animation') !== 'none') {

		// Animations 
		$out = $element->get_settings('animate_out') ? $element->get_settings('animate_out') : 'false';

		$settings = $element->get_settings_for_display();

		if ($settings['mask_type'] === 'square' && $settings['select_animation'] === 'customMask') {
			$squareStart = ';square_start=inset(' . $settings['square_mask_start']['top'] . '% ' . $settings['square_mask_start']['right'] . '% ' . $settings['square_mask_start']['bottom'] . '% ' . $settings['square_mask_start']['left'] . '% ' . 'round ' . $settings['square_mask_radius'] . 'px)';
			$squareEnd = ';square_end=inset(' . $settings['square_mask_end']['top'] . '% ' . $settings['square_mask_end']['right'] . '% ' . $settings['square_mask_end']['bottom'] . '% ' . $settings['square_mask_end']['left'] . '% ' . 'round ' . $settings['square_mask_radius_end'] . 'px)';
		} else {
			$squareStart = '';
			$squareEnd = '';
		}

		if ($settings['mask_type'] === 'circle' && $settings['select_animation'] === 'customMask') {
			$circleStart = ';circle_start=circle(' . $settings['circle_size_start']['size'] . '% at ' . $settings['circle_left_pos_start']['size'] . '% ' . $settings['circle_top_pos_start']['size'] . '%)';
			$circleEnd = ';circle_end=circle(' . $settings['circle_size_end']['size'] . '% at ' . $settings['circle_left_pos_end']['size'] . '% ' . $settings['circle_top_pos_end']['size'] . '%)';
		} else {
			$circleStart = '';
			$circleEnd = '';
		}

		if ($settings['select_animation'] === 'animateWidth') {
			$animateWidth = ';animate_width=' . $settings['animate_width']['size'] . $settings['animate_width']['unit'];

			$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

			foreach ($active_breakpoints as $breakpoint) {
				$name = $breakpoint->get_name();
				if (
					isset($settings['animate_width' . $name])
				) {
					$animateWidth .= ';animate_width_' . $name . '=' . $settings['animate_width' . $name]['size'];
				}
			}

		} else {
			$animateWidth = '';
		}

		$easing = 'default';
		if ($settings['animation_easing'] !== 'default' && $settings['animation_easing'] !== 'none') {
			$easing = $settings['animation_easing'] . '.' . $settings['easing_behavior'];
		} else if ($settings['animation_easing'] === 'none') {
			$easing = 'none';
		}

		$dataset = '{' .
			'duration=' . $element->get_settings('duration') . '' .
			';delay=' . $element->get_settings('delay') . '' .
			';stagger=' . $element->get_settings('stagger') . '' .
			';pin=' . $element->get_settings('pin') . '' .
			';mobilePin=' . $element->get_settings('mobile_pin') . '' .
			';pinTarget=' . $element->get_settings('pinned_target') . '' .
			';scrub=' . $element->get_settings('scrub') . '' .
			';repeat=' . $element->get_settings('repeat') . '' .
			';item_ref_start=' . $element->get_settings('item_ref_start') . '' .
			';window_ref_start=' . $element->get_settings('window_ref_start') . '' .
			';item_ref_end=' . $element->get_settings('item_ref_end') . '' .
			';window_ref_end=' . $element->get_settings('window_ref_end') . '' .
			';start_scale=' . $element->get_settings('gen_start_scale') . '' .
			';end_scale=' . $element->get_settings('gen_end_scale') . '' .
			';out=' . $out . '' .
			';easing=' . $easing . '' .
			';fade_blur=' . $element->get_settings('fade_blur') . '' .
			';mask_start=' . $settings['mask_type'] . '' . $squareStart . $squareEnd . $circleStart . $circleEnd . $animateWidth .
			'}';

		$checkMarkers = '';

		if (\Elementor\Plugin::$instance->editor->is_edit_mode() && $element->get_settings('show_markers')) {
			$checkMarkers = ' markers-on';
		}

		$animation = $element->get_settings('select_animation') !== 'none' ? $element->get_settings('select_animation') : '';

		//Scroll Button Attributes
		$element->add_render_attribute(
			'_wrapper',
			[
				'data-anim-general' => 'true',
				'data-animation' => $animation,
				'data-anim-settings' => $dataset,
			]
		);

	}

	if ($element->get_settings('container_title')) {

		//Scroll Button Attributes
		$element->add_render_attribute(
			'_wrapper',
			[
				'data-title' => $element->get_settings('container_title'),
			]
		);

	}

	if ($element->get_settings('pin_container') === 'true' || $element->get_settings('reveal_inners') === 'reveal--inners' || $element->get_settings('stack_inners') === 'stack--inners' || $element->get_settings('stacked_grid') === 'grid--stacked') {

		$speed = $element->get_settings('reveal_inners') === 'reveal--inners' ? $element->get_settings('reveal_speed') : 0;
		$start = $element->get_settings('ct_element_item_ref_start') . ' ' . $element->get_settings('ct_element_window_ref_start') . '+=' . $element->get_settings('ct_element_start_offset');
		$end = $element->get_settings('ct_element_item_ref_end') . '+=' . $speed . ' ' . $element->get_settings('ct_element_window_ref_end');


		$element->add_render_attribute(
			'_wrapper',
			[
				'data-pin-start' => $start,
				'data-pin-end' => $end,
				'data-pin-mobile' => $element->get_settings('ct_pin_mobile'),
			]
		);

	}

	if ($element->get_settings('html_tag') === 'a' && $element->get_settings('cont_select_page') && $element->get_settings('is_dynamic_link') !== 'true') {

		$element->add_render_attribute(
			'_wrapper',
			[
				'href' => get_the_permalink($element->get_settings('cont_select_page')),
			]
		);

	}

	if ($element->get_settings('html_tag') === 'a' && filter_var($element->get_settings('is_dynamic_link'), FILTER_VALIDATE_BOOLEAN)) {

		$loop = new \WP_Query([
			'post_type' => 'portfolio',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'order' => 'ASC',
		]);
		wp_reset_postdata();


		global $wp_query;

		$id = $wp_query->post->ID;
		$previous_post = get_previous_post();
		$next_post = get_next_post();


		if ($element->get_settings('dynamic_target') === 'next_post' && $next_post) {
			if (!$next_post) {
				while ($loop->have_posts()):
					$loop->the_post();
					$id = get_the_ID();
				endwhile;
			} else {
				$id = $next_post->ID;
			}
		} elseif ($element->get_settings('dynamic_target') === 'prev_post' && $previous_post) {
			$id = $previous_post->ID;
		}

		$element->add_render_attribute(
			'_wrapper',
			[
				'href' => get_the_permalink($id),
			]
		);

	}



}
add_action('elementor/frontend/container/before_render', 'container_attributes');


function container_backgrounds($element)
{
	$id = $element->get_id();
	$settings = $element->get_settings_for_display();

	$overlay = '';

	if ($settings['cont_background_overlay'] === 'true') {
		$overlay = '<span class="cont--bg--overlay"></span>';
	}

	if ($element->get_settings('bg_fade_edges') === 'bg--fade--edges' && $element->get_settings('animate_fade_bg') === 'bg--fade--animate') {
		echo '<div class="bg--fade--overlay fade--overlay--for--' . $id . '"></div>';
	}

	$dataFixed = '';

	if ($settings['fixed_bg'] === 'true' && $settings['fixed_bg_strength']['size']) {
		$dataFixed = 'data-fixed-strength="' . $settings['fixed_bg_strength']['size'] . '"';
	}

	if ($element->get_settings('background_type') === 'customcss') { ?>

		<div class="container--bg bg--<?php echo $element->get_settings('background_type') ?> bg--for--<?php echo $id ?>"></div>
		<style>
			.bg--for--<?php echo $id ?> {
				<?php echo $element->get_settings('custom_bg_code') ?>
			}
		</style>

	<?php }

	if ($element->get_settings('background_type') === 'video') {
		$provider = $element->get_settings('video_provider');

		if ($provider === 'vimeo') {

			$video_id = $element->get_settings('vimeo_id');

		} else if ($provider === 'youtube') {

			$video_id = $element->get_settings('youtube_id');
		} else {
			$video_id = false;
		}


		?>
		<div class="container--bg bg--<?php echo $element->get_settings('background_type') ?> bg--for--<?php echo $id ?>" <?php echo $dataFixed ?>>

			<?php echo $overlay ?>

			<div class="pe-video n-<?php echo $provider; ?> no-interactions" data-controls="false" data-autoplay=true
				data-muted=true data-loop=true>

				<?php if ($settings['video_poster'] === 'true') { ?>

					<div class="pe--video--poster">

						<?php
						echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'full', 'poster_image');
						?>

					</div>

				<?php } ?>


				<?php if ($provider !== 'self' && $provider !== 'stream') { ?>

					<div class="p-video" data-plyr-provider="<?php echo $provider; ?>" data-plyr-embed-id="<?php echo $video_id ?>">
					</div>

				<?php } else if ($provider === 'self' || $provider === 'stream') {
					$url = '';
					if ($provider === 'self') {
						$url = $element->get_settings('sec_video')['url'];
					} else if ($provider === 'stream') {
						$url = $element->get_settings('stream_url');
					}
					?>

						<video autoplay muted playsinline loop class="p-video">
							<source src="<?php echo esc_url($url) ?>">
						</video>

				<?php } ?>
			</div>

		</div>

		<?php

	} else if ($element->get_settings('background_type') === 'image') { ?>
			<div class="container--bg bg--<?php echo $element->get_settings('background_type') ?> bg--for--<?php echo $id ?>" <?php echo $dataFixed ?>>
			<?php echo $overlay ?>
				<div class="cont--bg--wrap">
				<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($element->get_settings_for_display(), 'cont_background_image_size', 'cont_background_image'); ?>
				</div>
			</div>

	<?php } else if ($element->get_settings('background_type') === 'customcs') { ?>
				<div class="container--bg bg--<?php echo $element->get_settings('background_type') ?> bg--for--<?php echo $id ?>"></div>

	<?php } else if ($element->get_settings('background_type') === 'gradient') {

		$gradient_type = $settings['gradient_type'];
		$color_1 = $settings['color_1'];
		$color_1_location = $settings['color_1_location']['size'];
		$color_2 = $settings['color_2'];
		$color_2_location = $settings['color_2_location']['size'];

		if ($settings['gradient_type'] === 'linear') {

			$gradientBeh = $settings['gradient_angle']['size'] . 'deg';
		} else {
			$gradientBeh = 'at ' . $settings['gradient_position'];
		}
		$gradient_css_1 = "{$gradient_type}-gradient({$gradientBeh}, {$color_1} {$color_1_location}%, {$color_2} {$color_2_location}%)";

		if (isset($settings['animated_gradient']) && $settings['animated_gradient'] === 'animated--gradient') {
			$color_3 = $settings['color_3'];
			$color_3_location = $settings['color_3_location']['size'];
			$color_4 = $settings['color_4'];
			$color_4_location = $settings['color_4_location']['size'];
			// $gradient_angle_animated = $settings['gradient_angle_animated']['size'];

			if ($settings['gradient_type'] === 'linear') {
				$gradientBeh2 = $settings['gradient_angle_animated']['size'] . 'deg';
			} else {
				$gradientBeh2 = 'at ' . $settings['gradient_position_animated'];
			}

			$gradient_css_2 = "{$gradient_type}-gradient({$gradientBeh2}, {$color_3} {$color_3_location}%, {$color_4} {$color_4_location}%)";

			$b2_gradient = "--b2: {$gradient_css_2};";
		} else {
			$b2_gradient = '';
		}

		?>

					<div style="--b1: <?php echo $gradient_css_1 ?>; <?php echo $b2_gradient ?>"
						class=" bg--<?php echo $element->get_settings('background_type') ?> container--bg cont--bg--gradient <?php echo isset($settings['animated_gradient']) && $settings['animated_gradient'] === 'yes' ? 'animated' : ''; ?> bg--for--<?php echo $id ?>">
					</div>

	<?php }

	if ($element->get_settings('curved_bg') === 'true' && $element->get_settings('background_type') === 'color' && $element->get_settings('curves') !== 'bottom') {

		$size = $element->get_settings('curve')['size'] . $element->get_settings('curve')['unit'];

		echo '<div style="--curveMargin:' . $size . '" class="reverse--hold rh--top ' . $element->get_settings('adjust_margins') . ' reverse__' . $element->get_id() . '"><span style="--curveWidth:' . $size . ';--curveHeight:' . $size . '" class="bg--reverse-layer rl-top rl-left"></span>';
		echo '<span  style="--curveWidth:' . $size . ';--curveHeight:' . $size . '"class="bg--reverse-layer rl-top rl-right"></span></div>';

	}


	if ($element->get_settings('container_title')) { ?>

		<div class="container--accordion--title" data-id="<?php echo $element->get_id(); ?>">

			<span class="ac-order">1</span>

			<?php echo $element->get_settings('container_title') ?>

			<!-- <span class="accordion-toggle toggle--custom"> -->

			<!-- toggle custom  -->
			<!-- <span class="ac--togle ac-toggle-open"></span> -->

			<!-- <span class="ac--togle ac-toggle-close"></span> -->
			<!-- toggle custom  -->
			<!-- </span> -->

			<span class="accordion-toggle toggle--plus">

				<!-- toggle plus  -->
				<span></span>
				<span></span>
				<!-- toggle plus  -->
			</span>

			<span class="accordion-toggle toggle--dot">

				<!-- toggle dot  -->
				<span></span>
				<!-- toggle dot  -->

			</span>

		</div>

		<?php
	}

}
add_action('elementor/frontend/container/before_render', 'container_backgrounds');

function reverse_backgrounds($element)
{

	if ($element->get_settings('curved_bg') === 'true' && $element->get_settings('background_type') === 'color' && $element->get_settings('curves') !== 'top') {

		$size = $element->get_settings('curve')['size'] . $element->get_settings('curve')['unit'];

		echo '<div style="--curveMargin:' . $size . '" class="reverse--hold rh--bottom ' . $element->get_settings('adjust_margins') . ' reverse__' . $element->get_id() . '"><span style="--curveWidth:' . $size . ';--curveHeight:' . $size . '" class="bg--reverse-layer rl-bottom rl-left"></span>';
		echo '<span style="--curveWidth:' . $size . ';--curveHeight:' . $size . '" class="bg--reverse-layer rl-bottom rl-right"></span></div>';
	}

}
add_action('elementor/frontend/container/after_render', 'reverse_backgrounds');

function container_render($template, $element)
{

	ob_start();
	?>

	<# bgType=settings.background_type; #>

		<# if ( 'true'===settings.pin_container ) { let start=settings.ct_element_item_ref_start + ' ' +
			settings.ct_element_window_ref_start + '+=' +settings.ct_element_start_offset,
		end=settings.ct_element_item_ref_end + ' ' + settings.ct_element_window_ref_end,
			pinMobile=settings.ct_pin_mobile; #>

			<div class="container--pin--sett" data-pin-start="{{start}}" data-pin-end="{{end}}"
				data-pin-mobile="{{pinMobile}}">
			</div>

			<# } #>

				<# if ( 'true'===settings.curved_bg && 'color'===settings.background_type && 'bottom' !==settings.curves ) {
					#>
					<div class="reverse--hold">
						<span class="bg--reverse-layer rl-top rl-left"></span>
						<span class="bg--reverse-layer rl-top rl-right"></span>
					</div>
					<# } #>

						<# if ( 'video'===settings.background_type ) { let provider=settings.video_provider; if
							(provider==='vimeo' ) { var video_id=settings.vimeo_id; } else if (provider==='youtube' ) { var
							video_id=settings.youtube_id; } else { var video_id=false; } let poster=settings.video_poster;
							let dataFixed=settings.fixed_bg_strength.size; #>

							<div class="container--bg bg--{{bgType}}" data-fixed-strength="{{dataFixed}}">


								<# if ( 'true'===settings.cont_background_overlay ) { #>
									<span class="cont--bg--overlay"></span>
									<# } #>

										<div class="pe-video n-{{provider}} no-interactions" data-controls="false"
											data-autoplay=true data-muted=true data-loop=true>

											<# if ( 'true'===poster ) { #>

												<div class="pe--video--poster">
													<img src="{{settings.poster_image.url}}">
												</div>

												<# } #>

													<# if ( 'self' !==provider && 'stream' !==provider ) { #>

														<div class="p-video" data-plyr-provider="{{provider}}"
															data-plyr-embed-id="{{video_id}}">
														</div>

														<# } else if ('self'===provider || 'stream'===provider) { url='' ;
															if ('self'===provider) { url=settings.sec_video.url } else if
															('stream'===provider) { url=settings.stream_url } #>

															<video autoplay muted playsinline loop class="p-video">
																<source src="{{url}}">
															</video>

															<# } #>
										</div>

							</div>
							<# } else if ('image'===settings.background_type) { let
								dataFixed=settings.fixed_bg_strength.size; #>

								<div class="container--bg bg--{{bgType}}" data-fixed-strength="{{dataFixed}}">
									<# if ( 'true'===settings.cont_background_overlay ) { #>
										<span class="cont--bg--overlay"></span>
										<# } #>
											<div class="cont--bg--wrap">
												<img src="{{settings.cont_background_image.url}}">
											</div>
								</div>

								<# } else if ('customcss'===settings.background_type) { let bg=settings.custom_bg_code; #>

									<div style="{{bg}}" class="container--bg bg--{{bgType}}"></div>

									<# } else if ('gradient'===settings.background_type) { let
										gradient_type=settings.gradient_type; let color_1=settings.color_1; let
										color_1_location=settings.color_1_location.size; let color_2=settings.color_2; let
										color_2_location=settings.color_2_location.size; let color_3=settings.color_3; let
										color_3_location=settings.color_3_location.size; let color_4=settings.color_4; let
										color_4_location=settings.color_4_location.size; let gradient_beh; let
										gradient_beh2; if (settings.gradient_type==='linear' ) {
										gradient_beh=settings.gradient_angle.size + 'deg' ;
										gradient_beh2=settings.gradient_angle.size + 'deg' ; } else { gradient_beh='at ' +
										settings.gradient_position; gradient_beh2='at ' +
										settings.gradient_position_animated; } #>

										<div style="--b1:{{gradient_type}}-gradient({{gradient_beh}}, {{color_1}}
								{{color_1_location}}%, {{color_2}} {{color_2_location}}%);--b2:{{gradient_type}}-gradient({{gradient_beh2}}, {{color_3}}
								{{color_3_location}}%, {{color_4}} {{color_4_location}}%)"
											class="container--bg bg--{{bgType}} container--bg cont--bg--gradient">
										</div>

										<# } #>

											<?php

											$acc = ob_get_clean();

											ob_start(); ?>

											<# if ( 'true'===settings.curved_bg && 'color'===settings.background_type
												&& 'top' !==settings.curves ) { #>

												<div class="reverse--hold">
													<span class="bg--reverse-layer rl-bottom rl-left"></span>
													<span class="bg--reverse-layer rl-bottom rl-right"></span>
												</div>
												<# } #>

													<?php $dcc = ob_get_clean();

													ob_start(); ?>
													<# if ( 'none' !==settings.select_animation ) { let
														anim=settings.select_animation, duration=settings.duration,
														delay=settings.delay, stagger=settings.stagger, pin=settings.pin,
														pinTarget=settings.pinned_target, scrub=settings.scrub,
														item_ref_start=settings.item_ref_start,
														window_ref_start=settings.window_ref_start,
														item_ref_end=settings.item_ref_end,
														window_ref_end=settings.window_ref_end,
														start_scale=settings.gen_start_scale,
														end_scale=settings.gen_end_scale, out=settings.animate_out; #>
														<div hidden class="container--anim--hold" data-anim-general=true
															data-animation="{{anim}}"
															data-anim-settings="{duration={{duration}};delay={{delay}};stagger={{stagger}};pin={{pin}};pinTarget={{pinTarget}};scrub={{scrub}};item_ref_start={{item_ref_start}};window_ref_start={{window_ref_start}};item_ref_end={{item_ref_end}};window_ref_end={{window_ref_end}};out={{out}}}">
														</div>
														<# } #>

															<?php $anim = ob_get_clean();

															ob_start(); ?>

															<# if ( settings.container_title) { let
																title=settings.container_title; #>
																<div class="container--accordion--title" data-id="">

																	<span class="ac-order">1</span>

																	{{title}}

																	<span class="accordion-toggle toggle--plus">
																		<span></span>
																		<span></span>
																	</span>

																	<span class="accordion-toggle toggle--dot">
																		<span></span>
																	</span>

																</div>
																<# } #>

																	<?php $accordion = ob_get_clean();


																	return $acc . $anim . $accordion . $template . $dcc;
}

add_action("elementor/container/print_template", "container_render", 10, 2);


/**
 * Allow .lottie file uploads for administrators only.
 */
function add_custom_mime_types($mime_types)
{
	if (current_user_can('manage_options')) {
		$mime_types['lottie'] = 'application/lottie+zip';
	}
	return $mime_types;
}

/**
 * Add Lottie filter in Media Library.
 */
function edit_post_mime_types($post_mime_types)
{
	$post_mime_types['application/lottie+zip'][0] = 'Lottie';
	return $post_mime_types;
}

$option = get_option('pe-redux');
if ($option['pe_lotties'] == true) {
	add_filter('upload_mimes', 'add_custom_mime_types');
	add_filter('post_mime_types', 'edit_post_mime_types');

}

add_action('wp_ajax_pe_contact_form', 'pe_contact_form_handler');
add_action('wp_ajax_nopriv_pe_contact_form', 'pe_contact_form_handler');

function pe_contact_form_handler()
{

	$option = get_option('pe-redux');
	$widget_settings = '';

	if (!isset($_POST)) {
		wp_send_json_error('No form data received');
		wp_die();
	}


	function find_widget_settings_by_id($elements, $target_id)
	{
		foreach ($elements as $element) {
			if (isset($element['elType']) && $element['elType'] === 'widget' && $element['id'] === $target_id) {
				return $element['settings'];
			}


			if (isset($element['elements']) && is_array($element['elements'])) {
				$result = find_widget_settings_by_id($element['elements'], $target_id);
				if ($result !== null) {
					return $result;
				}
			}
		}
		return null;
	}

	if (isset($_POST['form_id']) && isset($_POST['post_id'])) {
		$post_id = $_POST['post_id'];
		$target_widget_id = $_POST['form_id'];

		$elementor_data = \Elementor\Plugin::$instance->documents->get($post_id)->get_elements_data();
		$widget_settings = find_widget_settings_by_id($elementor_data, $target_widget_id);

		$email_to = sanitize_email($widget_settings['email_to']);
		$email_subject = sanitize_text_field($widget_settings['email_subject']);
		$email_template = $widget_settings['email_template'];
	}


	// if (!isset($_POST['pe_contact_form_nonce']) || !wp_verify_nonce($_POST['pe_contact_form_nonce'], 'pe_contact_form_action')) {
	// 	wp_send_json_error($widget_settings['nonce_error_message']);
	// 	wp_die();
	// }


	if (isset($_POST['recaptcha_token'])) {

		$recaptcha_token = sanitize_text_field($_POST['recaptcha_token'] ?? '');
		$recaptcha_version = sanitize_text_field($_POST['recaptcha_version'] ?? '');

		if (empty($option['recaptcha_secret_key'])) {
			wp_send_json_error($widget_settings['recaptcha_error_message']);
			wp_die();
		}

		if ($recaptcha_version === 'v3' || $recaptcha_version === 'v2') {

			$response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", [
				'body' => [
					'secret' => $option['recaptcha_secret_key'],
					'response' => $recaptcha_token
				]
			]);

			$result = json_decode(wp_remote_retrieve_body($response), true);

			if (!$result['success']) {
				wp_send_json_error($widget_settings['recaptcha_error_message']);
				wp_die();
			}
		}

	}

	$allowed_file_types_raw = isset($_POST['allowed_file_types']) ? sanitize_text_field($_POST['allowed_file_types']) : '.jpg, .jpeg, .png, .pdf';
	$max_file_size = isset($_POST['max_file_size']) ? intval($_POST['max_file_size']) * 1024 * 1024 : 5 * 1024 * 1024;

	$ext_to_mime = [
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png' => 'image/png',
		'pdf' => 'application/pdf',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'psd' => 'image/vnd.adobe.photoshop',
		'svg' => 'image/svg+xml'
	];

	$allowed_mime_types = [];
	$extensions = array_map('trim', explode(',', $allowed_file_types_raw));

	foreach ($extensions as $ext) {
		$ext = ltrim($ext, '.');
		if (isset($ext_to_mime[$ext])) {
			$allowed_mime_types[] = $ext_to_mime[$ext];
		}
	}

	$uploaded_files = [];

	if (!empty($_FILES)) {
		foreach ($_FILES as $field_name => $file_info) {
			$file_urls = [];

			if (is_array($file_info['name'])) {
				$file_count = count($file_info['name']);
				for ($i = 0; $i < $file_count; $i++) {
					if (empty($file_info['name'][$i]))
						continue;

					$tmp_name = $file_info['tmp_name'][$i];
					$name = sanitize_file_name($file_info['name'][$i]);
					$type = $file_info['type'][$i];
					$size = $file_info['size'][$i];

					if (!in_array($type, $allowed_mime_types)) {
						wp_send_json_error('Invalid file type: ' . $type);
					}

					if ($size > $max_file_size) {
						wp_send_json_error('File size exceeds limit.');
					}

					$file_array = [
						'name' => $name,
						'type' => $type,
						'tmp_name' => $tmp_name,
						'error' => 0,
						'size' => $size,
					];

					$upload = wp_handle_sideload($file_array, ['test_form' => false]);

					if (!empty($upload['error'])) {
						wp_send_json_error('Upload error: ' . $upload['error']);
					}

					$file_urls[] = $upload['url'];
				}
			} else {
				if (empty($file_info['name']))
					continue;

				$tmp_name = $file_info['tmp_name'];
				$name = sanitize_file_name($file_info['name']);
				$type = $file_info['type'];
				$size = $file_info['size'];

				if (!in_array($type, $allowed_mime_types)) {
					wp_send_json_error($widget_settings['file_type_error'] . ': ' . $type);
				}

				if ($size > $max_file_size) {
					wp_send_json_error($widget_settings['file_size_error']);
				}

				$upload = wp_handle_upload($file_info, ['test_form' => false]);

				if (!empty($upload['error'])) {
					wp_send_json_error('Upload error: ' . $upload['error']);
				}

				$file_urls[] = $upload['url'];
			}

			$_POST[$field_name] = implode(", ", $file_urls);
		}
	}

	$form_data = $_POST;
	unset($form_data['action'], $form_data['email_to'], $form_data['email_subject'], $form_data['email_template'], $form_data['allowed_file_types'], $form_data['max_file_size']);

	foreach ($form_data as $key => $value) {
		if (is_array($value)) {
			$value = implode(', ', array_map('sanitize_text_field', $value));
		} else {
			$value = sanitize_text_field($value);
		}

		if (!empty($value)) {
			$email_template = str_replace("{" . $key . "}", $value, $email_template);
		} else {

			$email_template = preg_replace('/.*\{' . $key . '\}.*(\r\n|\n|\r)?/', '', $email_template);
		}
	}


	$lines = explode("\n", $email_template);
	$final_lines = [];

	foreach ($lines as $line) {
		if (preg_match_all('/\{(.*?)\}/', $line, $matches)) {
			$has_value = true;

			foreach ($matches[1] as $placeholder_key) {
				if (isset($form_data[$placeholder_key])) {
					$value = $form_data[$placeholder_key];
					if (is_array($value)) {
						$value = implode(', ', array_map('sanitize_text_field', $value));
					} else {
						$value = sanitize_text_field($value);
					}

					if (!empty($value)) {
						$line = str_replace("{" . $placeholder_key . "}", $value, $line);
					} else {
						$has_value = false;
						break;
					}
				} else {
					$has_value = false;
					break;
				}
			}

			if ($has_value) {
				$final_lines[] = $line;
			}
		} else {

			$final_lines[] = $line;
		}
	}

	$email_template = implode("\n", $final_lines);

	// Mail Sent

	$headers = [];
	$headers[] = 'Content-type: text/html; charset=utf-8';

	$formName = isset($widget_settings['form_name']) ? $widget_settings['form_name'] : '';
	$email_from = isset($widget_settings['email_from']) ? sanitize_email($widget_settings['email_from']) : get_option('admin_email');
	$headers[] = 'From: ' . $formName . ' <' . $email_from . '>';
	$headers[] = 'X-Mailer: PHP/' . phpversion();

	$mail_sent = wp_mail($email_to, $email_subject, $email_template, $headers);

	if ($mail_sent) {
		wp_send_json_success($widget_settings['success_message']);
	} else {
		wp_send_json_error($widget_settings['form_error_message']);
	}

	wp_die();
}

// add_action('elementor/element/nested-tabs/section_tabs_style/before_section_end', function ($element, $args) {
// 	$element->start_injection([
// 		'at' => 'before',
// 		'of' => 'tabs_title_space_between',
// 	]);

// 	flexOptions($element, false, '.e-n-tabs-heading', 'en_tab_titles_', 'Titles', true, '.e-n-tab-title');

// 	$element->end_injection();

// 	$element->start_injection([
// 		'at' => 'after',
// 		'of' => 'padding',
// 	]);

// 	objectAbsolutePositioning($element, '.e-n-tabs-heading', 'tab_titles_', 'Titles', false);
// 	$element->end_injection();
// }, 10, 2);


function inject_tabs_styles($element, $args)
{
	flexOptions($element, false, '.e-n-tabs-heading', 'en_tab_titles_', 'Titles', true, '.e-n-tab-title');

	objectAbsolutePositioning($element, '.e-n-tabs-heading', 'tab_titles_', 'Titles', false);

}

add_action('elementor/element/nested-tabs/section_tabs_style/before_section_end', 'inject_tabs_styles', 10, 2);




function inject_accordion_states($element, $args)
{

	$element->add_control(
		'accordion_state',
		[
			'type' => \Elementor\Controls_Manager::HIDDEN,
			'default' => 'first--expanded',
			'prefix_class' => '',
			'render_type' => 'template',
			'condition' => ['default_state' => 'expanded'],
		]
	);

	$element->add_control(
		'accordion_state_2',
		[
			'type' => \Elementor\Controls_Manager::HIDDEN,
			'default' => 'allow--multiple',
			'prefix_class' => '',
			'render_type' => 'template',
			'condition' => ['max_items_expended' => 'multiple'],
		]
	);

	$element->add_responsive_control(
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
			'default' => 'text-p',
			'toggle' => false,
			'selectors' => [
				'{{WRAPPER}} .e-n-accordion-item-title-text' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing) !important;--anim--letter--spacing: var(--{{VALUE}}-letter-spacing) !important',
			],
		]
	);

	$element->add_responsive_control(
		'accordion_paragraph_size',
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
				'{{WRAPPER}} .e-n-accordion-item-title-text' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
			],
		]
	);

	$element->add_responsive_control(
		'accordion_heading_size',
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
				'{{WRAPPER}} .e-n-accordion-item-title-text' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
			],
		]
	);

}
add_action('elementor/element/nested-accordion/section_items/after_section_start', 'inject_accordion_states', 10, 2);

function nested_accordion_extend_items_controls($element, $args)
{

	if ('nested-accordion' !== $element->get_name()) {
		return;
	}

	$controls = $element->get_controls();

	if (empty($controls['items'])) {
		return;
	}

	$repeater = $controls['items'];

	if (empty($repeater['fields']) || !is_array($repeater['fields'])) {
		return;
	}

	$repeater['fields']['item_add_icon'] = [
		'name' => 'item_add_icon',
		'label' => esc_html__('Add Custom Icon', 'pe-core'),
		'type' => \Elementor\Controls_Manager::SWITCHER,
		'label_on' => esc_html__('Yes', 'pe-core'),
		'label_off' => esc_html__('No', 'pe-core'),
		'return_value' => 'yes',
		'default' => '',
	];

	$repeater['fields']['item_icon'] = [
		'name' => 'item_icon',
		'label' => esc_html__('Icon', 'pe-core'),
		'type' => \Elementor\Controls_Manager::ICONS,
		'condition' => ['item_add_icon' => 'yes'],
	];

	$element->update_control('items', $repeater);
}

add_action(
	'elementor/element/nested-accordion/section_items/before_section_end',
	'nested_accordion_extend_items_controls',
	10,
	2
);

function nested_accordion_images_controls($element, $args)
{
	$element->add_control(
		'accordion_images',
		[
			'label' => esc_html__('Accordion Images', 'pe-core'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__('Yes', 'pe-core'),
			'label_off' => esc_html__('No', 'pe-core'),
			'return_value' => 'true',
			'prefix_class' => '',
			'render_type' => 'template',
			'default' => '',
		]
	);

	$element->add_control(
		'accordion_images_gallery',
		[
			'label' => esc_html__('Add Images', 'pe-core'),
			'description' => esc_html__('Please add the images in the order you want them to appear in the accordion items.', 'pe-core'),
			'type' => \Elementor\Controls_Manager::GALLERY,
			'show_label' => false,
			'default' => [],
			'condition' => ['accordion_images' => 'true'],
		]
	);

	$element->add_group_control(
		\Elementor\Group_Control_Image_Size::get_type(),
		[
			'name' => 'image',
			'exclude' => [],
			'include' => [],
			'default' => 'medium_large',
			'condition' => ['accordion_images' => 'true'],
		]
	);
}
add_action('elementor/element/nested-accordion/section_items/before_section_end', 'nested_accordion_images_controls', 10, 2);

function nested_accordion_icon_styles($element, $args)
{

	$element->add_responsive_control(
		'custom_icon_size',
		[
			'label' => esc_html__('Custom Icon Size', 'pe-core'),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
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
				'rem' => [
					'min' => 0,
					'max' => 100,
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
				'em' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .e-n-accordion-item-custom-icon.pe--styled--object' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		]
	);

	objectStyles($element, 'custom_icon_', 'Custom Icon', '.e-n-accordion-item-custom-icon.pe--styled--object', false, false, false, false, false, true);

}
add_action('elementor/element/nested-accordion/section_header_style/before_section_end', 'nested_accordion_icon_styles', 10, 2);

function nested_accordion_flex_styles($element, $args)
{
	flexOptions($element, ['accordion_images' => 'true'], '', 'accordion_flex_', 'Accordion');

	$element->add_responsive_control(
		'images_height',
		[
			'label' => esc_html__('Images Height', 'pe-core'),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
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
				'rem' => [
					'min' => 0,
					'max' => 100,
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
				'em' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .e-n-accordion-images .accordion--image' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition' => ['accordion_images' => 'true'],
		]
	);

	$element->add_responsive_control(
		'images_radius',
		[
			'label' => esc_html__('Images Radius', 'pe-core'),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%', 'em', 'rem', 'vh'],
			'selectors' => [
				'{{WRAPPER}} .e-n-accordion-images .accordion--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => ['accordion_images' => 'true'],
		]
	);

}
add_action('elementor/element/nested-accordion/section_accordion_style/before_section_end', 'nested_accordion_flex_styles', 10, 2);


function nested_accordion_images_render($widget_content, $widget)
{

	if ('nested-accordion' === $widget->get_name()) {
		$settings = $widget->get_settings();

		$id_int = substr($widget->get_id_int(), 0, 3);

		foreach ($settings['items'] as $index => $item) {
			if ($item['item_add_icon'] === 'yes' && !empty($item['item_icon']['value'])) {
				ob_start();
				\Elementor\Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true']);
				$icon = ob_get_clean();

				echo '<span class="e-n-accordion-item-custom-icon pe--styled--object e-n-accordion-item-custom-icon-' . $id_int . $index . '">' . $icon . '</span>';

			}
		}

		if ($settings['accordion_images'] === 'true') {

			ob_start(); ?>
																			<div class="e-n-accordion-images">
																				<?php foreach ($settings['accordion_images_gallery'] as $key => $image) {

																					?>

																					<div class="accordion--image ac--image--<?php echo $key ?>"
																						data-index="<?php echo $key ?>">
																						<?php echo wp_get_attachment_image($image['id'], $settings['image_size']) ?>
																					</div>

																					<?php

																				} ?>
																			</div>
																			<?php
																			$images = ob_get_clean();
																			$widget_content .= $images;
		}

	}

	return $widget_content;

}
add_filter('elementor/widget/render_content', 'nested_accordion_images_render', 10, 2);

function nested_accordion_images_print_template($template, $widget)
{
  
    if ('nested-accordion' !== $widget->get_name()) {
        return $template; 
    }

    ob_start(); ?>

    <# const elementUid = view.getIDInt().toString().substring(0,3); #>

    <# _.each( settings.items, function( item, index ) {
        if (
            item.item_add_icon !== 'yes' ||
            !item.item_icon ||
            !item.item_icon.value
        ) {
            return;
        }

        var iconHtml = '';

        if ( item.item_icon.library === 'svg' && item.item_icon.value.url ) {
            iconHtml = '<object type="image/svg+xml" data="' + item.item_icon.value.url + '" aria-hidden="true"></object>';
        } else {
            iconHtml = elementor.helpers.renderIcon( view, item.item_icon, { 'aria-hidden': true } ) || '';
        }
    #>
        <span class="e-n-accordion-item-custom-icon pe--styled--object e-n-accordion-item-custom-icon-{{ elementUid }}{{ index }}">
            {{{ iconHtml }}}
        </span>
    <# }); #>

    <?php
    $renderIcons = ob_get_clean();

    $template .= '
    <# if ( settings.accordion_images === "true" && settings.accordion_images_gallery && settings.accordion_images_gallery.length ) { #>
        <div class="e-n-accordion-images">
            <# _.each( settings.accordion_images_gallery, function( image, index ) { 
                var active = index === 0 ? "active" : "";
            #>
                <div class="accordion--image ac--image--{{ index }} {{ active }}" data-index="{{ index }}">
                    <img src="{{ image.url }}" alt="">
                </div>
            <# }); #>
        </div>
    <# } #>
    ';

    return $template . $renderIcons;
}

add_filter('elementor/widget/print_template', 'nested_accordion_images_print_template', 10, 2);

function inject_accordion_scroll($element, $args)
{

	$element->start_controls_section(
		'accordion_scroll',
		[
			'label' => esc_html__('Scroll Options', 'pe-core'),
		]
	);

	$element->add_control(
		'reveal_on_scroll',
		[
			'label' => esc_html__('Reveal on Scroll', 'pe-core'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__('Yes', 'pe-core'),
			'label_off' => esc_html__('No', 'pe-core'),
			'return_value' => 'reveal--on--scroll',
			'prefix_class' => '',
			'render_type' => 'template',
			'default' => '',
		]
	);


	$element->add_control(
		'accordion_anim_pin',
		[
			'label' => esc_html__('Pin', 'pe-core'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__('Yes', 'pe-core'),
			'label_off' => esc_html__('No', 'pe-core'),
			'return_value' => 'cont--behavior--pin',
			'prefix_class' => '',
			'default' => '',
			'render_type' => 'template',
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'pin_accordion_target',
		[
			'label' => esc_html__('Events Target', 'pe-core'),
			'placeholder' => esc_html__('Eg. #worksContainer', 'pe-core'),
			'type' => \Elementor\Controls_Manager::TEXT,
			'description' => esc_html__('Leave it empty if you want to use container as trigger.', 'pe-core'),
			'ai' => false,
			'prefix_class' => 'pin_accordion_',
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_pin_mobile',
		[
			'label' => esc_html__('Pin Mobile', 'pe-core'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__('Yes', 'pe-core'),
			'label_off' => esc_html__('No', 'pe-core'),
			'return_value' => 'true',
			'default' => '',
			'render_type' => 'template',
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_pin_header',
		[
			'label' => esc_html__('Disable Header Pinning', 'pe-core'),
			'description' => esc_html__('Normally the pin keeps header until completed if it starts on top of the page, you can disable header pin setting this option to "yes".', 'pe-core'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__('Yes', 'pe-core'),
			'label_off' => esc_html__('No', 'pe-core'),
			'return_value' => 'true',
			'default' => '',
			'render_type' => 'template',
			'prefix_class' => 'header--pin--disabled--',
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);


	$element->add_control(
		'accordion_element_start_references',
		[
			'label' => esc_html__('Start References', 'pe-core'),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_element_references_notice',
		[
			'type' => \Elementor\Controls_Manager::RAW_HTML,
			'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
			   This references below are adjusts the pinning start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; pinning will start when item's top edge enters the window's bottom edge.</b></div>",
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],


		]
	);

	$element->add_control(
		'accordion_element_start_offset',
		[
			'label' => esc_html__('Start Offset', 'pe-core'),
			'description' => esc_html__('An offset value (px) which will be added to pinning start position. Usefull if you are using a fixed,/sticky header.', 'pe-core'),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'min' => -1000,
			'max' => 1000,
			'step' => 1,
			'default' => 0,
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_element_item_ref_start',
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
			'render_type' => 'template',
			'default' => 'center',
			'toggle' => false,
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_element_window_ref_start',
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
			'render_type' => 'template',
			'default' => 'center',
			'toggle' => false,
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_element_end_references',
		[
			'label' => esc_html__('End References', 'pe-core'),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);
	$element->add_control(
		'accordion_element_end_offset',
		[
			'label' => esc_html__('End Offset', 'pe-core'),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'min' => 0,
			'max' => 10000,
			'step' => 1,
			'default' => 0,
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_element_item_ref_end',
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
			'render_type' => 'template',
			'default' => 'bottom',
			'toggle' => false,
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->add_control(
		'accordion_element_window_ref_end',
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
			'render_type' => 'template',
			'default' => 'top',
			'toggle' => false,
			'condition' => ['reveal_on_scroll' => 'reveal--on--scroll'],
		]
	);

	$element->end_controls_section();



}
add_action('elementor/element/nested-accordion/section_items/after_section_end', 'inject_accordion_scroll', 10, 2);


function accordion_attributes($element)
{
	if ($element->get_name() === 'nested-accordion' && $element->get_settings('reveal_on_scroll') === 'reveal--on--scroll') {


		$start = $element->get_settings('accordion_element_item_ref_start') . ' ' . $element->get_settings('accordion_element_window_ref_start') . '+=' . $element->get_settings('accordion_element_start_offset');
		$end = $element->get_settings('accordion_element_item_ref_end') . '+=' . $element->get_settings('accordion_element_end_offset') . ' ' . $element->get_settings('accordion_element_window_ref_end');


		$element->add_render_attribute(
			'_wrapper',
			[
				'data-pin' => $element->get_settings('accordion_anim_pin'),
				'data-trigger' => $element->get_settings('pin_accordion_target'),
				'data-start' => $start,
				'data-end' => $end,
				'data-pin-mobile' => $element->get_settings('accordion_pin_mobile'),
			]
		);
	}

}
add_action('elementor/frontend/widget/before_render', 'accordion_attributes');


add_action('wp_ajax_pe_import_elementor_template', function () {

	if (!current_user_can('edit_posts')) {
		wp_send_json_error('Permission denied');
	}

	$post_id = intval($_POST['post_id'] ?? 0);
	$json_path = esc_url_raw($_POST['json_path'] ?? '');

	if (!$post_id || !$json_path) {
		wp_send_json_error('Invalid request');
	}

	$response = wp_remote_get($json_path, [
		'timeout' => 20,
		'sslverify' => false,
	]);

	if (is_wp_error($response)) {
		wp_send_json_error($response->get_error_message());
	}

	$body = wp_remote_retrieve_body($response);

	if (!$body) {
		wp_send_json_error('Empty response');
	}

	$data = json_decode($body, true);

	if (json_last_error() !== JSON_ERROR_NONE) {
		wp_send_json_error('Invalid JSON');
	}

	// Elementor export uyumluluğu
	$content = $data['content'] ?? $data;

	wp_send_json_success([
		'content' => $content,
	]);
});

add_action('wp_ajax_pe_elementor_template_popup', function () {

	if (!current_user_can('edit_posts')) {
		wp_die('Permission denied');
	}

	ob_start();
	include plugin_dir_path(__FILE__) . '../inc/template-library/views/header.php';
	$header = ob_get_clean();

	ob_start();
	include plugin_dir_path(__FILE__) . '../inc/template-library/views/message.php';
	$message = ob_get_clean();

	wp_send_json_success([
		'header' => $header,
		'message' => $message,
	]);

	wp_die();
});