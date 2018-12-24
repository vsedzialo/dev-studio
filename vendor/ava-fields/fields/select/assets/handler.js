(function ($) {
    AVAFields.addHandler('select', {

        init: function() {
        },

        get: function ($group) {
            return $group.find('select').val();
        },
        
        set: function ($group, value) {
            let first_value = $group.find('select').find("option:first").val();
            if ( value!=='' || first_value === '') {
                $group.find('select').val(value);
            } else {
                $group.find('select').prop("selectedIndex", 0);
            }
        },
    });
})(window.jQuery);