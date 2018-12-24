(function ($) {
    AVAFields.addHandler('font-family', {

        init: function () {
        },

        get: function ($group) {
            return $group.find('select').val();
        },
        
        set: function ($group, value) {
            return  $group.find('select').val(value);
        },
    });
})(window.jQuery);