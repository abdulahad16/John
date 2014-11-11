<?php 
$settings = array(
				'homepage_intro_message_heading' => '',
				'homepage_intro_message_content' => '',
				'homepage_intro_message_button_label' => '',
				'homepage_intro_message_button_url' => ''
			);
					
$settings = woo_get_dynamic_values( $settings );
?>
<?php if ( '' != $settings['homepage_intro_message_heading'] ) { ?>

<section id="intro-message">
	<div class="col-full">
		<div class="left-section">
			<h2><?php echo $settings['homepage_intro_message_heading']; ?></h2>
			<p><?php echo $settings['homepage_intro_message_content']; ?></p>
		</div>
		<div class="right-section">
			<a class="button" href="<?php echo $settings['homepage_intro_message_button_url']; ?>"><?php echo $settings['homepage_intro_message_button_label']; ?></a>
		</div>
	</div>
</section><!--/#intro-message-->
<?php } ?>