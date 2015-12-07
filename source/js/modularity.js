var Modularity = Modularity || {};

// Initialize postboxes
if (typeof postboxes !== 'undefined') {
    postboxes.add_postbox_toggles(pagenow);
}

(function($){

    // Show spinner when clicking save on Modularity options page
    $('#modularity-options #publish').on('click', function () {
        $(this).siblings('.spinner').css('visibility', 'visible');
    });

    // Remove the first menu item in the Modularity submenu (admin menu)
    $('a.wp-first-item[href="admin.php?page=modularity"]').parent('li').remove();

    // Trigger autosave when switching tabs
    $('#modularity-tabs a').on('click', function (e) {
        if (wp.autosave) {
            $(window).unbind();

            wp.autosave.server.triggerSave();

            $(document).ajaxComplete(function () {
                return true;
            });
        }

        return true;
    });

})(jQuery);


/* Auto scrolling content */ 
(function($){
	jQuery(document).scroll(function(){
		if(jQuery("#modularity-mb-modules").length) {
		    var offset = jQuery("#modularity-mb-modules").offset(); 
			if ( window.pageYOffset > offset.top - jQuery("#wpadminbar").outerHeight() ) {
				jQuery("#modularity-mb-modules").css("paddingTop", window.pageYOffset-offset.top + jQuery("#wpadminbar").outerHeight() )
			} else {
				jQuery("#modularity-mb-modules").css("paddingTop",0); 
			}
	    }
	}); 
})(jQuery);

/* Max height */ 
(function($){
	$( window ).resize(function() {
		if(jQuery("#modularity-mb-modules").length) {
			jQuery(".modularity-modules").css("maxHeight", "60vh"); 
		}
	});
})(jQuery);

(function($){
	if(jQuery("#modularity-mb-modules").length) {
		jQuery(".modularity-modules").css("maxHeight", "60vh"); 
	}
})(jQuery);