(function($) {

    $(document).ready(function() {
        $('.f13-recpatcha-error-field').each(function() {
            $('#'+$(this).data('field')).val(atob($(this).text()));
            //$(this).remove();
        });
    });


})(jQuery);