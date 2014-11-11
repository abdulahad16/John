<?php
// File Security Check
if ( ! function_exists( 'wp' ) && ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?><?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;

	$settings = array(
				'homepage_enable_features' => 'true', 
				'homepage_enable_content' => 'true', 
				'homepage_content_type' => 'posts',
				'homepage_enable_testimonials' => 'true', 
				'homepage_enable_gallery' => 'true', 
				'homepage_enable_intro_message' => 'true',
				'homepage_number_of_features' => 3, 
				'homepage_number_of_testimonials' => 3, 
				'homepage_features_area_title' => 'Features',
				'homepage_enable_hero_product' => 'true',
				'homepage_testimonials_area_title' => sprintf( __( 'What people think of %s', 'woothemes' ), get_bloginfo( 'name' ) )
				);
					
	$settings = woo_get_dynamic_values( $settings );	
	
?>

	<?php
		// Intro message
    	if ( is_home() ) {
			if ( 'true' == $settings['homepage_enable_intro_message'] ) {
				get_template_part( 'includes/intro-message' );
			}	    		
    	}

	?>

    <div id="content" class="col-full">
    
    	<?php woo_main_before(); ?>
    
		<section id="main" class="homepage-area col-left">      
		
		<?php if ( is_home() && ! dynamic_sidebar( 'homepage' ) ) {

			if ( 'true' == $settings['homepage_enable_content'] ) {
				switch ( $settings['homepage_content_type'] ) {
					case 'page':
					get_template_part( 'includes/specific-page-content' );
					break;

					case 'posts':
					default:
					get_template_part( 'includes/blog-posts' );
					break;
				}
			}

			if ( 'true' == $settings['homepage_enable_features'] ) {
				$args = array( 'title' => $settings['homepage_features_area_title'], 'size' => 250, 'per_row' => 3, 'limit' => $settings['homepage_number_of_features'] );
				$args['before'] = '<section id="features" class="widget widget_woothemes_features home-section"><div class="inner"><a class="button view-all" href="' . get_post_type_archive_link( 'feature' ) . '" title="' . esc_attr__( 'Click here to view our features', 'woothemes' ) . '">' . __( 'View All' , 'woothemes' ) . '</a>';
				$args['after'] = '</div></section>';
				$args['before_title'] = '<header class="block"><h1>';
				$args['after_title'] = '</h1></header>';
				
				do_action( 'woothemes_features', $args );
			}

			if ( 'true' == $settings['homepage_enable_gallery'] ) {
				get_template_part( 'includes/home-gallery' );
			}

			if ( 'true' == $settings['homepage_enable_testimonials'] ) {
				$args = array( 'title' => $settings['homepage_testimonials_area_title'], 'size' => 60, 'per_row' => 3, 'limit' => $settings['homepage_number_of_testimonials'] );
				$args['before'] = '<section id="testimonials" class="widget widget_woothemes_testimonials home-section"><div class="inner"><a class="button view-all" href="' . get_post_type_archive_link( 'testimonial' ) . '" title="' . esc_attr__( 'Click here to view our testimonials', 'woothemes' ) . '">' . __( 'View All' , 'woothemes' ) . '</a>';
				$args['after'] = '</div></section>';
				$args['before_title'] = '<header class="block"><h1>';
				$args['after_title'] = '</h1></header>';
				
				do_action( 'woothemes_testimonials', $args );
			}			

		} ?>
                
		</section><!-- /#main -->

		<?php woo_main_after(); ?>

        <?php get_sidebar(); ?>       

    </div><!-- /#content -->

	<?php
		if ( is_woocommerce_activated() && 'true' == $settings['homepage_enable_hero_product'] ) {
			get_template_part( 'includes/hero-product' );
		}		
	?> 
		
<?php get_footer(); ?>