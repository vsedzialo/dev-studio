var AVAFields = {

    handlers: {},
    data: {},
    map: {},


    addHandler: function ( type, handler ) {
        this.handlers[type] = handler;
        this.handlers[type].init();
    },

    get: function ( group ) {
        let type = group.data('type');
        return {
            val: this.handlers[type].get(group),
            map: group.data('map')
        };
    },

    set: function ( group, value ) {
        let type = group.data('type');
        return this.handlers[type].set(group, value);
    },


    init: function () {
    }
}
AVAFields.init();

(function ($) {
    "use strict";

    // Change section
    $('body').on('click', '.avaf-nav-item:not(.active)', function (e) {
        e.preventDefault();

        let $container = $(this).parents('.avaf-container'), section = $(this).data('section');

        $container.find('.avaf-nav-item, .avaf-section').removeClass('active');
        $container.find('.avaf-nav-item[data-section=' + section + '], .avaf-section[data-section=' + section + ']').addClass('active');
    });

    // Change tab
    $('body').on('click', '.avaf-nav-tab:not(.active)', function (e) {
        e.preventDefault();

        let $section = $(this).parents('.avaf-section'), tab = $(this).data('tab');

        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        $section.find('.avaf-tab').removeClass('active');
        $section.find('.avaf-tab[data-tab=' + tab + ']').addClass('active');
    });


    function get_value($this) {
        if ($this.is('input')) return $this.val();
        if ($this.is('textarea')) return $this.val();
    }

    // Save data
    $('body').on('click', '.avaf-save', function (e) {
        e.preventDefault();

        var $container = $(this).parents('.avaf-container');

        AVAFields.data = {};

        $container.find('.avaf-group').each(function () {
            var $group = $(this),
                group = $group.data('group'),
                type = $group.data('type');

            if (typeof AVAFields.handlers[type] == 'object' ) {
                let data =  AVAFields.get($group);
                AVAFields.data[group] = data.val;
                AVAFields.map[group] = data.map;
            }
        });


        let data = {
            'action': 'avaf-save',
            'option_name': $container.data('option_name'),
            'options': AVAFields.data,
            'map': AVAFields.map,
        };

        let $checked = true;

        if ($checked) {
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: data,
                beforeSend: function () {
                    $('.avaf-preloader').fadeIn();
                },
                success: function (response) {
                    // console.log(response);
                },
                complete: function() {
                    $('.avaf-preloader').fadeOut("slow");
                }
            });
        }
    });


})(window.jQuery);

