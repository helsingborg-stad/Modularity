Modularity = Modularity || {};
Modularity.Helpers = Modularity.Helpers || {};

Modularity.Helpers = (function ($) {

    function Helpers() {
        $(function(){
        }.bind(this));
    }

    Helpers.prototype.uuid = function (separator) {
        return Math.random().toString(36).substr(2, 9);
    };

    return new Helpers();

})(jQuery);
