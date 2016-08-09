$(function () {
    $(".fix-height").height($(".fix-height").height());

    $.each($(".parent-height"),function () {
        $(this).height($(this).parent().outerHeight());
    })
});