require(['jquery', 'owlCarousel'], function($) {
    $('#blog-slider').owlCarousel({
        items : 3,
        autoplay:false,
        loop:false,
        nav : true,
        dots: true,
        autoplaySpeed : 500,
        navSpeed : 500,
        dotsSpeed : 500,
        autoplayHoverPause: true,
        margin:10
    });
});