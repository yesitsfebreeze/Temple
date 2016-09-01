$(function() {

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

    window.editor = {};

    window.editor.closeAnimation = function(editor) {
        editor.addClass("closing");
        setTimeout(function() {
            editor.remove();
        }, 1000);
    };
    window.editor.close = function(editor) {
        if(!editor.hasClass("fullscreen") && !editor.hasClass("minimized")) {
            window.editor.minimize(editor);
            setTimeout(function() {
                window.editor.closeAnimation(editor);
            }, 500);
        } else {
            window.editor.closeAnimation(editor);
        }
    };

    window.editor.minimize = function(editor) {
        if(editor.hasClass("minimized") || editor.hasClass("fullscreen")) {
            editor.removeClass("minimized").find(".editor-body").stop(true).slideDown();
        } else if(!editor.hasClass("fullscreen")) {
            editor.addClass("minimized").find(".editor-body").stop(true).slideUp();
        }
    };

    window.editor.fullscreen = function(editor) {
        var node = editor[0];
        if(editor.hasClass("fullscreen")) {
            editor.css({
                left: node.editorDimensions.newLeft,
                top: node.editorDimensions.newTop,
                position: "relative"
            }).stop(true).animate({
                width: node.editorDimensions.orgWidth,
                height: node.editorDimensions.orgHeight,
                left: 0,
                top: 0
            }, 300, function() {
                editor.css({
                    width: "",
                    height: ""
                })
            });
            editor.removeClass("fullscreen");
        } else {
            node.editorDimensions = {};
            node.editorDimensions.orgWidth = editor.width();
            node.editorDimensions.orgHeight = editor.height();
            node.editorDimensions.newLeft = 0 - editor.position().left;
            node.editorDimensions.newTop = 0 - editor.position().top;

            editor.addClass("fullscreen");
            window.editor.minimize(editor);
            editor.css({
                position: "relative"
            }).stop(true).animate({
                width: $(window).width(),
                maxWidth: $(window).width(),
                height: $(window).height(),
                maxHeight: $(window).height(),
                left: 0 - editor.position().left,
                top: 0 - editor.position().top
            }, 300, function() {
                editor.css({
                    position: "fixed",
                    left: 0,
                    top: 0
                });
            });
        }
    };
});