<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class peProjectMedia extends Widget_Base
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
		return 'projectmedia';
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
		return __('Project Media', 'pe-core');
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
		return 'eicon-featured-image pe-widget';
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


		// Tab Title Control
		$this->start_controls_section(
			'section_tab_title',
			[
				'label' => __('Project Media', 'pe-core'),
			]
		);

		$this->add_control(
			'media_type',
			[
				'label' => esc_html__('Media Type', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'featured',
				'options' => [
					'featured' => esc_html__('Featured Image/Video', 'pe-core'),
					'video-player' => esc_html__('Video With Player', 'pe-core'),
					'gallery' => esc_html__('Image/Video Gallery', 'pe-core'),
					'slider' => esc_html__('Images Slider', 'pe-core'),
				],

			]
		);

		pe_slider_settings($this, false, ['media_type' => 'slider'], 'project_');
		pe_video_settings($this, 'media_type', 'video-player', '', true);



		$this->add_control(
			'parallax',
			[
				'label' => esc_html__('Parallax Image', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'parallax--image',
				'default' => '',
				'condition' => ['media_type' => 'featured'],
			]
		);

		$this->add_control(
			'gallery_id',
			[
				'label' => esc_html__('Gallery ID', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => false,
				'placeholder' => esc_html__('Eg: project_gallery', 'pe-core'),
				'description' => esc_html__('Required when adding control widgets.', 'pe-core'),
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->add_control(
			'gallery_type',
			[
				'label' => esc_html__('Gallery Type', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'pr--gallery--normal',
				'options' => [
					'pr--gallery--normal' => esc_html__('Normal', 'pe-core'),
					'pr--gallery--lightbox' => esc_html__('Lightbox', 'pe-core'),
				],
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->add_control(
			'gallery_direction',
			[
				'label' => esc_html__('Direction', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'pr--gallery--horizontal',
				'render_types' => 'template',
				'prefix_class' => '',
				'options' => [
					'pr--gallery--horizontal' => esc_html__('Horizontal', 'pe-core'),
					'pr--gallery--vertical' => esc_html__('Vertical', 'pe-core'),
				],
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->add_control(
			'gallery_behavior',
			[
				'label' => esc_html__('Gallery Behavior', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'drag',
				'prefix_class' => 'cr--',
				'options' => [
					'scroll' => esc_html__('Scroll', 'pe-core'),
					'drag' => esc_html__('Drag', 'pe-core'),
				],
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->add_control(
			'pin_target',
			[
				'label' => esc_html__('Pin Target', 'pe-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => false,
				'placeholder' => esc_html__('Eg: #mainContainer', 'pe-core'),
				'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),
				'condition' => [
					'gallery_behavior' => 'scroll',
					'media_type' => 'gallery',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__('Scroll Speed', 'pe-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'step' => 100,
				'default' => 5000,
				'condition' => [
					'gallery_behavior' => 'scroll',
					'media_type' => 'gallery',
				],
			]
		);


		$this->add_control(
			'integrated_class',
			[
				'label' => esc_html__('Integrated Elements Class', 'pe-core'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => false,
				'placeholder' => esc_html__('Eg: .details_container', 'pe-core'),
				'description' => esc_html__('The targeted elements will be fade out on gallery moves.', 'pe-core'),
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__('Width', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
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
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['media_type' => 'featured'],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__('Height', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh', 'vw', 'custom'],
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
				],
				'selectors' => [
					'{{WRAPPER}} .project-featured-image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['media_type' => 'featured'],
			]
		);

		$this->add_responsive_control(
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
					'justify' => [
						'title' => esc_html__('Justify', 'pe-core'),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => is_rtl() ? 'right' : 'left',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'condition' => ['media_type' => 'featured'],
			]
		);

		$this->add_control(
			'get_data',
			[
				'label' => esc_html__('Get Data From', 'pe-core'),
				'description' => esc_html__('You can select "Next/Prev project when creating project paginations." ', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'current',
				'options' => [
					'current' => esc_html__('Current Project', 'pe-core'),
					'next' => esc_html__('Next Project', 'pe-core'),
					'prev' => esc_html__('Previous Project', 'pe-core'),

				],
				'label_block' => false,
			]
		);

		$this->add_control(
			'linked',
			[
				'label' => esc_html__('Linked?', 'pe-core'),
				'description' => esc_html__('Image will be linked to the project.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'pe-core'),
				'label_off' => esc_html__('No', 'pe-core'),
				'return_value' => 'yes',
				'default' => '',

			]
		);

		$this->add_control(
			'project_link_behavior',
			[
				'label' => esc_html__('Link Behavior', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'normal',
				'prefix_class' => 'project--link--',
				'options' => [
					'normal' => esc_html__('Normal', 'pe-core'),
					'scroll' => esc_html__('Scroll to Go', 'pe-core'),
				],
				'label_block' => false,
			]
		);

		pe_image_hover_settings($this);


		$this->end_controls_section();

		pe_slider_style_settings($this, true, ['media_type' => 'slider'], 'project_');

		$this->start_controls_section(
			'additonal_options',
			[

				'label' => esc_html__('Additional Options', 'pe-core'),

			]
		);

		$options = [];

		$products = get_posts([
			'post_type' => 'portfolio',
			'numberposts' => -1
		]);

		foreach ($products as $product) {
			$options[$product->ID] = $product->post_title;
		}
		$this->add_control(
			'preview_product',
			[
				'label' => __('Preview Product', 'pe-core'),
				'label_block' => true,
				'description' => __('Select product to preview.', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $options,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'media_styles',
			[
				'label' => esc_html__('Styles', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['media_type!' => 'video-player'],
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .project-featured-image , {{WRAPPER}} .project--image--gallery--wrapper > div',
			]
		);



		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('Border Radius', 'pe-core'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'condition' => ['media_type!' => 'video-player'],
				'selectors' => [
					'{{WRAPPER}} .project-featured-image' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
					'{{WRAPPER}} .pe-video' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
					'{{WRAPPER}} .project--image--gallery--wrapper > div' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_height',
			[
				'label' => esc_html__('Gallery Height', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh'],
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
				],
				'selectors' => [
					'{{WRAPPER}} .project--image--gallery' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->add_responsive_control(
			'items_width',
			[
				'label' => esc_html__('Items Width', 'pe-core'),
				'description' => esc_html__('Leave it empty if you want to dispaly images with variable widths.', 'pe-core'),
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
					'vh' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .project--gallery--image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .project--archive--gallery--image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->add_responsive_control(
			'items_gap',
			[
				'label' => esc_html__('Items Gap', 'pe-core'),
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
					'vh' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .project--image--gallery--wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['media_type' => 'gallery'],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'player_styles',
			[
				'label' => esc_html__('Player Styles', 'pe-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['media_type' => 'video-player'],
			]
		);

		$this->add_responsive_control(
			'player_width',
			[
				'label' => esc_html__('Width', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['vh', 'vw', 'px', '%'],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				],
				'selectors' => [
					'{{WRAPPER}} .pe-video' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'player_custom_height',
			[
				'label' => esc_html__('Height', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['vw', 'vh', 'px', '%'],
				'render_type' => 'template',
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
					'vw' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pe-video' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'player_main_color',
			[
				'label' => esc_html__('Main Skin Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pe-video' => '--plyr-color-main: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'player_secondary_color',
			[
				'label' => esc_html__('Secondary Skin Color', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pe-video' => '--plyr-color-secondary: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'player_progress_bg',
			[
				'label' => esc_html__('Progress Buffered Background', 'pe-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pe-video' => '--plyr-video-progress-buffered-background: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'player_controls_spacing',
			[
				'label' => esc_html__('Controls Spacing', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 250,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pe-video' => '--plyr-control-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'player_controls_font-size',
			[
				'label' => esc_html__('Controls Font Size', 'pe-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pe-video' => '--plyr-font-size-small: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'player_border_radius',
			[
				'label' => esc_html__('Border Radius', 'pe-core'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .pe-video' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'player_css_filters',
				'selector' => '{{WRAPPER}} .pe-video',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'play_text_typography',
				'label' => esc_html__('Play Text Typography', 'pe-core'),
				'selector' => '{{WRAPPER}} .pe--play',
				'condition' => [
					'play_button' => ['text'],
					'controls' => ['true']
				],
			]
		);

		objectAbsolutePositioning($this, '.pe--large--play', 'play_button', 'Play Button/Text');

		$this->end_controls_section();
		pe_svg_mask_settings($this, true, '', '.project-featured-image');

		pe_cursor_settings($this, true);
		pe_general_animation_settings($this);

	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$loop = new \WP_Query([
			'post_type' => 'portfolio',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'order' => 'ASC',
			'order_by' => 'date',
			'post__in' => $settings['preview_product'] && \Elementor\Plugin::$instance->editor->is_edit_mode() ? array($settings['preview_product']) : [],
		]);
		wp_reset_postdata();


		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
			while ($loop->have_posts()):
				$loop->the_post();
				$id = get_the_ID();
			endwhile;
			wp_reset_query();
		} else {
			global $wp_query;
			$id = $wp_query->post->ID;
			$previous_post = get_previous_post();
			$next_post = get_next_post();

			if ($settings['get_data'] === 'next') {

				if (!$next_post) {
					while ($loop->have_posts()):
						$loop->the_post();
						$id = get_the_ID();
					endwhile;
				} else {
					$id = $next_post->ID;
				}

			} else if ($settings['get_data'] === 'prev') {
				$id = $previous_post->ID;
			}

		}

		$type = $settings['media_type'];

		if ($settings['get_data'] === 'current') {
			$classes = 'p--featured featured__' . $id;
		} else {
			$classes = 'project__image__' . $id;
		}



		?>

		<?php if ($type === 'featured') { ?>

			<?php if ($settings['linked'] === 'yes') { ?>

				<a <?php echo pe_cursor($settings, $this) ?> data-id="<?php echo esc_attr($id) ?>" class="barba--trigger"
					href="<?php echo esc_url(get_the_permalink($id)) ?>">

				<?php } ?>

				<div <?php echo pe_image_hover($this); ?>
					class="project-featured-image <?php echo esc_attr($classes . ' ' . $settings['parallax']) ?>" <?php echo pe_general_animation($this) ?>>

					<?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
						while ($loop->have_posts()):
							$loop->the_post();

							pe_project_image(get_the_ID(), false, false);

						endwhile;
						wp_reset_query();

					} else {

						pe_project_image($id, false, false);
					}

					?>
				</div>

				<?php if ($settings['linked'] === 'yes') { ?>
				</a>
			<?php } ?>

		<?php } else if ($type === 'gallery') { ?>

				<div class="project--image--gallery anim-multiple <?php echo $settings['gallery_type'] ?>" <?php echo pe_general_animation($this) ?> data-trigger="<?php echo esc_attr($settings['pin_target']) ?>"
					data-speed="<?php echo esc_attr($settings['speed']) ?>"
					data-integrated="<?php echo esc_attr($settings['integrated_class']) ?>">

					<div <?php echo pe_cursor($settings, $this) ?>
						class="project--image--gallery--wrapper <?php echo esc_attr($settings['gallery_id']) ?>"
						data-id="<?php echo esc_attr($settings['gallery_id']) ?>">

					<?php if (get_field('image', $id)) { ?>
							<div data-index="0"
								class="project--gallery--image p--featured cr--item  <?php echo esc_attr('featured__' . $id) ?>">

							<?php $image = get_field('image', $id);

							$image_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
							$image_title = get_the_title($image);
							?>

								<img src="<?php echo wp_get_attachment_image_url($image, 'full') ?>" alt="<?php echo $image_alt; ?>" title="<?php
									   echo $image_title; ?>">

							</div>
					<?php } ?>

					<?php if (get_field('project_gallery', $id)) {

						$gallery_ids = get_field('project_gallery', $id); ?>

							<?php
							foreach ($gallery_ids as $imageId) {
								echo '<div class=" project--archive--gallery--image cr--item inner--anim">';
								echo wp_get_attachment_image($imageId, 'medium-large');
								echo '</div>';
							} ?>

					<?php } ?>
					</div>

				</div>



		<?php } else if ($type === 'video-player') {

			$provider = get_field('ph_video_provider', $id);
			$self_video = get_field('ph_Self_video', $id);
			$video_id = get_field('ph_video_id', $id);
			$poster_image = get_field('ph_video_cover', $id) ? wp_get_attachment_image(get_field('ph_video_cover', $id)['ID'], 'medium-large') : '';

			$atributes = [
				'provider' => $provider,
				'self_video' => $self_video,
				'video_id' => $video_id,
				'poster_image' => $poster_image,
			];

			echo pe_video_render($this, false, $atributes);

			?>


		<?php } else if ($type === 'slider') {
			$arr = [];
			if (get_field('image', $id)) {
				ob_start();
				?>
							<div data-index="0" class="project--gallery--image p--featured  <?php echo esc_attr('featured__' . $id) ?>">

					<?php $image = get_field('image', $id);

					$image_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
					$image_title = get_the_title($image);
					?>

								<img src="<?php echo wp_get_attachment_image_url($image, 'full') ?>" alt="<?php echo $image_alt; ?>" title="<?php
									   echo $image_title; ?>">

							</div>
					<?php

					$html1 = ob_get_clean();
					$arr[] = $html1;
			}


			if (get_field('project_gallery', $id)) {

				$gallery_ids = get_field('project_gallery', $id); ?>

					<?php
					foreach ($gallery_ids as $imageId) {
						ob_start();
						echo '<div class="inner--anim project--archive--gallery--image cr--item">';
						echo wp_get_attachment_image($imageId, 'medium-large');
						echo '</div>';
						$html = ob_get_clean();
						$arr[] = $html;
					} ?>

			<?php }

			echo pe_slider_render($this, $arr, 'project_');


		}

	}

}
