$(function() {
    var $scrollEl = $(window);
    $scrollEl.scrollTop(0);

    var $parallaxEls = $(".parallax");
    $.each($parallaxEls, function() {
        var $el = $(this);

        var elOffset = this.getBoundingClientRect().top;
        var distance = $el.attr("data-distance");
        var direction = $el.attr("data-direction");

        $el.attr("data-originalPos", elOffset);

        if(direction == "left") {
            distance = distance + "px";
            $el.css({"margin-left": distance});
        } else if(direction == "top") {
            distance = distance + "px";
            $el.css({"margin-top": distance});
        } else if(direction == "right") {
            distance = -Math.abs(distance) + "px";
            $el.css({"margin-left": distance});
        } else if(direction == "bottom") {
            distance = -Math.abs(distance) + "px";
            $el.css({"margin-top": distance});
        }
    });


    var data = function ($el,$name,$default) {
        return (typeof $el.data($name) != "undefined") ? $el.data($name) : $default;
    };

    $scrollEl.off("scroll.parallax").on("scroll.parallax", function() {
        var windowOffset = $scrollEl.scrollTop();
        var $parallaxEls = $(".parallax");
        $.each($parallaxEls, function() {
            var $el = $(this);
            var offset = data($el,"offset",300);
            var distance = data($el,"distance",250);
            var direction = data($el,"direction","bottom");
            var fade = data($el,"fade",2);
            var speed = data($el,"lag",.5);
            var minus = data($el,"minus",true);
            var originalPosition = $el.attr("data-originalPos");
            var startPosition = originalPosition - distance;

            offset = windowOffset + offset;

            if(offset > startPosition) {
                var difference = (offset - startPosition);
                var realPercentage = (difference / distance);
                var percentage = realPercentage * speed;

                finalDistance =  distance - (distance * percentage);
                if (direction == "right" || direction == "bottom") {
                    var finalDistance =  -Math.abs(distance) + (distance * percentage);
                }

                if(finalDistance > distance) {
                    finalDistance = distance;
                }

                if ((direction == "right" || direction == "bottom") && !minus) {
                    if(finalDistance > 0) {
                        finalDistance = 0;
                    }
                }

                if(finalDistance < -Math.abs(distance)) {
                        finalDistance = -Math.abs(distance);
                }

                if ((direction == "left" || direction == "top") && !minus) {
                    if(finalDistance < 0) {
                        finalDistance = 0;
                    }
                }

                finalDistance = finalDistance + "px";

                if(direction == "left" || direction == "right") {
                    $el.css({"left": finalDistance});
                } else if(direction == "top" || direction == "bottom") {
                    $el.css({"margin-top": finalDistance});
                }

                var fadePercentage = (1 - (Math.abs(parseInt(finalDistance.replace("px", ""))) / distance)) * fade;
                $el.css({opacity: fadePercentage});
            } else {
                $el.css({opacity: 0});
            }
        });
    });
});