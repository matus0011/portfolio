<?php
/*
  Include all options file here
 */

/* Theme menu page */
add_action('csf_loaded', function () {

    if (class_exists('CSF')) {
       
        require_once BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/parent-page.php';
        /* Theme options  settings*/
        require_once BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/general.php';
        require_once BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/header.php';
        require_once BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/blog.php';
		require_once BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/footer.php';
		require_once BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/woo.php';  
        include_once( BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/custom-code.php' );
		include_once( BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/custom-post-type.php' );
		
		include_once( BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/theme-optimize.php' );

        /* Post Meta */      
        include_once(BRANDBERRY_ESSENTIAL_DIR_PATH . '/inc/options/settings/backup.php');      
    }

}, 15);
