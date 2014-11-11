<?php
/**
 * Template Name: Business
 *
 * This template creates a business style page optionally including a slider, features and testimonials 
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options;
 get_header();
 
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
$settings = array(
    'thumb_w' => 100, 
    'thumb_h' => 100, 
    'thumb_align' => 'alignleft',
    'business_display_slider' => 'true', 
    'business_display_features' => 'true', 
    'business_display_testimonials' => 'true', 
    'business_display_blog' => 'true'
);
                
$settings = woo_get_dynamic_values( $settings );
?>
    <!-- #content Starts -->
    <div id="content" class="col-full">
    
        <?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' && ! is_front_page() ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?> 

        <?php 
            // Display WooSlider if activated and specified in theme options
            if ( 'true' == $settings['business_display_slider'] ) { 
                do_action( 'wooslider' );
            }
        ?>

        <?php if (have_posts()) : $count = 0; ?>
        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <div <?php post_class(); ?>>

            	<div class="inner">

	                <header><h1 class="title"><?php the_title(); ?></h1></header>

	                <div class="entry">
	                    <?php the_content(); ?> 
	                </div><!-- /.entry -->

            	</div><!-- /.inner -->
                    
            </div><!-- /.post -->
            
                                                
        <?php endwhile; else: ?>
        <?php endif; ?>
        <div class="homepage-area">
        <?php 
            // Display Features if activated and specified in theme options
            if ( 'true' == $settings['business_display_features'] ) {

				$args = array( 'title' => 'Features', 'size' => 250, 'per_row' => 3, 'limit' => $settings['homepage_number_of_features'] );
				$args['before'] = '<section id="features" class="widget widget_woothemes_features home-section"><div class="inner"><a class="button view-all" href="' . get_post_type_archive_link( 'feature' ) . '" title="' . esc_attr__( 'Click here to view our features', 'woothemes' ) . '">' . __( 'View All' , 'woothemes' ) . '</a>';
				$args['after'] = '</div></section>';
				$args['before_title'] = '<header class="block"><h1>';
				$args['after_title'] = '</h1></header>';
				
				do_action( 'woothemes_features', $args );
 
            }
            // Display Features if activated and specified in theme options
            if ( 'true' == $settings['business_display_testimonials'] ) {

				$args = array( 'title' => 'Testimonials', 'size' => 60, 'per_row' => 3, 'limit' => $settings['homepage_number_of_testimonials'] );
				$args['before'] = '<section id="testimonials" class="widget widget_woothemes_testimonials home-section"><div class="inner"><a class="button view-all" href="' . get_post_type_archive_link( 'testimonial' ) . '" title="' . esc_attr__( 'Click here to view our testimonials', 'woothemes' ) . '">' . __( 'View All' , 'woothemes' ) . '</a>';
				$args['after'] = '</div></section>';
				$args['before_title'] = '<header class="block"><h1>';
				$args['after_title'] = '</h1></header>';
				
				do_action( 'woothemes_testimonials', $args );

            }
        ?>
    	</div><!--/.homepage-area-->

        <?php if ( 'true' == $settings['business_display_blog'] ) { ?>
        
        <!-- #main Starts -->
        <section id="main" class="col-left">       

        <?php
        	if ( get_query_var( 'paged') ) { $paged = get_query_var( 'paged' ); } elseif ( get_query_var( 'page') ) { $paged = get_query_var( 'page' ); } else { $paged = 1; }
        	
        	$query_args = array(
        						'post_type' => 'post', 
        						'paged' => $paged
        					);
        	
        	$query_args = apply_filters( 'woo_blog_template_query_args', $query_args ); // Do not remove. Used to exclude categories from displaying here.
        	
        	remove_filter( 'pre_get_posts', 'woo_exclude_categories_homepage' );
        	
        	query_posts( $query_args );
        	
        	if ( have_posts() ) {
        		$count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        ?>                                                            
				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>
                                                
        <?php
        		} // End WHILE Loop
        	
        	} else {
        ?>
            <article <?php post_class(); ?>>
            	<div class="inner">
                	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            	</div><!-- /.inner -->
            </article><!-- /.post -->
        <?php } // End IF Statement ?>  
    
            <?php woo_pagenav(); ?>
			<?php wp_reset_query(); ?>                

        </section><!-- /#main -->
            
		<?php get_sidebar(); ?>

        <?php } ?>

    </div><!-- /#content -->    
		
<?php get_footer(); ?>