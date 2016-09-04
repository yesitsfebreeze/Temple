$(function() {
    var animationSpeed = 350;
    var editors = $(".editor");

    $.each(editors, function() {
        var editor = $(this);
        var node = editor[0];
        node.editorDimensions = node.editorDimensions || {};
        node.editorDimensions.orgWidth = editor.width();
        node.editorDimensions.orgHeight = editor.height();
        editor.css({
            height: editor.height() - editor.find(".editor-header").outerHeight()
        }).css({
            maxHeight: "initial"
        })
    });

    $(document).on("click", ".editor .action-button", function() {
        var button = $(this);
        var editor = button.closest(".editor");
        if(button.hasClass("close-btn")) {
            window.editor.close(editor);
        } else if(button.hasClass("minimize-btn")) {
            window.editor.minimize(editor);
        } else if(button.hasClass("fullscreen-btn")) {
            window.editor.fullscreen(editor);
        }
    });

    editors.on('mouseenter', function() {
        window.currentScrollTop = $(window).scrollTop();
        window.currentScrollLeft = $(window).scrollTop();
        $(window).on("scroll.prevent", function() {
            $(window).scrollTop(window.currentScrollTop);
            $(window).scrollLeft(window.currentScrollLeft);
        });
    });

    editors.on('mouseleave', function() {
        $(window).off("scroll.prevent");
    });

    window.editor = {};

    window.editor.closeAnimation = function(editor) {
        editor.addClass("closing");
        setTimeout(function() {
            editor.remove();
        }, animationSpeed * 2);
    };
    window.editor.close = function(editor) {
        if(!editor.hasClass("fullscreen") && !editor.hasClass("minimized")) {
            window.editor.minimize(editor);
            setTimeout(function() {
                window.editor.closeAnimation(editor);
            }, animationSpeed);
        } else {
            window.editor.closeAnimation(editor);
        }
    };

    window.editor.minimize = function(editor) {
        var node = editor[0];
        if(editor.hasClass("minimized") || editor.hasClass("fullscreen")) {
            editor.removeClass("minimized").stop(true).animate({
                height: node.editorDimensions.orgHeight
            }, animationSpeed);
        } else if(!editor.hasClass("fullscreen")) {
            var headerHeight = editor.find(".editor-header").outerHeight();
            editor.addClass("minimized").stop(true).animate({
                height: headerHeight
            }, animationSpeed);
        }
    };

    window.editor.fullscreen = function(editor) {
        var node = editor[0];
        if(editor.hasClass("fullscreen")) {
            editor.stop(true).css({
                left: node.editorDimensions.newLeft,
                top: node.editorDimensions.newTop,
                position: "relative"
            }).animate({
                width: node.editorDimensions.orgWidth,
                height: node.editorDimensions.orgHeight,
                left: 0,
                top: 0
            }, animationSpeed);
            editor.removeClass("fullscreen");
        } else {
            node.editorDimensions = node.editorDimensions || {};
            node.editorDimensions.newLeft = 0 - (editor.offset().left - $(window).scrollLeft());
            node.editorDimensions.newTop = 0 - (editor.offset().top - $(window).scrollTop());

            editor.addClass("fullscreen");
            window.editor.minimize(editor);
            console.log(Math.abs(node.editorDimensions.newLeft));
            console.log(Math.abs(editor.offset().left));
            editor.css({
                position: "fixed",
                left: Math.abs(node.editorDimensions.newLeft),
                top: Math.abs(node.editorDimensions.newTop),
                height: node.editorDimensions.orgHeight,
                width: node.editorDimensions.orgWidth
            }).stop(true).animate({
                height: $(window).height(),
                width: $(window).width(),
                left: 0,
                top: 0
            }, animationSpeed, function() {
                editor.css({});
            });
        }
    };
});