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
        } else if(direction == "up") {
            distance = distance + "px";
            $el.css({"margin-top": distance});
        } else if(direction == "right") {
            distance = -Math.abs(distance) + "px";
            $el.css({"margin-left": distance});
        } else if(direction == "down") {
            distance = -Math.abs(distance) + "px";
            $el.css({"margin-top": distance});
        }
    });

    $scrollEl.off("scroll.parallax").on("scroll.parallax", function() {
        var offset = $scrollEl.scrollTop() + ($scrollEl.height() / 2);
        var $parallaxEls = $(".parallax");
        $.each($parallaxEls, function() {
            var $el = $(this);
            var distance = $el.data("distance");
            var direction = $el.data("direction");
            var speed = $el.data("speed");
            var originalPosition = $el.attr("data-originalPos");
            var startPosition = originalPosition - distance;

            if(offset > startPosition) {
                var difference = (offset - startPosition);
                var realPercentage = (difference / distance);
                var percentage = realPercentage * speed;

                var fadePecentage = realPercentage * 1.2;
                $el.css({opacity: fadePecentage});

                if(direction == "left" || direction == "up") {
                    distance = distance - (distance * percentage) + "px";
                    if(direction == "left") {
                        $el.css({"left": distance});
                    }
                    if(direction == "up") {
                        $el.css({"margin-top": distance});
                    }
                } else if(direction == "right" || direction == "down") {
                    distance = -Math.abs(distance) + (distance * percentage) + "px";

                    if(direction == "right") {
                        $el.css({"left": distance});
                    } else if(direction == "down") {
                        $el.css({"margin-top": distance});
                    }

                }

            } else {
                $el.css({opacity: 0});
            }


        });
    });
});