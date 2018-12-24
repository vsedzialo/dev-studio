
(function ($) {
    AVAFields.addHandler( 'text', {

        init: function() {
        },

        get: function(group) {
            return group.find('input[type=text]').val();
        }
    });
})(window.jQuery);