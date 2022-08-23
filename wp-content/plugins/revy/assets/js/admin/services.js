"use strict";
var RevyService = {
    keyword: null,
    s_min_cap: 0,
    s_max_cap: 0,
};

(function ($) {
    RevyService.init = function () {
        RevyMain.initCheckAll();
        RevyMain.initField($('.fat-semantic-container'));
        RevyService.loadServices();
        RevyMain.registerEventProcess($('.fat-sb-services-container .toolbox-action-group'));
        RevyMain.initPopupToolTip();
    };

    RevyService.initButtonToolTip = function () {
        $('.fat-item-bt-inline[data-title]', '.fat-semantic-container').each(function () {
            var position = $(this).attr('data-position'),
                option = {
                    title: '',
                    content: $(this).attr('data-title'),
                    inline: true
                };
            if (typeof position != 'undefined') {
                option['position'] = position;
            }
            $(this).popup(option);
        });
    };

    RevyService.searchNameOnKeyUp = function(self){
        var search_wrap = self.closest('.ui.input');
        if(self.val().length >=3 || self.val()==''){
            search_wrap.addClass('loading');
            RevyService.loadServices(1,function(){
                search_wrap.removeClass('loading');
            });
            if(self.val().length >=3){
                search_wrap.addClass('active-search');
            }
            if(self.val() == ''){
                search_wrap.removeClass('active-search');
            }
        }
    };

    RevyService.closeSearchOnClick = function(self){
        var search_wrap = self.closest('.ui.ui-search');
        $('input',search_wrap).val('');
        $('input',search_wrap).trigger('keyup');
    };

    RevyService.searchDropdownChange = function (self) {
        var dropdown = self.closest('.ui.dropdown');
        dropdown.addClass('loading');
        setTimeout(function () {
            RevyService.loadServices(1,function(){
                dropdown.removeClass('loading');
            });
        }, 300);
    };

    RevyService.loadServices = function (page, callback) {
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_services',
                s_name: $('.toolbox-action-group #s_name').val(),
                rm_id: $('#rm_search_model_id').val(),
                garage_id: $('#search_garage_id').val(),
                page: typeof page!='undefined' && page!='' ? page: 1
            }),
            success: function (data) {
                data = $.parseJSON(data);
                var total = data.total,
                    services = data.services;

                for (var $i = 0; $i < services.length; $i++) {
                    services[$i]['s_duration_label'] = RevyMain.data.durations[services[$i].s_min_duration];
                }
                var template = wp.template('fat-sb-service-item-template'),
                    items = $(template(services)),
                    elm_services = $('.fat-sb-list-services');

                $('tbody tr', elm_services).remove();
                $('.fat-tr-not-found', elm_services).remove();
                if (services.length > 0) {
                    elm_services.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-services'));
                    RevyService.initButtonToolTip();
                } else {
                    RevyMain.showNotFoundMessage(elm_services,'<tr class="fat-tr-not-found"><td colspan="7">','</td></tr>');
                }

                RevyMain.initPaging(total, page, $('.fat-sb-pagination'));
                RevyMain.initCheckAll();
                if (typeof callback == 'function') {
                    callback();
                }
            },
            error: function () {
                if (typeof callback == 'function') {
                    callback();
                }
            }
        })
    };

    RevyService.processEditService = function(elm){
        var s_id = typeof elm.attr('data-id') != 'undefined' ? elm.attr('data-id') : 0,
            popup_title = typeof s_id != 'undefined' ? RevyMain.data.modal_title.edit_service : '';
        RevyService.showPopupService(elm, s_id, popup_title);
    };

    RevyService.processAddService = function (elm){
        RevyService.showPopupService(elm, 0, '');
    };

    RevyService.showPopupService = function (elm, s_id, popup_title, callback) {
        RevyMain.showProcess(elm);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'get_service_by_id',
                s_id: s_id
            }),
            success: function (response) {
                RevyMain.closeProcess(elm);
                response = $.parseJSON(response);

                var services = response.services,
                    day_off = response.day_off,
                    attributes = response.attributes,
                    schedules = response.schedules;

                RevyMain.showPopup('fat-sb-services-template', popup_title, response, function () {
                    RevyMain.registerEventProcess($('.fat-services-modal'));

                    // init schedule tab
                    if (typeof schedules != 'undefined' && schedules!=null && schedules != '') {
                        var schedule_id = '',
                            schedule_class = '',
                            ss_day = '',
                            schedule_checkbox = '',
                            schedule_item = '',
                            work_hour_item = '';

                        for (var $index = 0; $index < schedules.length; $index++) {
                            ss_day = schedules[$index].ss_day;
                            switch (ss_day) {
                                case "2": {
                                    schedule_id = 'schedule_monday';
                                    schedule_class = 'schedule-monday';
                                    break;
                                }
                                case "3": {
                                    schedule_id = 'schedule_tuesday';
                                    schedule_class = 'schedule-tuesday';
                                    break;
                                }
                                case "4": {
                                    schedule_id = 'schedule_wednesday';
                                    schedule_class = 'schedule-wednesday';
                                    break;
                                }
                                case "5": {
                                    schedule_id = 'schedule_thursday';
                                    schedule_class = 'schedule-thursday';
                                    break;
                                }
                                case "6": {
                                    schedule_id = 'schedule_friday';
                                    schedule_class = 'schedule-friday';
                                    break;
                                }
                                case "7": {
                                    schedule_id = 'schedule_saturday';
                                    schedule_class = 'schedule-saturday';
                                    break;
                                }
                                case "8": {
                                    schedule_id = 'schedule_sunday';
                                    schedule_class = 'schedule-sunday';
                                    break;
                                }
                            }

                            schedule_checkbox = $('input#' + schedule_id);
                            schedule_item = schedule_checkbox.closest('.schedule-item');
                            if (schedules[$index].ss_enable == "1") {
                                schedule_checkbox.attr("checked", 'check');
                                $('.fat-sb-work-hour-wrap', schedule_item).removeClass('fat-sb-hidden').removeClass('fat-hidden');

                                $('.fat-bt-add-work-hour', schedule_item).trigger('click');
                                work_hour_item = $('.fat-sb-work-hour-item-wrap .fat-sb-work-hour-item:last-child', schedule_item);
                                $('.fat-work-hour-start-dropdown', work_hour_item).dropdown('refresh').dropdown('set selected', schedules[$index].ss_work_hour_start);
                                $('.fat-work-hour-end-dropdown', work_hour_item).dropdown('refresh').dropdown('set selected', schedules[$index].ss_work_hour_end);
                            } else {
                                schedule_checkbox.removeAttr("checked");
                                $('.fat-sb-work-hour-wrap', schedule_item).addClass('fat-sb-hidden');
                            }
                        }
                    }

                    //init day off
                    if(typeof day_off != 'undefined' && day_off!=null && day_off != ''){
                        var day_off_item = '';
                        for (var $index = 0; $index < day_off.length; $index++) {
                            RevyService.addDayOffItem($('.fat-day-off-wrap','.fat-services-modal'), day_off[$index].dof_name, day_off[$index].dof_start, day_off[$index].dof_end);
                        }
                    }

                    //init attribute tab
                    if(typeof attributes != 'undefined' && attributes!=null && attributes != ''){
                        var attr_item = '';
                        for (var $index = 0; $index < attributes.length; $index++) {
                            $('.fat-bt-add-attribute','.fat-services-modal').trigger('click');
                            attr_item = $('.fat-attribute-wrap .fat-attribute-inner .fat-sb-attribute-item:last-child', '.fat-services-modal');
                            $('#s_attr_title', attr_item).val(attributes[$index].s_attr_title);
                            $('#s_attr_value', attr_item).val(attributes[$index].s_attr_value);
                            $('#s_price', attr_item).val(attributes[$index].s_price);
                        }
                    }

                    if(callback){
                        callback();
                    }

                });
            },
            error: function () {
            }
        });
    };

    RevyService.processSubmitService = function (self) {
        if (RevyMain.isFormValid) {
            var form = $('.fat-services-modal'),
                image_url = $('#s_image_id img').attr('src'),
                hide_price = form.hasClass('hide-price'),
                data = {
                    services: {},
                    schedules: [],
                    day_off: [],
                    attributes: []
            };

            if (typeof self.attr('data-id') != 'undefined' && self.attr('data-id') != '') {
                data.services.s_id = self.attr('data-id');
            } else {
                data.services.s_id = '';
            }

            data.services.s_image_id = $('#s_image_id',form).attr('data-image-id');
            data.services.s_name = $('#s_name', form).val();
            data.services.s_model_id = $('#s_model_id', form).val();
            data.services.s_break_time = $('#s_break_time', form).val();
            data.services.s_duration = $('#s_duration', form).val();
            data.services.s_tax = $('#s_tax', form).val();
            data.services.s_maximum_slot = $('#s_maximum_slot', form).val();
            data.services.s_garage_ids = $('#s_garage_ids', form).val();
            data.services.s_description = $('#s_description', form).val();
            data.services.s_order = $('#s_order', form).val();
            data.services.s_allow_booking_online = $('#s_allow_booking_online', form).is(':checked') ? 1 : 0;

            // get schedule
            var schedules = [
                    {'id': 'schedule_monday', 'class': 'schedule-monday', 'day': 2},
                    {'id': 'schedule_tuesday', 'class': 'schedule-tuesday', 'day': 3},
                    {'id': 'schedule_wednesday', 'class': 'schedule-wednesday', 'day': 4},
                    {'id': 'schedule_thursday', 'class': 'schedule-thursday', 'day': 5},
                    {'id': 'schedule_friday', 'class': 'schedule-friday', 'day': 6},
                    {'id': 'schedule_saturday', 'class': 'schedule-saturday', 'day': 7},
                    {'id': 'schedule_sunday', 'class': 'schedule-sunday', 'day': 8}
                ],
                schedule_id = '',
                schedule_class = '',
                day = 0,
                work_hour_item_wrap = '',
                work_hour_item = '',
                work_hour_start = '',
                work_hour_end = '';

            for (var $i = 0; $i < schedules.length; $i++) {
                schedule_id = schedules[$i].id;
                schedule_class = schedules[$i].class;
                day = schedules[$i].day;
                if ($('input#' + schedule_id).is(':checked')) {
                    work_hour_item_wrap = $('.fat-sb-work-hour-item-wrap', '.fat-sb-work-hour-wrap.' + schedule_class);
                    $('.fat-sb-work-hour-item', work_hour_item_wrap).each(function () {
                        work_hour_item = $(this);
                        work_hour_start = $('input[name="work_hour_start"]', work_hour_item).val();
                        work_hour_end = $('input[name="work_hour_end"]', work_hour_item).val();

                        if (work_hour_start != '' && work_hour_end != '') {
                            data.schedules.push({
                                es_day: day,
                                es_enable: 1,
                                es_work_hour_start: work_hour_start,
                                es_work_hour_end: work_hour_end
                            })
                        }
                    });
                } else {
                    data.schedules.push({
                        es_day: day,
                        es_enable: 0
                    });
                }
            }

            // get day off
            var day_of_item = '',
                day_of_schedule = '';
            $('.fat-day-off-wrap .fat-sb-day-off-item', form).each(function () {
                day_of_item = $(this);
                day_of_schedule = $('input[name="day_off_schedule"]', day_of_item);
                data.day_off.push({
                    dof_name: $('input[name="day_off_name"]', day_of_item).val(),
                    dof_start: typeof day_of_schedule.attr('data-start') != 'undefined' ? day_of_schedule.attr('data-start') : '',
                    dof_end: typeof day_of_schedule.attr('data-end') != 'undefined' ? day_of_schedule.attr('data-end') : '',
                });
            });

            // get attribute & price
            var attr_title = '',
                attr_value = '',
                price = 0,
                min_price = 0;

            $('.fat-attribute-wrap .fat-sb-attribute-item', form).each(function(){
                attr_title = $('#s_attr_title', this).val();
                attr_value = $('#s_attr_value', this).val();
                price = parseFloat($('#s_price', this).val());
                if(attr_title!='' && attr_value !='' && price!=''){
                    min_price = min_price==0 || min_price >  price ? price  : min_price;
                    data.attributes.push({
                        s_attr_title: attr_title,
                        s_attr_value: attr_value,
                        s_price: price
                    }) ;
                }
            });
            data.services.s_min_price = min_price;

            if (typeof self.attr('data-id') != 'undefined' && self.attr('data-id') != '') {
                data.s_id = self.attr('data-id');
                RevyMain.showProcess(self);
                RevyService.submitService(self, data);
            } else {
                RevyMain.showProcess(self);
                RevyService.submitService(self, data);
            }
        }
    };

    RevyService.submitService = function (self, data) {
        var duration_label = $('#s_duration_label').html(),
            image_url = $('#s_image_id img').attr('src'),
            model = typeof data.services.s_model_id !='undefined' && data.services.s_model_id!='' ? $('.fat-services-modal .rm-model.dropdown').dropdown('get text') : '';

        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'save_service',
                data: data
            }),
            success: function (response) {
                RevyMain.closeProcess(self);
                self.closest('.ui.modal').modal('hide');
                response = $.parseJSON(response);

                if (response.result >= 0) {
                    RevyMain.showMessage(self.attr('data-success-message'));
                    $('.fat-sb-list-services .fat-sb-not-found').remove();

                    //update back to list
                    var item = $('.fat-sb-list-services .item[data-id="' + data.s_id + '"]');
                    data.services.s_image_url = typeof image_url != 'undefined' ? image_url : '';
                    data.services.s_duration_label = duration_label;
                    data.services.rm_name = model;

                    if (item.length == 0) {
                        data.services.s_id = response.result;
                        var template = wp.template('fat-sb-service-item-template'),
                            item = $(template([data.services]));
                        $('.fat-sb-list-services').append(item);
                        RevyMain.registerEventProcess(item);

                    } else {
                        $('.fat-s-name', item).html(data.services.s_name);
                        $('.fat-s-model', item).html(model);
                        $('.fat-s-duration', item).html(data.services.s_duration_label);
                        $('.fat-s-price', item).html(data.services.s_price);
                        $('.fat-s-max-slot', item).html(data.services.s_maximum_slot);
                        if(data.services.s_allow_booking_online=="1"){
                            $('.fat-s-status', item).text('Yes');
                        }else{
                            $('.fat-s-status', item).text('No');
                        }

                    }

                    if (typeof response.cats != 'undefined') {
                        for (var id in response.cats) {
                            $('.fat-sb-list-services-category .item[data-id="' + id + '"] .category-total-service', '.fat-sb-services-container').text(response.cats[id]);
                        }
                    }
                } else {
                    if (typeof response.message != 'undefined') {
                        RevyMain.showMessage(response.message, 3);
                    } else {
                        RevyMain.showMessage(RevyMain.data.error_message, 2);
                    }
                }
            },
            error: function () {
                RevyMain.closeProcess(self);
                RevyMain.showMessage(RevyMain.data.error_message, 2);
            }
        });
    };

    RevyService.btAddDayOfOnClick = function (self) {
        var day_off_wrap = self.closest('.fat-day-off-wrap');
        RevyService.addDayOffItem(day_off_wrap);
    };

    RevyService.addDayOffItem = function (day_off_wrap, name, start, end) {
        var template = wp.template('fat-sb-day-off-template'),
            dat_off_item = $(template([])),
            date_format = MainService.getDateFormat();

        if (typeof name != 'undefined' && name != null) {
            $('input[name="day_off_name"]', dat_off_item).val(name);
        }

        $('input[name="day_off_schedule"]', dat_off_item).attr('data-start', start);
        $('input[name="day_off_schedule"]', dat_off_item).attr('data-end', end);

        $('.fat-day-off-inner', day_off_wrap).append(dat_off_item);

        if ($.isFunction($.fn.daterangepicker)) {
            $('input.date-range-picker', dat_off_item).each(function () {
                var self = $(this),
                    opt = {
                        locale: {
                            format: date_format,
                            applyLabel: MainService.data.apply_title,
                            cancelLabel: MainService.data.cancel_title,
                            fromLabel: MainService.data.from_title,
                            toLabel: MainService.data.to_title,
                            daysOfWeek: MainService.data.day_of_week,
                            monthNames: MainService.data.month_name
                        }
                    };
                if (typeof start != 'undefined' && start != '') {
                    opt.startDate = moment(start, 'YYYY-MM-DD');
                }
                if (typeof end != 'undefined' && end != '') {
                    opt.endDate = moment(end, 'YYYY-MM-DD');
                }
                self.daterangepicker(opt, function (start, end, label) {
                    self.attr('data-start', start.format('YYYY-MM-DD'));
                    self.attr('data-end', end.format('YYYY-MM-DD'));
                });
            });

        }

        $('.fat-bt-remove-day-off').off('click').on('click', function () {
            $(this).closest('.fat-sb-day-off-item').remove();
        });
    };

    RevyService.btAddWorkHourOnClick = function (self) {
        var container = self.closest('.fat-sb-work-hour-wrap'),
            work_hour_item_wrap = $('.fat-sb-work-hour-item-wrap', container),
            template = wp.template('fat-sb-work-hour-template'),
            work_hour_item = $(template([]));

        $(work_hour_item_wrap).append(work_hour_item);

        $('.fat-bt-remove-work-hour').off('click').on('click', function () {
            $(this).closest('.fat-sb-work-hour-item').remove();
        });

        //init field
        $('.dropdown', work_hour_item).dropdown({
            'onShow': function () {
                RevyService.updateWorkHourBreakTimeItemStatus($(this));
            }
        });
    };

    RevyService.updateWorkHourBreakTimeItemStatus = function (elm) {
        var work_hours = [],
            schedule_item = $(elm).closest('.schedule-item'),
            work_hour_item = $(elm).closest('.fat-sb-work-hour-item'),
            work_hour_wrap = $('.fat-sb-work-hour-item-wrap', schedule_item),
            current_item_index = $('.fat-sb-work-hour-item', work_hour_wrap).index(work_hour_item),
            current_time_start = $('input[name="work_hour_start"]', work_hour_item).val(),
            start = '',
            end = '',
            index = 0,
            self = '';

        $('.fat-sb-work-hour-item', work_hour_wrap).each(function () {
            self = $(this);
            if (index != current_item_index) {
                start = $('input[name="work_hour_start"]', self).val();
                end = $('input[name="work_hour_end"]', self).val();
                if (start != '' && end != '') {
                    work_hours.push({
                        'start': parseInt(start),
                        'end': parseInt(end),
                        'type': 1 //work hour
                    });
                }
            }
            index++;
        });


        if (work_hours.length > 0) {
            $('.fat-time-dropdown .menu', work_hour_item).each(function () {
                var self = $(this);
                $('.item', self).removeClass('disabled');
            });
        }

    };

    RevyService.processCloneSchedule = function (self) {
        var btApplies = self;
        btApplies.addClass('loading');

        setTimeout(function () {
            var item_wrap = btApplies.closest('.schedule-item'),
                popup_clone = $('.fat-popup-work-hour-clone', item_wrap),
                clone_to = [];
            $('input[type="checkbox"]', popup_clone).each(function () {
                if ($(this).is(':checked')) {
                    clone_to.push($(this).val());
                }
            });
            if (clone_to.length > 0) {
                var work_hours = [],
                    break_times = [],
                    start = '',
                    end = '',
                    self = '',
                    s_id = '';
                $('.fat-sb-work-hour-item-wrap .fat-sb-work-hour-item', item_wrap).each(function () {
                    self = $(this);
                    start = $('input[name="work_hour_start"]', self).val();
                    end = $('input[name="work_hour_end"]', self).val();
                    s_id = $('select[name="assign-services"]', self).val();
                    if (start != '' || end != '') {
                        work_hours.push({
                            start: start,
                            end: end,
                            s_id: s_id
                        });
                    }
                });

                $('.fat-sb-break-time-item-wrap .fat-sb-break-time-item', item_wrap).each(function () {
                    self = $(this);
                    start = $('input[name="break_time_start"]', self).val();
                    end = $('input[name="break_time_end"]', self).val();
                    if (start != '' || end != '') {
                        break_times.push({
                            start: start,
                            end: end
                        });
                    }
                });

                var schedule_check = '',
                    schedule_item_wrap = '';
                for (var $i = 0; $i < clone_to.length; $i++) {
                    schedule_check = $('#' + clone_to[$i]);
                    if (typeof schedule_check != 'undefined' && schedule_check.length > 0) {
                        schedule_item_wrap = schedule_check.closest('.schedule-item');
                        schedule_check.prop('checked', true);
                        $('.fat-sb-work-hour-wrap', schedule_item_wrap).removeClass('fat-sb-hidden').removeClass('fat-hidden');
                        $('.fat-sb-work-hour-item', schedule_item_wrap).remove();
                        $('.fat-sb-break-time-item', schedule_item_wrap).remove();
                        var new_item = '';
                        if (work_hours != null) {
                            for (var $j = 0; $j < work_hours.length; $j++) {
                                $('button.fat-bt-add-work-hour', schedule_item_wrap).trigger('click');
                                new_item = $('.fat-sb-work-hour-item-wrap .fat-sb-work-hour-item:last-child', schedule_item_wrap);
                                $('.fat-work-hour-start-dropdown', new_item).dropdown('set selected', work_hours[$j].start);
                                $('.fat-work-hour-end-dropdown', new_item).dropdown('set selected', work_hours[$j].end);
                                if (work_hours[$j].s_id != null) {
                                    for (var $k = 0; $k < work_hours[$j].s_id.length; $k++) {
                                        $('select', new_item)[0].sumo.selectItem(work_hours[$j].s_id[$k]);
                                    }
                                }
                            }
                        }

                        //clone break time
                        if (break_times != null) {
                            for (var $j = 0; $j < break_times.length; $j++) {
                                $('button.fat-bt-add-break-time', schedule_item_wrap).trigger('click');
                                new_item = $('.fat-sb-break-time-item-wrap .fat-sb-break-time-item:last-child', schedule_item_wrap);
                                $('.fat-break-time-start-dropdown', new_item).dropdown('set selected', break_times[$j].start);
                                $('.fat-break-time-end-dropdown', new_item).dropdown('set selected', break_times[$j].end);
                            }
                        }
                    }
                }

                btApplies.removeClass('loading');
                btApplies.closest('.fat-popup-work-hour-clone').popup('hide');
            }
        }, 100);
    };

    RevyService.btAddDayOfOnClick = function (self) {
        var day_off_wrap = self.closest('.fat-day-off-wrap');
        RevyService.addDayOffItem(day_off_wrap);
    };

    RevyService.addDayOffItem = function (day_off_wrap, name, start, end) {
        var template = wp.template('fat-sb-day-off-template'),
            dat_off_item = $(template([])),
            date_format = RevyMain.getDateFormat();

        if (typeof name != 'undefined' && name != null) {
            $('input[name="day_off_name"]', dat_off_item).val(name);
        }

        $('input[name="day_off_schedule"]', dat_off_item).attr('data-start', start);
        $('input[name="day_off_schedule"]', dat_off_item).attr('data-end', end);

        $('.fat-day-off-inner', day_off_wrap).append(dat_off_item);

        if ($.isFunction($.fn.daterangepicker)) {
            $('input.date-range-picker', dat_off_item).each(function () {
                var self = $(this),
                    opt = {
                        locale: {
                            format: date_format,
                            applyLabel: RevyMain.data.apply_title,
                            cancelLabel: RevyMain.data.cancel_title,
                            fromLabel: RevyMain.data.from_title,
                            toLabel: RevyMain.data.to_title,
                            daysOfWeek: RevyMain.data.day_of_week,
                            monthNames: RevyMain.data.month_name
                        }
                    };
                if (typeof start != 'undefined' && start != '') {
                    opt.startDate = moment(start, 'YYYY-MM-DD');
                }
                if (typeof end != 'undefined' && end != '') {
                    opt.endDate = moment(end, 'YYYY-MM-DD');
                }
                self.daterangepicker(opt, function (start, end, label) {
                    self.attr('data-start', start.format('YYYY-MM-DD'));
                    self.attr('data-end', end.format('YYYY-MM-DD'));
                });
            });

        }

        $('.fat-bt-remove-day-off').off('click').on('click', function () {
            $(this).closest('.fat-sb-day-off-item').remove();
        });
    };

    RevyService.processDeleteService = function(self){
        var btDelete = self;
        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title, RevyMain.data.confirm_delete_message, function (result, popup) {
            if (result == 1) {
                var self = $('.fat-sb-bt-confirm.yes', popup),
                    s_ids = [];

                if(btDelete.hasClass('fat-item-bt-inline')){
                    s_ids.push(btDelete.attr('data-id'));
                }else{
                    $('input.check-item[type="checkbox"]', 'table.fat-sb-list-services').each(function(){
                        if($(this).is(':checked')){
                            s_ids.push($(this).attr('data-id'));
                        }
                    });
                }

                RevyMain.showProcess(self);
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_service',
                        s_ids: s_ids
                    }),
                    success: function (response) {
                        try {
                            RevyMain.closeProcess(self);
                            self.closest('.ui.modal').modal('hide');
                            response = $.parseJSON(response);
                            if (response.result > 0) {

                                for(var $i=0; $i< s_ids.length; $i++){
                                    $('tr[data-id="'+ s_ids[$i] +'"]','.fat-sb-list-services').remove();
                                }
                                if ($('.fat-sb-list-services .item').length == 0) {
                                    RevyMain.showNotFoundMessage($('.fat-sb-list-services'),'<tr class="fat-tr-not-found"><td colspan="7">','</td></tr>');
                                }
                            } else {
                                if(typeof response.message!='undefined'){
                                    RevyMain.showMessage(response.message, 3);
                                }else{
                                    RevyMain.showMessage(RevyMain.data.error_message, 2);
                                }
                            }
                        } catch (err) {
                            RevyMain.closeProcess(self);
                            RevyMain.showMessage(RevyMain.data.error_message);
                        }
                    },
                    error: function () {
                        FatSbMain.closeProcess(self);
                        FatSbMain.showMessage(FatSbMain.data.error_message);
                    }
                });
            }
        });
    };

    RevyService.processCloneService = function(elm){
        var s_id = typeof elm.attr('data-id') != 'undefined' ? elm.attr('data-id') : 0;
        RevyService.showPopupService(elm, s_id, RevyMain.data.modal_title.clone_service, function(){
            $('.fat-services-modal button.fat-bt-submit-service').attr('data-id', 0);
        });
    };

    RevyService.btAddAttributeOnClick = function (self) {
        var attr_wrap = self.closest('.fat-attribute-wrap');
        RevyService.addAttributeItem(attr_wrap);
    };

    RevyService.addAttributeItem = function (attr_wrap, duration, price) {
        var template = wp.template('fat-sb-attribute-template'),
            attr_item = $(template([]));

        $('.fat-attribute-inner', attr_wrap).append(attr_item);

        $('.ui.dropdown', attr_wrap).each(function () {
            var self = $(this);
            self.dropdown({
                clearable: self.hasClass('clearable')
            });
        });
        RevyMain.initNumberField(attr_wrap);

        $('.fat-bt-remove-attribute').off('click').on('click', function () {
            $(this).closest('.fat-sb-attribute-item').remove();
        });
    };

    RevyService.openImport = function (self){
        $('.fat-semantic-container .content.services').fadeOut(function(){
            $('.fat-semantic-container .content.fat-sb-import-section').fadeIn();
        })
    };

    RevyService.closeImport = function (self){
        $('.fat-semantic-container .content.fat-sb-import-section').fadeOut(function(){
            $('.fat-semantic-container .content.services').fadeIn();
        })
    };

    $(document).ready(function () {
        if ($('.fat-sb-services-container').length > 0) {
            RevyService.init();
        }
    });

})(jQuery)