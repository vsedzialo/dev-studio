(function ($) {
    AVAFields.addHandler('checkbox-groups', {

        init: function () {
            
            $('body')
                .on('change', '.avaf-field.avaf-field-checkbox-groups .avaf-cg-parent input[type=checkbox]', function () {
                    let $this = $(this);
                    $this.parents('.avaf-cgroup').find('.avaf-cg-childs input[type=checkbox]').prop('checked', $this.is(':checked'));
                })
                .on('change', '.avaf-field.avaf-field-checkbox-groups .avaf-cg-childs input[type=checkbox]', function () {
                    let $this = $(this);
                    $this.parents('.avaf-cgroup').find('.avaf-cg-parent input[type=checkbox]').prop(
                        'checked',
                        $this.parents('.avaf-cg-childs').find('input[type=checkbox]:checked').length > 0
                    );
                });
        },

        get: function ($group) {
            let data = [];
            $group.find('.avaf-cg-childs input:checked').each(function(index, el) {
                data.push($(el).val());
            });
            return data;
        },
        
        set: function ($group, value) {
            $group.find('input[type=checkbox]').prop('checked', false);
            $.each(value, function(index, val) {
                $group.find('.avaf-cg-childs input[type=checkbox][value="'+val+'"]').each(function(index, el) {
                    $(this).prop('checked', true);
                    $(this).trigger('change');
                });
            });
        },
        
    });
})(window.jQuery);