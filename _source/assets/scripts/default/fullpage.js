$(function() {
    var fullapge = function() {
        $(".fullpage").css({
            width: $(window).width(),
            height: $(window).height()
        });
        var larger = ($(window).width() > $(window).height()) ? $(window).width() : $(window).height();
        $(".fullpage").find(".gradient").css({
            minWidth: larger,
            minHeight: larger,
            marginLeft: 0 - larger / 2,
            marginTop: 0 - larger / 2
        });
    };
    fullapge();


    $(window).on("resize", function() {
        var timeOut = ("undefined" != typeof window.fullpageTimer) ? window.fullpageTimer : false;
        clearTimeout(timeOut);
        window.fullpageTimer = window.setTimeout(function() {
            fullapge();
        }, 80);
    });
});