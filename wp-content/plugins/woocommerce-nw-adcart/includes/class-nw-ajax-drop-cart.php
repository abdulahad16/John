<?php
/**
 * NW Ajax Drop Cart Class
 *
 * Main class
 *
 * @author 		Netwakies
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'NW_Ajax_Drop_Cart' ) ) :

class NW_Ajax_Drop_Cart {
	
	var $settings;
	var $icon_styles;
	
	public function __construct() {
		
		$this->settings = get_option( "nwadcart_plugin_settings" );
		$this->icon_styles = array(
			1=>'style1',
			2=>'style2',
			3=>'style3',
			4=>'style4',
			5=>'style5',
			6=>'style6',
			7=>'style7',
			8=>'style8',
			9=>'style9',
			10=>'style10',
		);
		
		/** Check WooCommerce Instalation **/
		add_action( 'wp_head', array($this , 'woostore_check_environment') );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'wp_enqueue_scripts', array($this,'nw_drop_cart_stylesheets') );
		add_action( 'wp_enqueue_scripts', array($this,'nw_drop_cart_scripts'),100 );
		
		add_action('wp_ajax_woocommerce_remove_from_cart',array(&$this,'woocommerce_ajax_remove_from_cart'),1000);
		add_action('wp_ajax_nopriv_woocommerce_remove_from_cart', array(&$this,'woocommerce_ajax_remove_from_cart'),1000);
		
		add_filter('add_to_cart_fragments', array(&$this,'woocommerce_header_add_to_cart_fragment'));
		
		add_shortcode('nwadcart_widget', array($this,'nwadcart_widget_shortcode'));
		
		if(is_admin()) {
			add_action( 'init', array($this,'pw_add_image_sizes') );
			add_filter('image_size_names_choose', array($this,'pw_show_image_sizes'));
	 		// create custom plugin settings menu
			add_action('admin_menu', array($this,'nwajaxdropcart_create_menu'));
			add_action( 'admin_enqueue_scripts', array($this,'nw_drop_cart_admin_stylesheets') );
			add_action( 'admin_enqueue_scripts', array($this,'nw_drop_cart_admin_scripts') );
			$settings = get_option( "nwadcart_plugin_settings" );
			if ( empty( $settings ) ) {
				$settings = array(
					'nwadcart-skin' => 'light',
					'nwadcart-position' => 'top',
					'nwadcart-subtotal' => 0,
					'nwadcart-border-radius' => 0,
					'nwadcart-item-name' => 'item',
					'nwadcart-item-name-plural' => 'items',
					'nwadcart-icon-style' => 'style1',
					'nwadcart-icon-color' => '000000',
					'nwadcart-custom-icon' => '',
					'nwadcart-use-custom-icon' => '',
					'nwadcart-text-color' => '000000',
					'nwadcart-link-color' => '000000',
					'nwadcart-button-text-color' => '000000',
					'nwadcart-background-color' => 'ffffff',
					'nwadcart-background-border-color' => 'e4e4e4',
				);
				add_option( "nwadcart_plugin_settings", $settings, '', 'yes' );
			}
		}
		
	}
	function pw_show_image_sizes($sizes) {
		    $sizes['nwadcart-thumb'] = __( 'Cart icon', 'nwadcart' );		 
		    return $sizes;
	}
	function pw_add_image_sizes() {
		    add_image_size( 'nwadcart-thumb', 20, 20, true );
	}
	function nwadcart_widget_shortcode( $atts ) {
		$type = "NW_Widget_Ajax_Drop_Cart";
		// Configure defaults and extract the attributes into variables
		extract( shortcode_atts( 
			array( 
				'title'  => ''
			), 
			$atts 
		));
		
		$args = array(
			
		);
		
		ob_start();
		the_widget( $type, $atts, $args ); 
		$output = ob_get_clean();
		
		return $output;
	}
	
	function nwadcart_admin_tabs( $current = 'homepage' ) {
	    $tabs = array( 'nwadcart-skin' => 'Skin', 'nwadcart-colors' => 'Colors', 'nwadcart-icons' => 'Icons', 'nwadcart-other' => 'Other' );
	    echo '<div id="icon-nwadcart" class="icon32"><br></div>';
	    echo '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
	        echo "<a class='nav-tab$class' href='?page=nwadcart-settings&tab=$tab'>$name</a>";
	
	    }
	    echo '</h2>';
	}
	
	function nwajaxdropcart_create_menu() {
	
		//create new top-level menu
		$settings_page = add_menu_page('NW ADCart Plugin Settings', 'NW ADCart', 'manage_options', 'nwadcart-settings', array($this,'nwadcart_settings_page'),"");
		
		add_action( "load-{$settings_page}", array($this,'nwadcart_load_settings_page') );
	}
	
	function nwadcart_load_settings_page() {
		if ( $_POST["nwadcart-settings-submit"] == 'Y' ) {
			check_admin_referer( "nwadcart-settings-page" );
			$this->nwadcart_save_plugin_settings();
			$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
			wp_redirect(admin_url('admin.php?page=nwadcart-settings&'.$url_parameters));
			exit;
		}
	}
	
	function nwadcart_save_plugin_settings() {
		global $pagenow;
		$settings = get_option( "nwadcart_plugin_settings" );
		
		if ( $pagenow == 'admin.php' && $_GET['page'] == 'nwadcart-settings' ){ 
			if ( isset ( $_GET['tab'] ) )
		        $tab = $_GET['tab']; 
		    else
		        $tab = 'nwadcart-skin'; 
	
		    switch ( $tab ){ 
		        case 'nwadcart-skin' :
					$settings['nwadcart-skin']	  = $_POST['nwadcart-skin'];
					$settings['nwadcart-position']	  = $_POST['nwadcart-position'];
					$settings['nwadcart-subtotal']	  = $_POST['nwadcart-subtotal'];
					$settings['nwadcart-border-radius']	  = $_POST['nwadcart-border-radius'];
					$settings['nwadcart-item-name']	  = $_POST['nwadcart-item-name'];
					$settings['nwadcart-item-name-plural']	  = $_POST['nwadcart-item-name-plural'];
				break;
				case 'nwadcart-colors' :
					$settings['nwadcart-icon-color']	  = $_POST['nwadcart-icon-color'];
					$settings['nwadcart-text-color']	  = $_POST['nwadcart-text-color'];
					$settings['nwadcart-link-color']	  = $_POST['nwadcart-link-color'];
					$settings['nwadcart-button-text-color']	  = $_POST['nwadcart-button-text-color'];
					$settings['nwadcart-background-color']	  = $_POST['nwadcart-background-color'];
					$settings['nwadcart-background-border-color']	  = $_POST['nwadcart-background-border-color'];	
				break;
				case 'nwadcart-icons' :
					$settings['nwadcart-icon-style']	  = $_POST['icon-style'];
					if(isset($_POST['custom_icon']) && !empty($_POST['custom_icon'])) $settings['nwadcart-custom-icon']	  = $_POST['custom_icon'];
					$settings['nwadcart-use-custom-icon']	  = ($_POST['use-custom-icon']=='1') ? $_POST['use-custom-icon'] : 0;
					$settings['nwadcart-icon-position']	  = $_POST['icon-position'];
				break;
				case 'nwadcart-other' :
					$settings['nwadcart-drop-trigger']	  = $_POST['drop-trigger'];
					$settings['nwadcart-display-cart']	  = $_POST['display-cart'];
				break;
		    }
		}
	
		$updated = update_option( "nwadcart_plugin_settings", $settings );
	}

	function nwadcart_settings_page() {
		global $pagenow;
	    $settings = get_option( "nwadcart_plugin_settings" );
	    
	    ?>
	    <div class="wrap nwadcart">
	    <?php
	    	if ( isset ( $_GET['tab'] ) ) $this->nwadcart_admin_tabs($_GET['tab']); else $this->nwadcart_admin_tabs('nwadcart-skin');
	    	
	    	if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
			else $tab = 'nwadcart-skin';
	    	
	    	
		?>
			<form method="post" action="<?php admin_url( 'admin.php?page=nwadcart-settings' ); ?>" enctype="multipart/form-data">
				<table class="form-table">
				
				<?php wp_nonce_field( "nwadcart-settings-page" ); 
				
					if ( $pagenow == 'admin.php' && $_GET['page'] == 'nwadcart-settings' ){
					
						 switch ( $tab ){
							 
							 case'nwadcart-skin':
							 
							 	 ?>
						         
						         <tr>
						            <th>Select preferred skin:</th>
						            <td>
						               <select name="nwadcart-skin" id="nwadcart-skin">
						               		<option <?php echo ($settings['nwadcart-skin']=='light') ? " selected='selected'": "";?>value="light">Light</option>           								               		<option <?php echo ($settings['nwadcart-skin']=='dark') ? " selected='selected'": "";?>value="dark">Dark</option>
						               		<option <?php echo ($settings['nwadcart-skin']=='custom') ? " selected='selected'": "";?>value="custom">Custom</option>
						               </select>
						            </td>
						         </tr>
						         <tr>
						            <th>Position of the widget in your theme:</th>
						            <td>
						               <select name="nwadcart-position" id="nwadcart-position">
						               		<option <?php echo ($settings['nwadcart-position']=='top') ? " selected='selected'": "";?>value="top">Top</option>           								               		<option <?php echo ($settings['nwadcart-position']=='left') ? " selected='selected'": "";?>value="left">Left</option>
						               		<option <?php echo ($settings['nwadcart-position']=='right') ? " selected='selected'": "";?>value="right">Right</option>
						               </select>
						            </td>
						         </tr>
						         <tr>
						            <th>Show subtotal:</th>
						            <td>
						               <input type="radio" <?php echo ($settings['nwadcart-subtotal']==1) ? "checked='checked'": "" ;?>name="nwadcart-subtotal" value="1"/>&nbsp;Yes
						               <input type="radio" <?php echo ($settings['nwadcart-subtotal']==0) ? "checked='checked'": "" ;?>name="nwadcart-subtotal" value="0"/>&nbsp;No
						            </td>
						         </tr>
						         
						         <tr>
						            <th>Border radius:</th>
						            <td>
						              <div id="radiusSlider"></div>
						              <input type="hidden" name="nwadcart-border-radius" value="<?php echo $settings['nwadcart-border-radius'];?>" id="radiusVal"/>
						              <span id="radiusSliderVal">0</span>px
						              <script type="text/javascript">
							              
							              jQuery(function() {
										    jQuery( "#radiusSlider" ).slider({
											    step:1,
											    value: <?php echo ($settings['nwadcart-border-radius']) ? $settings['nwadcart-border-radius'] : '0' ;?>,
											    slide: function( event, ui ) {
												    
												   jQuery('#radiusSliderVal').text(ui.value);
												   jQuery('#radiusVal').val(ui.value); 
											    }
										    });
										    
										    jQuery('#radiusSliderVal').text(<?php echo $settings['nwadcart-border-radius'];?>);
										    
										  });
							              
						              </script>
						            </td>
						         </tr>
						         
						         <tr>
						            <th>Item name:</th>
						            <td>
						              
						              <input type="text" name="nwadcart-item-name" value="<?php echo $settings['nwadcart-item-name'];?>"/>
						              
						            </td>
						         </tr>
						         
						         <tr>
						            <th>Item name plural:</th>
						            <td>
						              
						              <input type="text" name="nwadcart-item-name-plural" value="<?php echo $settings['nwadcart-item-name-plural'];?>"/>
						              
						            </td>
						         </tr>
						         
						         <?php
							 
							 break;
							 case'nwadcart-colors':
							 
							 	 ?>
						         
						         <tr>
						            <th>Icon color:</th>
						            <td>
						               <div id="colorSelector-i" class="colorSelector"><div></div></div>
						               <input type="hidden" name="nwadcart-icon-color" id="colorSelVal-i" value="<?php echo $settings['nwadcart-icon-color'];?>"/>
						               <script type="text/javascript">
							               jQuery('#colorSelector-i').ColorPicker({
												color: '#<?php echo $settings['nwadcart-icon-color'];?>',
												onShow: function (colpkr) {
													jQuery(colpkr).fadeIn(500);
													return false;
												},
												onHide: function (colpkr) {
													jQuery(colpkr).fadeOut(500);
													return false;
												},
												onChange: function (hsb, hex, rgb) {
													jQuery('#colorSelector-i div').css('backgroundColor', '#' + hex);
													jQuery('#colorSelVal-i').val(hex);
												}
											});
											jQuery('#colorSelector-i div').css('background-color','#<?php echo $settings['nwadcart-icon-color'];?>');
											jQuery('#colorSelVal-i').val('<?php echo $settings['nwadcart-icon-color'];?>');
						               </script>
						            </td>
						         </tr>
						         <tr>
						            <th>Text color:</th>
						            <td>
						               <div id="colorSelector-t" class="colorSelector"><div></div></div>
						               <input type="hidden" name="nwadcart-text-color" id="colorSelVal-t" value="<?php echo $settings['nwadcart-text-color'];?>"/>
						               <script type="text/javascript">
							               jQuery('#colorSelector-t').ColorPicker({
												color: '#<?php echo $settings['nwadcart-text-color'];?>',
												onShow: function (colpkr) {
													jQuery(colpkr).fadeIn(500);
													return false;
												},
												onHide: function (colpkr) {
													jQuery(colpkr).fadeOut(500);
													return false;
												},
												onChange: function (hsb, hex, rgb) {
													jQuery('#colorSelector-t div').css('backgroundColor', '#' + hex);
													jQuery('#colorSelVal-t').val(hex);
												}
											});
											jQuery('#colorSelector-t div').css('background-color','#<?php echo $settings['nwadcart-text-color'];?>');
											jQuery('#colorSelVal-t').val('<?php echo $settings['nwadcart-text-color'];?>');
						               </script>
						            </td>
						         </tr>
						         <tr>
						            <th>Link color:</th>
						            <td>
						               <div id="colorSelector-l" class="colorSelector"><div></div></div>
						               <input type="hidden" name="nwadcart-link-color" id="colorSelVal-l" value="<?php echo $settings['nwadcart-link-color'];?>"/>
						               <script type="text/javascript">
							               jQuery('#colorSelector-l').ColorPicker({
												color: '#<?php echo $settings['nwadcart-link-color'];?>',
												onShow: function (colpkr) {
													jQuery(colpkr).fadeIn(500);
													return false;
												},
												onHide: function (colpkr) {
													jQuery(colpkr).fadeOut(500);
													return false;
												},
												onChange: function (hsb, hex, rgb) {
													jQuery('#colorSelector-l div').css('backgroundColor', '#' + hex);
													jQuery('#colorSelVal-l').val(hex);
												}
											});
											jQuery('#colorSelector-l div').css('background-color','#<?php echo $settings['nwadcart-link-color'];?>');
											jQuery('#colorSelVal-l').val('<?php echo $settings['nwadcart-link-color'];?>');
						               </script>
						            </td>
						         </tr>
						         <tr>
						            <th>Button text color:</th>
						            <td>
						               <div id="colorSelector-btc" class="colorSelector"><div></div></div>
						               <input type="hidden" name="nwadcart-button-text-color" id="colorSelVal-btc" value="<?php echo $settings['nwadcart-button-text-color'];?>"/>
						               <script type="text/javascript">
							               jQuery('#colorSelector-btc').ColorPicker({
												color: '#<?php echo $settings['nwadcart-button-text-color'];?>',
												onShow: function (colpkr) {
													jQuery(colpkr).fadeIn(500);
													return false;
												},
												onHide: function (colpkr) {
													jQuery(colpkr).fadeOut(500);
													return false;
												},
												onChange: function (hsb, hex, rgb) {
													jQuery('#colorSelector-btc div').css('backgroundColor', '#' + hex);
													jQuery('#colorSelVal-btc').val(hex);
												}
											});
											jQuery('#colorSelector-btc div').css('background-color','#<?php echo $settings['nwadcart-button-text-color'];?>');
											jQuery('#colorSelVal-btc').val('<?php echo $settings['nwadcart-button-text-color'];?>');
						               </script>
						            </td>
						         </tr>
						         <tr>
						            <th>Background color:</th>
						            <td>
						               <div id="colorSelector-dc" class="colorSelector"><div></div></div>
						               <input type="hidden" name="nwadcart-background-color" id="colorSelVal-dc" value="<?php echo $settings['nwadcart-background-color'];?>"/>
						               <script type="text/javascript">
							               jQuery('#colorSelector-dc').ColorPicker({
												color: '#<?php echo $settings['nwadcart-background-color'];?>',
												onShow: function (colpkr) {
													jQuery(colpkr).fadeIn(500);
													return false;
												},
												onHide: function (colpkr) {
													jQuery(colpkr).fadeOut(500);
													return false;
												},
												onChange: function (hsb, hex, rgb) {
													jQuery('#colorSelector-dc div').css('backgroundColor', '#' + hex);
													jQuery('#colorSelVal-dc').val(hex);
												}
											});
											jQuery('#colorSelector-dc div').css('background-color','#<?php echo $settings['nwadcart-background-color'];?>');
											jQuery('#colorSelVal-dc').val('<?php echo $settings['nwadcart-background-color'];?>');
						               </script>
						            </td>
						         </tr>
						         <tr>
						            <th>Border color:</th>
						            <td>
						               <div id="colorSelector-dbc" class="colorSelector"><div></div></div>
						               <input type="hidden" name="nwadcart-background-border-color" id="colorSelVal-dbc" value="<?php echo $settings['nwadcart-background-border-color'];?>"/>
						               <script type="text/javascript">
							               jQuery('#colorSelector-dbc').ColorPicker({
												color: '#<?php echo $settings['nwadcart-background-border-color'];?>',
												onShow: function (colpkr) {
													jQuery(colpkr).fadeIn(500);
													return false;
												},
												onHide: function (colpkr) {
													jQuery(colpkr).fadeOut(500);
													return false;
												},
												onChange: function (hsb, hex, rgb) {
													jQuery('#colorSelector-dbc div').css('backgroundColor', '#' + hex);
													jQuery('#colorSelVal-dbc').val(hex);
												}
											});
											jQuery('#colorSelector-dbc div').css('background-color','#<?php echo $settings['nwadcart-background-border-color'];?>');
											jQuery('#colorSelVal-dbc').val('<?php echo $settings['nwadcart-background-border-color'];?>');
						               </script>
						            </td>
						         </tr>
						         
						         <?php
							
							 break;
							 case'nwadcart-icons':
							 
							 	 ?>
						         
						         <tr>
						            <th>Icon Style:</th>
						            <td>
						               <ul class="list-icons">
						               <?php foreach($this->icon_styles as $key=>$icon_style) : 
							               if($settings['nwadcart-icon-style']==$key) $cls = 'activei';
							               else $cls='';
						               ?>
						               	<li class="<?php echo $cls;?>">
						               		<div class="icon-adcartfont icon-<?php echo $icon_style;?>"></div>
						               		<input type="radio" name="icon-style" value="<?php echo $key+1;?>" style="display:none;"<?php echo ($settings['nwadcart-icon-style']==$key+1) ? " checked='checked'": "" ;?> />
						               	</li>
						               	<?php endforeach;?>
						               </ul>
						            </td>
						         </tr>
						         <tr valign="top">
								 <th scope="row">Custom icon:</th>
								 <td><label for="upload_image">
								 <input id="custom_icon" type="text" size="36" name="custom_icon" value="" />
								 <input id="upload_image_button" type="button" value="Upload Image" />
								 <br />Enter an URL or upload an image for the icon (preferred size 20x20).
								 </label>
								 <?php 
								
								 if($settings['nwadcart-custom-icon'] && file_exists(ABSPATH.str_replace(get_site_url()."/","",$settings['nwadcart-custom-icon']))) :?>
								 <br/><br/>
								 <label style="vertical-align:top;">
								 Current icon:</label>
								 <img src="<?php echo $settings['nwadcart-custom-icon'];?>"/>
								 <br/>
								 <label for="use-custom-icon">
								 Use custom icon:
								 <input type="checkbox"<?php echo ($settings['nwadcart-use-custom-icon']) ? " checked='checked'" : "" ;?> name="use-custom-icon" id="use-custom-icon" value="1"/>&nbsp;Yes 
								 </label>
								 <?php endif;?>
								 </td>
								 </tr>
								 <tr>
						            <th>Icon Position:</th>
						            <td>
						               <select name="icon-position" id="icon-position">
						               		<option <?php echo ($settings['nwadcart-icon-position']=='left') ? " selected='selected'": "";?>value="left">Left</option>           								            <option <?php echo ($settings['nwadcart-icon-position']=='right') ? " selected='selected'": "";?>value="right">Right</option>
						               </select>
						            </td>
						         </tr>
								 
						         <?php
							 
							 break;
							 case'nwadcart-other':
							 ?>
							 	<tr>
						            <th>Select drop down action:</th>
						            <td>
						               <select name="drop-trigger" id="drop-trigger">
						               		<option <?php echo ($settings['nwadcart-drop-trigger']=='click') ? " selected='selected'": "";?>value="click">Click</option>           								            <option <?php echo ($settings['nwadcart-drop-trigger']=='hover') ? " selected='selected'": "";?>value="hover">Hover</option>
						               </select>
						            </td>
						         </tr>
						         <tr>
						            <th>Show cart on:</th>
						            <td>
						            <?php //var_dump($settings['nwadcart-display-cart']); ?>
						               <select name="display-cart[]" id="display-cartr" multiple size="8">
						               		<option <?php echo (in_array('shop',(array)$settings['nwadcart-display-cart'])) ? " selected='selected'": "";?>value="shop">Shop Page</option>
						               		<option <?php echo (in_array('category',(array)$settings['nwadcart-display-cart'])) ? " selected='selected'": "";?>value="category">Category Page</option>
						               		<option <?php echo (in_array('product_tag',(array)$settings['nwadcart-display-cart'])) ? " selected='selected'": "";?>value="product_tag">Product Tag Page</option>
						               		<option <?php echo (in_array('product',(array)$settings['nwadcart-display-cart'])) ? " selected='selected'": "";?>value="product">Product Page</option>
						               		<option <?php echo (in_array('cart',(array)$settings['nwadcart-display-cart'])) ? " selected='selected'": "";?>value="cart">Cart Page</option>
						               		<option <?php echo (in_array('checkout',(array)$settings['nwadcart-display-cart'])) ? " selected='selected'": "";?>value="checkout">Checkout Page</option>
						               		<option <?php echo (in_array('account',(array)$settings['nwadcart-display-cart'])) ? " selected='selected'": "";?>value="account">Account Page</option>
						               </select>
						            </td>
						         </tr>
							 <?php
							 break;
							 
						 }
					}
					

				?>
				</table>
					<p class="submit">
	                    <input type="submit" class="button-primary" value="<?php _e('Save Changes','nwadcart') ?>" />
	                    <input type="hidden" name="nwadcart-settings-submit" value="Y"/>
	                </p>
		
				</form>
			</div>
			
		<?php
		 
		
	}
	
	public function woocommerce_ajax_remove_from_cart() {
		global $woocommerce;
		
		$woocommerce->cart->set_quantity( $_POST['remove_item'], 0 );
		
		$ver = explode(".", WC_VERSION);
		
		if($ver[1] == 1 && $ver[2] >= 2 ) :
			$wc_ajax = new WC_AJAX();
			$wc_ajax->get_refreshed_fragments();
		else :
			woocommerce_get_refreshed_fragments();
		endif;
		
		die();
	}
	
	/**
	 * Checks WooCommerce Installation
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function woostore_check_environment() {
		if (!class_exists('woocommerce')) wp_die(__('WooCommerce must be installed', 'oxfordshire')); 
	}
	
	/**
	 * Enqueue plugin style-files
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function nw_drop_cart_stylesheets() {
		// Respects SSL, Style.css is relative to the current file
        wp_register_style( 'nw-styles', plugins_url('assets/css/style.css', dirname(__FILE__)) );
        
        wp_register_style( 'nw-styles-dark', plugins_url('assets/css/style-dark.css', dirname(__FILE__)), 'nw-styles' );
        wp_register_style( 'nw-styles-light', plugins_url('assets/css/style-light.css', dirname(__FILE__)), 'nw-styles' );
        wp_register_style( 'nw-styles-custom', plugins_url('assets/css/custom-style.php', dirname(__FILE__)), 'nw-styles' );
        
        wp_enqueue_style( 'nw-styles' );
        
        switch($this->settings['nwadcart-skin']) {
	        case'light':
	        	wp_enqueue_style( 'nw-styles-light' );
	        break;
	        case'dark':
	        	wp_enqueue_style( 'nw-styles-dark' );
	        break;
	        case'custom':
	        	wp_enqueue_style( 'nw-styles-custom' );
	        break;
        }
       
        
	}
	
	/**
	 * Enqueue plugin style-files for admin
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function nw_drop_cart_admin_stylesheets() {
		if(strstr($_SERVER['REQUEST_URI'], 'nwadcart-settings')) {
	        wp_register_style( 'nw-styles', plugins_url('admin/assets/css/style.css', dirname(__FILE__)) );
	        wp_register_style( 'nw-styles-colorpicker', plugins_url('admin/lib/colorpicker/css/colorpicker.css', dirname(__FILE__)) );
	        wp_register_style( 'nw-styles-colorpicker-layout', plugins_url('admin/lib/colorpicker/css/layout.css', dirname(__FILE__)), 'nw-styles-colorpicker');
	        wp_register_style( 'nw-styles-jquery-ui', "http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" );
	        
	        wp_enqueue_style('thickbox');
	        wp_enqueue_style( 'nw-styles-jquery-ui' );
	        wp_enqueue_style( 'nw-styles' );
	        wp_enqueue_style( 'nw-styles-colorpicker' );
	        wp_enqueue_style('nw-styles-colorpicker-layout');
        }
	}
	
	/**
	 * Enqueue plugin javascript-files for admin
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function nw_drop_cart_admin_scripts() {
		if(strstr($_SERVER['REQUEST_URI'], 'nwadcart-settings')) {
	        wp_register_script( 'nw-scripts', plugins_url('admin/assets/js/ui.js', dirname(__FILE__)), array('jquery','media-upload','thickbox') );
	        wp_register_script( 'nw-scripts-colorpicker', plugins_url('admin/lib/colorpicker/js/colorpicker.js', dirname(__FILE__)) );
	        
	        wp_register_script( 'jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', 'jquery', '', true );
	        wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
	        
			wp_enqueue_script( 'jquery-ui' );
	        
	        wp_enqueue_script( 'nw-scripts' );
	        wp_enqueue_script( 'nw-scripts-colorpicker' );
        }
	}
	
	/**
	 * Enqueue plugin javascript-files
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function nw_drop_cart_scripts() {
		// Respects SSL, Style.css is relative to the current file
		wp_enqueue_script("jquery");
		wp_enqueue_script( "jquery-effects-core" );
        wp_register_script( 'nw-scripts', plugins_url('assets/js/ui.js', dirname(__FILE__)) ); 
        wp_enqueue_script( 'nw-scripts', plugins_url('assets/js/ui.js', dirname(__FILE__)), array('jquery','jquery-effects-core') );
       
	}
	
	/**
	 * register_widgets function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_widgets() {
		include(NW_AJAX_DROP_CART_PATH . "includes/class-nw-ajax-drop-cart-widget.php");
		
		register_widget( 'NW_Widget_Ajax_Drop_Cart' );
	}
 
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		
		$cart_contents_count = 0;
		$show_only_individual = false;
		$settings = get_option( "nwadcart_plugin_settings" );
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
		
		ob_start();
	//var_dump($settings['nwadcart-icon-position']);
		?>
		<div id="nw-cart-contents"<?php echo ($settings['nwadcart-icon-position']=="right") ? ' class="nw-pull-left"': "" ;?>>
		<span class="nw-visible-desktop nw-visible-phone">
		<?php echo sprintf(_n('%d '.$this->settings['nwadcart-item-name'], '%d '.$this->settings['nwadcart-item-name-plural'], $cart_contents_count, 'nwajaxdropcart'), $cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?>
		</span>
		<span id="nw-short-contents" class="nw-visible-tablet nw-hidden-phone">
			<?php echo $cart_contents_count;?>
		</span>
		</div>
		
		<?php
		
		$fragments['div#nw-cart-contents'] = ob_get_clean();
		
		ob_start();
		
		include_once(dirname(__FILE__).'/../templates/cart/mini-cart.php');
		
		$fragments['div#nw-cart-drop-content-in'] = ob_get_clean();
		
		return $fragments;
		
	}

	
}

endif;