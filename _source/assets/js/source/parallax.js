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
            var elOffset = $(this).offset().top;
            var originalPosition = $el.attr("data-originalPos");
            var startPosition = originalPosition - distance;

            if(offset > startPosition) {
                var difference = offset - startPosition; 
                var percentage = difference / distance;

                $el.css({"margin-top": ((-Math.abs(distance) * speed) * percentage)});

                // var percentage =  distance / difference;
                // console.log(percentage);
            }


            // console.log(elOffset);
        });
    });
});