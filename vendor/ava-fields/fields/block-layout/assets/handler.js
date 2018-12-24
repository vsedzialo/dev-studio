(function ($) {
    AVAFields.addHandler('block-layout', {

        init: function () {
        },

        get: function ($group) {
            
            let m_top = $group.find('input[name="bl-margin-top"]').val(), 
                m_right = $group.find('input[name="bl-margin-right"]').val(),
                m_bottom = $group.find('input[name="bl-margin-bottom"]').val(),
                m_left = $group.find('input[name="bl-margin-left"]').val(),
                
                b_top = $group.find('input[name="bl-border-top"]').val(), 
                b_right = $group.find('input[name="bl-border-right"]').val(),
                b_bottom = $group.find('input[name="bl-border-bottom"]').val(),
                b_left = $group.find('input[name="bl-border-left"]').val(),
                
                p_top = $group.find('input[name="bl-padding-top"]').val(), 
                p_right = $group.find('input[name="bl-padding-right"]').val(),
                p_bottom = $group.find('input[name="bl-padding-bottom"]').val(),
                p_left = $group.find('input[name="bl-padding-left"]').val(); 
            
            m_top = m_top && m_top!=='' ? m_top:0;
            m_right = m_right && m_right!=='' ? m_right:0;
            m_bottom = m_bottom && m_bottom!=='' ? m_bottom:0;
            m_left = m_left && m_left!=='' ? m_left:0;
            
            b_top = b_top && b_top!=='' ? b_top:0;
            b_right = b_right && b_right!=='' ? b_right:0;
            b_bottom = b_bottom && b_bottom!=='' ? b_bottom:0;
            b_left = b_left && b_left!=='' ? b_left:0;
            
            p_top = p_top && p_top!=='' ? p_top:0;
            p_right = p_right && p_right!=='' ? p_right:0;
            p_bottom = p_bottom && p_bottom!=='' ? p_bottom:0;
            p_left = p_left && p_left!=='' ? p_left:0;
            
            return {
                'margin': m_top+' '+m_right+' '+m_bottom+' '+m_left,
                'border': b_top+' '+b_right+' '+b_bottom+' '+b_left,
                'padding': p_top+' '+p_right+' '+p_bottom+' '+p_left
            }
        },
        
        set: function($group, value) {
            let margin = value.margin.split(' ');
            $group.find('input[name="bl-margin-top"]').val(margin[0] && margin[0]!==0 ? margin[0]:'');
            $group.find('input[name="bl-margin-right"]').val(margin[1] && margin[1]!==0 ? margin[1]:'');
            $group.find('input[name="bl-margin-bottom"]').val(margin[2] && margin[2]!==0 ? margin[2]:'');
            $group.find('input[name="bl-margin-left"]').val(margin[3] && margin[3]!==0 ? margin[3]:'');
            
            let border = value.border.split(' ');
            $group.find('input[name="bl-border-top"]').val(border[0] && border[0]!==0 ? border[0]:'');
            $group.find('input[name="bl-border-right"]').val(border[1] && border[1]!==0 ? border[1]:'');
            $group.find('input[name="bl-border-bottom"]').val(border[2] && border[2]!==0 ? border[2]:'');
            $group.find('input[name="bl-border-left"]').val(border[3] && border[3]!==0 ? border[3]:'');

            let padding = value.padding.split(' ');
            $group.find('input[name="bl-padding-top"]').val(padding[0] && padding[0]!==0 ? padding[0]:'');
            $group.find('input[name="bl-padding-right"]').val(padding[1] && padding[1]!==0 ? padding[1]:'');
            $group.find('input[name="bl-padding-bottom"]').val(padding[2] && padding[2]!==0 ? padding[2]:'');
            $group.find('input[name="bl-padding-left"]').val(padding[3] && padding[3]!==0 ? padding[3]:'');
        }
    });
})(window.jQuery);