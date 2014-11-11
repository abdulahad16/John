<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Attachment Template
 *
 * This template is the default attachment template. It is used to display content when someone is viewing an attachment.
 * @link http://codex.wordpress.org/Post_Types
 *
 * @package Resort
 */
	get_header();
	global $post;

/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */

	$settings = array(
					'thumb_single' => 'false', 
					'single_w' => 200, 
					'single_h' => 200, 
					'thumb_single_align' => 'alignright', 
					'gallery_meta_enable' => 'true', 
					'gallery_attachments_author_box_enable' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );

?>

    <div id="content" class="col-full">

    	<?php woo_main_before(); ?>

		<section id="main" class="col-left">

<?php
if ( have_posts() ) { $count = 0;
	while ( have_posts() ) { the_post(); $count++;
		$author_id = get_the_author_meta( 'ID' );
		$single_post_id = get_the_ID();
?>
			<article <?php post_class( 'post' ); ?>>
				<div class="inner">
					<header>
		                <h1><?php the_title(); ?></h1>
	                	<?php
	                	if ( 'true' == $settings['gallery_meta_enable'] ) {
                			woo_post_meta();
                		}
	                	?>
	                </header>

	                <section class="entry fix">
	                	<div class="entry-attachment">
	<?php
		if ( wp_attachment_is_image() ) {
		$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID )
				break;
		}
		$k++;
		// If there is more than 1 image attachment in a gallery
		if ( count( $attachments ) > 1 ) {
			if ( isset( $attachments[ $k ] ) ) {
				// get the URL of the next image attachment
				$next_attachment_url = get_attachment_link( $attachments[$k]->ID );
			} else {
				// or get the URL of the first image attachment
				$next_attachment_url = get_attachment_link( $attachments[0]->ID );
			}
		} else {
			// or, if there's only 1 image attachment, get the URL of the image
			$next_attachment_url = wp_get_attachment_url();
		}
	?>
									<p class="attachment">
										<a href="<?php echo $next_attachment_url; ?>" title="<?php echo the_title_attribute(); ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a>
									</p>
									<div id="nav-below" class="navigation">
										<div class="nav-previous fl"><?php previous_image_link( false, '&larr; ' . __( 'Previous Image', 'woothemes' ) ); ?></div>
										<div class="nav-next fr"><?php next_image_link( false, __( 'Next Image', 'woothemes' ) . ' &rarr;' ); ?></div>
										<div class="fix"></div>
									</div><!-- #nav-below -->
	<?php } else { ?>
									<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo the_title_attribute(); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
	<?php } // End IF Statement ?>
								</div><!-- .entry-attachment -->

								<div class="entry-caption"><?php if ( has_excerpt() ) { the_excerpt(); } ?></div>
								
								<aside class="photo-meta fix">
						            <ul>
						            <?php the_tags( '<li><span class="tags">', ', ', '</span></li>' ); ?>
									<?php
											if ( wp_attachment_is_image() ) {
												echo '<li><span class="image-sizes">';
												$metadata = wp_get_attachment_metadata();
												printf( __( 'Full size is %s pixels', 'woothemes' ),
													sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
														wp_get_attachment_url(),
														esc_attr( __( 'Link to full-size image', 'woothemes' ) ),
														$metadata['width'],
														$metadata['height']
													)
												);
												echo '</span><!--/.image-sizes--></li>' . "\n";
											}
									?>
						        	</ul>
						        </aside>

						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
						<?php the_tags( '<p class="tags">', ', ', '</p>' ); ?>
						<?php if ( 0 < $post->post_parent ) { ?>
						<nav id="post-entries" class="fix">
		        		    <div class="nav-prev fl"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> %title' ); ?></div>
		        		    <div class="nav-next fr"><?php next_post_link( '%link', '%title <span class="meta-nav">&rarr;</span>' ); ?></div>
		        		</nav><!-- #post-entries -->
		        		<?php } ?>

					</section>

				</div>

            </article><!-- .post -->
            <?php
        		} // End while have_posts()
        	?>
        	<?php if ( 'true' == $settings['gallery_attachments_author_box_enable'] ) { ?>
				<aside id="post-author" class="fix">
					<div class="inner">
						<div class="profile-image"><?php echo get_avatar( get_the_author_meta( 'ID' ), '70' ); ?></div>
						<div class="profile-content">
							<h3 class="title"><?php printf( esc_attr__( 'About %s', 'woothemes' ), get_the_author() ); ?></h3>
							<?php the_author_meta( 'description' ); ?>
							<div class="profile-link">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'woothemes' ), get_the_author() ); ?>
								</a>
							</div><!-- #profile-link	-->
						</div><!-- .post-entries -->
					</div><!-- .inner -->
				</aside><!-- .post-author-box -->
			<?php } ?>
        	<?php
        	} else {
        	?>
			<article <?php post_class(); ?>>
            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
			</article><!-- .post -->
       	<?php } // End if have_posts() ?>

		<?php
			comments_template();
		?>

       </section>

       <?php woo_main_after(); ?>

        <?php get_sidebar(); ?>

        <div class="fix"></div>

    </div><!-- #content -->
<?php get_footer(); ?>