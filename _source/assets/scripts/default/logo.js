$(function() {
    $(window).on("scroll", function() {
        var header = $("header");
        var distance = header.offset().top + header.outerHeight();
        var st = $(window).scrollTop();
        var stickyLogo = $(".logo-sticky");
        if (st > distance) {
            stickyLogo.addClass("in");
        } else {
            stickyLogo.removeClass("in");
        }
    })
});