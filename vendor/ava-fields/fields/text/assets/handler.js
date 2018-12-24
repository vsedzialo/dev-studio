(function ($) {
    AVAFields.addHandler( 'text', {
        init: function() {
        },
        get: function($group) {
            return $group.find('input[type=text]').val();
        },
        set: function($group, value) {
            $group.find('input[type=text]').val(value);
        },
    });
})(window.jQuery);