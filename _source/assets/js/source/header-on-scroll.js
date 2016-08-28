$(function() {
    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var menu = $('.menu');
    var menuHeight = menu.outerHeight();

    $(window).on("scroll", function() {
        didScroll = true;
    });

    setInterval(function() {
        if(didScroll) {
            var scrollTop = $(window).scrollTop();

            if(Math.abs(lastScrollTop - scrollTop) <= delta) {
                return;
            }

            if(scrollTop > lastScrollTop && scrollTop > menuHeight) {
                $('.menu').removeClass('down').addClass('up');
            } else if(scrollTop + $(window).height() < $(document).height()) {
                $('.menu').removeClass('up').addClass('down');
            }

            lastScrollTop = scrollTop;
            didScroll = false;
        }
    }, 250);
});



