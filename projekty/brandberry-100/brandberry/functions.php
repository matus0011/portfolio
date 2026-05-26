<?php

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME VERSION
-----------------------------------------------------*/
if ( site_url() === 'http://brandberry.local/' ) {
    define( 'BRANDBERRY_VERSION', time() );
} else {
    define( 'BRANDBERRY_VERSION', '1.0.1' );
    
}

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS URL
-----------------------------------------------------*/
define( 'BRANDBERRY_THEME_URI', get_template_directory_uri() );
define( 'BRANDBERRY_ASSETS', BRANDBERRY_THEME_URI . '/assets/' );
define( 'BRANDBERRY_IMG', BRANDBERRY_THEME_URI . '/assets/imgs' );
define( 'BRANDBERRY_CSS', BRANDBERRY_THEME_URI . '/assets/css' );
define( 'BRANDBERRY_JS', BRANDBERRY_THEME_URI . '/assets/js' );

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS DIRECTORY PATH
-----------------------------------------------------*/
define( 'BRANDBERRY_THEME_DIR', get_template_directory() );
define( 'BRANDBERRY_IMG_DIR', BRANDBERRY_THEME_DIR . '/assets/imgs' );
define( 'BRANDBERRY_CSS_DIR', BRANDBERRY_THEME_DIR . '/assets/css' );
define( 'BRANDBERRY_JS_DIR', BRANDBERRY_THEME_DIR . '/assets/js' );

if (! defined('BRANDBERRY_PRODUCT_DOMAIN')) {
  define('BRANDBERRY_PRODUCT_DOMAIN', 'https://member.treethemes.com/');
}

if (! defined('BRANDBERRY_TOKEN_ID')) {
  define('BRANDBERRY_TOKEN_ID', 'token_1765969152834');
}

if (! defined('BRANDBERRY_PARENT_PAGE_SLUG')) {
  define('BRANDBERRY_PARENT_PAGE_SLUG', 'wcf-brandberry-theme-parent');
}

if(! defined('BRANDBERRY_TPL_SLUG')){
  define('BRANDBERRY_TPL_SLUG', 'brandberry');
}

/*----------------------------------------------------
LOAD Classes
-----------------------------------------------------*/
if ( file_exists( dirname( __FILE__ ) . '/app/loader.php' ) ):
    require_once dirname( __FILE__ ) . '/app/loader.php';    
endif;
/*----------------------------------------------------
SET UP THE CONTENT WIDTH VALUE BASED ON THE THEME'S DESIGN
-----------------------------------------------------*/
if ( !isset( $content_width ) ) {
    $content_width = 800;
}

add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );


//Woocommerce Supports
function brandberry_add_woocommerce_support() {
  add_theme_support( 'woocommerce', array(
    'thumbnail_image_width' => 350,
    'single_image_width'    => 350,
    'product_grid'          => array(
      'default_rows'    => 3,
      'min_rows'        => 2,
      'max_rows'        => 8,
      'default_columns' => 4,
      'min_columns'     => 2,
      'max_columns'     => 5,
    ),
  ) );

  add_theme_support( 'wc-product-gallery-zoom' );
  add_theme_support( 'wc-product-gallery-lightbox' );
  add_theme_support( 'wc-product-gallery-slider' );


}

add_action( 'after_setup_theme', 'brandberry_add_woocommerce_support' );

use Elementor\Plugin;

class Brandberry_Elementor_Compatibility {

    public static function get_site_settings_link() {

        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return false;
        }

        $kit_id = Plugin::$instance->kits_manager->get_active_id();

        if ( ! $kit_id ) {
            return false;
        }

        return admin_url(
            'post.php?post=' . absint( $kit_id ) . '&action=elementor#site-settings'
        );
    }
}


/**
 * Find option value even if cs_get_option() returns null on frontend.
 * (Caches the discovered option_name in a static var to avoid repeated work.)
 */
function bb_get_option_anywhere($key, $default = null) {
    // 1) Try Codestar first
    if (function_exists('cs_get_option')) {
        $v = cs_get_option($key);
        if ($v !== null) return $v;
    }

    // 2) Search wp_options for a serialized array containing this key
    static $cached_option_name = null;

    if ($cached_option_name) {
        $all = get_option($cached_option_name);
        if (is_array($all) && array_key_exists($key, $all)) {
            return $all[$key];
        }
    }

    global $wpdb;
    $like = '%' . $wpdb->esc_like($key) . '%';

    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT option_name, option_value
             FROM {$wpdb->options}
             WHERE option_value LIKE %s
             LIMIT 50",
            $like
        )
    );

    foreach ($rows as $r) {
        $val = maybe_unserialize($r->option_value);
        if (is_array($val) && array_key_exists($key, $val)) {
            $cached_option_name = $r->option_name;
            return $val[$key];
        }
    }

    return $default;
}

function bb_to_bool($raw, $default = true): bool {
    if ($raw === null) return $default;
    if (is_bool($raw)) return $raw;
    if (is_int($raw)) return $raw === 1;
    if (is_string($raw)) {
        $v = strtolower(trim($raw));
        if (in_array($v, ['1','true','yes','on'], true)) return true;
        if (in_array($v, ['0','false','no','off',''], true)) return false;
    }
    return (bool) intval($raw);
}

function mytheme_enqueue_lines_script() {
  
  // Plugin not active → constant not defined → do nothing
    if ( ! defined( 'BRANDBERRY_ESSENTIAL_OPTION_KEY' ) ) {
        return;
    }
    
    $options = get_option( BRANDBERRY_ESSENTIAL_OPTION_KEY );

    // Only load JS if the switch is ON
    if ( empty( $options['show_lines_pattern'] ) ) {
        return;
    }

    wp_enqueue_script(
        'mytheme-lines-pattern',
        get_stylesheet_directory_uri() . '/assets/js/lines-pattern.js',
        array( 'jquery' ),
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_lines_script' );

function brandberry_is_elementor_editor_context(): bool {
  $is_admin_editor   = is_admin() && isset($_GET['action']) && $_GET['action'] === 'elementor';
  $is_preview_iframe = isset($_GET['elementor-preview']);
  return $is_admin_editor || $is_preview_iframe;
}

function brandberry_is_elementor_preview_iframe(): bool {
  return isset($_GET['elementor-preview']);
}

function brandberry_opt(string $id, bool $default = true): bool {
    
    if (function_exists('csf_get_option')) {
    $val = csf_get_option(BRANDBERRY_ESSENTIAL_OPTION_KEY, $id);
    if ($val === null || $val === '') return $default;
    return (bool) $val;
  }
  return $default;
}


function brandberry_dequeue_aae_gsap_libs_in_editor() {
  if (!brandberry_is_elementor_preview_iframe()) return;
  if (!brandberry_opt('disable_gsap_on_elementor_editor', true)) return;

  if (empty($GLOBALS['wp_scripts']) || empty($GLOBALS['wp_scripts']->queue)) return;

  $targets = ['gsap', 'scrolltrigger', 'scrollsmoother', 'splittext', 'scrolltoplugin'];

  foreach ($GLOBALS['wp_scripts']->queue as $handle) {
    $src = $GLOBALS['wp_scripts']->registered[$handle]->src ?? '';
    if (!$src) continue;

    // Only Animation Addons PRO
    if (strpos($src, '/wp-content/plugins/animation-addons-for-elementor-pro/') === false) continue;

    $lower = strtolower($src);
    foreach ($targets as $t) {
      if (strpos($lower, $t) !== false) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
        break;
      }
    }
  }
}

// Run late enough (Elementor + AA enqueue late)
add_action('elementor/frontend/after_enqueue_scripts', 'brandberry_dequeue_aae_gsap_libs_in_editor', 9999);
add_action('wp_print_scripts', 'brandberry_dequeue_aae_gsap_libs_in_editor', 9999);


add_filter('pre_http_request', function ($pre, $args, $url) {

  if (!brandberry_is_elementor_editor_context()) return $pre;
  if (!brandberry_opt('block_aa_fingerprint_editor', true)) return $pre;

  if (stripos($url, 'animation-addons.com/wp-json/live/v1/fingerprint/') !== false) {
    return new WP_Error('brandberry_blocked_fingerprint', 'Blocked fingerprint request in Elementor editor.');
  }

  return $pre;

}, 10, 3);


add_filter('pre_http_request', function ($pre, $args, $url) {

  if (!brandberry_is_elementor_editor_context()) return $pre;
  if (!brandberry_opt('cache_member_templates_editor', true)) return $pre;

  if (stripos($url, 'member.treethemes.com/wp-json/templates/') === false) return $pre;

  $method = strtoupper($args['method'] ?? 'GET');
  if ($method !== 'GET') return $pre;

  $key = 'brandberry_tpl_' . md5($url);
  $cached = get_transient($key);

  if ($cached !== false) {
    return $cached; // Must be WP HTTP response array
  }

  return $pre;

}, 10, 3);
if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG === false ) {
  // Production-safe: do nothing
}
add_filter('http_response', function ($response, $args, $url) {

  if (stripos($url, 'member.treethemes.com/wp-json/templates/') === false) return $response;

  $method = strtoupper($args['method'] ?? 'GET');
  if ($method !== 'GET') return $response;

  if (!brandberry_opt('cache_member_templates_editor', true)) return $response;

  $code = (int) wp_remote_retrieve_response_code($response);
  if ($code >= 200 && $code < 300) {
    $key = 'brandberry_tpl_' . md5($url);
    set_transient($key, $response, 12 * HOUR_IN_SECONDS);
  }

  return $response;

}, 10, 3);

/* Preview templates on a clean template */
add_filter('template_include', function ($template) {
  if (is_user_logged_in() && !empty($_GET['elementor_library'])) {
    $custom = get_stylesheet_directory() . '/page-templates/elementor-library-preview.php';
    if (file_exists($custom)) return $custom;
  }
  return $template;
}, 99);


