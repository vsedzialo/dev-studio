(function ($) {
    AVAFields.addHandler( 'textarea', {

        init: function() {
        },

        get: function(group) {
            return group.find('textarea').val();
        }
    });
})(window.jQuery);    