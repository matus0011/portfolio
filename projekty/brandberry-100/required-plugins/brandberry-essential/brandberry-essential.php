<?php

/**
 * Plugin Name: Brandberry Essential
 * Description: Essential plugin for Brandberry Theme.
 * Plugin URI:  https://treethemes.com/
 * Version:     1.0.0
 * Author:      treethemes
 * Author URI:  https://treethemes.com/
 * Text Domain: brandberry
 * Elementor tested up to: 3.30.3
 * Elementor Pro tested up to: 3.30.3
 */

if (! defined('ABSPATH')) {
	exit;
} // Exit if accessed directly
define('BRANDBERRY_ESSENTIAL_VERSION', '1.0.0');
define('BRANDBERRY_ESSENTIAL', true);
define('BRANDBERRY_ESSENTIAL_LITE', true);
define('BRANDBERRY_ESSENTIAL_ROOT', __FILE__);
define('BRANDBERRY_ESSENTIAL_URL', plugins_url('/', BRANDBERRY_ESSENTIAL_ROOT));
define('BRANDBERRY_ESSENTIAL_ASSETS_URL', BRANDBERRY_ESSENTIAL_URL . 'assets/');
define('BRANDBERRY_ESSENTIAL_DIR_PATH', plugin_dir_path(BRANDBERRY_ESSENTIAL_ROOT));
define('BRANDBERRY_ESSENTIAL_PLUGIN_BASE', plugin_basename(BRANDBERRY_ESSENTIAL_ROOT));

/* change value */
if(! defined('BRANDBERRY_TPL_SLUG')){
	define('BRANDBERRY_TPL_SLUG', 'brandberry');
}	

define('BRANDBERRY_ESSENTIAL_OPTION_KEY', BRANDBERRY_TPL_SLUG.'_settings'); 
define('BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE', 'https://member.treethemes.com/');
define('BRANDBERRY_ESSENTIAL_THEME_DEMO_BASE_PATH', BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE.'wp-json/wp/v2');

define('BRANDBERRY_ESSENTIAL_DEMO_CAT_ID', '2'); // theme category from Theme demo / license server
define('BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_PATH', BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE.'wp-json/wp/v2/wcf-templates/'); 
//https://member.treethemes.com/wp-json/wp/v2/wcf-templates

define('BRANDBERRY_ESSENTIAL_THEME_PARENT_SLUG', 'wcf-brandberry-theme-parent');


/* what are this? */
define('BRANDBERRY_ESSENTIAL_DEMO_BASE_PATH', BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE.'wp-json/api/v1/');
define('BRANDBERRY_ESSENTIAL_DEMO_PAGE_BASE_PATH', BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE.'wp-content/plugins/wcf-elementor-templates/inc/demo/page-xml-layout/');
/**/


final class Brandberry_Esential_Plugin
{

	/**
	 * Plugin Version
	 *
	 * @since 1.2.1
	 * @var string The plugin version.
	 */
	const VERSION = '1.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.30.3';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.4';

	public $plugin_slug;

	public $plugin_path;

	public $version;

	public $cache_key;

	public $cache_allowed;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct()
	{

		
		add_action('admin_menu', [$this, 'register_theme_admin_menu']);
		// Init Plugin
		add_action('plugins_loaded', [$this, 'init'],10);
		add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 500);
		add_action('admin_init', [$this, 'disable_all_admin_notices'], 1);	
	
	}

	public function enqueue_scripts() {}

	function disable_all_admin_notices()
	{
		if (isset($_GET['page']) && $_GET['page'] == 'the-theme-demo-importer') {
			remove_all_actions('admin_notices');
			remove_all_actions('all_admin_notices');
			remove_all_actions('user_admin_notices');
			remove_all_actions('network_admin_notices');
		}
	}
	public function admin_enqueue_scripts()
	{

		wp_enqueue_script('brandberry-admin', BRANDBERRY_ESSENTIAL_ASSETS_URL . '/js/admin.js', array('jquery'), time(), true);
		$info_data = [
			'admin_ajax' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce('wcf_theme_secure'),
		];

		wp_localize_script('brandberry-admin', 'info_admin_obj', $info_data);

		if (isset($_GET['page']) && $_GET['page'] == 'the-theme-demo-importer') {
			wp_enqueue_script('wcf-theme-startup-template', BRANDBERRY_ESSENTIAL_ASSETS_URL . 'build/modules/starter-template/main.js', array('react', 'react-dom', 'wp-element', 'wp-i18n'), time(), true);

			$localize_data = [
				'ajaxurl'            => admin_url('admin-ajax.php'),
				'nonce'              => wp_create_nonce('wcf_admin_nonce'),
				'adminURL'           => admin_url(),
				'version'            => BRANDBERRY_ESSENTIAL_VERSION,
				'st_template_domain' => BRANDBERRY_ESSENTIAL_THEME_DEMO_BASE_PATH,
				'base_url'           => BRANDBERRY_ESSENTIAL_THEME_DEMO_BASE_PATH,
				'theme_cat_id'       => BRANDBERRY_ESSENTIAL_DEMO_CAT_ID,
				'home_url'           => home_url('/')
			];
			wp_localize_script('wcf-theme-startup-template', 'theme_template_obj', $localize_data);
			wp_enqueue_style('wcf-theme-starter-template', BRANDBERRY_ESSENTIAL_ASSETS_URL . 'build/modules/starter-template/main.css', array(), time(), 'all');
		}
	}

	function register_theme_admin_menu()
	{

		add_menu_page(
			__('Brandberry Theme', 'brandberry'),
			esc_html__('Brandberry Theme', 'brandberry'),
			'manage_options',
			BRANDBERRY_ESSENTIAL_THEME_PARENT_SLUG,
			[$this, '_render_dashboard'],
			BRANDBERRY_ESSENTIAL_ASSETS_URL . 'images/logo-icon.svg',
			6
		);
		
		if (brandberry_option('theme_demo_activate', true) && brandberry_theme_allowed_features()) {
			$demo_page = "the-theme-demo-importer";
			add_submenu_page(
				BRANDBERRY_ESSENTIAL_THEME_PARENT_SLUG,
				'Demo Import',
				'Demo Import',
				'manage_options',
				$demo_page,
				[$this, 'render_demo_importer']
			);
		}
		
		
	}

	public function _render_dashboard()
	{
		echo '<div id="wcf-user-guider-dashboard" class="wcf-user-guider-dashboard"></div>';
	}
	public function render_demo_importer()
	{
		if (! brandberry_theme_allowed_features()) {
			return;
		}
		echo '<div id="wcf-demo-importer-starter" class="wcf-demo-importer-starter"></div>';
	}

	public function init()
	{
		include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/helper.php');
		include_once BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/hook.php';
		global $pagenow;		

		include_once BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/packages/codestar-framework/codestar-framework.php';
		
		include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/wc/init.php');

		
		if (file_exists(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/options/settings.init.class.php')) {
			include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/options/settings.init.class.php');
		}

		// Check if Elementor installed and activated
		if (! did_action('elementor/loaded')) {
			add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));
			return;
		}

		// Check for required Elementor version
		if (! version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', array($this, 'admin_notice_minimum_elementor_version'));

			return;
		}

		// Check for required PHP version
		if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));

			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once('plugin.php');
		
		
		require_once BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/integrations/elementor-text-3d-controls.php';
		require_once BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/integrations/elementor-text-3d-render.php';
		
		// Includes
		require_once BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/integrations/wcf-nav-menu-brandberry-hover-control.php';
		require_once BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/integrations/wcf-nav-menu-brandberry-hover-render.php';
		
		if (file_exists(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/packages/ele-template-library/loader.php')) {
			include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/packages/ele-template-library/loader.php');
			
		}

		if(file_exists(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/packages/page-import.php'))  {
			include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/packages/page-import.php');
		}
		
		
		include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/packages/minifiy-css.php');
		global $pagenow;

		include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/cpt/cpt.php');

		include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/optimize-assets.php');
		include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . 'inc/cpt/dynamic.php');
		
		
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin()
	{

		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'brandberry'),
			'<strong>' . esc_html__('Brandberry Essential', 'brandberry') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'brandberry') . '</strong>'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'brandberry'),
			'<strong>' . esc_html__('Brandberry Essential', 'brandberry') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'brandberry') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version()
	{
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'brandberry'),
			'<strong>' . esc_html__('Brandberry Essential', 'brandberry') . '</strong>',
			'<strong>' . esc_html__('PHP', 'brandberry') . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}
}

new Brandberry_Esential_Plugin();

function brandberry_clear_product_data_cron_event()
{
	$timestamp = wp_next_scheduled('wcf_check_product_daily_event');
	if ($timestamp) {
		wp_unschedule_event($timestamp, 'wcf_check_product_daily_event');
	}
}
register_deactivation_hook(__FILE__, 'brandberry_clear_product_data_cron_event');



/** 
 * You can remove below code if you don't want to use update feature
 * Updater API URL
 * 
 */

define( 'BRANDBERRY_ESSENTIAL_SLUG', 'brandberry-essential' );
define( 'BRANDBERRY_ESSENTIAL_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'BRANDBERRY_UPDATE_API', BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE.'wp-json/update-server/v1/manifest/brandberry-essential' );


require_once __DIR__ . '/inc/class-plugin-updater.php';
 if ( is_admin() ) {
	new Brandberry_Essential_Updater();
 }


define( 'BRANDBERRY_THEME_UPDATER_SLUG', 'brandberry' );
define( 'BRANDBERRY_THEME_UPDATER_VERSION', wp_get_theme()->get( 'Version' ) );
define(
    'BRANDBERRY_THEME_UPDATER_API',
    BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE.'wp-json/update-server/v1/manifest/brandberry'
);

require_once __DIR__ . '/inc/class-theme-updater.php';
 if ( is_admin() ) {
	new BRANDBERRY_THEME_UPDATER();
 }

/*Enqueue CSS + JS (frontend) */
function brandberry_enqueue_text_3d_assets() {
    wp_enqueue_style(
        'brandberry-text-3d',
        plugins_url('assets/css/text-3d.css', __FILE__),
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'brandberry-text-3d-trigger',
        plugins_url('assets/js/text-3d-trigger.js', __FILE__),
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'brandberry_enqueue_text_3d_assets');

/*Enqueue in Elementor editor */
function brandberry_enqueue_text_3d_assets_editor() {
    wp_enqueue_style(
        'brandberry-text-3d-editor',
        plugins_url('assets/css/text-3d.css', __FILE__),
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'brandberry-text-3d-trigger-editor',
        plugins_url('assets/js/text-3d-trigger.js', __FILE__),
        array(),
        '1.0.0',
        true
    );
}
add_action('elementor/editor/after_enqueue_styles', 'brandberry_enqueue_text_3d_assets_editor');
add_action('elementor/editor/after_enqueue_scripts', 'brandberry_enqueue_text_3d_assets_editor');

// Register (not enqueue) — loaded only when option is enabled on a widget
add_action('wp_enqueue_scripts', function () {
    wp_register_style(
        'bb-wcf-nav-menu-brandberry-hover',
        plugins_url('assets/css/wcf-nav-menu-brandberry-hover.css', __FILE__),
        [],
        '1.0.0'
    );
}, 20);

// Load Brandberry hover CSS inside Elementor editor iframe (editor + Theme Builder).
add_action('elementor/editor/after_enqueue_styles', function () {
    wp_enqueue_style('bb-wcf-nav-menu-brandberry-hover');
}, 20);

// Theme Builder preview on frontend uses Elementor preview mode.
add_action('elementor/preview/enqueue_styles', function () {
    wp_enqueue_style('bb-wcf-nav-menu-brandberry-hover');
}, 20);



// ------------------------------------------------------------
// Brandberry Portfolio assets (registered globally; enqueued by Elementor dependencies)
// ------------------------------------------------------------
function brandberry_register_portfolio_assets() {
    // Core portfolio styles
    wp_register_style(
        'bb--portfolio',
        plugins_url('assets/css/bb-portfolio.css', __FILE__),
        [],
        '1.0.0'
    );
    wp_register_style(
        'bb--advance-portfolio',
        plugins_url('assets/css/bb-advance-portfolio.css', __FILE__),
        [],
        '1.0.0'
    );

    wp_register_style(
        'bb--brave-portfolio-scoped',
        plugins_url('assets/css/bb-brave-portfolio.css', __FILE__),
        [],
        '1.0.0'
    );

    // Brave portfolio tabs (Grid/List).
    wp_register_script(
        'bb--brave-portfolio-tabs',
        plugins_url('assets/js/bb-brave-portfolio-tabs.js', __FILE__),
        [],
        '1.0.0',
        true
    );
}

add_action('wp_enqueue_scripts', 'brandberry_register_portfolio_assets', 5);
add_action('elementor/editor/after_enqueue_styles', 'brandberry_register_portfolio_assets', 5);
add_action('elementor/editor/after_enqueue_scripts', 'brandberry_register_portfolio_assets', 5);
