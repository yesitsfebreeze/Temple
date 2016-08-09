$(function () {

    var sectionResize = function () {
        var $sections = $("section");
        $sections.css("height","");
        $.each($sections,function () {
            var height = $(this).outerHeight();
            $(this).css("height",height);
        });
    };

    sectionResize();
    $(window).off("resize.sectionResize").on("resize.sectionResize",function () {
        if (typeof window.sectionResizeTimer != "undefined") {
            window.clearTimeout(window.sectionResizeTimer);
        }
        window.sectionResizeTimer = setTimeout(function () {
            sectionResize();
        },200);
    })

});