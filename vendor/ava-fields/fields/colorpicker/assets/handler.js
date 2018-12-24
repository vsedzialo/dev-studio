
(function ($) {
    AVAFields.addHandler( 'colorpicker', {
        
        elements: [],

        init: function() {
            
            $('.avaf-colorpicker').each( function() {
                var $this = $(this), layout = $this.data('layout'), color = $this.data('color');
                
                $this.css({backgroundColor: color});
                
                $(this).colpick({
                    //flat:   true,           // Always visible
                    layout: layout,          // full, rgbhex, hex
                    //submit: false,          // no submit button and no previous color viewer
                    colorScheme: 'light',   // light, dark,
                    color: color,        // default color
                    //showEvent: 'click', // Event on show picker
                    //style: { color:'red' },
                    onBeforeShow: function(el) {
                        $(this).colpickSetColor($(this).data('color'));
                    },
                    onShow: function(el) {
                    },
                    onChange: function(hsb, hex, rgb, el, bySetColor) {
                    },
                    onSubmit: function(hsb, hex, rgb, el) {
                        $(el).data('color', '#'+hex).trigger('datachange').css({backgroundColor:'#'+hex}).colpickHide();
                    },
                    onHide: function(el) {
                    },
                });
                
            });
        },

        get: function($group) {
            return $group.find('.avaf-colorpicker').data('color');
        },
        
        set: function($group, value) {
            let colorpicker = $group.find('.avaf-colorpicker');
            colorpicker.data('color', value).trigger('datachange');
            if (value !=='' ) {
                colorpicker.css({backgroundColor:value});
            } else {
                colorpicker.css({backgroundColor:''});
            }
        }
    });
})(window.jQuery);