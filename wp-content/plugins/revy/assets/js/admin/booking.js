"use strict";
var RevyBooking = {
    bod_field: null,
    order: '',
    order_by: '',
    b_id: 0,
    b_time: 0,
    b_date: '',
    booking_detail: [],
    s_ids: [],
    garage_id: 0,
    delivery_method: 1,
    time_slot_monthly: [],
    time_slot: [],
    is_first_init: 0,
    b_date_type: ''
};
(function ($) {
        RevyBooking.init = function () {
            RevyMain.initField($('.fat-semantic-container'));
            RevyMain.initCheckAll();

            RevyBooking.loadBooking(1);
            RevyMain.bindServicesDicHierarchy($('.fat-checkbox-dropdown-wrap.fat-sb-services-dic'));
            RevyMain.bindCustomersDic($('.fat-checkbox-dropdown-wrap.fat-sb-customers-dic'));
            RevyMain.bindGarageDic($('.fat-sb-booking-container .fat-sb-garage-dic'));

            RevyMain.registerEventProcess($('.fat-booking-status-list', '.fat-sb-booking-container'));
            RevyMain.registerEventProcess($('.toolbox-action-group', '.fat-sb-booking-container'));
            RevyMain.registerOnClick($('.fat-sb-order-wrap', '.fat-sb-booking-container'));
        };

        RevyBooking.loadBooking = function (page, callback) {
            var b_customer_name = $('#b_customer_name').val(),
                start_date = $('#date_of_book').attr('data-start'),
                start_time = $('#date_of_book').attr('data-start-time'),
                end_date = $('#date_of_book').attr('data-end'),
                end_time = $('#date_of_book').attr('data-end-time'),
                b_customer = $('#b_customer').val(),
                b_service = $('#b_service').val(),
                b_process_status = $('#b_process_status').val(),
                b_delivery_method = $('#b_delivery_method').val();
            $('.fat-sb-list-booking tbody tr').remove();
            page = typeof page != 'undefined' && page != '' ? page : 1;
            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'GET',
                data: ({
                    action: 'get_booking',
                    b_customer_name: b_customer_name,
                    start_date: start_date,
                    start_time: start_time,
                    end_date: end_date,
                    end_time: end_time,
                    b_customer: b_customer,
                    b_service: b_service,
                    b_process_status: b_process_status,
                    b_delivery_method: b_delivery_method,
                    order: RevyBooking.order,
                    order_by: RevyBooking.order_by,
                    garage: $('#garage').val(),
                    page: page
                }),
                success: function (response) {
                    response = $.parseJSON(response);
                    var total = response.total,
                        bookings = response.bookings,
                        template = wp.template('fat-sb-booking-item-template'),
                        items = '',
                        elm_bookings = $('.fat-sb-list-booking');

                    RevyBooking.booking_detail = response.booking_detail;

                    if (bookings.length > 0) {
                        $('.fat-bt-export').removeClass('disabled');
                    } else {
                        $('.fat-bt-export').addClass('disabled');
                    }

                    var hour = 0,
                        minute = 0;
                    for (var $b_index = 0; $b_index < bookings.length; $b_index++) {
                        bookings[$b_index].b_total_pay = RevyMain.data.symbol_position == 'before' ? (RevyMain.data.symbol + bookings[$b_index].b_total_pay) : (bookings[$b_index].b_total_pay + RevyMain.data.symbol);
                        hour = Math.floor(bookings[$b_index].b_time / 60);
                        hour = hour < 10 ? ('0' + hour) : hour;
                        minute = bookings[$b_index].b_time % 60;
                        minute = minute < 10 ? ('0' + minute) : minute;
                        bookings[$b_index].b_date_display = bookings[$b_index].b_date + ' ' + (hour + ':' + minute);
                    }
                    items = $(template(bookings));

                    $('#total_canceled').html(response.total_cancel);
                    $('#total_pending').html(response.total_pending);
                    $('#total_rejected').html(response.total_reject);
                    $('#total_approved').html(response.total_approved);

                    $('tbody tr', elm_bookings).remove();
                    if (bookings.length > 0) {
                        elm_bookings.append(items);
                        RevyMain.registerEventProcess($('.fat-sb-list-booking'));
                        RevyMain.initCheckAll();
                        $('.fat-item-bt-inline[data-title]', '.fat-semantic-container').each(function () {
                            $(this).popup({
                                title: '',
                                content: $(this).attr('data-title'),
                                inline: true
                            });
                        });
                    } else {
                        RevyMain.showNotFoundMessage($('tbody', elm_bookings), '<tr><td colspan="10">', '</td></tr>');
                    }
                    RevyMain.initPaging(total, page, $('.fat-sb-pagination'));

                    $('table.fat-sb-list-booking .ui.dropdown').dropdown();

                    $('.fat-item-bt-inline[data-title]', '.fat-semantic-container').each(function () {
                        $(this).popup({
                            title: '',
                            content: $(this).attr('data-title'),
                            inline: true
                        });
                    });

                    if (callback) {
                        callback();
                    }
                },
                error: function () {
                    if (callback) {
                        callback();
                    }
                }
            })
        };

        RevyBooking.showPopupBooking = function (elm, callback) {
            var b_id = typeof elm.attr('data-id') != 'undefined' ? elm.attr('data-id') : 0,
                submit_callback = elm.attr('data-submit-callback');

            RevyMain.showProcess(elm);

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'GET',
                data: ({
                    action: 'get_booking_by_id',
                    b_id: b_id
                }),
                success: function (response) {
                    RevyMain.closeProcess(elm);
                    response = $.parseJSON(response);
                    RevyMain.showPopup('fat-sb-booking-template', '', response, function () {
                        var date_format = RevyBooking.getDateFormat(),
                            elmBookingDate = $('.air-date-picker', '.fat-sb-booking-form'),
                            locale = elmBookingDate.attr('data-locale');

                        locale = locale.split('_').length > 1 ? locale.split('_')[0] : locale;
                        var option = {
                            language: locale,
                            minDate: new Date(),
                            dateFormat: date_format,
                            autoClose: true
                        };

                        RevyBooking.garage_id = response.booking.b_garage_id;
                        RevyBooking.b_date = response.booking.b_date;
                        RevyBooking.b_time = response.booking.b_time;
                        RevyBooking.booking_detail = response.booking_detail;
                        RevyBooking.b_date_type = new Date(response.booking.b_date + ' 00:00:00');
                        RevyBooking.delivery_method = response.booking.b_delivery_method;

                        RevyBooking.s_ids = [];
                        for (let $bk of response.booking_detail) {
                            RevyBooking.s_ids.push($bk.s_id);
                        }

                        if(RevyBooking.delivery_method != 3){
                            $('.fat-sb-booking-form .ui.form .date-time-fields').removeClass('fat-sb-hidden');
                            RevyBooking.bod_field = elmBookingDate.datepicker(option).data('datepicker');

                            if (response.booking.b_process_status != 0 && response.booking.b_process_status != 1) {
                                $('.fat-sb-booking-date-wrap .air-date-picker', '.fat-sb-booking-form').addClass('disabled');
                                $('.fat-sb-booking-time-wrap', '.fat-sb-booking-form').addClass('disabled');
                            } else {
                                RevyBooking.b_id = response.booking.b_id;
                                RevyBooking.getTimeSlot(response.booking.b_date, RevyBooking.garage_id, RevyBooking.s_ids, function () {
                                    RevyBooking.is_first_init = 1;
                                    RevyBooking.initDate();
                                    RevyBooking.bod_field.selectDate(RevyBooking.b_date_type);

                                    if( $('.fat-sb-booking-time-wrap .scrolling.menu .item[data-value="' +  RevyBooking.b_time + '"]', '.fat-sb-booking-form').length ==0){
                                        $('.fat-sb-booking-time-wrap .scrolling.menu', '.fat-sb-booking-form').append('<div class="item active selected" data-value="' + RevyBooking.b_time + '">' + RevyMain.data.slots[RevyBooking.b_time] + '</div>')
                                    }
                                    setTimeout(function () {
                                        $('.fat-sb-booking-time-wrap', '.fat-sb-booking-form').dropdown('set selected', RevyBooking.b_time);
                                    }, 500);
                                });
                            }
                        }else{
                            $('.fat-sb-booking-form .ui.form .date-time-fields').addClass('fat-sb-hidden');
                        }


                        RevyMain.registerEventProcess($('.fat-sb-booking-form'));

                        if (typeof submit_callback != 'undefined' && submit_callback != '') {
                            $('.fat-sb-booking-form .fat-submit-modal').attr('data-callback', submit_callback);
                        }

                        if (typeof callback == 'function') {
                            callback();
                        }
                    });
                },
                error: function () {
                }
            });
        };

        /*
        Process on change
         */

        RevyBooking.dropdownClick = function (self) {
            var value = '',
                message = '';
            if (self.hasClass('fat-sb-services-dic')) {
                value = $('#b_service_cat_id').val();
                message = self.attr('data-warning-message');
            }

            if (self.hasClass('fat-sb-services-extra-dic')) {
                value = $('#b_service_id').val();
                message = self.attr('data-warning-message');
            }

            if (self.hasClass('fat-sb-employees-dic')) {
                value = $('#b_service_id').val();
                value = $('#b_garage_id').val() == '' ? '' : value;
                message = self.attr('data-warning-message');
            }
            if (self.hasClass('fat-customer-number-dic')) {
                value = $('#b_service_id').val();
                message = self.attr('data-warning-message');
            }

            if (value == '') {
                self.popup({
                    title: '',
                    on: 'click',
                    hoverable: true,
                    position: 'bottom left',
                    content: message,
                    inline: true
                }).popup('toggle');
            } else {
                self.popup('destroy');
            }
        };

        RevyBooking.searchNameKeyup = function (self) {
            var search_wrap = self.closest('.ui.input');
            if (self.val().length >= 3 || self.val() == '') {
                search_wrap.addClass('loading');
                RevyBooking.loadBooking(1, function () {
                    search_wrap.removeClass('loading');
                });
                if (self.val().length >= 3) {
                    search_wrap.addClass('active-search');
                }
                if (self.val() == '') {
                    search_wrap.removeClass('active-search');
                }
            }
        };

        RevyBooking.sumoSearchOnChange = function (self) {
            var sumoContainer = self.closest('.SumoSelect'),
                prev_value = self.attr('data-prev-value'),
                value = self.val();

            value = value != null ? value : '';

            if (value != prev_value) {
                $('.ui.loader', sumoContainer).remove();
                sumoContainer.addClass('fat-loading');
                sumoContainer.append('<div class="ui active tiny inline loader"></div>');
                self.attr('data-prev-value', value);
                RevyBooking.loadBooking(1, function () {
                    $('.ui.loader', sumoContainer).remove();
                    sumoContainer.removeClass('fat-loading');
                });
            }
        };

        RevyBooking.closeSearchOnClick = function (self) {
            var search_wrap = self.closest('.ui.ui-search');
            $('input', search_wrap).val('');
            $('input', search_wrap).trigger('keyup');
        };

        RevyBooking.searchDateOnChange = function (self) {
            var date_picker = self.closest('.ui.date-input');
            $('.ui.loader', date_picker).remove();
            date_picker.addClass('fat-loading');
            date_picker.append('<div class="ui active tiny inline loader"></div>');
            RevyBooking.loadBooking(1, function () {
                $('.ui.loader', date_picker).remove();
                date_picker.removeClass('fat-loading');
            });
        };

        RevyBooking.searchStatusChange = function (self) {
            var dropdown = self.closest('.ui.dropdown');
            dropdown.addClass('loading');
            setTimeout(function () {
                RevyBooking.loadBooking(1, function () {
                    dropdown.removeClass('loading');
                });
            }, 300);
        };

        RevyBooking.searchBookingDeliveryChange = function (self) {
            var dropdown = self.closest('.ui.dropdown');
            dropdown.addClass('loading');
            setTimeout(function () {
                RevyBooking.loadBooking(1, function () {
                    dropdown.removeClass('loading');
                });
            }, 300);
        };

        RevyBooking.initDate = function () {
            RevyBooking.time_slot = [];
            $('.air-date-picker').datepicker({
                onRenderCell: function (date, cellType) {
                    if (cellType == 'day') {
                        if (RevyBooking.b_date_type <= date && RevyBooking.isSlotAvailable(date, RevyBooking.s_ids, RevyBooking.time_slot_monthly)) {
                            return {
                                classes: 'has-time-slot',
                                disabled: false
                            };
                        } else {
                            return {
                                classes: 'none-time-slot',
                                disabled: false
                            };
                        }
                    }
                },
                onSelect: function (formattedDate, date, inst) {
                    var date_str = RevyBooking.getDateStr(date);
                    $('#b_date', '.fat-sb-booking-form').attr('data-date', date_str);
                    if (!RevyBooking.is_first_init) {
                        RevyBooking.getTimeSlot(date_str, RevyBooking.garage_id, RevyBooking.s_ids, function () {
                            RevyBooking.initTimeSlot(date);
                            $('.fat-sb-booking-time-wrap', '.fat-sb-booking-form').dropdown('set text', 'Select time');
                        });
                    } else {
                        RevyBooking.initTimeSlot(date);
                        RevyBooking.is_first_init = 0;
                    }
                }
            });
        };

        RevyBooking.initTimeSlot = function (date) {
            var date_wrap = $('.fat-sb-booking-date-wrap', '.fat-sb-booking-form'),
                time_wrap = $('.fat-sb-booking-time-wrap', '.fat-sb-booking-form');

            var date_str = RevyBooking.getDateStr(date),
                dt_slot = _.findWhere(RevyBooking.time_slot, {date: date_str});

            if (typeof dt_slot == 'undefined' || dt_slot.time_slot.length == 0) {
                return;
            }

            date_wrap.attr('data-date', date_str);

            $('.scrolling.menu', time_wrap).empty();
            var item = '';
            if (date_str == RevyBooking.b_date) {
                item = '<div class="item" data-value="' + RevyBooking.b_time + '">' + RevyMain.data.slots[RevyBooking.b_time] + '</div>';
                $('.scrolling.menu', time_wrap).append(item);
            }
            for (let ts of dt_slot.time_slot) {
                item = '<div class="item" data-value="' + ts.slot + '">' + RevyMain.data.slots[ts.slot] + '</div>';
                $('.scrolling.menu', time_wrap).append(item);
            }

            if ($('.item', time_wrap).length == 0) {
                //display not found message
                time_wrap.dropdown('set text', RevyMain.data.empty_time_slot);
            }
        };

        RevyBooking.getTimeSlot = function (date, garage_id, s_ids, callback) {
            var date_wrap = $('.fat-sb-booking-date-wrap', '.fat-sb-booking-form'),
                time_wrap = $('.fat-sb-booking-time-wrap', '.fat-sb-booking-form');

            date_wrap.addClass('fat-loading');
            time_wrap.addClass('fat-loading');
            date_wrap.append('<div class="ui button loading"></div>');
            time_wrap.append('<div class="ui button loading"></div>');
            time_wrap.dropdown('clear');

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'GET',
                data: ({
                    action: 'get_time_slot_monthly',
                    s_field: revy_data.ajax_s_field,
                    s_ids: s_ids,
                    garage_id: garage_id,
                    date: date,
                }),
                success: function (response) {
                    response = $.parseJSON(response);
                    RevyBooking.time_slot_monthly = response;

                    date_wrap.removeClass('fat-loading');
                    time_wrap.removeClass('fat-loading');
                    $('.ui.button.loading', date_wrap).remove();
                    $('.ui.button.loading', time_wrap).remove();

                    if (callback) {
                        callback(response);
                    }
                },
                error: function () {
                    date_wrap.removeClass('fat-loading');
                    time_wrap.removeClass('fat-loading');
                    $('.ui.button.loading', date_wrap).remove();
                    $('.ui.button.loading', time_wrap).remove();
                }
            });
        };

        RevyBooking.isSlotAvailable = function (date, s_ids, data) {
            var date_str = RevyBooking.getDateStr(date),
                booking_in_day = [],
                day = [],
                min_cap = 1,
                max_cap = 1,
                cap = 1,
                booking_service_in_day = [],
                time_slot = [],
                group_slot = [],
                time = 0,
                end_time = 0,
                range = 0,
                is_conflict = 0,
                time_step = parseInt(RevyMain.data.time_step),
                now = RevyMain.parseDateTime(RevyMain.data.now),
                now_minute = now.getHours() * 60 + now.getMinutes(),
                service;

            for (let s_id of s_ids) {
                booking_in_day = _.where(data[s_id].booking, {b_date: date_str});
                day = _.findWhere(data[s_id].days, {date: date_str});
                service = _.findWhere(RevyBooking.booking_detail, {b_service_id: s_id.toString()});
                booking_service_in_day = RevyBooking.delivery_method == 2 ? _.where(booking_in_day, {b_service_id: s_id.toString()}) : []; //Only check for Carry In
                max_cap = parseInt(data[s_id].max_cap);
                cap = max_cap;
                if (typeof day == 'undefined' || typeof service == 'undefined') {
                    return false;
                }

                if (day.work_hour.length == 0) {
                    return false;
                }
                service.b_service_duration = parseInt(service.b_service_duration);
                service.b_service_break_time = parseInt(service.b_service_break_time);

                for (let wh of day.work_hour) {
                    wh.es_work_hour_end = parseInt(wh.es_work_hour_end);
                    wh.es_work_hour_start = parseInt(wh.es_work_hour_start);
                    range = (wh.es_work_hour_end - wh.es_work_hour_start) / time_step;

                    for (var $i = 0; $i < range; $i++) {
                        time = wh.es_work_hour_start + $i * time_step;
                        end_time = time + service.b_service_duration + service.b_service_break_time;
                        is_conflict = 0;

                        if (end_time > wh.es_work_hour_end) {
                            break;
                        }

                        if (typeof booking_service_in_day != 'undefined') {
                            for (let bk of booking_service_in_day) {
                                bk.b_time_end = parseInt(bk.b_time_end);
                                bk.b_time = parseInt(bk.b_time);
                                bk.b_garage_id = parseInt(bk.b_garage_id);
                                bk.total_device = parseInt(bk.total_device);
                                if (bk.b_time <= time && end_time <= bk.b_time_end) {
                                    if (bk.b_garage_id == RevyBooking.garage_id && (max_cap - bk.total_device) > min_cap) {
                                        is_conflict = 0;
                                        cap = max_cap - bk.total_device;
                                    } else {
                                        is_conflict = 1;
                                    }
                                    break;
                                }
                            }
                        }

                        if (!is_conflict && typeof booking_in_day != 'undefined') {
                            for (let bk of booking_in_day) {
                                if (bk.b_time <= time && end_time <= bk.b_time_end && bk.b_garage_id == RevyBooking.garage_id && bk.b_service_id == service.s_id) {
                                    break;
                                } else {
                                    is_conflict = !(end_time <= bk.b_time || time >= bk.b_time_end);
                                }
                                if (is_conflict) {
                                    break;
                                }
                            }
                        }

                        if (RevyMain.equalDay(now, date) && time <= now_minute) {
                            is_conflict = 1;
                        }
                        if (!is_conflict) {
                            group_slot.push(time);
                            time_slot.push({
                                s_id: s_id,
                                slot: time,
                                available: cap
                            });
                        }
                    }
                }
            }

            var group_slot = _.groupBy(group_slot),
                total_service = s_ids.length;

            time_slot = _.filter(time_slot, function (item) {
                return typeof group_slot[item.slot] != 'undefined' && group_slot[item.slot].length == total_service;
            });
            if (time_slot.length > 0) {
                RevyBooking.time_slot.push(
                    {date: date_str, time_slot: time_slot}
                );
                return true;
            } else {
                return false;
            }
        };

        RevyBooking.getESDay = function (date) {
            switch (date.getDay()) {
                case 0: {
                    return 8;
                }
                case 1: {
                    return 2;
                }
                case 2: {
                    return 3;
                }
                case 3: {
                    return 4;
                }
                case 4: {
                    return 5;
                    break;
                }
                case 5: {
                    return 6;
                }
                case 6: {
                    return 7;
                }
            }
            return 0;
        };

        RevyBooking.processSubmitBooking = function (self) {
            var b_id = self.attr('data-id'),
                b_date = $('#b_date', '.fat-sb-booking-form').attr('data-date'),
                b_time = $('#b_time', '.fat-sb-booking-form').val(),
                current_page = $('#pagination').val(),
                is_valid = 1;

            current_page = typeof current_page != 'undefined' ? current_page : 1;

            if (b_date == '') {
                $('.date-field').addClass('field-error');
                is_valid = 0;
            } else {
                $('.date-field').closest('.field').removeClass('field-error');
            }

            if (b_time == '') {
                $('.time-field').addClass('field-error');
                is_valid = 0;
            } else {
                $('.time-field').closest('.field').removeClass('field-error');
            }
            if (!is_valid) {
                return;
            }
            RevyMain.showProcess(self);
            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_booking',
                    b_id: b_id,
                    date: b_date,
                    time: b_time,
                    pay_now: $('#pay_now').is(':checked') ? 1 : 0
                }),
                success: function (response) {
                    response = $.parseJSON(response);
                    RevyMain.closeProcess(self);
                    if (response.result > 0) {
                        self.closest('.ui.modal').modal('hide');
                        RevyMain.showMessage(self.attr('data-success-message'));
                        RevyBooking.loadBooking(current_page);
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
                }
            });

        };

        RevyBooking.processUpdateProcessStatus = function (self) {
            var b_id = self.attr('data-id'),
                dropdown = self.closest('.ui.dropdown'),
                current_status = self.attr('data-value'),
                b_process_status = self.val();
            RevyMain.showProcess(dropdown);

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'update_booking_status',
                    b_id: b_id,
                    b_process_status: b_process_status
                }),
                success: function (response) {
                    response = $.parseJSON(response);
                    RevyMain.closeProcess(dropdown);
                    if (response.result > 0) {

                        //send mail notify
                        $.ajax({
                            url: RevyMain.data.ajax_url,
                            type: 'POST',
                            data: ({
                                action: 'send_booking_mail',
                                b_id: b_id,
                                is_fe: 1
                            })
                        });

                        RevyMain.showMessage(response.message);
                        self.attr('data-value', b_process_status);
                    } else {
                        if (typeof response.message != 'undefined') {
                            RevyMain.showMessage(response.message, 3);
                        } else {
                            RevyMain.showMessage(RevyMain.data.error_message, 2);
                        }
                        self.addClass('onChange-disabled');
                        setTimeout(function () {
                            dropdown.dropdown('refresh').dropdown('set selected', current_status);
                            self.removeClass('onChange-disabled');
                        }, 800);
                    }

                },
                error: function () {
                    RevyMain.closeProcess(dropdown);
                }
            });
        };

        RevyBooking.processDeleteBooking = function (self) {
            var btDelete = self;
            RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title, RevyMain.data.confirm_delete_message, function (result, popup) {
                if (result == 1) {
                    var self = $('.fat-sb-bt-confirm.yes', popup),
                        b_ids = [];
                    RevyMain.showProcess(self);
                    if (btDelete.hasClass('fat-item-bt-inline')) {
                        b_ids.push(btDelete.attr('data-id'));
                    } else {
                        $('input.check-item[type="checkbox"]', 'table.fat-sb-list-booking').each(function () {
                            if ($(this).is(':checked')) {
                                b_ids.push($(this).attr('data-id'));
                            }
                        });
                    }
                    $.ajax({
                        url: RevyMain.data.ajax_url,
                        type: 'POST',
                        data: ({
                            action: 'delete_booking',
                            b_ids: b_ids
                        }),
                        success: function (response) {
                            try {
                                self.closest('.ui.modal').modal('hide');
                                response = $.parseJSON(response);
                                RevyMain.closeProcess(self);
                                $('.table-check-all', '.fat-sb-list-booking').prop("checked", false);
                                if (response.result > 0) {
                                    RevyMain.showMessage(response.message);
                                    for (var $i = 0; $i < b_ids.length; $i++) {
                                        $('tr[data-id="' + b_ids[$i] + '"]', '.fat-sb-list-booking').remove();
                                    }
                                } else {
                                    if (typeof response.message != 'undefined') {
                                        RevyMain.showMessage(response.message, 3);
                                    } else {
                                        RevyMain.showMessage(RevyMain.data.error_message, 2);
                                    }
                                }
                            } catch (err) {
                            }
                        },
                        error: function () {
                            RevyMain.closeProcess(self);
                        }
                    });
                }
            });

        };

        RevyBooking.exportBooking = function (self) {
            var b_customer_name = $('#b_customer_name').val(),
                start_date = $('#date_of_book').attr('data-start'),
                start_time = $('#date_of_book').attr('data-start-time'),
                end_date = $('#date_of_book').attr('data-end'),
                end_time = $('#date_of_book').attr('data-end-time'),
                b_employee = $('#b_employee').val(),
                b_customer = $('#b_customer').val(),
                b_service = $('#b_service').val(),
                b_process_status = $('#b_process_status').val();

            RevyMain.showProcess(self);

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'GET',
                data: ({
                    action: 'get_booking_export',
                    b_customer_name: b_customer_name,
                    start_date: start_date,
                    end_date: end_date,
                    start_time: start_time,
                    end_time: end_time,
                    b_employee: b_employee,
                    b_customer: b_customer,
                    b_service: b_service,
                    b_process_status: b_process_status,
                    location: $('#location').val()
                }),
                success: function (response) {
                    RevyMain.closeProcess(self);
                    response = $.parseJSON(response);

                    var csv = [],
                        row = [],
                        csvFile,
                        downloadLink,
                        bookings = response.booking,
                        booking_detail = response.booking_detail;

                    if (bookings.length > 0) {

                        row = [];
                        row.push(RevyMain.data.appointment_date_column);
                        row.push(RevyMain.data.customer_column);
                        row.push(RevyMain.data.customer_email_column);
                        row.push(RevyMain.data.customer_phone_column);
                        row.push(RevyMain.data.customer_address);
                        row.push(RevyMain.data.customer_city);
                        row.push(RevyMain.data.customer_country);
                        row.push(RevyMain.data.customer_postal_code);
                        row.push(RevyMain.data.model_column);
                        row.push(RevyMain.data.services_column);
                        row.push(RevyMain.data.garage_name_column);
                        row.push(RevyMain.data.garage_address_column);
                        row.push(RevyMain.data.payment_column);
                        row.push(RevyMain.data.status_column);
                        csv.push(row.join(","));

                        var $bk_detail = [],
                            s_name;
                        for (let bk of bookings) {
                            s_name = '';
                            $bk_detail = _.filter(booking_detail, {b_id: bk.b_id});
                            for(let bkd of $bk_detail){
                                s_name += s_name=='' ? '' : '. ';
                                s_name += bkd.s_name + ' ' + bkd.b_attr_title + ' ' + bkd.b_attr_value ;
                            }

                            row = [];
                            row.push(bk.b_date + RevyMain.data.slots[bk.b_time]);
                            row.push(bk.c_first_name + ' ' + bk.c_last_name);
                            row.push(bk.c_email);
                            row.push(bk.c_phone);
                            row.push(bk.b_customer_address);
                            row.push(bk.b_customer_city);
                            row.push(bk.b_customer_country);
                            row.push(bk.b_customer_postal_code);
                            row.push(bk.rm_name);
                            row.push(s_name);
                            row.push(bk.rg_name);
                            row.push(bk.rg_address);
                            row.push(RevyMain.formatPrice(bk.b_total_pay));

                            if (bk.b_process_status == 0) {
                                row.push(RevyMain.data.pending_label);
                            }
                            if (bk.b_process_status == 1) {
                                row.push(RevyMain.data.approved_label);
                            }
                            if (bk.b_process_status == 2) {
                                row.push(RevyMain.data.canceled_label);
                            }
                            if (bk.b_process_status == 3) {
                                row.push(RevyMain.data.rejected_label);
                            }

                            csv.push(row.join(","));
                        }

                        csv = csv.join("\n");
                        csvFile = new Blob([csv], {type: "text/csv"});

                        // Download link
                        downloadLink = document.createElement("a");

                        // File name
                        downloadLink.download = 'fat_booking.csv';

                        // Create a link to the file
                        downloadLink.href = window.URL.createObjectURL(csvFile);

                        // Hide download link
                        downloadLink.style.display = "none";

                        // Add the link to DOM
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                    }


                },
                error: function () {
                    RevyMain.closeProcess(self);
                }
            })

        };

        RevyBooking.getDateFormat = function () {
            var date_format = RevyMain.data.date_format;
            date_format = date_format.replace('M', 'M');
            date_format = date_format.replace('F', 'MM');
            date_format = date_format.replace('m', 'mm');
            date_format = date_format.replace('n', 'mm');

            date_format = date_format.replace('d', 'dd');
            date_format = date_format.replace('jS', 'dd');
            date_format = date_format.replace('j', 'dd');
            date_format = date_format.replace('s', 'dd');

            date_format = date_format.replace('Y', 'yyyy');
            return date_format;
        };

        RevyBooking.processOrder = function (elm) {
            if (!elm.hasClass('active')) {
                var container = elm.closest('.fat-sb-order-wrap');
                RevyBooking.order_by = container.attr('data-order-by');
                RevyBooking.order = elm.attr('data-order');
                RevyBooking.loadBooking(1);
                $('.fat-sb-order-wrap i.icon.active', '.fat-sb-list-booking').removeClass('active');
                $('i.icon.' + RevyBooking.order, container).addClass('active');
            }
        };

        RevyBooking.calculatePrice = function ($quantity, $price, $s_id) {
            return $price;
        };

        RevyBooking.calculateSubtotal = function ($quantity, $price, $s_id) {
            return ($quantity * $price);
        };

        RevyBooking.getPriceLabel = function ($quantity, $price, $s_id) {
            return $price;
        };

        RevyBooking.onExpandDetailClick = function (self) {
            var b_id = self.attr('data-id'),
                elm_tr = self.closest('tr'),
                list_detail = _.filter(RevyBooking.booking_detail, {b_id: b_id}),
                template = wp.template('fat-sb-booking-detail-item-template'),
                item;

            for (let bk of list_detail) {
                bk.b_service_duration_label = RevyMain.data.durations[bk.b_service_duration];
                bk.b_price_label = RevyMain.formatPrice(bk.b_price);
            }
            item = $(template(list_detail));
            $('.dt-item-wrap', 'tr.booking-detail-item[data-id="' + b_id + '"]').slideUp(function () {
                $('tr.booking-detail-item[data-id="' + b_id + '"]').remove();
            })
            if (!self.hasClass('expand')) {
                item.attr('data-id', b_id);
                item.insertAfter(elm_tr);
                self.addClass('expand');
                $('.dt-item-wrap', item).slideDown();
            } else {
                self.removeClass('expand');
            }


        };

        RevyBooking.getDateStr = function (date) {
            var month = date.getMonth() + 1,
                day = date.getDate();
            month = parseInt(month);
            day = parseInt(day);
            month = month < 10 ? ('0' + month) : month;
            day = day < 10 ? ('0' + day) : day;
            return date.getFullYear() + '-' + month + '-' + day;
        };

        $(document).ready(function () {
            if ($('.fat-sb-booking-container').length > 0) {
                RevyBooking.init();
            }
        });
    }
)(jQuery);