<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Template Name: Widgetized
 *
 * This template will display widgets added to the "Widgetized" sidebar area.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 global $woo_options;
 get_header();
?>
       
    <div id="content" class="page col-full">
    
    	<?php woo_main_before(); ?>
    	
		<section id="main" class="col-left fix">

            <?php if ( ! dynamic_sidebar( 'widgetized-template' ) ) { ?>

            <?php } ?>
                      
		</section><!-- /#main -->
		
		<?php woo_main_after(); ?>
		
        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>