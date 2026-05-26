<?php
/**
 * the template for displaying 404 pages (Not Found)
 */
get_header();
get_template_part( 'template-parts/banner/content', 'banner-404' ); 
$button_text = brandberry_option('error_btn_text',esc_html__('Back to Home','brandberry'));
$brandberry_option = brandberry_option('opt-tabbed-general');
$button_style = isset($brandberry_option['gl_button_style']) ? $brandberry_option['gl_button_style']: 'btn-hover-divide';
$is_elementorpro_exist = function_exists( 'elementor_theme_do_location' );        
if ( ! $is_elementorpro_exist || ! elementor_theme_do_location( '404' ) ) {
?>

   <main id="content-area">  
      <!-- 404 area start  -->
       <section class="default-error__area pt-150 pb-150">
         <div class="container">
           <div class="default-error__content">
             <h2 class="default-error__title mb-10"><?php echo esc_html(brandberry_option('error_title',esc_html__( '404', 'brandberry' ))); ?></h2>
             <h3 class="default-error__sub-title mb-40"><?php echo esc_html(brandberry_option('error_subtitle',esc_html__( 'Ops! Page not found', 'brandberry' ))); ?></h3>
             <div class="cf_text default-error__content mb-50">
             <?php echo wpautop( wp_kses_post(brandberry_option('error_content',esc_html__( 'The page you are looking for was moved, removed, renamed or never existed.', 'brandberry' )))); ?>
             </div>
             <?php if(brandberry_option('enable_404_search_button',1)): ?> 
             <div class="cf_btn default-error_go_btn">
               <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wc-btn-primary <?php echo esc_attr($button_style); ?>"><?php echo esc_html($button_text); ?> <i class="icon-wcf-checvron-right"></i> </a>
             </div>
             <?php endif; ?>
           </div>
         </div>
       </section>
       <!-- 404 area end  -->   
    </main>
<?php } ?>    
<?php get_footer(); ?>