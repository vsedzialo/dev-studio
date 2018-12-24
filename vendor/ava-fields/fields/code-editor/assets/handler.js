
(function ($) {
    AVAFields.addHandler( 'code-editor', {
        elements: [],
        
        init: function() {
            var _this = this;
            $('.avaf-code-editor').each(function() {
                var id = $(this).prop('id');
                _this.elements[id] = CodeMirror.fromTextArea($(this).get(0), {
                  mode: "text/javascript",
                  styleActiveLine: true,
                  lineNumbers: true,
                  lineWrapping: true
                });

                _this.elements[id].on('change', function() {
                    $('.avaf-code-editor').trigger('change');
                });
            });
        },
        
        /*    
        var js_editor = ace.edit("js_editor", {
            mode: "ace/mode/javascript",
            selectionStyle: "text"
        });
        */

        get: function($group) {
            var instance = $group.find('textarea.avaf-code-editor'), id = instance.prop('id');
            return this.elements[id].getDoc().getValue();
        },
        
        set: function($group, value) {
            var _this = this, instance = $group.find('textarea.avaf-code-editor'), id = instance.prop('id');
            
            this.elements[id].getDoc().setValue(value);
            setTimeout(function() {
                _this.elements[id].refresh();
                _this.elements[id].focus();
            },2);
        }
    });
})(window.jQuery);