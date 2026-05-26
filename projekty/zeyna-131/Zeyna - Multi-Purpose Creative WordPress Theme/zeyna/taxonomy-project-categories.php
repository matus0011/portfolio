<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zeyna
 */

get_header();

if (class_exists("Redux")) {
    $option = get_option('pe-redux');
}


?>

<main id="primary" class="site-main" <?php echo zeyna_barba(false) ?>>
    <?php if (isset($option['portfolio_archive_template']) && !empty($option['portfolio_archive_template'])) {

        $id = $option['portfolio_archive_template'];

        if (function_exists('icl_object_id')) {
            $id = icl_object_id($id, 'elementor_library', true, ICL_LANGUAGE_CODE);
        }

        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($id);

    } else { ?>

        <div class="pe-section">

            <div class="pe-wrapper">

                <div class="pe-col-12">

                    <?php if (have_posts()): ?>

                        <?php
                        the_archive_title('<h2 class="page-title">', '</h2>');
                        the_archive_description('<div class="archive-description">', '</div>');
                        ?>

                        <div class="pe-archive-grid">

                            <div class="pe-archive-grid-sizer"></div>
                            <div class="pe-archive-grid-gutter"></div>

                            <?php
                            /* Start the Loop */
                            while (have_posts()):
                                the_post();

                                /*
                                 * Include the Post-Type-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                 */
                                get_template_part('template-parts/content', get_post_type());

                            endwhile;

                            ?>

                        </div>

                        <?php zeyna_posts_nav();

                    else:

                        get_template_part('template-parts/content', 'none');

                    endif;
                    ?>

                </div>
            </div>



        </div>
    <?php } ?>
</main><!-- #main -->

<?php

get_footer();
