<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The default template for displaying content
 */

	global $woo_options;
 
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */

 	$settings = array(
					'thumb_w' => 100, 
					'thumb_h' => 100, 
					'thumb_align' => 'alignleft'
					);
					
	$settings = woo_get_dynamic_values( $settings );
 
?>

	<article <?php post_class(); ?>>

		<div class="inner">

			<header>
				<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				<?php if ( 'feature' != get_post_type() ) { woo_post_meta(); } ?>
			</header>

			<section class="entry">
			    <?php 
			    	if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] != 'content' ) { 
			    		woo_image( 'width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&class=thumbnail ' . $settings['thumb_align'] ); 
			    	} 
			    ?>				
				<?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'content' ) { the_content( __( 'Continue Reading', 'woothemes' ) ); } else { the_excerpt(); } ?>
			
				<div class="fix"></div>
					
			</section>

			<?php if ( 'feature' != get_post_type() ): ?>
				<footer class="post-more">      
					<?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'excerpt' ) { ?>
						<span class="read-more"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading', 'woothemes' ); ?>"><?php _e( 'Continue Reading', 'woothemes' ); ?></a></span>
					<?php } ?>
				</footer>
			<?php endif; ?>

		</div><!-- /.article-inner --> 

	</article><!-- /.post -->