$(function() {
    var fullapge = function() {
        $(".fullpage").css({
            minHeight: $(window).height()
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