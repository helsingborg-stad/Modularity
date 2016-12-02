Modularity = Modularity || {};
Modularity.Prompt = Modularity.Prompt || {};

Modularity.Prompt.Modal = (function ($) {

    var isOpen = false;

    function Modal() {
        this.handleEvents();
    }

    Modal.prototype.open = function (url) {
        $('body').addClass('modularity-modal-open').append('\
            <div id="modularity-modal">\
                <div class="modularity-modal-wrapper">\
                    <button class="modularity-modal-close" data-modularity-modal-action="close">&times; ' + modularityAdminLanguage.close + '</button>\
                    <span id="modularity-iframe-loader" class="spinner modularity-spinner-center" style="visibility:visible;"></span>\
                    <iframe class="modularity-modal-iframe" src="' + url + '" frameborder="0" onload="document.getElementById(\'modularity-iframe-loader\').style.visibility=\'hidden\';" allowtransparency></iframe>\
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
        $(document).on('click', '[data-modularity-modal-action="close"]', function (e) {
            e.preventDefault();
            this.close();
        }.bind(this));
    };

    return new Modal();

})(jQuery);
