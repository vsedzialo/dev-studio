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