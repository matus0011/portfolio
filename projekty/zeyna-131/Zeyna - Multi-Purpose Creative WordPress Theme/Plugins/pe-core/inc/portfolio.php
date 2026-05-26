<?php

// Our custom post type function
function create_posttype()
{


}

// Hooking up our function to theme setup
add_action('init', 'create_posttype');

/*
 * Creating a function to create our CPT
 */

function portfolio_post_type()
{

    // Set UI labels for Custom Post Type
    $labels = array(
        'name' => _x('Portfolio', 'Post Type General Name', 'pe-core'),
        'singular_name' => _x('Project', 'Post Type Singular Name', 'pe-core'),
        'menu_name' => __('Portfolio', 'pe-core'),
        'parent_item_colon' => __('Parent Portfolio', 'pe-core'),
        'all_items' => __('All Projects', 'pe-core'),
        'view_item' => __('View Project', 'pe-core'),
        'add_new_item' => __('Add New Project', 'pe-core'),
        'add_new' => __('Add New', 'pe-core'),
        'edit_item' => __('Edit Project', 'pe-core'),
        'update_item' => __('Update Project', 'pe-core'),
        'search_items' => __('Search Project', 'pe-core'),
        'not_found' => __('Not Found', 'pe-core'),
        'not_found_in_trash' => __('Not found in Trash', 'pe-core'),

    );

    // Set other options for Custom Post Type

    $port_slug = 'portfolio';

    if (class_exists('Redux')) {

        $option = get_option('pe-redux');

        if (!empty($option['portfolio-slug'])) {

            $port_slug = $option['portfolio-slug'];
        }

    }


    $args = array(
        'label' => __('portfolio', 'pe-core'),
        'description' => __('Portfolio projects', 'pe-core'),
        'labels' => $labels,
        // Features this CPT supports in Post Editor
        'supports' => array('title', 'editor', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies' => array('work-types', 'post_tag'),
        /* A hierarchical CPT is like Pages and can have
         * Parent and child items. A non-hierarchical CPT
         * is like Posts.
         */
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => $port_slug
        )
    );

    // Registering your Custom Post Type
    register_post_type('portfolio', $args);

}

/* Hook into the 'init' action so that the function
 * Containing our post type registration is not 
 * unnecessarily executed. 
 */

add_action('init', 'portfolio_post_type', 0);

//create a custom taxonomy name it "type" for your posts
function pe_create_portfolio_taxonomies()
{

    $labels = array(
        'name' => _x('Categories', 'taxonomy general name'),
        'singular_name' => _x('Category', 'taxonomy singular name'),
        'search_items' => __('Search Categories'),
        'all_items' => __('All Categories'),
        'parent_item' => __('Parent Category'),
        'parent_item_colon' => __('Parent Category:'),
        'edit_item' => __('Edit Category'),
        'update_item' => __('Update Category'),
        'add_new_item' => __('Add New Category'),
        'new_item_name' => __('New Category Name'),
        'menu_name' => __('Categories'),
    );

    register_taxonomy('project-categories', array('portfolio'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true, // Needed for tax to appear in Gutenberg editor.
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio-category'),
    ));
}

// Let us create Taxonomy for Custom Post Type
add_action('init', 'pe_create_portfolio_taxonomies', 0);

add_action('init', function () {
    register_taxonomy('project-client', array(
        0 => 'portfolio',
    ), array(
        'labels' => array(
            'name' => 'Clients',
            'singular_name' => 'Client',
            'menu_name' => 'Client',
            'all_items' => 'All Client',
            'edit_item' => 'Edit Client',
            'view_item' => 'View Client',
            'update_item' => 'Update Client',
            'add_new_item' => 'Add New Client',
            'new_item_name' => 'New Client Name',
            'parent_item' => 'Parent Client',
            'parent_item_colon' => 'Parent Client:',
            'search_items' => 'Search Client',
            'not_found' => 'No client found',
            'no_terms' => 'No client',
            'filter_by_item' => 'Filter by Client',
            'items_list_navigation' => 'Client list navigation',
            'items_list' => 'Client list',
            'back_to_items' => '← Go to client',
            'item_link' => 'Client Link',
            'item_link_description' => 'A link to a client',
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ));

    register_taxonomy('project-year', array(
        0 => 'portfolio',
    ), array(
        'labels' => array(
            'name' => 'Years',
            'singular_name' => 'Year',
            'menu_name' => 'Year',
            'all_items' => 'All Year',
            'edit_item' => 'Edit Year',
            'view_item' => 'View Year',
            'update_item' => 'Update Year',
            'add_new_item' => 'Add New Year',
            'new_item_name' => 'New Year Name',
            'parent_item' => 'Parent Year',
            'parent_item_colon' => 'Parent Year:',
            'search_items' => 'Search Year',
            'not_found' => 'No year found',
            'no_terms' => 'No year',
            'filter_by_item' => 'Filter by year',
            'items_list_navigation' => 'Year list navigation',
            'items_list' => 'Year list',
            'back_to_items' => '← Go to year',
            'item_link' => 'Year Link',
            'item_link_description' => 'A link to a year',
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ));
});

function zeyna_add_cpt_support()
{

    //if exists, assign to $cpt_support var
    $cpt_support = get_option('elementor_cpt_support');

    //check if option DOESN'T exist in db
    if (!$cpt_support) {
        $cpt_support = ['page', 'post', 'portfolio']; //create array of our default supported post types
        update_option('elementor_cpt_support', $cpt_support); //write it to the database
    }

    //if it DOES exist, but portfolio is NOT defined
    else if (!in_array('portfolio', $cpt_support)) {
        $cpt_support[] = 'portfolio'; //append to array
        update_option('elementor_cpt_support', $cpt_support); //update database
    }

    //otherwise do nothing, portfolio already exists in elementor_cpt_support option
}
add_action('after_switch_theme', 'zeyna_add_cpt_support');



function pe_get_projects()
{

    $request = isset($_POST['request']) ? $_POST['request'] : '';


    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $args = isset($_POST['args']) ? json_decode(stripslashes($_POST['args']), true) : false;
    $settings = isset($_POST['settings']) ? json_decode(stripslashes($_POST['settings']), true) : false;

    if (!$args || !is_array($args)) {
        wp_send_json_error(['message' => esc_html__('Invalid query parameters', 'pe-core')]);
        wp_die();
    }

    if ($request === 'load-more') {
        $args['offset'] = $offset * $args['posts_per_page'];
    }



    if ($request === 'filter' && !empty($_POST['filters'])) {
        $filters = $_POST['filters'];
        $taxQuery = [
            'relation' => 'AND',
        ];

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

        $args['tax_query'] = $taxQuery;
    }


    $layout = false;
    if ($_POST['layout']) {
        $layout = $_POST['layout'];
        $settings['portfolio_style'] = $_POST['layout'];
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

    $index = 0;
    $the_query = new \WP_Query($args);
    $projects = [];
    $projectImages = [];


    if ($settings['cursor'] !== 'none') {

        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['cursor_icon'], ['aria-hidden' => 'true']);
        $cursorIcon = ob_get_clean();

        $cursor = [
            'data-cursor' => "true",
            'data-cursor-type' => $settings['cursor_type'],
            'data-cursor-text' => $settings['cursor_text'],
            'data-cursor-icon' => $cursorIcon,
        ];

        $cursorAttributes = implode(' ', array_map(
            fn($key, $value) => $key . '="' . esc_attr($value) . '"',
            array_keys($cursor),
            $cursor
        ));

    } else {
        $cursorAttributes = '';
    }



    while ($the_query->have_posts()):
        $the_query->the_post();
        $index++;

        $isHighlighted = in_array(get_the_ID(), $highlighted) || in_array($index, $highlighted) ? 'project--highlighted' : '';
        $classes = $isHighlighted;


        ob_start();

        if ($settings['portfolio_style'] === 'list') {

            zeyna_project_list_render($settings, $classes, $cursorAttributes, $index);
        } else {

            zeyna_project_render($settings, $classes, $cursorAttributes);

        }

        $project_html = ob_get_clean();
        $projects[] = $project_html;

        if ($settings['portfolio_images_style'] === 'hover' || $settings['portfolio_images_style'] === 'fullscreen') {
            ob_start(); ?>

            <div class="portfolio--list--image image__<?php echo get_the_ID() ?>" data-id="<?php echo get_the_ID() ?>">

                <?php pe_project_image(get_the_ID(), false, false); ?>

            </div>

            <?php $image_html = ob_get_clean();
            $projectImages[] = $image_html;

        }

    endwhile;

    wp_reset_postdata();



    wp_send_json_success([
        'projects' => $projects,
        'project_images' => $projectImages,
        'settings' => $settings,
        'request' => $request,
        'args' => $args,
        'filters' => $filters,
        'layout' => $layout,
    ]);

    wp_die();
}

add_action('wp_ajax_pe_get_projects', 'pe_get_projects');
add_action('wp_ajax_nopriv_pe_get_projects', 'pe_get_projects');



function pe_get_posts()
{

    $request = isset($_POST['request']) ? $_POST['request'] : '';


    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $args = isset($_POST['args']) ? json_decode(stripslashes($_POST['args']), true) : false;
    $settings = isset($_POST['settings']) ? json_decode(stripslashes($_POST['settings']), true) : false;

    if (!$args || !is_array($args)) {
        wp_send_json_error(['message' => esc_html__('Invalid query parameters', 'pe-core')]);
        wp_die();
    }

    if ($request === 'load-more') {
        $args['offset'] = $offset * $args['posts_per_page'];
    }



    if ($request === 'filter' && !empty($_POST['filters'])) {
        $filters = $_POST['filters'];
        $taxQuery = [
            'relation' => 'AND',
        ];

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

        $args['tax_query'] = $taxQuery;
    }


    $index = 0;
    $the_query = new \WP_Query($args);
    $projects = [];


    if ($settings['cursor'] !== 'none') {

        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['cursor_icon'], ['aria-hidden' => 'true']);
        $cursorIcon = ob_get_clean();

        $cursor = [
            'data-cursor' => "true",
            'data-cursor-type' => $settings['cursor_type'],
            'data-cursor-text' => $settings['cursor_text'],
            'data-cursor-icon' => $cursorIcon,
        ];

        $cursorAttributes = implode(' ', array_map(
            fn($key, $value) => $key . '="' . esc_attr($value) . '"',
            array_keys($cursor),
            $cursor
        ));

    } else {
        $cursorAttributes = '';
    }



    while ($the_query->have_posts()):
        $the_query->the_post();
        $index++;

        $classes = [];
        $classes[] = 'pe--single--post inner--anim pe--styled--object grid--post--item psp--elementor post--' .
            $settings['posts_style'];

        ob_start();

        ?>

        <article <?php echo $cursor ?> id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>

            <?php if ($settings['posts_style'] === 'metro' || $settings['posts_style'] === 'card' || $settings['posts_style'] === 'hidden-image') {
                echo '<a class="post--link--wrap" href="' . get_the_permalink() . '">';
            } ?>

            <div class="pe--single--post--wrapper">

                <?php if ($settings['post_thumbnail'] === 'show') { ?>
                    <div class="pe--single--post--image">
                        <?php pe_post_thumbnail(); ?>
                    </div>
                <?php } ?>

                <div class="pe--single--post--details pe--styled--object">

                    <div class="pe--post--meta">

                        <?php if ($settings['post_cat'] === 'show') { ?>
                            <div class="post--categories pe--styled--object"><?php
                            if ('post' === get_post_type()) {

                                $categories_list = get_the_category_list(esc_html__(', ', 'pe-core'));
                                if ($categories_list) {
                                    printf('<span class="cat-links">' . esc_html__('%1$s', 'pe-core') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            } ?>
                            </div>
                        <?php } ?>

                        <?php if ($settings['post_date'] === 'show') { ?>
                            <div class="post--date pe--styled--object">
                                <?php pe_posted_on(); ?>
                            </div>
                        <?php } ?>

                        <?php if ($settings['post_author'] === 'show') { ?>
                            <div class="post--author pe--styled--object">
                                <?php the_author(); ?>
                            </div>
                        <?php } ?>

                    </div>

                    <div class="pe--post--title">
                        <?php echo '<' . $settings['text_type'] . ' class="post-title entry-title">' . get_the_title() . '</' . $settings['text_type'] . '>'; ?>
                    </div>

                    <?php if ($settings['post_excerpt'] === 'show') { ?>
                        <div class="pe--post--excerpt pe--styled--object">
                            <?php

                            echo wp_trim_words(get_the_excerpt(), 20, '...');

                            ?>
                        </div>
                    <?php } ?>

                    <?php if ($settings['post_button'] === 'show') { ?>
                        <div class="pe--post--button">
                            <?php
                            $attr = [
                                'href' => get_permalink(),
                                'class' => 'pb--handle'
                            ];
                            pe_button_render($settings, $attr, false, 'post_button', ''); ?>
                        </div>
                    <?php } ?>


                </div>
            </div>

            <?php if ($settings['posts_style'] === 'metro' || $settings['posts_style'] === 'card' || $settings['posts_style'] === 'hidden-image') {
                echo '</a>';
            } ?>

        </article>

        <?php

        $project_html = ob_get_clean();
        $projects[] = $project_html;

    endwhile;

    wp_reset_postdata();

    wp_send_json_success([
        'posts' => $projects,
        'settings' => $settings,
        'request' => $request,
        'args' => $args,
        'filters' => $filters,
    ]);

    wp_die();
}

add_action('wp_ajax_pe_get_posts', 'pe_get_posts');
add_action('wp_ajax_nopriv_pe_get_posts', 'pe_get_posts');

