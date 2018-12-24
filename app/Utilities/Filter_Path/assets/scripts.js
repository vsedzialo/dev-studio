(function ($) {

    $('body').on('click', '#ds-fp-button', function (e) {
        let $filter = $('#ds-fp-filter'), util = $('#ds-util').data('util');
        if ($filter.val()==='') {
            $filter.addClass('ds-not-validated');
            return;
        }
        $filter.removeClass('ds-not-validated');

        let args = {
            filter: $filter.val().trim()
        };

        DevStudio.ajax({request: 'utility_enable', utility: util, args:args}, function() {
            window.location.reload();
        });
    });

    $('body').on('click', '.ds-ui-block.ds-ui-link', function (e) {
        let id = $(this).data('id'), $wr = $('.ds-ui-block-info-wr[data-id="'+id+'"]');

        if ($wr.length > 0) {
            $('.ds-ui-block').removeClass('ds-active');
            $(this).addClass('ds-active');
            DevStudio.setDataInfo($wr.html());
        }

    });

})(window.jQuery);