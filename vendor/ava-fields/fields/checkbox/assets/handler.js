(function ($) {
    AVAFields.addHandler('checkbox', {

        init: function () {
        },

        get: function ($group) {
            return $group.find('input:checked').length > 0 ? 'yes':'no';
        },
        
        set: function ($group, value) {
            $group.find('input[type=checkbox]').attr('checked', value === 'yes');
        },
        
    });
})(window.jQuery);