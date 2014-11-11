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
					'thumb_align' => 'alignleft', 
					'gallery_meta_enable' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );
 
?>

	<article <?php post_class(); ?>>

		<div class="inner">

			<header>
				<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				<?php if ( 'true' == $settings['gallery_meta_enable'] ) { woo_post_meta(); } ?>
			</header>

			<section class="entry">
<?php
	if ( post_password_required() ) {
		the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'woothemes' ) );
	} else {
		$total_images = woo_count_gallery_images( get_the_ID() );
		if ( 0 < $total_images ) {
			$image_img_tag = woo_image( 'return=true&width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&class=thumbnail ' . $settings['thumb_align'] );
			
			$image_align = ' alignleft';
			$alignment_options = array( '', 'alignleft', 'alignright', 'aligncenter' );
			
			if (  in_array( $settings['thumb_align'], $alignment_options ) ) {
				$image_align = ' ' . $settings['thumb_align'];
			}
?>		
			    <span class="gallery-thumb<?php echo esc_attr( $image_align ); ?>">
					<?php echo $image_img_tag; ?>
				</span><!-- .gallery-thumb -->
				
				<p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'woothemes' ),
						'href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( sprintf( __( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?></em></p>
<?php
		}
		the_excerpt();
	}
?>
					
			</section>


			<footer class="post-more">      
			<?php if ( 'true' == $settings['gallery_meta_enable'] ) { ?>
				<span class="read-more"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'View Gallery', 'woothemes' ); ?>"><?php _e( 'View Gallery', 'woothemes' ); ?></a></span>
			<?php } ?>
			</footer>

		</div><!-- /.article-inner --> 

	</article><!-- /.post -->