<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Home Gallery Component
 *
 * Display latest gallery posts
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */
$settings = array(
				'thumb_single' => 'false', 
				'single_w' => 200, 
				'single_h' => 200, 
				'thumb_single_align' => 'alignleft',
				'homepage_gallery_area_title' => 'Gallery',
				'homepage_number_of_galleries' => 3
				);
					
$settings = woo_get_dynamic_values( $settings );

$args = array(
	'posts_per_page' => intval( $settings['homepage_number_of_galleries'] ),
	'post_type' => 'post',
	'post_status' => 'publish',
	'tax_query' => array(
		array(
			'taxonomy' => 'post_format',
			'field' => 'slug',
			'terms' => 'post-format-gallery'
		)
	)
);

$query = new WP_Query( $args );

if ( ! is_wp_error( $query ) && $query->have_posts() ) {
?>

<section id="home-gallery">

	<article <?php post_class(); ?>>

		<div class="inner">

			<a href="<?php echo esc_url( get_post_format_link( 'gallery' ) ); ?>" title="<?php esc_attr_e( 'Click here to view our gallery', 'woothemes' ); ?>" class="button view-all"><?php _e( 'View All', 'woothemes' ); ?></a>

			<header class="block">
				<h1><?php echo $settings['homepage_gallery_area_title']; ?></h1>
			</header>

	<?php woo_loop_before(); ?>
	
		<ul>
		<?php
			while ( $query->have_posts() ) { $query->the_post();
		?>

			<li <?php post_class(); ?>>

					<?php
						if ( has_post_thumbnail() ) {
					?>
			    		<a href="<?php the_permalink(); ?>">
				    		<?php woo_image( 'width=280&height=190&class=thumbnail&link=img' ); ?>
							<div class="details">
								<h3><?php the_title(); ?></h3>
							</div>
						</a>
					<?php
						}
					?>

			</li>

		<?php
				} // End WHILE Loop ?>
		</ul>

		</div>

	</article>

</section>	
	
<?php } ?> 

<?php woo_loop_after(); ?>
