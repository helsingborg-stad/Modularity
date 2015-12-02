Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Thickbox = (function ($) {

    function Thickbox() {
        $(function(){
            //this.handleEvents();
        }.bind(this));
    }

    Thickbox.prototype.modulePostCreated = function (postId) {
        tb_remove();
        console.info('Module content created with ID: ' + postId);
    };

    /**
     * Handle events
     * @return {void}
     */
    Thickbox.prototype.handleEvents = function () {

    };

    return new Thickbox();

})(jQuery);
