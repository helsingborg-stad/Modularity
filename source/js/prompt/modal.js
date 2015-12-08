Modularity = Modularity || {};
Modularity.Prompt = Modularity.Prompt || {};

Modularity.Prompt.Modal = (function ($) {

    var isOpen = false;

    function Modal() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    Modal.prototype.open = function (url) {
        $('body').addClass('modularity-modal-open').append('\
            <div id="modularity-modal">\
                <div class="modularity-modal-wrapper">\
                    <button class="modularity-modal-close" data-modularity-modal-action="close">&times; ' + modularityAdminLanguage.close + '</button>\
                    <iframe class="modularity-modal-iframe" src="' + url + '" frameborder="0" allowtransparency></iframe>\
                </div>\
            </div>\
        ');

        isOpen = true;
    };

    Modal.prototype.close = function () {
        $('body').removeClass('modularity-modal-open');
        $('#modularity-modal').remove();
        isOpen = false;
    };

    Modal.prototype.handleEvents = function () {
        /*
        $(document).on('click', 'a[data-modularity-modal]', function (e) {
            e.preventDefault();
            var element = $(e.target).closest('a[data-modularity-modal]');
            this.open(element.attr('href'));
        }.bind(this));
        */

        $(document).on('click', '[data-modularity-modal-action="close"]', function (e) {
            e.preventDefault();
            this.close();
        }.bind(this));
    };

    return new Modal();

})(jQuery);
