<?php
/**
 * NW Ajax Drop Cart Class
 *
 * Main class
 *
 * @author 		Netwakies
 * @version 	1.0.0
 * @extends		WC_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class NW_Widget_Ajax_Drop_Cart extends WP_Widget {

	public function NW_Widget_Ajax_Drop_Cart(  ) {
		// widget actual processes
		parent::__construct( false, 'NW ADCart' );
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
		global $woocommerce;
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
				
		$cart_contents_count = 0;
		$settings = get_option( "nwadcart_plugin_settings" );
		
		$current_page = "other";
		if(is_shop())
			$current_page = "shop";
		if(is_product_category())
			$current_page = "category";
		if(is_product_tag())
			$current_page = "product_tag";
		if(is_product())
			$current_page = "product";
		if(is_cart())
			$current_page = "cart";
		if(is_checkout())
			$current_page = "checkout";
		if(is_account_page())
			$current_page = "account";
		
		$display = true;
		if(!in_array($current_page,(array)$settings['nwadcart-display-cart']) && $current_page!="other")
			$display = false;
		
		$show_only_individual = false;
		
		foreach($woocommerce->cart->cart_contents as $key => $product) {

			if($show_only_individual) {
				if($product['data']->product_type=='simple' && !isset($product['bundled_by']) ) $cart_contents_count++;
				if($product['data']->product_type=='bundle') $cart_contents_count++;
				if($product['data']->product_type=='variation') $cart_contents_count++;
			}else {
				if($product['data']->product_type=='simple' && !isset($product['bundled_by']) ) $cart_contents_count += $product['quantity'] ;
				if($product['data']->product_type=='bundle') $cart_contents_count += $product['quantity'];
				if($product['data']->product_type=='variation') $cart_contents_count += $product['quantity'];
			}
			
		}
		
		
		if($display) :
		
		?>
		<div id="nw-drop-cart">
			<?php if ( ! empty( $title ) ) : ?>
			<div class="nw-cart-title">
			
			<?php echo $before_title . $title . $after_title; ?>	
			
			</div>
			<?php endif; ?>
			<div class="nw-cart-container<?php echo (isset($settings['nwadcart-drop-trigger'])) ? " nw-cart-".$settings['nwadcart-drop-trigger']: " nw-cart-hover";?><?php echo (isset($settings['nwadcart-position']) && ($settings['nwadcart-position'] == "left" || $settings['nwadcart-position'] == "right")) ? " nw-cart-side" : "" ;?>">
				<?php //if($settings['nwadcart-position']=='top') : ?>
				<div id="nw-cart-drop-toggle">
					<div id="nw-cart-contents"<?php echo ($settings['nwadcart-icon-position']=="right") ? ' class="nw-pull-left"': "" ;?>>
						<span class="nw-visible-desktop nw-visible-phone">	
							<?php echo sprintf(_n('%d '.$settings['nwadcart-item-name'], '%d '.$settings['nwadcart-item-name-plural'], $cart_contents_count, 'woothemes'), $cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?>
						</span>
						<span id="nw-short-contents" class="nw-visible-tablet nw-hidden-phone">
							<?php echo $cart_contents_count;?>
						</span>
					</div>
					
					<div class="nw-cart-icns<?php echo ($settings['nwadcart-icon-position']=="right") ? " nw-pull-right": "" ;?>">
						<?php if($settings['nwadcart-use-custom-icon']==1 && $settings['nwadcart-custom-icon']!="") :?>
						<img src="<?php echo $settings['nwadcart-custom-icon'];?>" width="20px" height="20px" alt="Shopping Cart"/>
						<?php else: ?>
						<div class="nw-cart-icns-shape icns-adcartfont icns-style<?php echo $settings['nwadcart-icon-style'];?>"></div>
						<?php endif; ?>
					</div>
				</div>
				<?php //endif; ?>
				<div id="nw-cart-drop-content" class="nw-hidden<?php echo ($settings['nwadcart-position']!='top') ? " nw-cart-".$settings['nwadcart-position'] : "" ;?>">
					<?php 		
						include_once(dirname(__FILE__).'/../templates/cart/mini-cart.php');		
					?>
				</div>
				
			</div>
		</div>
		<?php
		endif;
		echo $after_widget;

	}
	
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Cart', 'nwadcart' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'nwadcart' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}