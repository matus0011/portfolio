<?php

namespace BrandberryEssentialApp\Widgets\BrandberryPortfolio\Skin;

use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Brandberry Portfolio Three (Brave)
 *
 * Renders the Brave/Webflow portfolio section with real Grid/List tabs.
 * The associated Webflow CSS is scoped to `.bb-portfolio--brave`.
 */
class Skin_Brandberry_Portfolio_Three extends Skin_Portfolio_Base {

	public function get_id() {
		return 'skin-brandberry-portfolio-three';
	}

	public function get_title() {
		return __( 'Brandberry Portfolio Three', 'brandberry' );
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;
	}

	/**
	 * Collect post data once so we can render both panes without rewinding the query.
	 */
	protected function get_posts_data(): array {
		$query = $this->parent->get_query();
		if ( ! $query || ! $query->have_posts() ) {
			return [];
		}

		$taxonomy = $this->parent->get_settings( 'post_taxonomy' );
		// Back-compat: older widget instances may have stored the taxonomy under a different key.
		if ( empty( $taxonomy ) ) {
			$taxonomy = $this->parent->get_settings( 'taxonomy' );
		}
		// If still empty or invalid, fall back to the first taxonomy registered for the queried post type.
		if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
			$post_type = $query->get( 'post_type' );
			if ( is_array( $post_type ) ) {
				$post_type = reset( $post_type );
			}
			if ( empty( $post_type ) ) {
				$post_type = get_post_type();
			}
			$taxes = $post_type ? get_object_taxonomies( $post_type, 'names' ) : [];
			$taxes = is_array( $taxes ) ? array_values( $taxes ) : [];
			if ( ! empty( $taxes ) ) {
				$taxonomy = $taxes[0];
			}
		}
		$thumb_size = $this->parent->get_settings( 'thumbnail_size' );
		if ( empty( $thumb_size ) ) {
			$thumb_size = 'large';
		}

		// Taxonomy candidates for smart fallback.
		$post_type = $query->get( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		if ( empty( $post_type ) ) {
			$post_type = get_post_type();
		}
		$tax_candidates = $post_type ? get_object_taxonomies( $post_type, 'names' ) : [];
		$tax_candidates = is_array( $tax_candidates ) ? array_values( $tax_candidates ) : [];
		// Keep the user-selected taxonomy first.
		if ( ! empty( $taxonomy ) ) {
			$tax_candidates = array_values( array_unique( array_merge( [ $taxonomy ], $tax_candidates ) ) );
		}

		$items = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$terms = ( $taxonomy && taxonomy_exists( $taxonomy ) ) ? get_the_terms( get_the_ID(), $taxonomy ) : [];
			if ( is_wp_error( $terms ) ) {
				$terms = [];
			}
			$terms = is_array( $terms ) ? array_values( $terms ) : [];

			// Smart fallback: if the chosen taxonomy has no terms, try other taxonomies attached to this post type
			// and pick the first one that actually has terms.
			if ( empty( $terms ) && ! empty( $tax_candidates ) ) {
				foreach ( $tax_candidates as $tax_name ) {
					if ( empty( $tax_name ) || ! taxonomy_exists( $tax_name ) ) {
						continue;
					}
					$maybe_terms = get_the_terms( get_the_ID(), $tax_name );
					if ( is_wp_error( $maybe_terms ) || empty( $maybe_terms ) ) {
						continue;
					}
					$terms = array_values( $maybe_terms );
					break;
				}
			}
			$thumb_id = get_post_thumbnail_id( get_the_ID() );
			$grid_img = $thumb_id ? wp_get_attachment_image( $thumb_id, $thumb_size, false, [
				'class'   => 'project-thumbnail',
				'loading' => 'lazy',
			] ) : '<img loading="lazy" class="project-thumbnail" alt="" src="' . esc_url( Utils::get_placeholder_image_src() ) . '">';

			$list_img_hidden = $thumb_id ? wp_get_attachment_image( $thumb_id, $thumb_size, false, [
				'class'   => 'projects-list-image opacity-zero',
				'loading' => 'eager',
			] ) : '<img loading="eager" class="projects-list-image opacity-zero" alt="" src="' . esc_url( Utils::get_placeholder_image_src() ) . '">';

			$list_img_main = $thumb_id ? wp_get_attachment_image( $thumb_id, $thumb_size, false, [
				'class'   => 'projects-list-image',
				'loading' => 'eager',
			] ) : '<img loading="eager" class="projects-list-image" alt="" src="' . esc_url( Utils::get_placeholder_image_src() ) . '">';

			$items[] = [
				'id'        => get_the_ID(),
				'title'     => get_the_title(),
				'url'       => get_the_permalink(),
				'terms'     => $terms,
				'grid_image_html'      => $grid_img,
				'list_image_hidden_html' => $list_img_hidden,
				'list_image_main_html'   => $list_img_main,
			];
		}
		wp_reset_postdata();
		return $items;
	}

	protected function render_terms_badges( array $terms, int $max = 2 ): void {
		$printed = 0;
		foreach ( $terms as $term ) {
			if ( ! $term || empty( $term->name ) ) {
				continue;
			}
			echo '<div class="project-label"><div class="project-label-text">' . esc_html( $term->name ) . '</div></div>';
			$printed++;
			if ( $printed >= $max ) {
				break;
			}
		}
	}

	protected function render_terms_inline( array $terms, int $max = 2 ): void {
		$names = [];
		foreach ( $terms as $term ) {
			if ( $term && ! empty( $term->name ) ) {
				$names[] = $term->name;
			}
			if ( count( $names ) >= $max ) {
				break;
			}
		}
		if ( empty( $names ) ) {
			return;
		}
		echo '<div class="projects-list-categories">';
		for ( $i = 0; $i < count( $names ); $i++ ) {
			if ( $i > 0 ) {
				echo '<div class="projects-list-comma">,</div>';
			}
			echo '<div>' . esc_html( $names[ $i ] ) . '</div>';
		}
		echo '</div>';
	}

	protected function render_grid_item( array $item ): void {
		$show_title      = ( 'yes' === $this->parent->get_settings( 'show_title' ) );
		$show_categories = ( 'yes' === $this->parent->get_settings( 'show_categories' ) );
		$show_arrow      = ( 'yes' === $this->parent->get_settings( 'show_arrow' ) );
		$grid_style      = $this->parent->get_settings( 'bb_brave_grid_style' );
		if ( empty( $grid_style ) ) {
			$grid_style = 'style_1';
		}
		?>
		<?php if ( 'style_2' === $grid_style || 'style_3' === $grid_style ) : ?>
			<a aria-label="" href="<?php echo esc_url( $item['url'] ); ?>" class="project-link w-inline-block bb-grid-link <?php echo esc_attr( $grid_style ); ?>">
				<div class="bb-grid-media">
					<?php echo $item['grid_image_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( 'style_2' === $grid_style && $show_categories ) : ?>
						<div class="bb-grid-cats-top">
							<div class="project-content-footer">
								<?php $this->render_terms_badges( $item['terms'], 2 ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="bb-grid-below">
					<div class="bb-grid-below-header">
						<?php if ( $show_title ) : ?>
							<div class="project-title-wrapper">
								<div class="project-title-front">
									<h4 class="project-title"><?php echo esc_html( $item['title'] ); ?></h4>
								</div>
								<div aria-hidden="true" class="project-title-back">
									<div class="project-title"><?php echo esc_html( $item['title'] ); ?></div>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( $show_arrow ) : ?>
							<div class="project-arrow">
								<div class="project-arrow-inner">
									<div class="project-arrow-front"><img loading="lazy" src="<?php echo esc_url( BRANDBERRY_ESSENTIAL_ASSETS_URL . 'images/treethemes/arrow-up-right.svg' ); ?>" alt="" class="project-arrow-image"></div>
									<div class="project-arrow-back"><img loading="lazy" src="<?php echo esc_url( BRANDBERRY_ESSENTIAL_ASSETS_URL . 'images/treethemes/arrow-up-right.svg' ); ?>" alt="" class="project-arrow-image"></div>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( 'style_3' === $grid_style && $show_categories ) : ?>
						<div class="bb-grid-below-cats">
							<div class="project-content-footer">
								<?php $this->render_terms_badges( $item['terms'], 2 ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</a>
		<?php else : ?>
			<a aria-label="" href="<?php echo esc_url( $item['url'] ); ?>" class="project-link w-inline-block">
				<?php echo $item['grid_image_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="project-content">
					<div class="project-content-header">
						<?php if ( $show_title ) : ?>
						<div class="project-title-wrapper">
							<div class="project-title-front">
								<h4 class="project-title"><?php echo esc_html( $item['title'] ); ?></h4>
							</div>
							<div aria-hidden="true" class="project-title-back">
								<div class="project-title"><?php echo esc_html( $item['title'] ); ?></div>
							</div>
						</div>
						<?php endif; ?>
						<?php if ( $show_arrow ) : ?>
						<div class="project-arrow">
							<div class="project-arrow-inner">
							<div class="project-arrow-front"><img loading="lazy" src="<?php echo esc_url( BRANDBERRY_ESSENTIAL_ASSETS_URL . 'images/treethemes/arrow-up-right.svg' ); ?>" alt="" class="project-arrow-image"></div>
							<div class="project-arrow-back"><img loading="lazy" src="<?php echo esc_url( BRANDBERRY_ESSENTIAL_ASSETS_URL . 'images/treethemes/arrow-up-right.svg' ); ?>" alt="" class="project-arrow-image"></div>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<div class="project-content-footer">
						<?php if ( $show_categories ) { $this->render_terms_badges( $item['terms'], 2 ); } ?>
					</div>
				</div>
			</a>
		<?php endif; ?>
		<?php
	}

	protected function render_list_item( array $item ): void {
		$show_title      = ( 'yes' === $this->parent->get_settings( 'show_title' ) );
		$show_categories = ( 'yes' === $this->parent->get_settings( 'show_categories' ) );
		$show_arrow      = ( 'yes' === $this->parent->get_settings( 'show_arrow' ) );
		?>
		<div class="projects-list-item">
			<div class="bb-brave-item-image" aria-hidden="true">
				<?php echo $item['list_image_hidden_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<a href="<?php echo esc_url( $item['url'] ); ?>" class="projects-list-link w-inline-block">
				<div class="divider"></div>
				<div class="projects-list-inner">
					<div class="w-layout-grid projects-list-columns">
						<div class="projects-list-column-first">
							<?php if ( $show_title ) : ?>
								<h4 class="projects-list-title"><?php echo esc_html( $item['title'] ); ?></h4>
							<?php endif; ?>
						</div>
						<div class="projects-list-text">
							<?php if ( $show_categories ) { $this->render_terms_inline( $item['terms'], 2 ); } ?>
						</div>
						<div class="projects-list-column-last">
							<?php if ( $show_arrow ) : ?>
								<div class="projects-list-arrow">
								<img loading="lazy" src="<?php echo esc_url( BRANDBERRY_ESSENTIAL_ASSETS_URL . 'images/treethemes/arrow-up-right.svg' ); ?>" alt="" class="projects-list-arrow-black">
								<img loading="lazy" src="<?php echo esc_url( BRANDBERRY_ESSENTIAL_ASSETS_URL . 'images/treethemes/arrow-up-right-white.svg' ); ?>" alt="" class="projects-list-arrow-white">
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="projects-list-hover"></div>
			</a>
		</div>
		<?php
	}

	public function render() {
		$items = $this->get_posts_data();
		if ( empty( $items ) ) {
			return;
		}

		$tabs_mode = $this->parent->get_settings( 'bb_brave_tabs_mode' );
		if ( empty( $tabs_mode ) ) {
			$tabs_mode = 'both';
		}
		$default_tab = $this->parent->get_settings( 'bb_brave_default_tab' );
		if ( empty( $default_tab ) ) {
			$default_tab = 'grid';
		}

		$caption = $this->parent->get_settings( 'bb_brave_caption' );
		$caption = is_string( $caption ) ? $caption : '';
		$caption_html = nl2br( esc_html( $caption ) );

		// Wrapper to scope CSS.
		$this->parent->add_render_attribute( 'bb_wrapper', 'class', [ 'bb-portfolio--brave', $this->get_id() ] );
		// The responsive Grid Columns control outputs CSS for `--bb-cols` via its selectors.
		// Avoid setting it inline here, otherwise it cannot be overridden per breakpoint.
		$this->parent->add_render_attribute( 'bb_wrapper', 'data-bb-tabs-mode', $tabs_mode );
		$this->parent->add_render_attribute( 'bb_wrapper', 'data-bb-default-tab', $default_tab );
		?>
		<div <?php $this->parent->print_render_attribute_string( 'bb_wrapper' ); ?>>
			<section class="section">
				<div class="w-layout-blockcontainer container-fluid w-container">
					<div class="w-layout-grid">
						<div class="section-header-caption">
							<div class="caption"><?php echo $caption_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						</div>
					</div>

					<div data-current="<?php echo esc_attr( 'grid' === $default_tab ? 'Tab 1' : 'Tab 2' ); ?>" class="portfolio-tabs w-tabs">
						<?php if ( 'both' === $tabs_mode ) : ?>
							<div class="portfolio-tabs-menu no-margin-top w-tab-menu">
								<a data-w-tab="Tab 1" class="portfolio-tab-link first w-inline-block w-tab-link <?php echo ( 'grid' === $default_tab ? 'w--current' : '' ); ?>">
									<div class="portfolio-tab-active">
										<div class="portfolio-tab-active-background"></div>
									</div>
									<div class="portfolio-tab-text">Grid</div>
								</a>
								<a data-w-tab="Tab 2" class="portfolio-tab-link second w-inline-block w-tab-link <?php echo ( 'list' === $default_tab ? 'w--current' : '' ); ?>">
									<div class="portfolio-tab-active">
										<div class="portfolio-tab-active-background"></div>
									</div>
									<div class="portfolio-tab-text">List</div>
								</a>
							</div>
						<?php endif; ?>

						<div class="tabs-content w-tab-content">
							<?php if ( 'grid' === $tabs_mode || 'both' === $tabs_mode ) : ?>
								<div data-w-tab="Tab 1" class="portfolio-tab-pane padding-smaller w-tab-pane <?php echo ( 'grid' === $default_tab || 'grid' === $tabs_mode ? 'w--tab-active' : '' ); ?>">
									<div class="portfolio-tab-inner">
										<div class="margin-bottom-large">
										<div class="w-layout-grid projects-grid">
												<?php foreach ( $items as $item ) { $this->render_grid_item( $item ); } ?>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( 'list' === $tabs_mode || 'both' === $tabs_mode ) : ?>
								<div data-w-tab="Tab 2" class="portfolio-tab-pane padding-smaller w-tab-pane <?php echo ( 'list' === $default_tab || 'list' === $tabs_mode ? 'w--tab-active' : '' ); ?>">
									<div class="portfolio-tab-inner">
										<div class="margin-bottom-large">
											<div class="position-relative">
													<div class="w-layout-grid">
														<div class="bb-brave-list-grid">
														<div class="projects-list-image-wrapper">
									<div class="projects-list-image-inner bb-brave-list-main">
																<?php
												// Default image (use first item image).
											echo $items[0]['list_image_main_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																?>
															</div>
														</div>
														<div class="projects-list">
															<?php foreach ( $items as $item ) { $this->render_list_item( $item ); } ?>
														</div>
														<div class="divider"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php
	}
}
