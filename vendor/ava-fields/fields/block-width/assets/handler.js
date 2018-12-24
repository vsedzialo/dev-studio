(function ($) {
    AVAFields.addHandler('block-width', {
        init: function () {
        },
        
        get: function ($group) {
            return {
                'type': $group.find('select').val(),
                'pixels': $group.find('.block-width-pixels').val(),
                'percents': $group.find('.block-width-percents').val(),
            }
        },
        
        set: function($group, value) {
            if (value.type!='') {
                $group.find('select').val(value.type);
            } else {
                $group.find('select').prop("selectedIndex", 0);
            }
            $group.find('.block-width-pixels').val(value.pixels);
            $group.find('.block-width-percents').val(value.percents);
        }
    });
})(window.jQuery);