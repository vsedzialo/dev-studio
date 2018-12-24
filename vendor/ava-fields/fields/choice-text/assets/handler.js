(function ($) {
    AVAFields.addHandler('choice-text', {

        init: function() {
        },

        get: function ($group) {
            return {
                'choice': $group.find('select').val(),
                'text': $group.find('input').val()
            }
        },
        
        set: function ($group, value) {
            let first_value = $group.find('select').find("option:first").val();
            
            if ( value!=='' || first_value === '') {
                $group.find('select').val(value.choice);
            } else {
                $group.find('select').prop("selectedIndex", 0);
            }
            $group.find('input').val(value.text);
        },
    });
})(window.jQuery);