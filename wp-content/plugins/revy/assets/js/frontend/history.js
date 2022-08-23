"use strict";
var RevyBookingHistory = {};
(function ($) {

    RevyBookingHistory.init = function(){
        RevyMain_FE.registerOnClick($('.fat-sb-booking-history'));

        $('.fat-sb-booking-history .ui.dropdown').dropdown({
            clearable: false
        });

        if ($.isFunction($.fn.daterangepicker)) {
            var date_format = RevyMain_FE.getDateFormat();
            $('input#date_of_book').attr('autocomplete', 'off');
            $('input#date_of_book').each(function () {
                var self = $(this),
                    locale = typeof self.attr('data-locale') !='undefined' && self.attr('data-locale')!='' ? self.attr('data-locale') : '',
                    start_date = self.attr('data-start-init'),
                    end_date = self.attr('data-end-init'),
                    time_picker = self.attr('date-time-picker') =='1',
                    autoUpdate = typeof self.attr('data-auto-update') != 'undefined' && self.attr('data-auto-update') == '1',
                    options = {
                        autoUpdateInput: autoUpdate,
                        autoApply: true,
                        timePicker: false,
                        locale: {
                            format: date_format,
                            applyLabel: RevyMain_FE.data.apply_title,
                            cancelLabel: RevyMain_FE.data.cancel_title,
                            fromLabel: RevyMain_FE.data.from_title,
                            toLabel: RevyMain_FE.data.to_title,
                            daysOfWeek: RevyMain_FE.i18n_daysOfWeek(locale),
                            monthNames: RevyMain_FE.i18n_monthName(locale)
                        }
                    };

                if(locale!=''){
                    moment.locale(locale);
                }
                if (typeof start_date != 'undefined' && start_date != '') {
                    options.startDate = start_date;
                }
                if (typeof end_date != 'undefined' && end_date != '') {
                    options.endDate = end_date;
                }
                self.daterangepicker(options, function (start, end, label) {
                    self.val(label);
                    self.attr('data-start', start.format('YYYY-MM-DD'));
                    self.attr('data-end', end.format('YYYY-MM-DD'));
                    if(time_picker){
                        self.attr('data-start-time', start.format('HH:mm'));
                        self.attr('data-end-time', end.format('HH:mm'));
                    }
                });
            });

        }
    };

    RevyBookingHistory.viewHistory = function(self){
        var container = $('.fat-sb-booking-history'),
            code_field = $('input', container),
            code = code_field.val(),
            error_message = code_field.attr('data-error');
        if(code=='' ){
            RevyMain_FE.showMessage(error_message,2);
        }else{
            RevyMain_FE.addLoading(container, self);
            RevyBookingHistory.loadHistory(1,function(){
                RevyMain_FE.removeLoading(container, self);
            });
        }

    };

    RevyBookingHistory.loadHistory = function(page, callback){
        var container = $('.fat-sb-booking-history'),
            code_field = $('input', container),
            code = code_field.val(),
            b_process_status = $('#b_process_status').val(),
            start_date = $('#date_of_book').attr('data-start'),
            end_date = $('#date_of_book').attr('data-end');

        try {
            $.ajax({
                url: RevyMain_FE.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'get_booking_history',
                    s_field: RevyMain_FE.data.ajax_s_field,
                    c_code: code,
                    start_date: start_date,
                    end_date: end_date,
                    status: b_process_status,
                    page: page
                }),
                success: function (response) {

                    response = $.parseJSON(response);

                    if(response.result> 0){
                        $('.fat-sb-booking-history table tbody').empty();

                        var total = response.total,
                            bookings = response.bookings,
                            bookings_detail = response.bookings_detail,
                            bk_detail = [],
                            template = wp.template('fat-sb-history-item-template'),
                            items = '';

                        for (var $b_index = 0; $b_index < bookings.length; $b_index++) {
                            bk_detail = _.filter(bookings_detail,  {b_id:bookings[$b_index].b_id});
                            bookings[$b_index].s_name = bookings[$b_index].b_attr = '';
                            for(let bk of bk_detail){
                                bookings[$b_index].s_name += bookings[$b_index].s_name =='' ? bk.s_name  : (' ; ' +  bk.s_name);
                                bookings[$b_index].b_attr += bookings[$b_index].b_attr =='' ? (bk.b_attr_title +  ' ' + bk.b_attr_value) : (' ; ' + bk.b_attr_title +  ' ' + bk.b_attr_value);
                            }
                            bookings[$b_index].b_total_pay = RevyMain_FE.data.symbol_position == 'before' ? (RevyMain_FE.data.symbol + bookings[$b_index].b_total_pay) : (bookings[$b_index].b_total_pay + RevyMain_FE.data.symbol);
                            if(bookings[$b_index].b_process_status == 0){
                                bookings[$b_index].b_status_display = RevyMain_FE.data.pending_label;
                            }
                            if(bookings[$b_index].b_process_status == 1){
                                bookings[$b_index].b_status_display = RevyMain_FE.data.approved_label;
                            }
                            if(bookings[$b_index].b_process_status == 2){
                                bookings[$b_index].b_status_display = RevyMain_FE.data.canceled_label;
                            }
                            if(bookings[$b_index].b_process_status == 3){
                                bookings[$b_index].b_status_display = RevyMain_FE.data.rejected_label;
                            }
                        }
                        items = $(template(bookings));

                        if (bookings.length > 0) {
                            $('.fat-sb-booking-history table tbody').append(items);
                            RevyMain_FE.registerOnClick( $('.fat-sb-booking-history table tbody'));
                        } else {
                            RevyMain_FE.showNotFoundMessage($('tbody'), '<tr><td colspan="9">', '</td></tr>');
                        }
                        RevyMain_FE.initPaging(total, page, $('.fat-sb-pagination', container));
                    }else{
                        RevyMain_FE.showMessage(response.message,2);
                    }
                    if(callback){
                        callback();
                    }
                },
                error: function (response) {
                    if(callback){
                        callback();
                    }
                }
            });
        } catch (err) {
            if(callback){
                callback();
            }
        }
    };

    RevyBookingHistory.submitCancel = function(self){
        var container = self.closest('.fat-sb-popup-modal'),
            history_container = $('.fat-sb-booking-history'),
            id = self.attr('data-id'),
            code = $('#c_code').val(),
            error_message = $('#c_code').attr('data-error');
        if(code=='' && !container.hasClass('has-login')){
            RevyMain_FE.showMessage(error_message,2);
        }else{
            RevyMain_FE.addLoading(container, self);
            try {
                $.ajax({
                    url: RevyMain_FE.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'cancel_booking',
                        s_field: RevyMain_FE.data.ajax_s_field,
                        c_code: code,
                        id: id
                    }),
                    success: function (response) {
                        RevyMain_FE.removeLoading(container, self);
                        response = $.parseJSON(response);
                        if(response.result> 0){
                            RevyMain_FE.showMessage(response.message);
                            $('tr[data-id="' +id +'"]','.fat-sb-booking-history').remove();

                            //send mail notify
                            $.ajax({
                                url: RevyMain_FE.data.ajax_url,
                                type: 'POST',
                                data: ({
                                    action: 'cancel_send_mail',
                                    s_field: RevyMain_FE.data.ajax_s_field,
                                    b_id: id,
                                })
                            });

                            RevyBookingHistory.closePopupModal();

                        }else{
                            RevyMain_FE.showMessage(response.message,2);
                        }

                    },
                    error: function (response) {
                        RevyMain_FE.removeLoading(container, self);
                    }
                });
            } catch (err) {
            }
        }
    };

    RevyBookingHistory.openPopupCancel = function(self){
        var row = self.closest('tr'),
            container = self.closest('.fat-sb-booking-history'),
            id = row.attr('data-id'),
            edit = row.attr('data-edit');

        if(edit==0){
            RevyMain_FE.showMessage(RevyMain_FE.data.not_edit_message,2);
        }else{
            var template = wp.template('fat-sb-popup-cancel-template');
            $('body').append(template);
            $('body .fat-sb-popup-modal .fat-sb-popup-modal-content').fadeIn();
            $('button.fat-bt-submit','body .fat-sb-popup-modal .fat-sb-popup-modal-content').attr('data-id',id);
            RevyMain_FE.registerOnClick($('body .fat-sb-popup-modal'));
        }
    };

    RevyBookingHistory.openPopupGetCustomerCode = function(self){
        var template = wp.template('fat-sb-get-customer-code-template');
        $('body').append(template);
        $('body .fat-sb-popup-modal .fat-sb-popup-modal-content').fadeIn();
        RevyMain_FE.registerOnClick($('body .fat-sb-popup-modal'));
    };

    RevyBookingHistory.getCustomerCode = function(self){
        var container = self.closest('.fat-sb-popup-modal'),
            email = $('input#c_email',container).val().trim(),
            pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/),
            error_message = $('input#c_email',container).attr('data-error');
        if(email=='' || !pattern.test(email)){
            RevyMain_FE.showMessage(error_message,2);

        }else{
            RevyMain_FE.addLoading(container, self);
            try {
                $.ajax({
                    url: RevyMain_FE.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'get_customer_code',
                        s_field: RevyMain_FE.data.ajax_s_field,
                        c_email: email
                    }),
                    success: function (response) {
                        RevyMain_FE.removeLoading(container, self);
                        response = $.parseJSON(response);
                        if(response.result> 0){
                            RevyMain_FE.showMessage(response.message);
                        }else{
                            RevyMain_FE.showMessage(response.message,2);
                        }
                        RevyBookingHistory.closePopupModal();
                    },
                    error: function (response) {
                        RevyMain_FE.removeLoading(container, self);
                    }
                });
            } catch (err) {
            }
        }
    };

    RevyBookingHistory.closePopupModal = function(self){
        $('body .fat-sb-popup-modal .fat-sb-popup-modal-content').fadeOut(function(){
            $('body .fat-sb-popup-modal').remove();
        });
    };

    $(document).ready(function () {
        RevyBookingHistory.init();
    })
})(jQuery);