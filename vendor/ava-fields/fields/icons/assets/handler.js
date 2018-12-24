
(function ($) {
    AVAFields.addHandler( 'icons', {
        init: function() {
            $('body').on('click', '.avaf-field-icons .avaf-icons-list div', function() {
                let icon = $(this).find('.fa').data('icon');
                $(this).parents('.avaf-group').find('.avaf-icon span').attr('class', 'fa fa-'+icon).data('icon', icon)

            });
        },
        get: function($group) {
            return $group.find('.avaf-icon span').data('icon');
        },
        set: function($group, value) {
            $group.find('.avaf-icon span').attr('class', 'fa fa-'+value).data('icon',value)
        },
    });
})(window.jQuery);