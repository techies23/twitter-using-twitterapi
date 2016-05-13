jQuery('.tf-logo-popup').click(function($) {
    var width  = 575,
    height = 400,
    left   = (jQuery(window).width()  - width)  / 2,
    top    = (jQuery(window).height() - height) / 2,
    url    = this.href,
    opts   = 'status=1' +
    ',width='  + width  +
    ',height=' + height +
    ',top='    + top    +
    ',left='   + left;
    
    window.open(url, 'twitter', opts);

    return false;
});

jQuery('.tf-widget-logo-follow').click(function($) {
    var width  = 575,
    height = 400,
    left   = (jQuery(window).width()  - width)  / 2,
    top    = (jQuery(window).height() - height) / 2,
    url    = this.href,
    opts   = 'status=1' +
    ',width='  + width  +
    ',height=' + height +
    ',top='    + top    +
    ',left='   + left;
    
    window.open(url, 'twitter', opts);

    return false;
});

jQuery('.tf-logo-follow').click(function($) {
    var width  = 575,
    height = 400,
    left   = (jQuery(window).width()  - width)  / 2,
    top    = (jQuery(window).height() - height) / 2,
    url    = this.href,
    opts   = 'status=1' +
    ',width='  + width  +
    ',height=' + height +
    ',top='    + top    +
    ',left='   + left;
    
    window.open(url, 'twitter', opts);

    return false;
});

//Lightbox Gallery
jQuery(document).ready(function($) {
    $('.tf-media-image').magnificPopup({
        type: 'image',
        delegate: 'a',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }
    });
    $('.tf-widget-media-image').magnificPopup({
        type: 'image',
        delegate: 'a',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }
    });
});
