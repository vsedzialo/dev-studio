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

(function ($) {
    AVAFields.addHandler('sortable', {

        init: function() {
            $('.avaf-sortable').each(function(index, el) {
                let obj = Sortable.create(el, {
                    handle: ".avaf-sortable-handle",
                });
            });
        },

        get: function ($group) {
            let value = [];
            $group.find('.avaf-sortable-item').each(function(index, el) {
                value.push($(el).data('value'));
            });
            return value;
        },
        
        set: function ($group, value) {
        },
    });
})(window.jQuery);

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

