<?php
/**
 * Plugin Name: Pe Core
 * Plugin URI: http://www.pethemes.com
 * Description: A core plugin for PeThemes's WordPress themes.
 * Version: 1.3.1
 * Author: PeThemes
 * Author URI: http://www.pethemes.com
 */

require_once('pe-elementor.php');
require_once('inc/elementor.php');
require_once('inc/theme-functions.php');
require_once('inc/theme-tags.php');
require_once('inc/short-controls.php');
require_once('inc/portfolio.php');
require_once('inc/woocommerce.php');
require_once('inc/acf.php');
require_once('redux/redux.php');
require_once('redux/hooks.php');

define('ZEYNA_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ZEYNA_PLUGIN_URL', plugin_dir_url(__FILE__));


function pe_core_admin_assets($hook)
{
    $plugin_url = plugin_dir_url(__FILE__);

    if (is_rtl()) {
        wp_enqueue_style('pe-core-admin-rtl', $plugin_url . "assets/css/admin-rtl.css");
    } else {
        wp_enqueue_style('pe-core-admin', $plugin_url . "assets/css/admin.css");
    }

    wp_enqueue_script('pe-core-admin-scripts', $plugin_url . "assets/js/admin.js", ['jquery'], null, true);


    wp_enqueue_script('pe-core-custom-ajax', $plugin_url . "assets/js/activator.js", ['jquery'], null, true);

    wp_localize_script('pe-core-custom-ajax', 'pe_core_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);


    global $post;
    if (in_array($hook, ['post.php', 'post-new.php']) && isset($post->post_type) && $post->post_type === 'portfolio') {
        wp_enqueue_media();
        wp_enqueue_script('pe-gallery-metabox', $plugin_url . 'assets/js/pe-gallery-metabox.js', ['jquery'], null, true);
        wp_enqueue_style('pe-gallery-metabox-style', $plugin_url . 'assets/css/pe-gallery-metabox.css');
    }
}
add_action('admin_enqueue_scripts', 'pe_core_admin_assets');


function zeyna_enqueue_adobe_fonts()
{
    $option = get_option('pe-redux');

    if (!empty($option['adobe_fonts_url'])) {
        wp_enqueue_style(
            'adobe-fonts',
            esc_url($option['adobe_fonts_url']),
            array(),
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'zeyna_enqueue_adobe_fonts');

function zeyna_get_adobe_fonts()
{
    $options = get_option('pe-redux');

    if (empty($options['adobe_fonts_url'])) {
        return [];
    }

    $response = wp_remote_get($options['adobe_fonts_url']);

    if (is_wp_error($response)) {
        return [];
    }

    $css = wp_remote_retrieve_body($response);

    preg_match_all('/font-family:\s*["\']?([^;"\']+)["\']?/i', $css, $matches);

    if (empty($matches[1])) {
        return [];
    }

    $fonts = [];
    foreach ($matches[1] as $font) {
        $fonts[$font] = $font;
    }

    $fonts['Arial, Helvetica, sans-serif'] = 'Arial, Helvetica, sans-serif';
    $fonts["'Arial Black', Gadget, sans-serif"] = "'Arial Black', Gadget, sans-serif";
    $fonts["'Bookman Old Style', serif"] = "'Bookman Old Style', serif";
    $fonts["'Comic Sans MS', cursive"] = "'Comic Sans MS', cursive";
    $fonts["Courier, monospace"] = "Courier, monospace";
    $fonts["Garamond, serif"] = "Garamond, serif";
    $fonts["Georgia, serif"] = "Georgia, serif";
    $fonts["Impact, Charcoal, sans-serif"] = "Impact, Charcoal, sans-serif";
    $fonts["'Lucida Console', Monaco, monospace"] = "'Lucida Console', Monaco, monospace";
    $fonts["'Lucida Sans Unicode', 'Lucida Grande', sans-serif"] = "'Lucida Sans Unicode', 'Lucida Grande', sans-serif";
    $fonts["'MS Sans Serif', Geneva, sans-serif"] = "'MS Sans Serif', Geneva, sans-serif";
    $fonts["'MS Serif', 'New York', sans-serif"] = "'MS Serif', 'New York', sans-serif";
    $fonts["'Palatino Linotype', 'Book Antiqua', Palatino, serif"] = "'Palatino Linotype', 'Book Antiqua', Palatino, serif";
    $fonts["Tahoma,Geneva, sans-serif"] = "Tahoma,Geneva, sans-serif";
    $fonts["'Times New Roman', Times,serif"] = "'Times New Roman', Times,serif";
    $fonts["'Trebuchet MS', Helvetica, sans-serif"] = "'Trebuchet MS', Helvetica, sans-serif";
    $fonts["Verdana, Geneva, sans-serif"] = "Verdana, Geneva, sans-serif";

    return $fonts;
}


defined('ABSPATH') || exit;

