"use strict";
/**
 * Number.prototype.format(n, x, s)
 *
 * @param integer n: length of decimal
 * @param integer x: length of sections
 * @param string s:  separator
 */
Number.prototype.format = function (n, x, s) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&' + s);
};

var RevyMain_FE = {
    data: revy_data,
    client_longitude: '',
    client_latitude: ''
};
(function ($) {
    /*
        register event
         */
    RevyMain_FE.registerOnChange = function (container) {
        container = typeof container == 'undefined' ? $('.fat-semantic-container') : container;
        $('[data-onChange]', container).each(function () {
            var self = $(this),
                callback = self.attr('data-onChange').split('.'),
                obj = callback.length == 2 ? callback[0] : '',
                func = callback.length == 2 ? callback[1] : callback[0];

            /*semantic dropdown*/
            if (self.hasClass('ui') && self.hasClass('dropdown')) {
                self.dropdown({
                    onChange: function (value, text, $choice) {
                        if (self.hasClass('onChange-disabled')) {
                            return;
                        }
                        if (obj != '') {
                            (typeof window[obj][func] != 'undefined' && window[obj][func] != null) ? window[obj][func](value, text, $choice, self) : '';
                        } else {
                            (typeof window[func] != 'undefined' && window[func] != null) ? window[func](value, text, $choice, self) : '';
                        }
                    }
                });
                return;
            }

            /*sumo dropdown*/
            if (self.hasClass('SumoUnder')) {
                self.on('sumo:closed', function (sumo) {
                    if (obj != '') {
                        (typeof window[obj][func] != 'undefined' && window[obj][func] != null) ? window[obj][func](self, sumo) : '';
                    } else {
                        (typeof window[func] != 'undefined' && window[func] != null) ? window[func](self, sumo) : '';
                    }
                });
                return;
            }

            /*default field*/
            self.off('change').on('change', function () {
                if (self.hasClass('onChange-disabled')) {
                    return;
                }
                if (obj != '') {
                    (typeof window[obj][func] != 'undefined' && window[obj][func] != null) ? window[obj][func](self) : '';
                } else {
                    (typeof window[func] != 'undefined' && window[func] != null) ? window[func](self) : '';
                }
            });
        });
    };

    RevyMain_FE.registerOnClick = function (container) {
        container = typeof container == 'undefined' ? $('.fat-semantic-container') : container;
        $('[data-onClick]', container).each(function () {
            var self = $(this),
                callback = self.attr('data-onClick').split('.'),
                obj = callback.length == 2 ? callback[0] : '',
                func = callback.length == 2 ? callback[1] : callback[0],
                prevent_event = self.attr('data-prevent-event');

            self.on('click', function (event) {
                if (prevent_event) {
                    event.preventDefault();
                }

                if (obj != '') {
                    (typeof window[obj][func] != 'undefined' && window[obj][func] != null) ? window[obj][func](self, event) : '';
                } else {
                    (typeof window[func] != 'undefined' && window[func] != null) ? window[func](self, event) : '';
                }
                if (prevent_event) {
                    return false;
                }
            });
        });
    };

    RevyMain_FE.registerOnKeyUp = function (container) {
        container = typeof container == 'undefined' ? $('.fat-semantic-container') : container;
        $('[data-onKeyUp]', container).each(function () {
            var self = $(this),
                callback = self.attr('data-onKeyUp').split('.'),
                obj = callback.length == 2 ? callback[0] : '',
                func = callback.length == 2 ? callback[1] : callback[0];

            self.off('keyup').on('keyup', function () {
                if (obj != '') {
                    (typeof window[obj][func] != 'undefined' && window[obj][func] != null) ? window[obj][func](self) : '';
                } else {
                    (typeof window[func] != 'undefined' && window[func] != null) ? window[func](self) : '';
                }
            });
        });
    };

    RevyMain_FE.registerEventProcess = function (container) {
        container = typeof container == 'undefined' ? $('.fat-semantic-container') : container;
        RevyMain_FE.registerOnChange(container);
        RevyMain_FE.registerOnClick(container);
        RevyMain_FE.registerOnKeyUp(container);
    };

    RevyMain_FE.showLoading = function (container, elm) {
        $('.fat-ui-loader-container', container).remove();
        container.append('<div class="fat-ui-loader-container"><div class="fat-ui-loader">' + revy_data.loading_label + '</div></div>');
        if(typeof elm !='undefined'){
            elm.addClass('loading');
        }
    };

    RevyMain_FE.closeLoading = function (container, elm) {
        $('.fat-ui-loader-container', container).remove();
        if(typeof elm !='undefined'){
            elm.removeClass('loading');
        }
    };

    RevyMain_FE.addLoading = function (container, elm) {
        if ($('.fat-loading-container', container).length == 0) {
            container.append('<div class="fat-loading-container"></div>');
        }
        var field = $(elm).closest('.field');
        if (typeof field != 'undefined' && $('label', field).length > 0) {
            $('label', field).append('<div class="ui active mini inline loader"></div>');
        }
        $(elm).addClass('loading');
    };

    RevyMain_FE.removeLoading = function (container, elm) {
        $('.fat-loading-container,.fat-ui-loader-container', container).remove();
        if(typeof elm!='undefined'){
            var field = $(elm).closest('.field');
            if (typeof field != 'undefined' && $('label', field).length > 0) {
                $('label .ui.loader', field).remove();
            }
            $(elm).removeClass('loading');
        }

    };

    RevyMain_FE.validateForm = function (form) {
        var input,
            isValid = true;
        $('input[required]', form).each(function () {
            input = $(this);
            if ((input.val().trim() == '' && !input.hasClass('air-date-picker')) || (input.hasClass('air-date-picker') && typeof input.attr('data-date') == 'undefined')) {
                input.closest('.field').addClass('field-error');
                isValid = false;
            } else {
                input.closest('.field').removeClass('field-error');
            }
        });
        $('input[type="email"]', form).each(function () {
            input = $(this);
            var pattern = new RegExp(/^[^\s@]+@[^\s@]+\.[^\s@]+$/),
                email = input.val().trim();
            if (email == '' || !pattern.test(email)) {
                input.closest('.field').addClass('field-error');
                isValid = false;
            } else {
                input.closest('.field').removeClass('field-error');
            }
        });

        $('.fat-sb-checkbox-group[required]', form).each(function () {
            var checkGroup = $(this);
            if ($('input[type="checkbox"]', checkGroup).is(':checked') == false) {
                checkGroup.closest('.field').addClass('field-error');
                isValid = false;
            } else {
                checkGroup.closest('.field').removeClass('field-error');
            }
        });

        $('.fat-sb-radio-group[required]', form).each(function () {
            var radioGroup = $(this);
            if ($('input[type="radio"]', radioGroup).is(':checked') == false) {
                radioGroup.closest('.field').addClass('field-error');
                isValid = false;
            } else {
                radioGroup.closest('.field').removeClass('field-error');
            }
        });


        return isValid;
    };

    RevyMain_FE.initFormBuilder = function () {
        $('.fat-sb-field-builder.fat-sb-date-field').each(function () {
            var self = $(this),
                lang = self.attr('data-locale');
            $(this).datepicker({
                language: lang,
                onSelect: function (formattedDate, date, inst) {
                    if (typeof date == 'undefined' || date == '') {
                        return;
                    }

                    var month = date.getMonth() + 1,
                        day = date.getDate(),
                        selected_date_value = '';
                    month = parseInt(month);
                    day = parseInt(day);
                    month = month < 10 ? ('0' + month) : month;
                    day = day < 10 ? ('0' + day) : day;
                    var selected_date_value = date.getFullYear() + '-' + month + '-' + day;
                    $(inst.el).attr('data-date', selected_date_value)
                }
            })
        });

        $('.ui-tooltip','.fat-booking-container').popup({
            inline: true,
            hoverable: true,
            position: 'top left',
            delay: {
                show: 300,
                hide: 500
            }
        });
    };

    RevyMain_FE.showMessage = function (message, type) {
        var css_class = typeof type == 'undefined' || type == '1' ? 'blue' : 'red',  //1:success message, 2: error message
            icon = typeof type == 'undefined' || type == '1' ? 'check icon' : 'close icon';

        css_class = type == '3' ? 'orange' : css_class;

        var elm_message = '<div class="fat-sb-message ' + css_class + '">';
        elm_message += typeof icon != 'undefined' && icon != '' ? '<i class="' + icon + '"></i>' : '';
        elm_message += '<span>' + message + '</span>';
        elm_message = $(elm_message);
        var top = ($('body .fat-sb-message').length * 60 + 50) + 'px';
        $(elm_message).css('top', top);
        $('body').append(elm_message);

        $('.fat-sb-message i.close').on('click',function(){
            $(elm_message).removeClass('show-up');
            setTimeout(function () {
                $(elm_message).remove();
            }, 300);
        });

        setTimeout(function () {
            $(elm_message).addClass('show-up');
            setTimeout(function () {
                $(elm_message).removeClass('show-up');
                setTimeout(function () {
                    $(elm_message).remove();
                }, 300);
            }, 5000);
        }, 200);

    };

    RevyMain_FE.showNotFoundMessage = function (elm, wrap_start, wrap_end) {
        var content = '';
        if (typeof wrap_start != 'undefined' && wrap_start != '') {
            content = wrap_start;
        }
        content += '<div class="fat-sb-not-found">' + RevyMain_FE.data.not_found_message + '</div>';
        if (typeof wrap_end != 'undefined' && wrap_end != '') {
            content += wrap_end;
        }
        $('.fat-sb-not-found', elm).remove();
        elm.append(content);
    };

    RevyMain_FE.equalDay = function ($date1, $date2) {
        return ($date1.getDate() == $date2.getDate()) && ($date1.getMonth() == $date2.getMonth()) && ($date1.getFullYear() == $date2.getFullYear());
    };

    //fix for Safari Date Time
    RevyMain_FE.parseDateTime = function ($now) {
        $now = $now.trim().split(' ');
        if($now.length ==2){
            var $date = $now[0].split('-'),
                $time = $now[1].split(':');
            if($date.length==3 && $time.length==3){
                var month = parseInt($date[1])-1;
                return new Date($date[0], month, $date[2], $time[0], $time[1], $time[2]);
            }
        }
        return new Date($now);
    };

    RevyMain_FE.calculatePrice = function ($quantity, $price, $s_id) {
        return ($quantity * $price);
    };

    RevyMain_FE.getPriceLabel = function($quantity, $price, $price_base_quantity, $s_id){
        var price_label = '<span>' + $quantity + ' ' + RevyMain_FE.data.person_label + ' x ' + RevyMain_FE.data.symbol_prefix + $price.format(RevyMain_FE.data.number_of_decimals, 3, ',')  + RevyMain_FE.data.symbol_suffix;
        price_label += ' = </span>' + RevyMain_FE.data.symbol_prefix + $price_base_quantity.format(RevyMain_FE.data.number_of_decimals, 3, ',') + RevyMain_FE.data.symbol_suffix;
        return price_label;
    };

    RevyMain_FE.initPopupToolTip = function(){
        //init popup
        $('.fat-has-popup').each(function () {
            var self = $(this),
                trigger = self.hasClass('popup-click') ? 'click' : 'hover',
                popup_id = self.attr('data-popup-id'),
                popup = $('.ui.popup[data-popup-id="' + popup_id + '"]'),
                inline = typeof self.attr('data-popup-inline') != 'undefined' && self.attr('data-popup-inline') != '' ? self.attr('data-popup-inline') : true,
                lastResort = typeof self.attr('data-last-resort') !='undefined' ? self.attr('data-last-resort') : '',
                option = {
                    popup: popup,
                    on: trigger,
                    inline: inline,
                    hoverable: true
                };
            if(lastResort!=''){
                option.lastResort = lastResort;
            }
            if (popup.length > 0) {
                self.popup(option)
            }
        });

        //tooltip
        $('.ui-tooltip,.ui-popup').popup({
            inline: true,
            hoverable: true,
            position: 'top left',
            delay: {
                show: 300,
                hide: 500
            }
        });
    };

    RevyMain_FE.show_deactive_slot = function($es_day, e_schedules){
        if(RevyMain_FE.data.enable_time_slot_deactive=='1' && typeof RevyMain_FE.data.working_hour!='undefined'
            && typeof RevyMain_FE.data.working_hour.schedules!='undefined' ){
            var start_hour = 0,
                end_hour = 0;
            for(let $wh of e_schedules){
                if($wh.es_day==$es_day && $wh.es_enable=='1' && typeof $wh.work_hours!='undefined'){
                    start_hour = parseInt($wh.work_hours[0].es_work_hour_start);
                    end_hour = parseInt($wh.work_hours[0].es_work_hour_end);
                    break;
                }
            }
            var slot_value = 0;
            $('.fat-sb-booking-time-wrap .item').each(function(){
                $(this).removeClass('show-deactive');
                $('.time-label', this).css('background-color', 'inherit');
            });

            $('.fat-sb-booking-time-wrap .item.disabled:not(.over-day)').each(function(){
                slot_value = parseInt($(this).attr('data-value'));
                if(slot_value >= start_hour && slot_value <= end_hour){
                    $(this).addClass('show-deactive');
                    $('.time-label', this).css('background-color', RevyMain_FE.data.bg_time_slot_not_active);
                }else{
                    $(this).removeClass('show-deactive');
                    $('.time-label', this).css('background-color', 'transparent');
                }
            });
        }
    };

    RevyMain_FE.initNumberField = function(container){
        // number
        $('.ui.input.number > input', container).off('keypress').on('keypress', function (event) {
            var self = $(this),
                type = self.attr('data-type'),
                min = self.attr('data-min'),
                max = self.attr('data-max'),
                validkeys = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
            if (self.hasClass('disabled')) {
                return false;
            }

            type = typeof type == 'undefined' ? 'int' : type;
            if (type == 'decimal') {
                validkeys.push('.');
            }
            if (validkeys.indexOf(event.key) < 0) {
                return false;
            }
        });

        $('.ui.input.number > input', container).on('change', function (event) {
            var self = $(this),
                min = self.attr('data-min'),
                max = self.attr('data-max'),
                value = self.val();
            if (typeof min != 'undefined' && !isNaN(min) && value != '' && !isNaN(value)) {
                if (parseFloat(value) < parseFloat(min)) {
                    $(this).val(min);
                    event.preventDefault();
                }
            }
            if (typeof max != 'undefined' && !isNaN(max) && value != '' && !isNaN(value)) {
                if (parseFloat(value) > parseFloat(max)) {
                    $(this).val(max);
                    event.preventDefault();
                }
            }
        });

        $('.ui.input.number > input[data-min]', container).each(function () {
            if ($(this).val() == '') {
                $(this).val($(this).attr('data-min'));
            }
        });

        $('.button', '.input.number.has-button', container).off('click').on('click', function () {
            var self = $(this),
                container = self.closest('.input.number.has-button'),
                input = $('input', container),
                value = input.val(),
                step = input.attr('data-step'),
                type = input.attr('data-type'),
                min = input.attr('data-min'),
                max = input.attr('data-max');

            type = typeof type == 'undefined' ? 'int' : type;

            if (type == 'decimal') {
                step = typeof step == 'undefined' || isNaN(step) ? 1 : parseFloat(step);
                min = !isNaN(min) ? parseFloat(min) : '';
                max = !isNaN(max) ? parseFloat(max) : '';
                value = value == '' ? 0 : parseFloat(value);
            } else {
                step = typeof step == 'undefined' || isNaN(step) ? 1 : parseInt(step);
                min = !isNaN(min) ? parseInt(min) : '';
                max = !isNaN(max) ? parseInt(max) : '';
                value = value == '' ? 0 : parseInt(value);
            }

            if (self.hasClass('number-decrease')) {
                if (min !== '' && ((value - step) < min)) {
                    RevyMain_FE.showMessage(revy_data.min_value_message + min,2);
                } else {
                    value >= step ? input.val(value - step) : input.val(0);
                }
            } else {
                if (max !== '' && ((value + step) > max)) {
                    RevyMain_FE.showMessage(revy_data.max_value_message + max,2);
                } else {
                    input.val(value + step);
                }
            }
        });
    };

    RevyMain_FE.formatPrice = function(price){
        if(isNaN(price)){
            return price;
        }
        price = parseFloat(price);
        return RevyMain_FE.data.symbol_prefix + price.format(RevyMain_FE.data.number_of_decimals, 3, ',') + RevyMain_FE.data.symbol_suffix;
    }

    RevyMain_FE.initPaging = function (total, page, elm, callback) {

        var item_per_page = RevyMain_FE.data.item_per_page,
            page_display = revy_data.item_per_page,
            obj = elm.attr('data-obj'),
            func = elm.attr('data-func'),
            paging = '<div class="ui right floated pagination menu" >';

        page = parseInt(page);

        $('.ui.pagination', elm).remove();
        if (total > item_per_page) {
            var number_of_page = Math.floor(total / item_per_page) + (total % item_per_page > 0 ? 1 : 0),
                $start_index = 1,
                $end_index = 0;

            $start_index = page - 2 > 0 ? (page - 2) : 1;
            $end_index = page + 2 < number_of_page ? (page + 2) : number_of_page;

            if (page == 1) {
                paging += ' <button class="ui button nav-first nav-disabled"> <i class="angle double left icon"></i></button>';
                paging += ' <button class="ui button fat-bt-prev nav-disabled"> <i class="angle left icon"></i></button>';
            } else {
                paging += ' <button class="ui button nav-first" data-page="1"> <i class="angle double left icon"></i></button>';
                paging += ' <button class="ui button fat-bt-prev" data-page="' + (page - 1) + '"> <i class="angle left icon"></i></button>';
            }

            if ($start_index >= (page_display - 1)) {
                paging += '<button class="ui button nav-disabled">...</button>';
            }

            for (var $page_index = $start_index; $page_index <= $end_index; $page_index++) {
                paging += '<button class="ui button" data-page="' + $page_index + '">' + $page_index + '</button>';
            }
            if ($end_index < number_of_page) {
                paging += '<button class="ui button nav-disabled">...</button>';
            }

            if (page == number_of_page) {
                paging += ' <button class="ui button fat-bt-next nav-disabled"> <i class="angle right icon"></i></button>';
                paging += ' <button class="ui button nav-last nav-disabled"> <i class="angle double right icon"></i></button>';
            } else {
                paging += ' <button class="ui button fat-bt-next" data-page="' + (page + 1) + '"> <i class="angle right icon"></i></button>';
                paging += ' <button class="ui button nav-last" data-page="' + number_of_page + '"> <i class="angle double right icon"></i></button>';
            }

            $(elm).append(paging);
            $('.ui.pagination button.ui.button[data-page="' + page + '"]', elm).addClass('active');

            if (typeof window[obj][func] != 'undefined' && window[obj][func] != null) {
                $('.ui.pagination button.ui.button:not(.nav-disabled)', '.fat-sb-pagination').off('click').on('click', function () {
                    var self = $(this),
                        page = self.attr('data-page');
                    if (!self.hasClass('active')) {
                        self.addClass('loading');
                        window[obj][func](page);
                    }
                });
            }

            if (typeof callback == 'function') {
                callback();
            }
        }
    };

    RevyMain_FE.getDateFormat = function(){
        var date_format = RevyMain_FE.data.date_format;
        date_format = date_format.replace('M', 'MMM');
        date_format = date_format.replace('F', 'MMMM');
        date_format = date_format.replace('m', 'MM');
        date_format = date_format.replace('n', 'M');

        date_format = date_format.replace('jS', 'DD');
        date_format = date_format.replace('j', 'D');
        date_format = date_format.replace('d', 'DD');
        date_format = date_format.replace('s', 'Mo');

        date_format = date_format.replace('Y', 'YYYY');

        return date_format;
    };

    RevyMain_FE.getTimeFormat = function(){
        var time_format = RevyMain_FE.data.time_format;
        time_format = time_format.replace('H', 'HH');
        time_format = time_format.replace('h', 'hh');
        time_format = time_format.replace('g', 'hh');
        time_format = time_format.replace('G', 'HH');
        time_format = time_format.replace('i', 'mm');

        return time_format;
    };

    RevyMain_FE.i18n_daysOfWeek = function (locale){
        if(locale=='cs'){
            return ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'];
        }
        if(locale=='da'){
            return ['Søn', 'Man', 'Tir', 'Ons', 'Tor', 'Fre', 'Lør'];
        }
        if(locale=='de'){
            return ['Son', 'Mon', 'Die', 'Mit', 'Don', 'Fre', 'Sam'];
        }
        if(locale=='en'){
            return ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        }
        if(locale=='es'){
            return ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
        }
        if(locale=='fi'){
            return ['Su', 'Ma', 'Ti', 'Ke', 'To', 'Pe', 'La'];
        }
        if(locale=='fr'){
            return ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
        }
        if(locale=='hu'){
            return ['Va', 'Hé', 'Ke', 'Sze', 'Cs', 'Pé', 'Szo'];
        }
        if(locale=='it'){
            return ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
        }
        if(locale=='ja'){
            return ['太陽', '月曜', '火', '水曜日', '木曜日', '金曜日', '土曜日'];
        }
        if(locale=='nl'){
            return ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'];
        }
        if(locale=='pl'){
            return ['Nie', 'Pon', 'Wto', 'Śro', 'Czw', 'Pią', 'Sob'];
        }
        if(locale=='pt'){
            return ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
        }
        if(locale=='pt-BR'){
            return ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
        }
        if(locale=='ro'){
            return ['Dum', 'Lun', 'Mar', 'Mie', 'Joi', 'Vin', 'Sâm'];
        }
        if(locale=='sk'){
            return ['Ned', 'Pon', 'Uto', 'Str', 'Štv', 'Pia', 'Sob'];
        }
        if(locale=='zh'){
            return ['日', '一', '二', '三', '四', '五', '六'];
        }

        return  RevyMain_FE.data.day_of_week;
    };

    RevyMain_FE.i18n_monthName = function (locale){
        if(locale=='cs'){
            return ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];
        }
        if(locale=='da'){
            return ['Januar','Februar','Marts','April','Maj','Juni', 'Juli','August','September','Oktober','November','December'];
        }
        if(locale=='de'){
            return ['Januar','Februar','März','April','Mai','Juni', 'Juli','August','September','Oktober','November','Dezember'];
        }
        if(locale=='en'){
            return ['January','February','March','April','May','June', 'July','August','September','October','November','December'];
        }
        if(locale=='es'){
            return ['Enero','Febrero','Marzo','Abril','Mayo','Junio', 'Julio','Augosto','Septiembre','Octubre','Noviembre','Diciembre'];
        }
        if(locale=='fi'){
            return ['Tammikuu','Helmikuu','Maaliskuu','Huhtikuu','Toukokuu','Kesäkuu', 'Heinäkuu','Elokuu','Syyskuu','Lokakuu','Marraskuu','Joulukuu'];
        }
        if(locale=='fr'){
            return ['Janvier','Février','Mars','Avril','Mai','Juin', 'Juillet','Août','Septembre','Octobre','Novembre','Decembre'];
        }
        if(locale=='hu'){
            return ['Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December'];
        }
        if(locale=='it'){
            return ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno', 'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
        }
        if(locale=='ja'){
            return ['一月','2月','行進','4月','5月','六月', '7月','8月','九月','10月','11月','12月'];
        }
        if(locale=='nl'){
            return ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
        }
        if(locale=='pl'){
            return ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec', 'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'];
        }
        if(locale=='pt'){
            return ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        }
        if(locale=='pt-BR'){
            return ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        }
        if(locale=='ro'){
            return ['Ianuarie','Februarie','Martie','Aprilie','Mai','Iunie','Iulie','August','Septembrie','Octombrie','Noiembrie','Decembrie'];
        }
        if(locale=='sk'){
            return ['Január','Február','Marec','Apríl','Máj','Jún', 'Júl','August','September','Október','November','December'];
        }
        if(locale=='zh'){
            return ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
        }

        return  RevyMain_FE.data.month_name;
    };

    RevyMain_FE.registerPhoneCodeFocus = function (){
        $('.phone-code-wrap input').focus(function (){
            $('.phone-code',$(this).closest('.phone-code-wrap')).addClass('focus');
        });

        $('.phone-code-wrap input').focusout(function (){
            $('.phone-code',$(this).closest('.phone-code-wrap')).removeClass('focus');
        });

        $('.phone-code-wrap .phone-code input').focus(function (){
            $('input',$(this).closest('.phone-code-wrap')).addClass('focus');
        });
        $('.phone-code-wrap .phone-code input').focusout(function (){
            $('input',$(this).closest('.phone-code-wrap')).removeClass('focus');
        });
    };

    RevyMain_FE.getClientLocation = function(callback){
        if ( navigator.geolocation )
        {
            navigator.geolocation.getCurrentPosition( function(position) {
                RevyMain_FE.client_longitude = position.coords.longitude;
                RevyMain_FE.client_latitude = position.coords.latitude;
                if(callback){
                    callback();
                }
            });
        }
    };

})(jQuery);