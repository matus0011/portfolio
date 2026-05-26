<?php

namespace BrandberryEssentialApp;

use Elementor\Plugin as ElementorPlugin;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin
{

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;
	const WIDGETS_DIR = BRANDBERRY_ESSENTIAL_DIR_PATH . 'widgets';
	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 *
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function after_register_styles()
	{
		// Register Offcanvas styles early so Elementor can enqueue them when needed.
		wp_register_style(
			'brandberry-offcanvas',
			plugins_url('/assets/css/offcanvas.css', __FILE__),
			[],
			'1.0.0'
		);

		// Brandberry Portfolio (scoped assets so they don't collide with Animation Addons).
		wp_register_style(
			'bb--portfolio',
			plugins_url('/assets/css/bb-portfolio.css', __FILE__),
			[],
			BRANDBERRY_ESSENTIAL_VERSION
		);
		wp_register_style(
			'bb--advance-portfolio',
			plugins_url('/assets/css/bb-advance-portfolio.css', __FILE__),
			['bb--portfolio'],
			BRANDBERRY_ESSENTIAL_VERSION
		);
		// Basic button styles used by the widget's pagination.
		wp_register_style(
			'bb--button',
			plugins_url('/assets/css/bb-button.css', __FILE__),
			[],
			BRANDBERRY_ESSENTIAL_VERSION
		);
	}

	public function widget_scripts()
	{

		wp_register_script( 'meanmenu', plugins_url( '/assets/js/jquery.meanmenu.min.js', __FILE__ ), array( 'jquery' ), false, true );

		wp_register_script('brandberry--global-core', plugins_url('/assets/js/wcf--global-core.min.js', __FILE__), ['jquery'], false, true);
		wp_enqueue_script('brandberry--global-core');
		
		// Register Offcanvas script early so Elementor can enqueue it when needed.
		wp_register_script(
			'brandberry-offcanvas',
			plugins_url('/assets/js/offcanvas.js', __FILE__),
			['jquery'],
			'1.0.0',
			true
		);

		// Brandberry Portfolio scripts (namespaced handles).
		wp_register_script(
			'bb--slider',
			plugins_url('/assets/js/bb-slider.js', __FILE__),
			['jquery', 'swiper'],
			BRANDBERRY_ESSENTIAL_VERSION,
			true
		);
		wp_register_script(
			'bb--portfolio',
			plugins_url('/assets/js/bb-portfolio.js', __FILE__),
			['jquery'],
			BRANDBERRY_ESSENTIAL_VERSION,
			true
		);
		wp_register_script(
			'bb--advanced-portfolio-skins',
			plugins_url('/assets/js/bb-advanced-portfolio-skins.js', __FILE__),
			['jquery', 'bb--slider'],
			BRANDBERRY_ESSENTIAL_VERSION,
			true
		);

		/**
		 * GSAP libraries (registered only; widgets enqueue conditionally)
		 *
		 * We ship local copies (from Animation Addons PRO) to avoid relying on
		 * external CDNs and to keep handles isolated from the bundled plugin.
		 */
		wp_register_script(
			'bb--gsap',
			plugins_url('/assets/lib/gsap.min.js', __FILE__),
			[],
			BRANDBERRY_ESSENTIAL_VERSION,
			true
		);
		wp_register_script(
			'bb--gsap-flip',
			plugins_url('/assets/lib/Flip.min.js', __FILE__),
			['bb--gsap'],
			BRANDBERRY_ESSENTIAL_VERSION,
			true
		);
		wp_register_script(
			'bb--gsap-scrolltrigger',
			plugins_url('/assets/lib/ScrollTrigger.min.js', __FILE__),
			['bb--gsap'],
			BRANDBERRY_ESSENTIAL_VERSION,
			true
		);
		wp_register_script(
			'bb--gsap-scrollsmoother',
			plugins_url('/assets/lib/ScrollSmoother.min.js', __FILE__),
			['bb--gsap', 'bb--gsap-scrolltrigger'],
			BRANDBERRY_ESSENTIAL_VERSION,
			true
		);
	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts integrations for Elementor editor.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function editor_scripts()
	{
		add_filter('script_loader_tag', [$this, 'editor_scripts_as_a_module'], 10, 2);

		wp_enqueue_script(
			'wcf--elementor--editor',
			plugins_url('/assets/js/editor/editor.js', __FILE__),
			[
				'elementor-editor',
			],
			time(),
			true
		);
	}

	/**
	 * Force load editor script as a module
	 *
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 * @since 1.2.1
	 *
	 */
	public function editor_scripts_as_a_module($tag, $handle)
	{
		if ('wcf--elementor--editor' === $handle) {
			$tag = str_replace('<script', '<script type="module"', $tag);
		}

		return $tag;
	}
	
    private function discover_widgets(): array {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $widgets = [];
        $base = rtrim(self::WIDGETS_DIR, '/\\');

        if ( ! is_dir($base) ) {
            return $cache = [];
        }

        // 1) widgets/slug.php
        foreach ((array) glob($base . '/*.php') as $file) {
            $slug = basename($file, '.php');
            $widgets[] = [
                'slug'  => $slug,
                'path'  => $file,
                'class' => $this->slug_to_fqcn($slug),
            ];
        }

        // 2) widgets/slug/slug.php
        foreach ((array) glob($base . '/*', GLOB_ONLYDIR) as $dir) {
            $slug = basename($dir);
            $entry = $dir . DIRECTORY_SEPARATOR . $slug . '.php';
            if (file_exists($entry)) {
                $widgets[] = [
                    'slug'  => $slug,
                    'path'  => $entry,
                    'class' => $this->slug_to_fqcn($slug),
                ];
            }
        }

        // Make slugs unique (folder file overrides flat file if duplicated)
        $unique = [];
        foreach ($widgets as $w) {
            $unique[$w['slug']] = $w;
        }

        return $cache = array_values($unique);
    }

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 */
	public function register_widgets($widgets_manager)
	{	

        foreach ( $this->discover_widgets() as $w ) { 

            // Require file
            require_once $w['path'];

            // Validate class
            if ( ! class_exists($w['class']) ) {
                // You can log here if needed
                continue;
            }

            // Register
            $widgets_manager->register( new $w['class']() );
        }
	}
	/**
	 * Get Widgets List.
	 *
	 * @return array
	 */
	public static function get_widgets() {

		return apply_filters( 'brandberry-essential/widgets', [
			'wc-cart-count'            => [
				'label'       => __( 'BB Cart Count', 'brandberry-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

		] );
	}
	
	
    private function slug_to_fqcn(string $slug): string {
        $parts = array_map(
            static fn($p) => ucfirst($p),
            preg_split('/[-_]+/', $slug) ?: []
        );
        $class = implode('_', $parts); // Your convention uses underscores
        return 'BrandberryEssentialApp\\Widgets\\' . $class;
    }

	/**
	 * Add page settings controls
	 *
	 * Register new settings for a document page settings.
	 *
	 * @since 1.2.1
	 * @access private
	 */
	private function add_page_settings_controls() {}

	function add_elementor_widget_categories($elements_manager)
	{

		$elements_manager->add_category(
			'brandberry',
			[
				'title' => esc_html__('Brandberry', 'brandberry'),
				'icon'  => 'fa fa-plug',
			]
		);
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct()
	{
		add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories'], 12);
		// Register widget scripts
		add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
		add_action('wp_enqueue_scripts', [$this, 'after_register_styles'], 12);

		// Register widgets
		add_action('elementor/widgets/register', [$this, 'register_widgets']);

		// Register editor scripts
		add_action('elementor/editor/after_enqueue_scripts', [$this, 'editor_scripts']);
	}
}

// Instantiate Plugin Class
Plugin::instance();
