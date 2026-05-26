<?php

namespace brandberry\Core;

class Theme_Setup
{
   
    public $theme_slug = BRANDBERRY_TPL_SLUG;

    public $api_params = [];
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'admin_menu', [ $this,'register_theme_admin_menu' ] );
        add_action( 'after_setup_theme' , array( $this, 'setup' ) );
        add_action( 'admin_init' , [ $this , 'theme_activated_options' ]);       
        add_action( 'after_switch_theme' , [ $this , 'theme_activated' ]);       
        // add extra html tags for smooth
        add_action( 'wp_ajax_the_user_guide_ls_checker' , [ $this,'user_guide'] );
        add_action( 'wp_ajax_wcf_user_guide_ls_remove' , [ $this,'license_deactivate'] );
        add_action( 'elementor/theme/register_locations', [ $this, 'register_locations' ] );      
    }
    public function register_locations( $manager ) {
        // only if Pro is active (the hook will only fire if Elementor core is loaded,
            // so this check guards against missing Pro)
            if ( ! class_exists( '\ElementorPro\Plugin' ) ) {
                return;
            }
            $locations = [
                'header',
                'footer',
                'single',
                'archive',
                'author',
                'canvas',
                'full_width',
                '404',             // custom 404 page
                'search',
                'woocommerce-shop',
                'woocommerce-archive',
                'woocommerce-single',
                'woocommerce-cart',
                'woocommerce-checkout',
            ];
        
            foreach ( $locations as $loc ) {
                $manager->register_location( $loc );
            }
    }
    
    function register_theme_admin_menu() {		
		
		if(!defined('BRANDBERRY_ESSENTIAL')) {
            add_menu_page(
                esc_html__( 'Brandberry Theme', 'brandberry' ),
                esc_html__( 'Brandberry Theme','brandberry'),
                'manage_options',
                'wcf-brandberry-theme-parent',
                [$this,'_render_dashboard'],
                null,
                6
            );	
        }
		
	}
    public function check_already_activated($code, $email, $user_submitted = 'no')
    {
        // Try to get cached response first
        $cache_key = BRANDBERRY_TPL_SLUG.'_license';
        $cached_response = get_transient($cache_key);
        
        if ($cached_response !== false && $user_submitted == 'no') {
            return $cached_response;
        }

        $url = add_query_arg(array(             
            'code'     => $code,
            'email'    => $email,
            'url'      => home_url(),
            'action'   => 'check_license',
            'token_id' => BRANDBERRY_TOKEN_ID,
        ), BRANDBERRY_PRODUCT_DOMAIN.'wp-json/eddenvatu/v1/license/verification');
      
        $args = [
            'sslverify'   => false,
            'timeout'     => 15,           
            'cookies'     => array(),
            'headers'     => array(
                'Accept' => 'application/json',
            )
        ];

        $response = wp_remote_get($url, $args);

        if ((! is_wp_error($response)) && (200 === wp_remote_retrieve_response_code($response))) {
             $responseBody = json_decode($response['body'], true);           
            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($responseBody['success']) && $responseBody['success'] == true) {                   
                    update_option($this->theme_slug . '_key_status', $responseBody['license']);
                    update_option($this->theme_slug . '_key', $code);    
                    update_option($this->theme_slug . '_item_id', $responseBody['item_id']);                              
                    if (isset($_REQUEST['email'])) {
                        update_option($this->theme_slug . '_email', $email);
                    }
                   
                    $response = $responseBody;
                    $response['msg'] = $responseBody['license'];
                    // Cache successful response for 24 hours
                    set_transient($cache_key, $response, DAY_IN_SECONDS);
                    return $response;
                } else {
                    $response = ['success' => false ,'msg' => esc_html__('Inactive License', 'brandberry')];          
                    return $response;
                }
            }
        }

        return ['success' => false ,'msg' => esc_html__('Missing args', 'brandberry')];
    }
    public function theme_activated(){
       
        if( isset( $_GET['activated'] ) ) {
            wp_safe_redirect( admin_url('admin.php?page=wcf-brandberry-theme-parent') );
            exit;
        }
	}
	
	public function _render_dashboard(){
		echo '<div id="wcf-user-guider-dashboard" class="wcf-user-guider-dashboard"></div>';
	}
    
    public function user_guide()
    {

        if (!wp_verify_nonce($_REQUEST['nonce'], "the_user_guider_secure")) {
            exit("No naughty business please");
        }

        $code           = isset($_REQUEST['code']) ? sanitize_text_field($_REQUEST['code']) : get_option($this->theme_slug . '_Key');
        $user_submitted = isset($_REQUEST['user_submitted']) ? sanitize_text_field($_REQUEST['user_submitted']) : false;
        $email          = isset($_REQUEST['email']) ? sanitize_email($_REQUEST['email']) : get_option('admin_email');

        if( $user_submitted == 'no' && get_option($this->theme_slug . '_email') != '' ){
          $email = get_option($this->theme_slug . '_email');
          $code = get_option($this->theme_slug . '_Key');
        }

        if( $user_submitted == 'no' && get_option($this->theme_slug . '_Key') != '' ){         
          $code = get_option($this->theme_slug . '_Key');
        }   
        if($user_submitted == 'no'){
            $result = $this->check_already_activated($code,$email,$user_submitted);
            $result['code'] = $code;
            $result['email'] = $email;
            wp_send_json_success($result);           
        }
        error_log($code);
        $result = $this->check_already_activated($code,$email,$user_submitted);    
        if (isset($result['success']) && isset($result['license']) && $result['license'] == 'valid'){ 
            wp_send_json_success($result);                
        } 
           
        $args = [ 
                'headers'   => ['Content-Type'=>'application/json'],
                'sslverify' => false,
                'timeout'   => 15,
                'body'      => wp_json_encode(
                    [            
                        'purchase_code' => $code,
                        'email'         => $email,
                        'token_id'      => BRANDBERRY_TOKEN_ID,
                        'domain'        => home_url(),
                    ]
                )
        ];
          
        $response = wp_remote_post(BRANDBERRY_PRODUCT_DOMAIN .'wp-json/eddenvatu/v1/license/activate', $args);
        $responseBody = json_decode($response['body']);
          
        if((! is_wp_error($response)) && (200 === wp_remote_retrieve_response_code($response))){
                $responseBody = json_decode($response['body']);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    if (isset($responseBody->success) && $responseBody->success == true) {
                       
                        update_option($this->theme_slug . '_key_status', 'valid');
                        update_option($this->theme_slug . '_key', $code);                     ;  

                        if (isset($_REQUEST['email'])) {
                            update_option($this->theme_slug . '_email', $email);
                        }
                       $this->check_already_activated($code,$email,'yes');
                        wp_send_json_success([
                            'next_step' => 'final_license_check',
                            'msg' => 'valid',
                            'code' => $code,
                            'email' => get_option('admin_email')
                        ]);
                    } else {
                        wp_send_json_error(['msg' => isset($responseBody->error) ? $responseBody->error : esc_html__('Contact Author', 'brandberry')]);
                    }
                }
        }else{
                wp_send_json_error(['msg' => isset($responseBody->message) ? $responseBody->message : esc_html__('Server Error', 'brandberry')]);
        }
        wp_send_json_error($result);     
        wp_die();
    }

    public function license_deactivate()
    {

        if (!wp_verify_nonce($_REQUEST['nonce'], "the_user_guider_secure")) {
            exit("No naughty business please");
        }

        $return['path'] = admin_url('admin.php?page='.BRANDBERRY_PARENT_PAGE_SLUG);
        if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'request') {

            $return['status']    = 'deactivated';
            $return['next_step'] = 'deactivated';
            $return['license']   = get_option($this->theme_slug . '_Key');
            $return['email']     = get_option($this->theme_slug . '_email');

            if (get_option($this->theme_slug . '_Key') && $this->theme_slug . '_key_status') {

                $this->api_params = array(
                    'edd_action'  => 'deactivate_license',
                    'license'     => get_option($this->theme_slug . '_Key'),                                 
                    'url'         => home_url(),
                    'item_id'   => get_option($this->theme_slug . '_item_id'),
                    'environment' => function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production',
                );
            
                // Call the custom API.			
                $response = wp_remote_post(BRANDBERRY_PRODUCT_DOMAIN, array('body' => $this->api_params, 'timeout' => 15, 'sslverify' => false));
            
                if (is_wp_error($response)) {
                    $return['status'] = wp_remote_retrieve_response_code($response);
                }

                $license_data = json_decode(wp_remote_retrieve_body($response), true);

                if (isset($license_data['license'])) {
                    $return['next_step']    = 'done';
                    $return['status']       = 'done';
                    update_option($this->theme_slug . '_Key', '');
                    delete_transient($this->theme_slug . '_theme_service_pass_cache');                    
                    delete_transient($this->theme_slug.'_license');
                    delete_user_meta(1, $this->theme_slug . '_theme_data');
                    update_option($this->theme_slug . '_key_status', '');
                } else {
                    $return['msg']       = $license_data['license'];
                    $return['next_step']    = 'done';
                }
            } else {
                $return['msg']       = 'removed';
                $return['next_step'] = 'done';
            }
        }

        wp_send_json($return);
        wp_die();
    }  
    
    function theme_activated_options()
    {
        $is_admin      = current_user_can('manage_options');
            
            $essential_plugin = [
                 array(
                    'title'         => esc_html__('Brandberry Essential', 'brandberry'),
                    'slug'         => 'brandberry-essential',
                    'required'     => true,                   
                    "remote-source" => false, 
                    'source'     => BRANDBERRY_THEME_DIR . '/app/third-party/brandberry-essential.zip', // The plugin source.               
                 ),
                 array(
                    'title'         => esc_html__('Contact Form 7', 'brandberry'),
                    'slug'         => 'contact-form-7',
                    "remote-source" => true,                              
                )
            ];
            if ((current_user_can('administrator') || $is_admin) && isset($_GET['page']) && $_GET['page'] === BRANDBERRY_PARENT_PAGE_SLUG) {
                wp_register_script(BRANDBERRY_TPL_SLUG.'-configure', BRANDBERRY_JS . '/user-configure.js', array('jquery'), time(), true);
                $params = array(
                    'ajaxurl'        => admin_url('admin-ajax.php'),
                    'ajax_nonce'     => wp_create_nonce('the_user_guider_secure'),
                    'license_domain' => BRANDBERRY_PRODUCT_DOMAIN,
                    'token_id'       => BRANDBERRY_TOKEN_ID,
                    'home_url'       => home_url(),
                    'required_plugins' => $essential_plugin,                    
                    'demo_active'    => function_exists('brandberry_option') ? brandberry_option('theme_demo_activate', true) : true,
                    'welcome_heading' => esc_html__('Welcome to Brandberry 1.0','brandberry'),
                    'welcome_desc' => esc_html__("Brandberry is a versatile, responsive, and beautifully crafted theme designed to be accessible and user-friendly. Built with Elementor, it includes seven stunning ready-made websites to get you started quickly.",'brandberry'),
                    'footer_link' => [
                        'documentation_link' => 'https://www.docs.treethemes.com/brandberry',
                        'support_link' => 'https://treethemes.ticksy.com/',
                    ],
                    'rating_link' => 'https://www.themeforest.net/user/treethemes',
                    'banner_link' => 'https://www.treethemes.com/assets/wp-content/uploads/2024/07/theme-preview.png',
                    'license_form_heading' => esc_html__('Unlock Full Access','brandberry'),
                    'license_form_desc' => esc_html__('Activate your license to enable all features, receive updates, and access premium support.','brandberry'),
                    'license_note' => esc_html__('We’ll send product updates to this email address. We won’t spam you.','brandberry'),
                    'license_instruction' => [
					    wp_kses_post(
					        sprintf(
					            __('How to find your license key → <a href="%s" target="_blank">View documentation</a>', 'brandberry'),
					            esc_url('https://docs.treethemes.com/brandberry/docs/getting-started/theme-installation-and-license-activation/#find-envato-license')
					        )
					    )
					]
                );
                wp_localize_script(BRANDBERRY_TPL_SLUG.'-configure', 'ajax_object', $params);
                wp_enqueue_script(BRANDBERRY_TPL_SLUG.'-configure');
            }
    }    

    public function setup(){
        /*
        * You can activate this if you're planning to build a multilingual theme
        */       

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'post-formats' , [
           'standard', 'image', 'video', 'audio'
        ]);
        
        //Thumbnail size 1200 x 780
        set_post_thumbnail_size(1200, 780, ['center', 'center']);

  
        add_theme_support( 'html5', array(
              'search-form',
              'comment-form',
              'comment-list',
              'gallery',
              'caption',
        ) );
        
        remove_theme_support( 'widgets-block-editor' );
        /*
        Register all your menus here
        */
        register_nav_menus( array(        
            'primary'     => esc_html__( 'Primary', 'brandberry' )    
        ) );
        
    }

    public function is_elementor_builder(){

	    if ( isset( $_GET['preview'] ) && $_GET['preview'] == true ) {
		    return false;
	    }

        if( ( isset($_GET['wcf-edit']) && $_GET['wcf-edit'] == '1' )) {
            return true;
        }
    
        return false;
    }
    
     
    
}
