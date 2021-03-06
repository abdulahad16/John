jQuery( window ).load( function() {
    if ( woo_localized_data.slideshow == 'true' ) { woo_localized_data.slideshow = true; } else { woo_localized_data.slideshow = false; }
    if ( woo_localized_data.directionNav == 'true' ) { woo_localized_data.directionNav = true; } else { woo_localized_data.directionNav = false; }
    if ( woo_localized_data.controlNav == 'true' ) { woo_localized_data.controlNav = true; } else { woo_localized_data.controlNav = false; }
    if ( woo_localized_data.pauseOnHover == 'true' ) { woo_localized_data.pauseOnHover = true; } else { woo_localized_data.pauseOnHover = false; }

    jQuery( '#featured-slider .col-full' ).flexslider({
      animation: woo_localized_data.animation,
      controlsContainer: woo_localized_data.controlsContainer,
      smoothHeight: woo_localized_data.smoothHeight,
      directionNav: woo_localized_data.directionNav,
      controlNav: woo_localized_data.controlNav,
      manualControls: woo_localized_data.manualControls,
      slideshow: woo_localized_data.slideshow,
      pauseOnHover: woo_localized_data.pauseOnHover,
      slideshowSpeed: woo_localized_data.slideshowSpeed,
      animationDuration: woo_localized_data.animationDuration,
      touch: woo_localized_data.touch,
      pauseOnAction: woo_localized_data.pauseOnAction
    });
});