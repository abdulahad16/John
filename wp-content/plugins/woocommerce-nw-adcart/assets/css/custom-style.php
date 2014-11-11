<?php header("Content-type: text/css; charset: UTF-8");
require_once( '../../../../../wp-load.php' );

$widget_params = get_option('nwadcart_plugin_settings');

?>
#nw-cart-drop-content { -webkit-border-radius: <?php echo $widget_params['nwadcart-border-radius'];?>px;-moz-border-radius: <?php echo $widget_params['nwadcart-border-radius'];?>px;border-radius: <?php echo $widget_params['nwadcart-border-radius'];?>px;border:1px solid #<?php echo $widget_params['nwadcart-background-border-color'];?>;background-color: #<?php echo $widget_params['nwadcart-background-color'];?>; }
#nw-cart-drop-content .button { color:#<?php echo $widget_params['nwadcart-button-text-color'];?> }
#nw-cart-drop-toggle { -webkit-border-radius: <?php echo $widget_params['nwadcart-border-radius'];?>px;-moz-border-radius: <?php echo $widget_params['nwadcart-border-radius'];?>px;border-radius: <?php echo $widget_params['nwadcart-border-radius'];?>px;border:1px solid #<?php echo $widget_params['nwadcart-background-border-color'];?>;background-color: #<?php echo $widget_params['nwadcart-background-color'];?>;color:#<?php echo $widget_params['nwadcart-text-color'];?> }
.nw-cart-contents, #nw-cart-drop-content { color:#<?php echo $widget_params['nwadcart-text-color'];?>; }
#nw-cart-drop-content a { color:#<?php echo $widget_params['nwadcart-link-color'];?>; }
.icns-adcartfont { color:#<?php echo $widget_params['nwadcart-icon-color'];?>; }

