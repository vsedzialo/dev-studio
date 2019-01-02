(function ($) {
    window.DevStudio = {

        module: undefined,
        component: undefined,
        unit: undefined,

        // Containers
        $modules: null,
        $components: null,
        $units: null,

        response: {},

        requestProgress: 0,

        init: function () {

            $(document).ready(function () {

                // Unit on init
                if (DSData.options.general.appearance.unit_init != undefined) {
                    [DevStudio.module, DevStudio.component, DevStudio.unit] = DSData.options.general.appearance.unit_init.split('.');
                }

                $('body').on('click', '#dev-studio .ds-on-off', function (e) {
                   $(this).toggleClass('ds-off');
                   if ($(this).hasClass('ds-off')) {
                       $('#dev-studio').addClass('ds-off');
                   } else {
                       $('#dev-studio').removeClass('ds-off');
                   }
                   let enabled = $(this).hasClass('ds-off') ? 'no':'yes';
                   DevStudio.ajax({request: 'enabled', enabled:enabled});
                });

                if (DSData.options.bar.enabled === 'yes') {
                    DevStudio.Bar();
                }

                //if (DSData.options.general.enabled == 'yes') {
                    DevStudio.UI();
                //}

                // Show/Hide UI
                $('body').on('click', '#dev-studio-bar .ds-bar-ds, #wp-admin-bar-dev-studio a', function (e) {
                    e.preventDefault();

                    DevStudio.setUIPosition();
                    $('#dev-studio').toggleClass('ds-show');

                    //DevStudio.UI()
                    DevStudio.buildUI();
                    DevStudio.loadData();
                });

                $('#dev-studio').css({
                    top: $('#wpadminbar').height(),
                    bottom: 0
                });

                // Click on module tab
                $('body').on('click', '#dev-studio .ds-tab-module:not(.ds-active)', function() {
                    $('#dev-studio .ds-tab-module').removeClass('ds-active');
                    $('#dev-studio .ds-tabs-components .ds-component').removeClass('ds-active');
                    $('#dev-studio .ds-tabs-units .ds-unit').removeClass('ds-active');

                    $(this).addClass('ds-active');

                    if ($(this).hasClass('ds-tab-utilities')) {
                        $('#dev-studio .ds-tabs-data .ds-tabs').addClass('ds-hide');
                        $('#dev-studio .ds-tabs-components').html('');
                        $('#dev-studio .ds-tabs-data .ds-tabs-utilcats').removeClass('ds-hide');
                        $('#dev-studio .ds-tabs-data .ds-utilcat').first().addClass('ds-active');
                        DevStudio.setData('');
                        DevStudio.setDataInfo('');
                        DevStudio.utilsShow();
                    } else {
                        $('#dev-studio .ds-tabs-data .ds-tabs').removeClass('ds-hide');
                        $('#dev-studio .ds-tabs-data .ds-tabs-utilcats').addClass('ds-hide');

                        DevStudio.unit = undefined;

                        DevStudio.buildUI();
                        DevStudio.loadData();
                    }
                });

                // Click on component tab
                $('body').on('click', '#dev-studio .ds-tabs-components .ds-component:not(.ds-active)', function() {
                    $('#dev-studio .ds-tabs-components .ds-component').removeClass('ds-active');
                    $('#dev-studio .ds-tabs-units .ds-unit').removeClass('ds-active');
                    $(this).addClass('ds-active');

                    DevStudio.unit = undefined;

                    DevStudio.buildUI();
                    DevStudio.loadData();
                });

                // Click on unit tab
                $('body').on('click', '#dev-studio .ds-tabs-units .ds-unit:not(.ds-active)', function() {
                    $('#dev-studio .ds-tabs-units .ds-unit').removeClass('ds-active');
                    $(this).addClass('ds-active');

                    DevStudio.unit = undefined;

                    DevStudio.setCondition();
                    DevStudio.loadData();
                });

                // Data detail
                $('body').on('click', '.data-table .row-info', function() {
                    let id = $(this).data('id'), type = $(this).data('type');
                    $('.data-table tr').removeClass('ds-active');
                    $('#row-'+id).addClass('ds-active');

                    let html = $('#'+type+'-'+id).html();
                    DevStudio.setDataInfo(html);
                });

                // Row Info
                $('body').on('click', '.data-table tr.info', function() {
                    let $this = $(this), id = $this.data('id'), $table = $this.parents('table');
                    $table.find('tr.info').not(this).removeClass('ds-active');
                    $this.toggleClass('ds-active');

                    if ($this.hasClass('ds-active')) {
                        DevStudio.setDataInfo($('#info'+'-'+id).html());
                    } else {
                        DevStudio.hideDataInfo();
                    }
                });

                // Checkpoints
                $('body').on('change', '#dev-studio #checkpoint', function() {
                    DevStudio.buildUI();
                    DevStudio.loadData();
                });

                $('body').on('click', '#dev-studio #ds-ajax', function() {
                    $(this).toggleClass('ds-active');

                    var mode = DevStudio.mode();

                    if ($('#dev-studio #ds-ajax').hasClass('ds-active')) {
                        $('#ds-ajax-test').addClass('ds-show');
                    } else {
                        $('#ds-ajax-test').removeClass('ds-show');
                    }

                    DevStudio.module = undefined;
                    DevStudio.component = undefined;
                    DevStudio.unit = undefined;

                    $('#dev-studio .ds-tabs-components .ds-component').removeClass('ds-active');
                    $('#dev-studio .ds-tabs-units .ds-unit').removeClass('ds-active');

                    // Update checkpoints select
                    DevStudio.updateCPSelect(DSData.options.checkpoints.actions[mode]);

                    // Create actions list
                    DevStudio.unsetUI();
                    DevStudio.ajax({request: 'actions', mode: mode}, DevStudio.updateActionsCallback);
                    DevStudio.buildUI();
                });

                /**
                 * Ajax Test
                 */
                $('body').on('click', '#dev-studio #ds-ajax-test div', function() {
                    DevStudio.ajaxTest(DevStudio.loadData);
                });

                /**
                 * Actions list
                 */
                $('body').on('click', '#dev-studio .ds-checkpoints .fa-cog', function() {
                    let $cp = $('#dev-studio-actions');
                    $cp.toggleClass('ds-show');
                    if ($cp.hasClass('ds-show')) {
                        $(this).parents('.ds-checkpoints').addClass('ds-active');
                        $('#dev-studio .ds-tab-checkpoints').show();
                    } else {
                        $(this).parents('.ds-checkpoints').removeClass('ds-active');
                        $('#dev-studio .ds-tab-checkpoints').hide();
                    }
                });

                /**
                 * Enable/disable checkpoint
                 */
                $('body').on('click', '#dev-studio .cp-wrapper input[type="checkbox"]', function() {
                    var cp = [];
                    //DevStudio.clearUI();
                    $('.cp-wrapper input[type="checkbox"]:checked').each(function(index, el) {
                        cp.push($(el).data('action'));
                    });
                    DevStudio.ajax({request: 'checkpoints', cp:cp.join('::')}, function() {
                        this.updateCPSelect(this.response.opts);
                        DSData.options.checkpoints.actions[this.mode()] = this.response.opts;
                    });
                });

                /**
                 * Actions carousel
                 */
                $('body').on('click', '#dev-studio-actions .cp-wrapper.start .cp-start', function() {
                    $(this).toggleClass('expanded');
                    $('#dev-studio-actions .cp-not-available').slideToggle(500);

                });


                /**
                 * Tools
                 *
                 */
                $('body').on('click', '#dev-studio-tools .ds-app-info', function() {
                    let $this = $(this), type = $this.data('type');

                    $('#dev-studio-tools .ds-app-info').not(this).removeClass('ds-active');
                    $this.toggleClass('ds-active');

                    if ($this.hasClass('ds-active')) {
                        DevStudio.ajax({request: 'info', type:type}, function() {
                            DevStudio.setDataInfo(this.response.html);
                        });
                    } else {
                        DevStudio.setDataInfo('');
                    }
                });

                $('body').on('click', '#dev-studio-tools .ds-settings', function() {
                    let $this = $(this);

                    $('#dev-studio-tools .ds-app-info').not(this).removeClass('ds-active');
                    $this.toggleClass('ds-active');

                    if ($this.hasClass('ds-active')) {
                        DevStudio.ajax({request: 'settings'}, function() {
                            DevStudio.setDataInfo(this.response.html, 'settings');

                            if (AVAFields !== undefined) {
                                $.each(AVAFields.handlers, function (index, el) {
                                    el.init();
                                });
                            }
                            if (DSData.options.general.appearance.tips == 'yes') {
                                tippy('.avaf-tip', {theme: 'ds'});
                            }
                        });
                    } else {
                        DevStudio.setDataInfo('');
                    }
                });

                $('body').on('click', '#dev-studio-tools .ds-stats', function() {
                    let $this = $(this);

                    $('#dev-studio-tools .ds-app-info').not(this).removeClass('ds-active');
                    $this.toggleClass('ds-active');

                    if ($this.hasClass('ds-active')) {
                        DevStudio.ajax({request: 'stats'}, function() {
                            DevStudio.setDataInfo(this.response.html, 'stats');
                        });
                    } else {
                        DevStudio.setDataInfo('');
                    }
                });

                /**
                 * Data Info
                 *
                 */
                $('body').on('click', '#dev-studio .ds-data-info-control .ds-pin', function() {
                    let $this = $(this);
                    $this.toggleClass('ds-active');
                    if ($this.hasClass('ds-active')) {
                        $('#dev-studio #ds-data-container').addClass('ds-col-2');
                        $('.ds-toggle-info .fa').removeClass('fa-chevron-left').addClass('fa-chevron-right');
                    } else {
                        $('#dev-studio #ds-data-container').removeClass('ds-col-2');
                        $('.ds-toggle-info .fa').removeClass('fa-chevron-right').addClass('fa-chevron-left');
                    }
                    DevStudio.ajax({request: 'features', feature:'data_info', value:$(this).hasClass('ds-active') ? 'yes':'no'});
                });

                $('body').on('click', '#dev-studio #dev-studio-tools .ds-toggle-info', function() {
                    $(this).find('.fa').hasClass('fa-chevron-left')
                        ? DevStudio.showDataInfo()
                        : DevStudio.hideDataInfo();
                });

                /**
                 * Filters
                 *
                 */
                $('body').on('change', '#dev-studio .ds-filter-select, #dev-studio .ds-filter-checkbox', function() {
                    let $this = $(this), src = $this.data('src'), src_data = $this.data('src_data'), src_value = $this.data('src_value');

                    // Clear another filters
                    $('#dev-studio .ds-filter-select').not(this).val('');
                    $('#dev-studio .ds-filter-checkbox').not(this).prop('checked', false);


                    if (src == 'data') {
                        var $rows = $('#dev-studio #dev-studio-data tbody tr[data-'+src_data+']');
                    }

                    if ($this.hasClass('ds-filter-select')) {
                        var filter_value = $this.val();
                    } else if ($this.hasClass('ds-filter-checkbox')) {
                        var filter_value = $this.is(':checked') ? $this.val():'';
                    }

                    $rows.removeClass('ds-active');
                    DevStudio.setDataInfo('');

                    if (filter_value=='') {
                        $rows.removeClass('ds-hide');
                    } else {
                        if (src == 'data') {
                            let pos = 0;
                            $rows.each(function(index, el) {
                                var row_value = '';
                                if (src == 'data') {
                                    row_value = $(this).data(src_data);
                                } else if (src == 'column') {
                                    $(el).find('td').get(src_value).html();

                                }

                                if (row_value == filter_value) {
                                    $(el).removeClass('ds-hide');
                                    $(el).find('td.ds-pos').html(++pos);
                                } else {
                                    $(el).addClass('ds-hide');
                                }
                            });
                        }
                    }

                    // Order table
                    $('#dev-studio th.ds-order.ds-active').each(function(index, el) {
                        DevStudio.orderTable($(el).parents('table'));
                    });

                });

                /**
                 * Order table
                 *
                 */
                $('body').on('click', '#dev-studio th.ds-order:not(.ds-disabled)', function() {
                    let $this = $(this), $table = $this.parents('table'), $thead = $table.find('thead');

                    $thead.find('th.ds-order').addClass('.ds-disabled');

                    $thead.find('th').not(this)
                        .removeClass('ds-active ds-order-down ds-order-up')
                        .find('span.ds-order')
                        .removeClass('fa-chevron-down fa-chevron-up')
                        .addClass('fa-sort');

                    if ($this.hasClass('ds-order-up')) {
                        $this.removeClass('ds-order-up').addClass('ds-active ds-order-down').find('span.ds-order').removeClass('fa-chevron-up fa-sort').addClass('fa-chevron-down');
                    } else if ($this.hasClass('ds-order-down')) {
                        $this.removeClass('ds-active ds-order-down').find('span.ds-order').removeClass('fa-chevron-up fa-chevron-down').addClass('fa-sort');
                    } else {
                        $this.addClass('ds-active ds-order-up').find('span.ds-order').removeClass('fa-chevron-down fa-sort').addClass('fa-chevron-up');
                    }
                    DevStudio.orderTable($table);

                    $thead.find('th.ds-order').removeClass('.ds-disabled');
                });
            });


        },

        /**
         * Get current mode
         */
        mode: function () {
            return $('#dev-studio #ds-ajax').hasClass('ds-active') ? 'ajax_' + DSData.mode : DSData.mode;
        },

        /**
         * Check on AJAX mode
         */
        is_ajax: function () {
            return $('#dev-studio #ds-ajax').hasClass('ds-active');
        },

        /**
         * Order table
         */
        orderTable: function ($table) {
            let $thead = $table.find('thead');

            $th = $thead.find('th.ds-active');
            if ($th.length == 1) {
                let otype = $th.data('otype');
                    
                // Get column index
                var index = 0;
                $thead.find('th').each(function(_index) {
                    if ($(this).hasClass('ds-active')) index = _index;
                });

                // Sort rows
                let dir = $th.hasClass('ds-order-up') ? 'up':'down';
                DevStudio.orderColumns($table, index, dir, otype);
            } else {
                DevStudio.orderColumns($table, 0, 'default');
            }
        },

        /**
         * Order columns
         */
        orderColumns: function ($table, $index, dir, type) {
            let $rows = $table.find('tbody tr:not(.ds-hide)');

            let items = [];
            $rows.each(function(index, el) {
                let $this = $(el), id = $this.prop('id'), td = $this.find('td').get($index);
                let value = dir != 'default' ? $(td).html():$this.data('pos');
                items.push({ id, value });
            });

            if (type==undefined) {
                if (dir == 'down') {
                    items.sort(this.sortStringDESC);
                } else {
                    items.sort(this.sortStringASC);
                }
            }
            if (dir=='default' || type=='number') {
                if (dir == 'down') {
                    items.sort(this.sortNumberDESC);
                } else {
                    items.sort(this.sortNumberASC);
                }
            }

            // Sort rows
            var $prevEl = undefined;
            items.forEach(function(el, index) {

                let $swapEl = $($rows.filter('[id='+el.id+']'));
                $swapEl.find('td.ds-pos').html(index+1);

                if ($prevEl == undefined) {
                    $prevEl = $($rows.get(index));
                    if ($prevEl.prop('id') != el.id) $prevEl.before($swapEl.detach());
                } else {
                    $prevEl.after($swapEl.detach());
                }
                $prevEl = $swapEl;
            });
        },

        sortStringASC: function (a, b) {
            if (a['value'].toString().toUpperCase() < b['value'].toString().toUpperCase()) {
                return -1;
            }
            if (a['value'].toString().toUpperCase() > b['value'].toString().toUpperCase()) {
                return 1;
            }
            return 0;
        },

        sortStringDESC: function (a, b) {
            if (a['value'].toString().toUpperCase() < b['value'].toString().toUpperCase()) {
                return 1;
            }
            if (a['value'].toString().toUpperCase() > b['value'].toString().toUpperCase()) {
                return -1;
            }
            return 0;
        },

        sortNumberASC: function (a, b) {
            if (parseFloat(a['value']) < parseFloat(b['value'])) {
                return -1;
            }
            if (parseFloat(a['value']) > parseFloat(b['value'])) {
                return 1;
            }
            return 0;
        },

        sortNumberDESC: function (a, b) {
            if (parseFloat(a['value']) < parseFloat(b['value'])) {
                return 1;
            }
            if (parseFloat(a['value']) > parseFloat(b['value'])) {
                return -1;
            }
            return 0;
        },



        /**
         * Bar
         */
        Bar: function () {
            let bar_html = DSData.bar_html;

            if (bar_html!='') {

                $('body').addClass('ds-bar');
                if (DSData.options.bar.expand == 'yes') {
                    $('body').addClass('ds-bar-expand');
                }

                // Output bar
                $('body').prepend(bar_html);

                // Get bar data
                this.ajax({request: 'bar'}, function () {
                    $('#dev-studio-bar .ds-bar-inner').html(this.response.html);
                });

                // Expand/Contract bar
                $('#dev-studio-bar').on('click', '.ds-bar-icon', function() {

                    //let bar = $(this).parents('#dev-studio-bar');
                    $('body').toggleClass('ds-bar-expand');

                    if ($('body').hasClass('ds-bar-expand')) {
                        $(this).find('.fa').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    } else {
                        $(this).find('.fa').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    }
                    DevStudio.setUIPosition();
                });
            }

        },

        clearUI: function() {
            $('#dev-studio .ds-tab-module').removeClass('ds-active');
            $('#dev-studio .ds-tabs-components .ds-component').removeClass('ds-active');
            $('#dev-studio .ds-tabs-units .ds-unit').removeClass('ds-active');
            this.unsetUI();
            this.setCondition();
        },

        unsetUI: function() {
            DevStudio.setData('');
            DevStudio.setDataInfo('');
            $('#dev-studio-tools .ds-app-info').removeClass('ds-active');
        },

        getUIPosition: function() {
            let top = $('#wpadminbar').height(), bottom = 0;
            if ($('#dev-studio-bar').length > 0) {
                top += $('#dev-studio-bar').height();
            }
            return { top, bottom }
        },

        setUIPosition: function() {
            let pos = this.getUIPosition();
            $('#dev-studio').css({top:pos.top, bottom:pos.bottom});
        },

        setData: function (html) {
            $('#dev-studio-data').html(html ? html:'');
        },

        setDataInfo: function (html, mode='') {
            if (mode!='settings') {
                $('#dev-studio-tools .ds-settings').removeClass('ds-active');
            }
            if (mode!='stats') {
                $('#dev-studio-tools .ds-stats').removeClass('ds-active');
            }
            $('#dev-studio-data-info .ds-data-info').html(html);
            if (html) {
                this.showDataInfo();
            }
        },

        showDataInfo: function () {
            $('#dev-studio #ds-data-container').addClass('ds-col-2');
            $('#dev-studio-tools .ds-toggle-info .fa').removeClass('fa-chevron-left').addClass('fa-chevron-right');
        },

        hideDataInfo: function () {
            $('#dev-studio #ds-data-container').removeClass('ds-col-2');
            $('#dev-studio-tools .ds-toggle-info .fa').removeClass('fa-chevron-right').addClass('fa-chevron-left');
        },

        /**
         * Update checkpoints select
         */
        updateCPSelect: function(cps) {
            var $el = $("#checkpoint"), val = $el.val();
            $el.empty();
            $.each(cps, function(key,value) {
                $el.append($("<option></option>")
                    .attr("value", key).text(key));
            });
            if ($('#checkpoint option[value='+val+']').length > 0) {
                $el.val(val);
            } else {
                $el.prop("selectedIndex", 0);
            }
        },

        /**
         * Load UI
         */
        UI: function () {
            if ($('#dev-studio').length == 0 ) {
                this.ajax({request: 'UI', mode: DSData.mode}, function () {
                    $('body').append(this.response.html);

                    // Show on full screen
                    //this.setUIPosition();
                    //DevStudio.buildUI();
                    //$('#dev-studio').toggleClass('ds-show');
                    //DevStudio.loadData();

                    //$('#dev-studio').css({top: this.getUITop()});
                });
            } else
                $('#dev-studio').toggleClass('ds-show');
        },

        /**
         * Build UI
         */
        buildUI: function() {
            this.$modules = $('#dev-studio .ds-modules');
            this.$components = $('#dev-studio .ds-tabs-components');
            this.$units = $('#dev-studio .ds-tabs-units');

            if (
                Object.is(this.module, undefined) ||
                Object.is(this.component, undefined) ||
                Object.is(this.unit, undefined) ||
                Object.is(DSData.map[this.module], undefined)
            ) {
                this.setCondition();
            }

            this.$components.html('');
            this.$units.html('');

            let mode = $('#dev-studio #ds-ajax').hasClass('ds-active') ? 'ajax_'+DSData.mode:DSData.mode;

            // Show components and units
            var comp_html = '', units_html = '';

            $.each(DSData.map, function(key, module) {
                if (key == DevStudio.module) {
                    $.each(module.components, function(key, component) {
                        if (Object.is(DevStudio.component, undefined) || Object.is(DevStudio.component, undefined)) DevStudio.component = key;
                        comp_html += '<div class="ds-component '+(key == DevStudio.component ? 'ds-active':'')+'" data-component="' + key + '">' + component.title + '</div>';

                        if (key == DevStudio.component) {
                            $.each(component.units, function(key, unit) {
                                if (Object.is(DevStudio.unit, undefined) || Object.is(DevStudio.unit, undefined)) DevStudio.unit = key;
                                units_html += '<div class="ds-unit '+(key == DevStudio.unit ? 'ds-active':'')+'" data-unit="' + key + '">' + unit.title + '</div>';
                            });
                        }
                    });
                    DevStudio.$components.html(comp_html);
                    DevStudio.$units.html(units_html);
                }
            });
        },


        // Load data
        loadData: function () {
            if ($('#dev-studio.ds-show:not(.ds-off)').length > 0) {
                this.unsetUI();
                this.setCondition();

                let dot_unit = this.module + '.' + this.component + '.' + this.unit;

                this.ajax({
                    request: 'data',
                    checkpoint: $('#checkpoint').val(),
                    dot_unit: dot_unit
                }, function() {
                    DevStudio.setData(this.response.html);
                });
            }
        },

        updateActionsCallback: function () {
            if (this.response.html) {
                $('#dev-studio-actions').html(this.response.html);
                this.loadData();
            } else {
                //this.ajaxTest(this.ajaxTestCallback);
            }
        },

        ajaxTest: function (callback) {
            DevStudio.ajax({action: 'dev_studio_test', request: 'test', mode: 'ajax_' + DSData.mode}, callback);
        },

        ajaxTestCallback: function () {
            this.ajax({request: 'actions', mode: this.mode()}, this.updateActionsCallback);
        },

        ajax: function (args, callback) {
            let data = {
                action: 'dev_studio',
                mode: this.mode(),
                _ajax_nonce: DSData.ajax_nonce
            }
            data = obj = Object.assign({}, data, args);
            //console.log( data );

            $.ajax({
                url: DSData.ajax_url,
                type: "POST",
                dataType: 'json',
                data: data,
                beforeSend: function () {
                    setTimeout(DevStudio.preloader, 1000);
                    DevStudio.requestProgress = true;
                },
                success: function (response) {
                    //console.log(response);

                    DevStudio.response = response;

                    if (response && response.result && response.result == 'ok') {

                        if (callback != undefined) callback.apply(DevStudio);

                        if (DSData.options.general.appearance.tips == 'yes') {
                            tippy('.ds-tip', {theme: 'ds', placement: 'right'});
                        }
                    }
                },
                complete: function () {
                    DevStudio.requestProgress = false;
                    $('#dev-studio .ds-preloader').hide();
                },
            });
        },

        preloader: function() {
            if (DevStudio.requestProgress) {
                $('#dev-studio .ds-preloader').show();
            }
        },

        setCondition: function() {
            this.module = $('#dev-studio .ds-tab-module.ds-active').data('module');
            this.component = $('#dev-studio .ds-tabs-components .ds-component.ds-active').data('component');
            this.unit = $('#dev-studio .ds-tabs-units .ds-unit.ds-active').data('unit');
        },

        /**
         * Utilities
         *
         */
        utilsShow: function() {
            let utilcat = $('.ds-utilcat.ds-active').data('utilcat'), html = '';

            $.each(DSData.utils, function(index, data) {
                if (utilcat=='all' || utilcat==data.category) {
                    html += '<div class="ds-utils" data-util="'+index+'">';
                    html += '<img src="' + data.icon + '" />';
                    html += '<span>' + data.title + '</span>';
                    html += '</div>';
                }
            });
            DevStudio.setData(html);
        },

        utilsPages: function(pages, page) {
            if (pages) {
                let  comp_html = '';
                $.each(pages, function (_key, _page) {
                    comp_html += '<div class="ds-component ' + (_key == page ? 'ds-active' : '') + '" data-page="' + _key + '">' + _page.title + '</div>';
                });
                DevStudio.$components.html(comp_html);
            }
        }
    }

    $(document).ready(function () {
        /**
         * Utilities
         *
         */

        // Click on util category tab
        $('body').on('click', '#dev-studio .ds-utilcat:not(.ds-active)', function() {
            $('#dev-studio .ds-tabs-utilcats .ds-utilcat').removeClass('ds-active');
            $(this).addClass('ds-active');
            DevStudio.utilsShow();
        });

        // Click on util
        $('body').on('click', '#dev-studio .ds-utils, #dev-studio .ds-autil', function() {
            let $this = $(this), util = $(this).data('util');

            $('#dev-studio .ds-utilcat').removeClass('ds-active');

            //if ($(this).hasClass('ds-autil')) {
                $('#dev-studio .ds-tab-utilities').trigger('click');
            //}

            DevStudio.ajax(
                {
                    request: 'utility',
                    utility: util
                },
                function() {
                    let html = '';
                    if (this.response) {
                        if (!Object.is(this.response.header, undefined)) html += '<div class="ds-util-header">' + this.response.header + '</div>';
                        if (!Object.is(this.response.html, undefined)) html += '<div class="ds-util-data">' + this.response.html + '</div>';
                        if (!Object.is(this.response.assets, undefined)) html += this.response.assets;
                    }

                    DevStudio.setData(html);

                    if (!Object.is(this.response.info, undefined)) {
                        DevStudio.setDataInfo(this.response.info);
                    } else {
                        DevStudio.setDataInfo('');
                    }

                    // Units
                    DevStudio.utilsPages(this.response.pages, this.response.page);
                }
            );
        });

    });

    DevStudio.init();
})(window.jQuery);
