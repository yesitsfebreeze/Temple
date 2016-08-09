$(function() {
    $(document).off("click.donate", ".jump-to-donate").on("click.donate", ".jump-to-donate", function(e) {
        e.preventDefault();
        $('html,body').animate({scrollTop: $('.donate-section').offset().top}, 555);
    });
});