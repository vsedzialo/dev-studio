(function ($) {
    AVAFields.addHandler( 'editor', {
        init: function() {
        },
        get: function($group) {
            return $group.find('textarea').val();
        },
        set: function($group, value) {
            $group.find('textarea').val(value);
        }
    });
})(window.jQuery);