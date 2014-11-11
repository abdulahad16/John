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

		<section class="entry">

			<?php $email = get_post_meta(get_the_id(), '_gravatar_email', 'true'); ?>
			<?php $url = get_post_meta(get_the_id(), '_url', 'true'); ?>

			<?php if ( '' != $email || woo_image('return=true') ): ?>
				<div class="testimonial-avatar">
					<span class="testimonial-border">
						<?php 
							if ( '' != $email ) {
								echo get_avatar( $email, 100 );
							} else {
			    				woo_image( 'width=100&height=100&class=avatar avatar-100 photo' ); 
							}
						?>
					</span>
					<span class="testimonial-author">
						<a href="<?php echo esc_attr($url); ?>" title=""><?php echo get_the_title(); ?></a>
					</span>
				</div>
			<?php endif; ?>

			<div class="testimonial-content">
				<?php the_content( __( 'Continue Reading', 'woothemes' ) ); ?>
			</div>

			<div class="fix"></div>
				
		</section>

	</article><!-- /.post -->