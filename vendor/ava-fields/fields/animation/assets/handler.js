(function ($) {
    AVAFields.addHandler('animation', {

        init: function () {
            $('body').on('change', '.avaf-field.avaf-field-animation select', function () {
                $(this).next().find('.animated').attr('class', 'animated '+$(this).val());
            });
        },
        
        get: function ($group) {
            return $group.find('select').val();
        },
        
        set: function ($group, value) {
            let first_value = $group.find('select').find("option:first").val();
            if ( value !== '' || first_value === '') {
                $group.find('select').val(value);
            } else {
                $group.find('select').prop("selectedIndex", 0);
            }
            $group.find('.animated').attr('class', 'animated '+value);
        }
    });
})(window.jQuery);