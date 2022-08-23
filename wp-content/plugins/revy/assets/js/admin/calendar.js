"use strict";
var RevyCalendar = {
    view: 'month' //listWeek, month, agendaDay, agendaWeek
};
(function ($) {
    RevyCalendar.init = function () {
        RevyCalendar.view = $('#fat_sb_calendar').attr('data-view');
        RevyCalendar.initCalendar([]);
        RevyCalendar.loadBooking();

        RevyMain.bindServicesDicHierarchy($('.fat-sb-calendar-container .fat-sb-service-dic'));
        RevyMain.bindCustomersDic($('.fat-sb-calendar-container .fat-sb-customer-dic'));
        RevyMain.bindGarageDic($('.fat-sb-calendar-container .fat-sb-garage-dic'));
        RevyMain.initField($('.fat-semantic-container'));

        RevyMain.registerEventProcess($('.fat-sb-calendar-container .toolbox-action-group'));

    };

    RevyCalendar.dateOnChange = function (self) {
        var date_picker = self.closest('.ui.date-input');
        $('.ui.loader', date_picker).remove();
        date_picker.addClass('fat-loading');
        date_picker.append('<div class="ui active tiny inline loader"></div>');

        if (self.attr('data-start') == self.attr('data-end')) {
            RevyCalendar.view = 'agendaDay';
            $('#fat_sb_calendar').fullCalendar('changeView',RevyCalendar.view);
            $('#fat_sb_calendar').fullCalendar('gotoDate', moment(self.attr('data-start'), 'YYYY-MM-DD'));
        }else{
            RevyCalendar.view = $('#fat_sb_calendar').attr('data-view');
            $('#fat_sb_calendar').fullCalendar('changeView',RevyCalendar.view);
        }
        RevyCalendar.loadBooking(function () {
            $('.ui.loader', date_picker).remove();
            date_picker.removeClass('fat-loading');
        });
    };

    RevyCalendar.sumoSearchOnChange = function (self) {
        var sumoContainer = self.closest('.SumoSelect'),
            prev_value = self.attr('data-prev-value'),
            value = self.val();

        value = value != null ? value : '';

        if (value != prev_value) {
            $('.ui.loader', sumoContainer).remove();
            sumoContainer.addClass('fat-loading');
            sumoContainer.append('<div class="ui active tiny inline loader"></div>');
            self.attr('data-prev-value', value);
            RevyCalendar.loadBooking(function () {
                $('.ui.loader', sumoContainer).remove();
                sumoContainer.removeClass('fat-loading');
            });
        }
    };

    RevyCalendar.searchStatusChange = function (self) {
        var dropdown = self.closest('.ui.dropdown');
        dropdown.addClass('loading');
        setTimeout(function () {
            RevyCalendar.loadBooking(function () {
                dropdown.removeClass('loading');
            });
        }, 300);
    };

    RevyCalendar.loadBooking = function (callback) {
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_booking_calendar',
                from_date: $('input#date_of_book').attr('data-start'),
                to_date: $('input#date_of_book').attr('data-end'),
                b_process_status: $('#b_process_status').val(),
                customer: $('#customer').val(),
                service: $('#services').val(),
                garage: $('#garage').val()
            }),
            success: function (response) {
                response = $.parseJSON(response);
                $('.ui.inverted.dimmer', '.fat-sb-calendar').fadeOut(function () {
                    $('.ui.inverted.dimmer', '.fat-sb-calendar').remove();
                });
                var elm_calendar = $('#fat_sb_calendar'),
                    bookings = response.bookings;

                for(let bk of bookings){
                    bk.services = _.filter(response.booking_detail, {b_id: bk.id});
                }
                $('#fat_sb_calendar').fullCalendar('gotoDate', moment(response.date));
                elm_calendar.fullCalendar('removeEvents');
                elm_calendar.fullCalendar('addEventSource', bookings);
                elm_calendar.fullCalendar("reinitView");

                if (typeof callback == 'function') {
                    callback();
                }

            },
            error: function (response) {
                RevyMain.showMessage(RevyMain_FE.data.error_message, 2);
            }
        })
    };

    RevyCalendar.initCalendar = function (data) {
        var locale = typeof $('#fat_sb_calendar').attr('data-locale')!='undefined' && $('#fat_sb_calendar').attr('data-locale')!='' ? $('#fat_sb_calendar').attr('data-locale') : 'en';
        $('#fat_sb_calendar').fullCalendar({
            header: {
                left: '',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            locale: locale,
            defaultView: RevyCalendar.view,
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: data.bookings,
            eventRender: function (eventObj, $el) {
                var popup_id = RevyMain.guid(),
                    popup = wp.template('fat-sb-popup-calendar-template'),
                    popup = $(popup(eventObj)),
                    service = '';

                $(popup).attr('data-popup-id', popup_id);
                $el.attr('data-popup-id', popup_id);
                $el.attr('data-popup-inline', false);
                $el.append(popup);
                if(typeof eventObj.services!='undefined'){
                    for(let s of eventObj.services){
                        service += s.s_name + '. ';
                    }
                }
                $('.fc-title', $el).html(eventObj.customer);
                $('.fc-content', $el).append('<div class="time">' + eventObj.time + '</div>');
                $('.fc-content', $el).append('<div>' + eventObj.model_name + '</div>');
                $('.fc-content', $el).append('<div>' + service + '</div>');
            },
            viewRender: function (view, element) {
                RevyCalendar.initPopupEvent();
                RevyMain.registerEventProcess($('#fat_sb_calendar'));
            },
            eventDrop: function(event, delta, revertFunc) {
                var b_id =  event.id,
                    time = '';
                if(typeof event.start._i[3]!='undefined' && typeof event.start._i[4]){
                    time = parseInt(event.start._i[3]) * 60 + parseInt(event.start._i[4]);
                }

                RevyMain.showConfirmPopup(RevyMain.data.confirm_reschedule_title, RevyMain.data.confirm_reschedule_message, function (result, popup) {
                   if(result==1){
                       $.ajax({
                           url: RevyMain.data.ajax_url,
                           type: 'GET',
                           data: ({
                               action: 'booking_reschedule',
                               id: b_id,
                               date: event.start.format('YYYY-MM-DD'),
                               time: time
                           }),
                           success: function (response) {
                               response = $.parseJSON(response);
                               if(response.result>0){
                                   RevyMain.showMessage(response.message, 1);
                                   RevyCalendar.initPopupEvent();
                                   RevyMain.registerEventProcess($('#fat_sb_calendar'));
                                   if(response.b_send_notify==1){
                                       $.ajax({
                                           url: RevyMain.data.ajax_url,
                                           type: 'POST',
                                           data: ({
                                               action: 'send_booking_mail',
                                               b_id: b_id,
                                           })
                                       });
                                   }
                               }else{
                                   RevyMain.showMessage(response.message, 2);
                                   revertFunc();
                               }
                           },
                           error: function (response) {
                               revertFunc();
                           }
                       })
                   }else{
                       revertFunc();
                   }
                });
            }
        });
    };

    RevyCalendar.initPopupEvent = function () {
        $('.fat-sb-calendar-container .fc-event').each(function () {
            var self = $(this),
                popup_id = self.attr('data-popup-id'),
                popup = $('.ui.popup[data-popup-id="' + popup_id + '"]');

            if (popup.length > 0) {
                self.popup({
                    popup: popup,
                    inline: false,
                    hoverable: true,
                })
            }
        });

        $('.fat-sb-calendar-container .fc-list-table tr.fc-list-item td.fc-list-item-title').each(function () {
            $(this).popup({
                popup: $('.fat-sb-calendar-popup', this),
                inline: false,
                hoverable: true,
            })
        });

        $('.fc-more-cell', '.fat-sb-calendar').off('click').on('click', function () {
            setTimeout(function () {
                $('.fat-sb-calendar-container .fc-event').each(function () {
                    var self = $(this),
                        popup_id = self.attr('data-popup-id'),
                        popup = $('.ui.popup[data-popup-id="' + popup_id + '"]');
                    if (popup.length > 0) {
                        self.popup({
                            popup: popup,
                            inline: false,
                            hoverable: true,
                        })
                    }
                });
                $('[data-onClick]', '#fat_sb_calendar').off('click');
                RevyMain.registerEventProcess($('#fat_sb_calendar'));
            }, 800)
        });

    };

    RevyCalendar.addBookingToCalendar = function(booking){
        var elm_calendar = $('.fat-sb-calendar');

        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_booking_calendar_by_id',
                b_id: booking.b_id,
            }),
            success: function (response) {
                response = $.parseJSON(response);

                elm_calendar.fullCalendar( 'removeEvents', response.b_id);
                elm_calendar.fullCalendar('renderEvent', {
                    id: response.b_id,
                    title: response.s_name ,
                    start: response.start,
                    end: response.end,
                    service: response.s_name,
                    employee: (response.e_first_name + ' ' + response.e_last_name),
                    e_avatar_url: response.e_avatar_url,
                    customer: (response.c_first_name + ' ' + response.c_last_name),
                    time: response.time,
                    garage_name: response.rg_name,
                    garage_address: response.garage_address,
                    color: RevyMain_FE.data.booking_color[response.b_process_status],
                    b_editable: response.b_editable
                });
                elm_calendar.fullCalendar("reinitView");
            },
            error: function() {
                
            }
        });
    };

    $(document).ready(function () {
        RevyCalendar.init();
    });
})(jQuery);