(function ($) {
    AVAFields.addHandler('variants', {

        init: function() {
            $('body').on('click', '.avaf-variant:not(.active)', function() {
                var $group = $(this).parents('.avaf-group'), group = $(this).data('group');
                $('.avaf-variant[data-group='+group+']').removeClass('active');
                $(this).addClass('active');
                
                $group.find('input[type=hidden]').val( $('.avaf-variant.active').data('value') ).trigger('change');
            });
        },
        
        get: function ($group) {
            return $group.find('.avaf-variant.active').data('value');
        },
        
        set: function($group, value) {
            $group.find('.avaf-variant').removeClass('active');
            if (value!='') {
                $group.find('.avaf-variant[data-value="'+value+'"]').addClass('active');
            } else {
                let first = $group.find('.avaf-variant')[0];
                $(first).addClass('active');
            }
            $group.find('input[type=hidden]').val( this.get($group) ).trigger('change');
        }
    });
})(window.jQuery);    