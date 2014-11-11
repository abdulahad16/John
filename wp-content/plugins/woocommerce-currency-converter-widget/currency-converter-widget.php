<?php
/**
 * Currency Converter Widget
 *
 * @package		WooCommerce
 * @category	Widgets
 * @author		WooThemes
 */
class WooCommerce_Widget_Currency_Converter extends WP_Widget {

	/** Variables to setup the widget. */
	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;

	/** constructor */
	function WooCommerce_Widget_Currency_Converter() {

		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_currency_converter';
		$this->woo_widget_description = __( 'Allow users to choose a currency for prices to be displayed in.', 'wc_currency_converter' );
		$this->woo_widget_idbase = 'woocommerce_currency_converter';
		$this->woo_widget_name = __('WooCommerce Currency Converter', 'wc_currency_converter' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Create the widget. */
		$this->WP_Widget($this->woo_widget_idbase, $this->woo_widget_name, $widget_ops);
	}

	/** @see WP_Widget */
	function widget( $args, $instance ) {
		extract($args);

		$title   = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$show_reset   = $instance['show_reset'];

		echo $before_widget;

		if ($title) echo $before_title . $title . $after_title;

		?>
		<form method="post" id="currency_converter" action="">
			<div>
				<?php
					if ( $instance['message'] )
						echo wpautop( $instance['message'] );

					$currencies = array_map( 'trim', array_filter( explode( "\n", $instance['currency_codes'] ) ) );

					if ( $currencies ) {

						echo '<select class="currency_switcher">';

						foreach ( $currencies as $currency ) {

							$class    = '';

							if ( $currency == get_option( 'woocommerce_currency' ) )
								$class = 'reset default';

							echo '<option class="' . esc_attr( $class ) . '" data-currencycode="' . esc_attr( $currency ) . '">' . $currency . '</option>';
						}

						if ( $show_reset )
							echo '<option class="reset">' . __('Reset', 'wc_currency_converter') . '</option>';

						echo '</select>';
					}
				?>
			</div>
		</form>
		<?php

		echo $after_widget;
	}

	/** @see WP_Widget->update */
	function update( $new_instance, $old_instance ) {
		$instance['title']          = empty( $new_instance['title'] ) ? '' : strip_tags(stripslashes($new_instance['title']));
		$instance['currency_codes'] = empty( $new_instance['currency_codes'] ) ? '' : strip_tags(stripslashes($new_instance['currency_codes']));
		$instance['message']        = empty( $new_instance['message'] ) ? '' : strip_tags(stripslashes($new_instance['message']));
		$instance['show_reset']     = empty( $new_instance['show_reset'] ) ? '' : strip_tags(stripslashes($new_instance['show_reset']));
		return $instance;
	}

	/** @see WP_Widget->form */
	function form( $instance ) {
		global $wpdb;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wc_currency_converter') ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} else {echo __('Currency converter', 'wc_currency_converter');} ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('currency_codes'); ?>"><?php _e('Currency codes:', 'wc_currency_converter') ?> <small>(<?php _e('1 per line', 'wc_currency_converter') ?>)</small></label>
		<textarea class="widefat" rows="5" cols="20" name="<?php echo $this->get_field_name('currency_codes'); ?>" id="<?php echo $this->get_field_id('currency_codes'); ?>"><?php if ( ! empty( $instance['currency_codes'] ) ) echo esc_attr( $instance['currency_codes'] ); else echo "USD\nEUR"; ?></textarea>
		</p>

		<p><label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Widget message:', 'wc_currency_converter') ?></label>
		<textarea class="widefat" rows="5" cols="20" name="<?php echo $this->get_field_name('message'); ?>" id="<?php echo $this->get_field_id('message'); ?>"><?php if (isset ( $instance['message'])) {echo esc_attr( $instance['message'] );} else { echo "Currency conversions are estimated and should be used for informational purposes only."; } ?></textarea>
		</p>

		<p><label for="<?php echo $this->get_field_id('show_reset'); ?>"><?php _e('Show reset link:', 'wc_currency_converter') ?></label>
		<input type="checkbox" class="" id="<?php echo esc_attr( $this->get_field_id('show_reset') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_reset') ); ?>" value="1" <?php if (isset($instance['show_reset'])) checked($instance['show_reset'], 1); ?> /></p>
		<?php
	}
}