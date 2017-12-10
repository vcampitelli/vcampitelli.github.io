$(function() {
    $('#navbar').affix({
        offset: {
            top: $('#whoami > h2').offset().top,
            /*
            bottom: function() {
                return (this.bottom = $('.footer').outerHeight(true))
            }
            */
        }
    });
})();
