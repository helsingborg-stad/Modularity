// Initialize postboxes
postboxes.add_postbox_toggles(pagenow);

(function($){

    $('#modularity-options #publish').on('click', function () {
        $(this).siblings('.spinner').css('visibility', 'visible');
    });

})(jQuery);
