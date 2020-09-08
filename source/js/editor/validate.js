export default (function ($) {

    let hasErrors = false;
    let errorClass = 'validation-error';

    function Validate() {
        this.handleEvents();
    }

    /**
     * Run validation methods
     * @return {void}
     */
    Validate.prototype.run = function () {
        hasErrors = false;
        this.checkRequired();
    };

    /**
     * Check required fileds is not empty
     * @return void
     */
    Validate.prototype.checkRequired = function () {
        var required = $('[required]');
        required.removeClass(errorClass);

        required.each(function (index, element) {
            if ($(element).val().length === 0) {
                $(element).parents('li').addClass(errorClass);

                $(element).one('change', function (e) {
                    $(e.target).parents('li').removeClass(errorClass);
                });

                hasErrors = true;
            }
        }.bind(this));
    };

    /**
     * Handle events
     * @return void
     */
    Validate.prototype.handleEvents = function () {
        $(document).on('click', '#modularity-mb-editor-publish #publish', function (e) {
            this.run();

            if (hasErrors) {
                $('#modularity-mb-editor-publish .spinner').css('visibility', 'hidden');
                return false;
            }

            return true;
        }.bind(this));
    };

    return new Validate();

})(jQuery);
