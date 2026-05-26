<?php

namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PePortfolio extends Widget_Base
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
		return 'peportfolio';
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
		return __('Portfolio', 'pe-core');
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
		return 'eicon-posts-grid pe-widget';
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


		$this->start_controls_section(
			'widget_content',
			[
				'label' => __('Portfolio ', 'pe-core'),
			]
		);

		$this->add_control(
			'portfolio_style',
			[
				'label' => esc_html__('Style', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'grid',
				'prefix_class' => 'portfolio--style--',
				'options' => [
					'grid' => esc_html__('Grid', 'pe-core'),
					'masonry' => esc_html__('Masonry', 'pe-core'),
					'list' => esc_html__('List', 'pe-core'),
					'grouped' => esc_html__('Grouped', 'pe-core'),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'portfolio_images_style',
			[
				'label' => esc_html__('List Images Style', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'static',
				'prefix_class' => 'portfolio--images--',
				'options' => [
					'static' => esc_html__('Static', 'pe-core'),
					'hover' => esc_html__('Hover', 'pe-core'),
					'fullscreen' => esc_html__('Fullscreen', 'pe-core'),
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'list',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'switcher_styles',
							'operator' => 'in',
							'value' => ['list'],
						],
					],
				],
				'frontend_available' => true,
			]
		);


		zeyna_project_query_selection($this, true);

		$this->end_controls_section();

		zeyna_project_settings($this);

		$this->start_controls_section(
			'portfolio_controls_settings',
			[
				'label' => __('Controls ', 'pe-core'),
				'condition' => [
					'portfolio_style!' => 'grouped',

				]
			]
		);

		$this->add_control(
			'filters',
			[
				'label' => __('Filters', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
			]
		);

		$this->add_control(
			'filters_style',
			[
				'label' => esc_html__('Filters Style', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'label_block' => false,
				'render_type' => 'template',
				'default' => 'block',
				'prefix_class' => 'filters--style--',
				'options' => [
					'block' => esc_html__('Block', 'pe-core'),
					'dropdown' => esc_html__('Dropdown', 'pe-core'),
					'popup' => esc_html__('Popup', 'pe-core'),
					'sidebar' => esc_html__('Sidebar', 'pe-core'),
				],
				'condition' => [
					'filters' => 'true',

				]
			]
		);

		$this->add_responsive_control(
			'filters_pos',
			[
				'label' => esc_html__('Filters Position', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__('Left', 'pe-core'),
						'icon' => 'eicon-align-start-h',
					],
					'row-reverse' => [
						'title' => esc_html__('Right', 'pe-core'),
						'icon' => 'eicon-align-end-h',
					],
					'column' => [
						'title' => esc_html__('Up', 'pe-core'),
						'icon' => 'eicon-v-align-bottom',
					],
					'column-reverse' => [
						'title' => esc_html__('Down', 'pe-core'),
						'icon' => 'eicon-v-align-top',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .pe--portfolio:has(.pe--portfolio--filters.filters--sidebar)' => 'flex-direction: {{VALUE}};',
				],
				'condition' => ['filters_style' => 'sidebar'],
			]
		);

		$post_type = 'portfolio';
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		$taxonomy_options = [];

		if (!empty($taxonomies) && !is_wp_error($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				$taxonomy_options[$taxonomy->name] = $taxonomy->label;
			}
		}

		$portfolioFilters = new \Elementor\Repeater();

		$portfolioFilters->add_control(
			'select_taxonomy',
			[
				'label' => __('Select Taxonomy', 'pe-core'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => true,
				'options' => $taxonomy_options,

			]
		);


		$portfolioFilters->start_controls_tabs(
			'filter_options_tabs'
		);

		$portfolioFilters->start_controls_tab(
			'content_tab',
			[
				'label' => esc_html__('Content', 'pe-core'),
			]
		);


		foreach ($taxonomies as $key => $tax) {

			$termsArray = [];
			$terms = get_terms([
				'taxonomy' => $tax->name,
				'hide_empty' => true,
			]);

			if (!is_wp_error($terms) && !empty($terms)) {

				foreach ($terms as $term) {
					$termsArray[$term->term_id] = $term->name;
				}
			}

			$portfolioFilters->add_control(
				'select_' . $tax->name,
				[
					'label' => __('Select ' . $tax->label, 'pe-core'),
					'description' => __('Leave it empty if you want to display all.', 'pe-core'),
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $termsArray,
					'condition' => [
						'select_taxonomy' => $tax->name,
					],
				]
			);
		}

		$portfolioFilters->add_control(
			'show_label',
			[
				'label' => __('Show Label', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
			]
		);

		$portfolioFilters->add_control(
			'show_all_button',
			[
				'label' => __('Show All Button', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'pe-core '),
				'label_off' => __('Hide', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
			]
		);


		$portfolioFilters->add_control(
			'show_all_text',
			[
				'label' => esc_html__('Show All Text', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('All ', 'pe-core'),
				'ai' => false,
				'condition' => [
					'show_all_button' => 'true'
				],
			]
		);

		$portfolioFilters->add_control(
			'show_counts',
			[
				'label' => __('Show Project Counts', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
			]
		);


		$portfolioFilters->add_control(
			'filter_style',
			[
				'label' => esc_html__('Filter Style', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'button' => esc_html__('Button', 'pe-core'),
					'checkbox' => esc_html__('Checkbox', 'pe-core'),
					'dropdown' => esc_html__('Dropdown', 'pe-core'),
				],
			]
		);


		$portfolioFilters->end_controls_tab();

		$portfolioFilters->start_controls_tab(
			'styles_tab',
			[
				'label' => esc_html__('Styles', 'pe-core'),
			]
		);

		$portfolioFilters->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'label' => esc_html__('Labels Typography', 'pe-core'),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .taxonomy--label',
			]
		);

		$portfolioFilters->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'terms_typography',
				'label' => esc_html__('Terms Typography', 'pe-core'),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .term-item',
			]
		);

		flexOptions($portfolioFilters, false, '{{CURRENT_ITEM}} ul.term--list', 'term_list', 'Terms', false);
		objectStyles($portfolioFilters, 'items_styles', 'Items', '{{CURRENT_ITEM}} ul.term--list .term-item.pe--styled--object', false, false, false, false, false);


		$portfolioFilters->end_controls_tab();

		$portfolioFilters->end_controls_tabs();

		$this->add_control(
			'portfolio_filters',
			[
				'label' => esc_html__('Filters', 'pe-core'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $portfolioFilters->get_controls(),
				'show_label' => false,
				'condition' => [
					'filters' => 'true',
				],
				'title_field' => '{{{ select_taxonomy }}}',
			]
		);

		$this->add_control(
			'pinned_filters',
			[
				'label' => __('Pinned Filters', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'prefix_class' => '',
				'return_value' => 'filters--pinned',
				'render_type' => 'template',
				'default' => '',
				'condition' => ['filters_style' => 'sidebar'],
			]
		);


		$this->add_control(
			'clear_filters',
			[
				'label' => __('Clear Filters Button', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'pe-core '),
				'label_off' => __('Hide', 'pe-core '),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'clear_filters_button_text',
			[
				'label' => esc_html__('Clear Button Text', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Clear Filters', 'pe-core'),
				'condition' => [
					'clear_filters' => 'yes',
					'filters' => 'true',
				],
			]
		);

		$this->add_control(
			'style_switcher',
			[
				'label' => __('Style Switcher', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
			]
		);

		$this->add_control(
			'switcher_styles',
			[
				'label' => esc_html__('Switcher Styles', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'grid' => esc_html__('Grid', 'pe-core'),
					'list' => esc_html__('List', 'pe-core'),
					'masonry' => esc_html__('Masonry', 'pe-core'),
				],
				'condition' => [
					'style_switcher' => 'true',

				]
			]
		);

		$this->add_control(
			'switcher_buttons',
			[
				'label' => esc_html__('Switcher Buttons', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text' => esc_html__('Text', 'pe-core'),
					'icon' => esc_html__('Icon', 'pe-core'),
				],
			]
		);

		$this->add_control(
			'grid_text',
			[
				'label' => esc_html__('Grid Text', 'pe-core'),
				'default' => esc_html__('Grid', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => false,
				'ai' => false,
				'condition' => [
					'switcher_buttons' => 'text',
					'switcher_styles' => 'grid',
				],
			]
		);

		$this->add_control(
			'grid_icon',
			[
				'label' => esc_html__('Grid Icon', 'pe-core'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'condition' => [
					'switcher_buttons' => 'icon',
					'switcher_styles' => 'grid',
				],

			]
		);

		$this->add_control(
			'list_text',
			[
				'label' => esc_html__('List Text', 'pe-core'),
				'default' => esc_html__('List', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => false,
				'ai' => false,
				'condition' => [
					'switcher_buttons' => 'text',
					'switcher_styles' => 'list',
				],
			]
		);

		$this->add_control(
			'list_icon',
			[
				'label' => esc_html__('List Icon', 'pe-core'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'condition' => [
					'switcher_buttons' => 'icon',
					'switcher_styles' => 'list',
				],

			]
		);

		$this->add_control(
			'masonry_text',
			[
				'label' => esc_html__('Masonry Text', 'pe-core'),
				'default' => esc_html__('Masonry', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => false,
				'ai' => false,
				'condition' => [
					'switcher_buttons' => 'text',
					'switcher_styles' => 'masonry',
				],
			]
		);

		$this->add_control(
			'masonry_icon',
			[
				'label' => esc_html__('Masonry Icon', 'pe-core'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'condition' => [
					'switcher_buttons' => 'icon',
					'switcher_styles' => 'masonry',
				],

			]
		);

		$this->add_control(
			'grid_style_switcher',
			[
				'label' => __('Grid Layout Switcher', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
				'condition' => [
					'portfolio_style' => 'grid',
				],
			]
		);

		$this->add_control(
			'grid_switch_columns',
			[
				'label' => esc_html__('Grid Switch Columns', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'1' => esc_html__('1 Column', 'pe-core'),
					'2' => esc_html__('2 Columns', 'pe-core'),
					'3' => esc_html__('3 Columns', 'pe-core'),
					'4' => esc_html__('4 Columns', 'pe-core'),
					'5' => esc_html__('5 Columns', 'pe-core'),
					'6' => esc_html__('6 Columns', 'pe-core'),
				],
				'default' => ['2-column', '3-column'],
				'condition' => [
					'portfolio_style' => 'grid',
					'grid_style_switcher' => 'true',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'pagination',
			[
				'label' => esc_html__('Pagination', 'pe-core'),
				'condition' => [
					'portfolio_style!' => 'grouped',

				]
			]
		);

		$this->add_control(
			'pagination_style',
			[
				'label' => esc_html__('Style', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__('None', 'pe-core'),
					'ajax-load-more' => esc_html__('AJAX Load More', 'pe-core'),
					'infinite-scroll' => esc_html__('Infinite Scroll', 'pe-core'),
					'paged' => esc_html__('Paged', 'pe-core'),
				],

			]
		);

		$this->add_control(
			'paged_style',
			[
				'label' => esc_html__('Prev/Next Styles', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text' => esc_html__('Text', 'pe-core'),
					'icon' => esc_html__('Icon', 'pe-core'),
				],

			]
		);

		$this->add_control(
			'pagination_prev_text',
			[
				'label' => esc_html__('Prev Text', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Prev', 'pe-core'),
				'condition' => [
					'pagination_style' => 'paged',
					'paged_style' => 'text',
				],
			]
		);

		$this->add_control(
			'pagination_next_text',
			[
				'label' => esc_html__('Next Text', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Next', 'pe-core'),
				'condition' => [
					'pagination_style' => 'paged',
					'paged_style' => 'text',
				],
			]
		);

		$this->add_control(
			'pagination_prev_icon',
			[
				'label' => esc_html__('Prev Icon', 'pe-core'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'material-icons md-arrow_back',
					'library' => 'material-design-icons',
				],
				'condition' => [
					'paged_style' => 'icon',
					'pagination_style' => 'paged',
				],

			]
		);

		$this->add_control(
			'pagination_next_icon',
			[
				'label' => esc_html__('Next Icon', 'pe-core'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'material-icons md-arrow_forward',
					'library' => 'material-design-icons',
				],
				'condition' => [
					'paged_style' => 'icon',
					'pagination_style' => 'paged',
				],

			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} .portfolios--pagination.pagination--paged',
				'condition' => [
					'pagination_style' => 'paged',
				],
			]
		);


		flexOptions($this, ['pagination_style' => 'paged'], '.portfolios--pagination.pagination--paged', 'pagination', 'Pagination');

		$this->add_responsive_control(
			'pagination_margin',
			[
				'label' => esc_html__('Margin', 'pe-core'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .portfolios--pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);



		$cond = ['pagination_style' => 'ajax-load-more'];
		pe_button_settings($this, false, $cond);

		$this->end_controls_section();

		$this->start_controls_section(
			'additional_options',
			[
				'label' => esc_html__('Additional Options', 'pe-core'),
				'condition' => [
					'portfolio_style!' => 'grouped',

				]
			]
		);

		$this->add_control(
			'is_archive',
			[
				'label' => __('Is Archive?', 'pe-core '),
				'description' => __('Switch this to "Yes" if the widget will be used in an archive page template.', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
			]
		);

		$this->add_control(
			'is_related',
			[
				'label' => __('Is Related Projects?', 'pe-core '),
				'description' => __('Switch this to "Yes" if the widget will be used in an archive page template.', 'pe-core '),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'pe-core '),
				'label_off' => __('No', 'pe-core '),
				'return_value' => 'true',
				'default' => '',
			]
		);


		$this->end_controls_section();

		pe_cursor_settings($this, false, true);
		pe_general_animation_settings($this);

		$this->start_controls_section(
			'portfolio_styles',
			[
				'label' => esc_html__('Styles', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'group_titles',
				'label' => esc_html__('Group Titles Typography', 'pe-core'),
				'selector' => '{{WRAPPER}} .projects--group--title h4',
			]
		);

		$this->add_responsive_control(
			'groups_spaces',
			[
				'label' => esc_html__('Space Between Groups', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh', 'em'],
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
					'{{WRAPPER}}.portfolio--style--grouped .portfolio--projects--wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['portfolio_style' => 'grouped'],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'group_counts',
				'label' => esc_html__('Group Counts Typography', 'pe-core'),
				'selector' => '{{WRAPPER}} .projects--group--title span.group--count',
			]
		);

		$this->add_responsive_control(
			'group_items_width',
			[
				'label' => esc_html__('Items Width', 'pe-core'),
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
					'{{WRAPPER}} .projects--group--wrapper>div.zeyna--portfolio--project' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['portfolio_style' => 'grouped'],
			]
		);

		$this->add_responsive_control(
			'group_items_gap',
			[
				'label' => esc_html__('Items Spacing', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh', 'em'],
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
					'{{WRAPPER}} .projects--group--wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['portfolio_style' => 'grouped'],
			]
		);

		$this->add_responsive_control(
			'group_items_align',
			[
				'label' => esc_html__('Alignment', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__('Start', 'pe-core'),
						'icon' => 'eicon-align-start-v'
					],
					'center' => [
						'title' => esc_html__('Center', 'pe-core'),
						'icon' => 'eicon-align-center-v'
					],
					'flex-end' => [
						'title' => esc_html__('End', 'pe-core'),
						'icon' => 'eicon-align-end-v'
					],
				],

				'selectors' => [
					'{{WRAPPER}} .projects--group--wrapper' => 'align-items: {{VALUE}};',
				],
				'condition' => ['portfolio_style' => 'grouped'],
			]
		);



		$this->add_responsive_control(
			'list_items_gap',
			[
				'label' => esc_html__('Items Spacing', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh', 'em'],
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
					'{{WRAPPER}}.portfolio--style--list .portfolio--projects--wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['portfolio_style' => 'list'],
			]
		);


		$this->add_responsive_control(
			'grid_columns',
			[
				'label' => esc_html__('Grid Columns', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['fr'],
				'range' => [
					'fr' => [
						'min' => 1,
						'max' => 6,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.portfolio--style--grid .portfolio--projects--wrapper' => '--columns: {{SIZE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'query_id',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => esc_html__('Columns Gap', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', '%', 'vw', 'custom'],
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
					'em' => [
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
					'{{WRAPPER}}.portfolio--style--grid .portfolio--projects--wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'query_id',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__('Rows Gap', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', '%', 'vh', 'custom'],
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
					'em' => [
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
					'{{WRAPPER}}.portfolio--style--grid .portfolio--projects--wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',

				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'query_id',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'rows_height',
			[
				'label' => esc_html__('Rows Height', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', '%', 'vh'],
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
					'em' => [
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
					'{{WRAPPER}}.portfolio--style--grid .portfolio--projects--wrapper' => 'grid-auto-rows: {{SIZE}}{{UNIT}};',

				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'query_id',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'items_width',
			[
				'label' => esc_html__('Items Width', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', '%', 'vh', 'custom'],
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
					'em' => [
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
					'{{WRAPPER}}.portfolio--style--grid .portfolio--projects--wrapper .zeyna--portfolio--project' => 'width: {{SIZE}}{{UNIT}};',

				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'query_id',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'randomized',
			[
				'label' => esc_html__('Randomize Positions', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'random--items',
				'frontend_available' => true,
				'condition' => ['portfolio_style' => 'grid'],
			]
		);

		$this->add_control(
			'randomize_range',
			[
				'label' => esc_html__('Randomize Range', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'start' => esc_html__('Start', 'pe-core'),
					'center' => esc_html__('Center', 'pe-core'),
					'end' => esc_html__('End', 'pe-core'),
				],
				'default' => ['start', 'center', 'end'],
				'frontend_available' => true,
				'condition' => ['randomized' => 'random--items'],
			]
		);

		$this->add_control(
			'grid_spacings',
			[
				'label' => esc_html__('Add Spacings', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$spacingsRepeater = new \Elementor\Repeater();

		$spacingsRepeater->add_control(
			'spacing_col',
			[
				'label' => esc_html__('Spacing Column', 'pe-core'),
				'placeholder' => esc_html__('Eg. 2', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => false,

			]
		);

		$spacingsRepeater->add_control(
			'spacing_row',
			[
				'label' => esc_html__('Spacing Row', 'pe-core'),
				'placeholder' => esc_html__('Eg. 5', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => false,
			]
		);

		$this->add_control(
			'spacings',
			[
				'label' => esc_html__('Spacings', 'pe-core'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $spacingsRepeater->get_controls(),
				'show_label' => false,
				'title_field' => 'Spaced: {{{ spacing_col }}} / {{{ spacing_row }}}',
				'condition' => [
					'grid_spacings' => 'yes',
				],
			]
		);


		$this->add_responsive_control(
			'grid_align_items',
			[
				'label' => esc_html__('Align Items', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__('Start', 'pe-core'),
						'icon' => 'eicon-align-start-v',
					],
					'center' => [
						'title' => esc_html__('Center', 'pe-core'),
						'icon' => 'eicon-align-center-v',
					],
					'end' => [
						'title' => esc_html__('End', 'pe-core'),
						'icon' => 'eicon-align-end-v',
					],
					'stretch' => [
						'title' => esc_html__('Stretch', 'pe-core'),
						'icon' => 'eicon-align-stretch-v',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}}.portfolio--style--grid .portfolio--projects--wrapper' => 'align-items: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'query_id',
							'operator' => '!==',
							'value' => '',
						],
					],
				],


			]
		);

		$this->add_responsive_control(
			'grid_justify_items',
			[
				'label' => esc_html__('Justify Items', 'pe-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__('Start', 'pe-core'),
						'icon' => 'eicon-justify-start-h',
					],
					'center' => [
						'title' => esc_html__('Center', 'pe-core'),
						'icon' => 'eicon-justify-center-h',
					],
					'end' => [
						'title' => esc_html__('End', 'pe-core'),
						'icon' => 'eicon-justify-end-h',
					],
					'stretch' => [
						'title' => esc_html__('Stretch', 'pe-core'),
						'icon' => 'eicon-align-stretch-h',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}}.portfolio--style--grid .portfolio--projects--wrapper' => 'justify-items: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'portfolio_style',
							'operator' => '===',
							'value' => 'grid',
						],
						[
							'name' => 'style_switcher',
							'operator' => '===',
							'value' => 'true',
						],
						[
							'name' => 'query_id',
							'operator' => '!==',
							'value' => '',
						],
					],
				],

			]
		);


		$this->add_responsive_control(
			'masonry_items_width',
			[
				'label' => esc_html__('Masonry Items Width', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw', 'vh', 'em'],
				'range' => [
					'px' => [
						'min' => 1,
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
					'vh' => [
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
					'{{WRAPPER}} .portfolio--projects--wrapper' => '--masonryItemsWidth: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['portfolio_style' => 'masonry'],
			]
		);



		$this->add_responsive_control(
			'masonry_items_gutter',
			[
				'label' => esc_html__('Items Gutter (Column)', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 1,
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
					'vh' => [
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
					'{{WRAPPER}} .portfolio--projects--wrapper .zeyna--projects--masonry--gutter' => '--masonryGutter: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['portfolio_style' => 'masonry'],
			]
		);

		$this->add_responsive_control(
			'masonry_items_gutter_row',
			[
				'label' => esc_html__('Items Gutter (Row)', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 1,
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
					'vh' => [
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
					'{{WRAPPER}} .portfolio--projects--wrapper .zeyna--portfolio--project' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['portfolio_style' => 'masonry'],
			]
		);


		$this->end_controls_section();

		zeyna_project_styles($this);


		$switcherCond = [
			'relation' => 'or',
			'terms' => [
				[
					'name' => 'portfolio_style',
					'operator' => '===',
					'value' => 'grid',
				],
				[
					'name' => 'style_switcher',
					'operator' => '===',
					'value' => 'true',
				],
				[
					'name' => 'query_id',
					'operator' => '!==',
					'value' => '',
				],
			],
		];

		zeyna_project_styles($this, $switcherCond, '.style--switched--grid', 'Switched');

		$this->start_controls_section(
			'controls_styles',
			[
				'label' => esc_html__('Controls Styles', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'portfolio_style!' => 'grouped',

				]
			]
		);

		$this->add_responsive_control(
			'filters_width',
			[
				'label' => esc_html__('Filters Width', 'pe-core'),
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
					'{{WRAPPER}}  .project--metas--wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		flexOptions($this, false, '.pe--portfolio--controls', 'controls', 'Controls');


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'controls_border',
				'selector' => '{{WRAPPER}} .pe--portfolio--controls',
			]
		);

		$this->add_responsive_control(
			'controls_border-radius',
			[
				'label' => esc_html__('Border Radius', 'pe-core'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .pe--portfolio--controls' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'controls_padding',
			[
				'label' => esc_html__('Padding', 'pe-core'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .pe--portfolio--controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'controls_margin',
			[
				'label' => esc_html__('Margin', 'pe-core'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .pe--portfolio--controls' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'filters_styles',
			[
				'label' => esc_html__('Filters Styles', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'portfolio_style!' => 'grouped',

				]
			]
		);

		flexOptions($this, false, '.pe--portfolio--filters', 'filters', 'Filters');
		popupOptions($this, ['filters_style' => 'popup']);


		$this->end_controls_section();

		$this->start_controls_section(
			'filters_popup_styles_sec',
			[
				'label' => esc_html__('Filters Popup Styles', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['filters_style' => 'popup'],
			]
		);

		objectStyles($this, 'filters_button', 'Popup Button', '.filters--button.pe--pop--button', false, false, false, false);
		popupStyles($this, ['filters_style' => 'popup']);
		$this->end_controls_section();





		objectStyles(
			$this,
			'paginations_styles',
			'Paginations',
			'.portfolios--pagination.pagination--paged .pe--styled--object ',
			true,
			['portfolio_style!' => 'grouped']
			,
			true,
			false,
			true
		);



		$this->start_controls_section(
			'style_switcher_styles',
			[
				'label' => esc_html__('Style Switcher Styles', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['style_switcher' => 'true'],
			]
		);

		$this->add_responsive_control(
			'ss_use_secondary_font',
			[
				'label' => esc_html__('Use Secondary Font', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'true',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pe--portfolio--style--switcher ul li' => '
						font-family: var(--sec_typo-font-family);
						font-size: var(--sec_typo-font-size);
						line-height: var(--sec_typo-line-height);
						letter-spacing: var(--sec_typo-letter-spacing);
						font-weight: var(--sec_typo-font-weight);
				   text-transform: var(--sec_typo-text-transform);',

				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'switchers_typography',
				'selector' => '{{WRAPPER}} .pe--portfolio--style--switcher ul li',
			]
		);

		$this->add_control(
			'switchers_sperator',
			[
				'label' => esc_html__('Seperator', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Eg: /', 'pe-core'),
				'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
				'condition' => [
					'style_switcher' => 'true',
				],
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Title Typography (for Grid view)', 'pe-core'),
				'name' => 'grid_title_typography',
				'condition' => [
					'switcher_styles' => 'grid',
				],
				'selector' => '{{WRAPPER}}.portfolio--style--grid .project--title',
			]
		);



		$this->end_controls_section();

		pe_button_style_settings($this, 'Load More Button', 'load_more_button', ['pagination_style' => 'ajax-load-more',]);
		pe_color_options($this);
	}

	protected function render()
	{
		$settings = $this->get_settings();
		$style = $settings['portfolio_style'];
		$queryArgs = zeyna_project_query_args(($this));




		if (!empty($_REQUEST['sfilters'])) {
			$filters = $_REQUEST['sfilters'];
			$taxQuery = [
				'relation' => 'AND',
			];


			if (!empty($filters['keyword'])) {
				$keyword = sanitize_text_field($filters['keyword']);
				$queryArgs['s'] = $keyword;
				unset($filters['keyword']); // keyword'ü taksonomi olarak işlemeyelim
			}


			foreach ($filters as $taxonomy => $termIds) {
				if (!is_array($termIds)) {
					$termIds = [$termIds];
				}

				$taxQuery[] = [
					'taxonomy' => sanitize_text_field($taxonomy),
					'field' => 'id',
					'terms' => array_map('intval', $termIds),
					'operator' => 'IN'
				];
			}


			if (count($taxQuery) > 1) {
				$queryArgs['tax_query'] = $taxQuery;
			}
		}


		if ($settings['is_archive'] === 'true' && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
			global $wp_query;
			$loop = $wp_query;
		} else {
			$loop = new \WP_Query($queryArgs);
		}


		$highlighted = [];
		if ($settings['highlight_projects'] === 'yes') {

			if ($settings['highlight_by'] === 'project') {

				$highlightSelection = is_array($settings['highlighted_projects']) ? $settings['highlighted_projects'] : [$settings['highlighted_projects']];

				foreach ($highlightSelection as $highlight) {
					$highlighted[] = $highlight;
				}

			} else if ($settings['highlight_by'] === 'key') {

				$keys = explode(",", $settings['highlight_keys']);

				foreach ($keys as $highlitedKey) {
					$highlighted[] = $highlitedKey;
				}
			}

		}

		$data_args = htmlspecialchars(json_encode(zeyna_project_query_args($this)), ENT_QUOTES, 'UTF-8');
		$cursor = pe_cursor($settings, $this);


		?>


		<div data-style="<?php echo esc_attr($style) ?>" data-found="<?php echo esc_attr($loop->found_posts) ?>"
			data-query-args="<?php echo $data_args ?>"
			class="pe--portfolio anim-multiple query--<?php echo $settings['query_id'] ?>" <?php echo pe_general_animation($this) ?>>

			<div class="pe--portfolio--controls">

				<?php if ($settings['filters'] === 'true') {

					if ($settings['filters_style'] === 'block' || $settings['filters_style'] === 'sidebar') {

						pe_portfolio_filters($settings, $loop);

					} else if ($settings['filters_style'] === 'dropdown' || $settings['filters_style'] === 'popup') { ?>

							<div class="filters--button pe--styled--object pe--pop--button">
							<?php echo file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/filter.svg'); ?>
								Filter

							</div>

					<?php }


				} ?>

				<?php if ($settings['style_switcher'] === 'true') { ?>
					<div class="pe--portfolio--style--switcher">

						<ul class="pe--switcher switcher--styles">
							<?php


							$x = 0;
							foreach ($settings['switcher_styles'] as $switcherStyle) {
								$x++;



								if ($settings['switcher_buttons'] === 'text') {
									$object = $settings[$switcherStyle . '_text'];
								} else {
									ob_start();
									\Elementor\Icons_Manager::render_icon($settings[$switcherStyle . '_icon'], ['aria-hidden' => 'true']);
									$object = ob_get_clean();
								}
								$active = $style === $switcherStyle ? 'active' : '';
								?>

								<li style="<?php echo esc_attr($switcherStyle) ?>"
									class="switch--item pe--styled--object <?php echo esc_attr($switcherStyle . ' ' . $active) ?>">
									<?php echo $object;
									if (!empty($settings['switchers_sperator']) && (count(($settings['switcher_styles'])) !== $x)) {
										echo '<span class="switchers--seperator">' . $settings['switchers_sperator'] . '</span>';
									} ?>
								</li>
							<?php } ?>
						</ul>
					</div>

				<?php } ?>

				<?php if ($settings['grid_style_switcher'] === 'true') { ?>
					<div class="pe--portfolio--style--switcher grid--switcher">

						<ul class="pe--switcher switcher--styles">
							<?php foreach ($settings['grid_switch_columns'] as $key => $col) {

								if ($col == $settings['grid_columns']['size']) {
									$act = 'switch--active';
								} else {
									$act = '';
								}

								$svgPath = plugin_dir_path(__FILE__) . '../assets/img/grid-col-' . $col . '.svg';
								$icon = file_get_contents($svgPath);

								echo '<li data-switch-cols="' . $col . '" class="switch--item ' . $col . '--col ' . $act . '">' . $icon . '</li>';

							} ?>
						</ul>
					</div>

				<?php } ?>


			</div>

			<?php if ($settings['filters'] === 'true') {

				if ($settings['filters_style'] === 'dropdown' || $settings['filters_style'] === 'popup') {

					if ($settings['filters_style'] === 'popup') { ?>
						<?php if ($settings['back_overlay'] === 'true') { ?>
							<span class="pop--overlay"></span>
						<?php } ?>

						<div class="pe--filters--popup pe--styled--popup">

							<span class="pop--close">

								<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
									<path
										d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
								</svg>

							</span>

							<?php pe_portfolio_filters($settings, $loop); ?>

						</div>

					<?php } else {
						pe_portfolio_filters($settings, $loop);
					}



				}
			}
			?>

			<div class="portfolio--projects--wrapper">

				<?php


				if ($style === 'grouped') {

					$args = array(
						'hide_empty' => true,
						'taxonomy' => 'project-categories'
					);
					$categories = !empty($settings['projects_group_cats']) ? $settings['projects_group_cats'] : get_categories($args);

					foreach ($categories as $key => $category) {


						$args = zeyna_project_query_args(($this));

						if (!empty($settings['projects_group_cats'])) {
							$category = get_term($category['projects_group_cats_select'], 'project-categories');
						}

						$taxQuery = [];
						$taxQuery[] = [
							'taxonomy' => 'project-categories',
							'field' => 'id',
							'terms' => [$category->term_id],
						];
						$args['tax_query'] = $taxQuery;



						$loop = new \WP_Query($args);
						?>

						<div data-count="<?php echo esc_attr($category->count) ?>"
							data-categroy-id="<?php echo esc_attr($category->term_id) ?>"
							class="portfolio--projects--group group--<?php echo esc_attr($category->slug) ?>">
							<div class="projects--group--title">
								<?php echo '<h4>' . esc_html($category->name) . '<span class="group--count">(' . esc_html($category->count) . ')</span>' . '</h4>'; ?>
							</div>
							<div class="projects--group--wrapper">

								<?php
								while ($loop->have_posts()):
									$loop->the_post();
									zeyna_project_render($this, '', $cursor);
								endwhile;
								wp_reset_query();
								?>

							</div>
						</div> <?php }
				} else {

					$masonryItem = '';
					if ($style === 'masonry') {
						echo '<span class="zeyna--projects--masonry--sizer"></span>';
						echo '<span class="zeyna--projects--masonry--gutter"></span>';
						$masonryItem = 'project--masonry--item';
					}
					;

					$index = 0;

					while ($loop->have_posts()):
						$loop->the_post();
						$index++;
						$isHighlighted = in_array(get_the_ID(), $highlighted) || in_array($index, $highlighted) ? 'project--highlighted' : '';
						$classes = $isHighlighted . ' inner--anim';

						if ($style !== 'list') {
							zeyna_project_render($this, $classes, $cursor);
						} else {
							zeyna_project_list_render($this, $classes, $cursor, $index);
						}

					endwhile;
					wp_reset_query();

				}

				if ($settings['spacings'] && $settings['grid_spacings'] === 'yes') {

					foreach ($settings['spacings'] as $span) {
						echo '<span style="grid-area:' . $span['spacing_col'] . '/' . $span['spacing_row'] . '" class="grid-template-area elementor-repeater-item-' . $span['_id'] . '"></span>';
					}

				} ?>


			</div>

			<?php if ($settings['pagination_style'] !== 'none') { ?>

				<div class="portfolios--pagination pagination--<?php echo $settings['pagination_style'] ?>">

					<?php if ($settings['pagination_style'] === 'ajax-load-more') { ?>
						<div class="portfolios--load--more">
							<?php pe_button_render($this); ?>
						</div>
					<?php } else if ($settings['pagination_style'] === 'infinite-scroll') {

						echo '<span class="ajax-infinite-scroll" hidden></span>';

					} else if ($settings['pagination_style'] === 'paged') {

						if ($settings['paged_style'] === 'text') {

							$next = !empty($settings['pagination_next_text']) ? $settings['pagination_next_text'] : __('Next »');
							$prev = !empty($settings['pagination_prev_text']) ? $settings['pagination_prev_text'] : __('« Prev');

						} else {

							$next = file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/arrow_forward.svg');
							$prev = file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/arrow_back.svg');

							if (!empty($settings['pagination_prev_icon']['value'])) {
								ob_start();
								\Elementor\Icons_Manager::render_icon($settings['pagination_prev_icon'], ['aria-hidden' => 'true']);
								$prev = ob_get_clean();
							}

							if (!empty($settings['pagination_next_icon']['value'])) {
								ob_start();
								\Elementor\Icons_Manager::render_icon($settings['pagination_next_icon'], ['aria-hidden' => 'true']);
								$next = ob_get_clean();
							}

						}

						$paged = max(1, get_query_var('paged'));

						$pagination = paginate_links(array(
							'base' => get_pagenum_link(1) . '%_%',
							'format' => 'page/%#%/',
							'current' => $paged,
							'total' => $loop->max_num_pages,
							'prev_text' => $prev,
							'next_text' => $next,
							'mid_size' => 2,
							'end_size' => 1,
							'add_args' => false,
							'add_fragment' => '',
						));

						if ($pagination) {

							$pagination = str_replace('<a', '<a class="pe--styled--object"', $pagination);
							$pagination = str_replace('class="page-numbers current"', 'class="page-link pe--styled--object current"', $pagination);
						}

						echo $pagination;
					} ?>


				</div>
			<?php } ?>

			<?php
			if ($style === 'list' && ($settings['portfolio_images_style'] === 'hover' || $settings['portfolio_images_style'] === 'fullscreen')) { ?>

				<div class="portfolio--list--images--wrap">
					<div class="portfolio--list--images--inner--wrap">

						<?php

						$imageIndex = 0;

						while ($loop->have_posts()):
							$loop->the_post();
							$imageIndex++; ?>

							<div class="portfolio--list--image list--image_<?php echo $index; ?> image__<?php echo get_the_ID() ?>">

								<?php pe_project_image(get_the_ID(), false, false); ?>

							</div>

						<?php endwhile;
						wp_reset_query(); ?>


					</div>
				</div>


			<?php }


			?>


		</div>


		<?php
	}
}
