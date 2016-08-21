$(function() {

    var jumpTo = function(link) {
        var href = link.attr("href").replace("#", "");
        window.location.hash = href;
        $('html,body').animate({scrollTop: $(".jump-" + href).offset().top}, 555, "easeInSine");
    };

    if(window.location.hash) {
        var link = $("a[href='" + window.location.hash + "']");
        jumpTo(link);
    }


    $(document).off("click.donate", ".doc-link").on("click.donate", ".doc-link", function(e) {
        e.preventDefault();
        var link = $(this).find("a");
        jumpTo(link);
    });

    window.sectionBreadcrum = $(".section-breadcrumb a");

    $(window).off("scroll.docs").on("scroll.docs", function() {
        var jumpers = $(".docs-page-jumper:not(.no-index)");
        var breadcrumbName = false;
        var breadcrumbLink = false;

        if($(window).scrollTop() > $(".sidebar").height() + 250 && $(window).scrollTop() < ($(document).height() - $(window).height() - $("footer .dark.no-height").height())) {
            $(".breadcrumbs").addClass("in");
        } else {
            $(".breadcrumbs").removeClass("in");
        }

        $.each(jumpers, function() {
            var jumper = $(this);
            var offset = jumper.offset().top - 15;
            if(offset < $(window).scrollTop()) {
                breadcrumbLink = jumper.attr("class").replace("docs-page-jumper", "").trim().replace(/^jump-/, "");
                breadcrumbName = breadcrumbLink.replace(/_/g, " ");
            }
        });
        if(breadcrumbName && breadcrumbLink) {
            window.sectionBreadcrum.parent().addClass("in");
            window.sectionBreadcrum.html(breadcrumbName);
            window.sectionBreadcrum.attr("href", breadcrumbLink);
            window.sectionBreadcrum.attr("title", breadcrumbName);
        } else {
            window.sectionBreadcrum.parent().removeClass("in");
        }
    });
});