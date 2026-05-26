
      <?php
             $is_elementorpro_exist = function_exists( 'elementor_theme_do_location' );        
	         if ( ! $is_elementorpro_exist || ! elementor_theme_do_location( 'footer' ) ) {
                  get_template_part( 'template-parts/footer/footer' ); 
               }
            ?>
      <?php wp_footer(); ?>            
   </body>
</html>