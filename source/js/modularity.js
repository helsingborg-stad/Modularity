// Initialize postboxes

if (typeof postboxes !== 'undefined') {
    postboxes.add_postbox_toggles(pagenow);
}

(function($){

    $('#modularity-options #publish').on('click', function () {
        $(this).siblings('.spinner').css('visibility', 'visible');
    });

    $('a.wp-first-item[href="admin.php?page=modularity"]').parent('li').remove();

})(jQuery);
