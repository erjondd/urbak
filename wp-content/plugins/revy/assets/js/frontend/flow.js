"use strict";
var RevyBookingFlow = {
    is_attribute_change: 0,
    weekly_timeslot: [],
    b_date: '',
    b_time: 0,
    device_name: '',
    brand_name: '',
    model_name: '',
    device_id: 0,
    brand_id: 0,
    model_id: 0,
    garage_id: 0,
    garage_title: '',
    garage_address: '',
    garage_desc: '',
    services_selected: [],
    garage_near_me: [],
    b_total_pay: 0,
    delivery_method: 3 //default mail in ; 1: FixItHome, 2:Carry In, 3: Mail in
};

(function ($) {
    RevyBookingFlow.init = function () {
        RevyBookingFlow.initField();
        RevyBookingFlow.initStripeCardInput();
        RevyMain_FE.registerOnClick($('.fat-booking-container.fat-sb-flow-layout'));
        RevyMain_FE.registerOnChange($('.fat-booking-container.fat-sb-flow-layout'));
        if(!$('.fat-sb-flow-layout').hasClass('hide-map')){
            RevyMain_FE.getClientLocation();
        }
    };

    RevyBookingFlow.initField = function () {
        $('.fat-booking-container.fat-sb-flow-layout').each(function () {
            var container = $(this);

            //dropdown
            $('.ui.dropdown', container).each(function () {
                var self = $(this);
                self.dropdown({
                    clearable: self.hasClass('clearable')
                });
            });
            container.addClass('has-init');

            //tooltip
            $('.apoint-tooltip').popup({
                position: 'bottom center'
            });

            RevyMain_FE.initNumberField(container);
        });
    };

    RevyBookingFlow.initCalendar = function () {
        var container = $('.fat-booking-container'),
            startOfWeek = moment().startOf('week'),
            endOfWeek = moment().endOf('week'),
            filter = startOfWeek.format('MMM DD') + ' - ' + endOfWeek.format('MMM DD, YYYY');

        $('.fat-sb-calendar-layout', container).css('opacity', 1);
        RevyBookingFlow.changeWeek(0, startOfWeek.format('YYYY-MM-DD'), container);

        $('.fat-sb-calendar-wrap span.next-week', container).on('click', function () {
            var week = $(this).closest('.calendar-filter').attr('data-week');
            RevyBookingFlow.changeWeek(1, week, container);
        });

        $('.fat-sb-calendar-wrap span.prev-week', container).on('click', function () {
            var week = $(this).closest('.calendar-filter').attr('data-week');
            RevyBookingFlow.changeWeek(-1, week, container);
        });

        $('.fat-sb-calendar-layout', container).addClass('has-init');

        if($('.fat-sb-calendar-wrap .week-date:not(.disabled)', container).length==0){
            $('.fat-sb-calendar-wrap span.next-week', container).trigger('click');
        }

    };

    RevyBookingFlow.initStripeCardInput = function () {
        if ($('form#stripe-payment-form').length == 0) {
            return;
        }
        $('form#stripe-payment-form').each(function () {
            var stripe_form = $(this),
                booking_container = stripe_form.closest('.fat-booking-container'),
                pk = stripe_form.attr('data-pk'),
                card_element_id = $('.card-element', booking_container).attr('id'),
                card_errors_id = $('.card-errors', booking_container).attr('id');
            if (typeof pk == 'undefined' || pk == '') {
                return;
            }

            RevyBookingFlow.stripe = Stripe(pk);
            var elements = RevyBookingFlow.stripe.elements(),
                style = {
                    base: {
                        iconColor: '#666ee8',
                        color: '#31325f',
                        fontWeight: 400,
                        fontFamily:
                            '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                        ':-webkit-autofill': {
                            color: '#666ee8',
                        },
                    },
                };

            // Create an instance of the card Element.
            RevyBookingFlow.card = elements.create('card',  {style, hidePostalCode: true});

            // Add an instance of the card Element into the `card-element` <div>.
            RevyBookingFlow.card.mount('#' + card_element_id);

            // Handle real-time validation errors from the card Element.
            RevyBookingFlow.card.addEventListener('change', function (event) {
                var displayError = document.getElementById(card_errors_id);
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

        });
    };

    RevyBookingFlow.changeWeek = function (direct, start_week, container) {
        //direct: 1 -> next, -1: previous
        var diff = 0,
            current_date = moment(),
            date_class = '',
            inWorkingHour = true;

        $('.week-date span.selected', container).removeClass('selected');
        $('.ui.dropdown.time-options item', container).remove();
        $('.ui.dropdown.time-options', container).dropdown('clear');
        $('.ui.dropdown.time-options', container).dropdown('restore defaults');

        current_date = moment(current_date.format('YYYY-MM-DD'));


        if (direct == -1) {
            diff = -7;
        }
        if (direct == 1) {
            diff = 7;
        }
        var startOfWeek = moment(start_week).add(diff, 'd').startOf('week'),
            endOfWeek = moment(start_week).add(diff, 'd').endOf('week'),
            filter = startOfWeek.format('MMM DD') + ' - ' + endOfWeek.format('MMM DD, YYYY');

        $('.fat-sb-calendar-wrap .calendar-filter').attr('data-week', startOfWeek.format('YYYY-MM-DD'));
        $('.fat-sb-calendar-wrap span.current-week').html(filter);

        inWorkingHour = RevyBookingFlow.inWorkingHour(startOfWeek);
        date_class = startOfWeek.isBefore(current_date) || !inWorkingHour ? 'disabled' : '';
        $('.week-day-header.sun .week-date').removeClass('disabled').addClass(date_class);
        $('.week-day-header.sun .week-date span').html(startOfWeek.format('DD')).attr('data-date', startOfWeek.format('YYYY-MM-DD'));
        $('.week-day-header.sun .week-header-mobile').html(revy_data.mon + ', ' + startOfWeek.format('DD') + '-' + startOfWeek.format('MMM'));

        startOfWeek = moment(startOfWeek).add(1, 'd');
        inWorkingHour = RevyBookingFlow.inWorkingHour(startOfWeek);
        date_class = startOfWeek.isBefore(current_date) || !inWorkingHour ? 'disabled' : '';
        $('.week-day-header.mon .week-date').removeClass('disabled').addClass(date_class);
        $('.week-day-header.mon .week-date span').html(startOfWeek.format('DD')).attr('data-date', startOfWeek.format('YYYY-MM-DD'));
        $('.week-day-header.mon .week-header-mobile').html(revy_data.tue + ', ' + startOfWeek.format('DD') + '-' + startOfWeek.format('MMM'));

        startOfWeek = moment(startOfWeek).add(1, 'd');
        inWorkingHour = RevyBookingFlow.inWorkingHour(startOfWeek);
        date_class = startOfWeek.isBefore(current_date) || !inWorkingHour ? 'disabled' : '';
        $('.week-day-header.tue .week-date').removeClass('disabled').addClass(date_class);
        $('.week-day-header.tue .week-date span').html(startOfWeek.format('DD')).attr('data-date', startOfWeek.format('YYYY-MM-DD'));
        $('.week-day-header.tue .week-header-mobile').html(revy_data.wed + ', ' + startOfWeek.format('DD') + '-' + startOfWeek.format('MMM'));

        startOfWeek = moment(startOfWeek).add(1, 'd');
        inWorkingHour = RevyBookingFlow.inWorkingHour(startOfWeek);
        date_class = startOfWeek.isBefore(current_date) || !inWorkingHour ? 'disabled' : '';
        $('.week-day-header.wed .week-date').removeClass('disabled').addClass(date_class);
        $('.week-day-header.wed .week-date span').html(startOfWeek.format('DD')).attr('data-date', startOfWeek.format('YYYY-MM-DD'));
        $('.week-day-content.wed  .week-header-mobile').html(revy_data.thu + ', ' + startOfWeek.format('DD') + '-' + startOfWeek.format('MMM'));

        startOfWeek = moment(startOfWeek).add(1, 'd');
        inWorkingHour = RevyBookingFlow.inWorkingHour(startOfWeek);
        date_class = startOfWeek.isBefore(current_date) || !inWorkingHour ? 'disabled' : '';
        $('.week-day-header.thu .week-date').removeClass('disabled').addClass(date_class);
        $('.week-day-header.thu .week-date span').html(startOfWeek.format('DD')).attr('data-date', startOfWeek.format('YYYY-MM-DD'));
        $('.week-day-header.thu  .week-header-mobile').html(revy_data.fri + ', ' + startOfWeek.format('DD') + '-' + startOfWeek.format('MMM'));

        startOfWeek = moment(startOfWeek).add(1, 'd');
        inWorkingHour = RevyBookingFlow.inWorkingHour(startOfWeek);
        date_class = startOfWeek.isBefore(current_date) || !inWorkingHour ? 'disabled' : '';
        $('.week-day-header.fri .week-date').removeClass('disabled').addClass(date_class);
        $('.week-day-header.fri .week-date span').html(startOfWeek.format('DD')).attr('data-date', startOfWeek.format('YYYY-MM-DD'));
        $('.week-day-header.fri  .week-header-mobile').html(revy_data.sat + ', ' + startOfWeek.format('DD') + '-' + startOfWeek.format('MMM'));

        startOfWeek = moment(startOfWeek).add(1, 'd');
        inWorkingHour = RevyBookingFlow.inWorkingHour(startOfWeek);
        date_class = startOfWeek.isBefore(current_date) || !inWorkingHour ? 'disabled' : '';
        $('.week-day-header.sat .week-date').removeClass('disabled').addClass(date_class);
        $('.week-day-header.sat .week-date span').html(startOfWeek.format('DD')).attr('data-date', startOfWeek.format('YYYY-MM-DD'));
        $('.week-day-header.sat  .week-header-mobile').html(revy_data.sun + ', ' + startOfWeek.format('DD') + '-' + startOfWeek.format('MMM'));

        if(RevyBookingFlow.delivery_method==2){ // Carry In
            RevyBookingFlow.initWeeklyTimeSlot(container);
        }
    }

    RevyBookingFlow.inWorkingHour = function (date) {
        var day_of_week = date.days();
        day_of_week = day_of_week == 0 ? 8 : (day_of_week + 1);
        day_of_week = day_of_week.toString();
        if (typeof revy_flow_data != 'undefined' && typeof revy_flow_data.working_hour != 'undefined') {
            var wh = _.filter(revy_flow_data.working_hour, function (item) {
                return item.es_day == day_of_week && item.es_enable == '1';
            });
            if (wh.length > 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    };

    RevyBookingFlow.deviceOnClick = function (elm) {
        var container = $(elm).closest('.fat-booking-container'),
            parent_step = $(elm).closest('.device-step'),
            next_step = $('.brand-model-step', container),
            device_item = $('.fat-it-inner', elm).clone(false).addClass('no-hover');

        RevyBookingFlow.device_id = $(elm).attr('data-device-id');
        parent_step.fadeOut(function () {
            RevyBookingFlow.bindBrandToDropdown(RevyBookingFlow.device_id, container);
            $('.fat-sb-item', next_step).empty().append(device_item);

            //reset models dropdown
            $('.ui.dropdown.models', container).dropdown('clear');

            next_step.fadeIn();
        })

    };

    RevyBookingFlow.deviceBrandModelStepOnClick = function (elm) {
        var container = $(elm).closest('.fat-booking-container'),
            parent_step = $(elm).closest('.fat-sb-list-devices');

        RevyBookingFlow.device_id = $(elm).attr('data-device-id');
        parent_step.fadeOut(function () {
            RevyBookingFlow.bindBrandToStep(RevyBookingFlow.device_id, container);
        })

    };

    RevyBookingFlow.brandOnClick = function(elm){
        var container = $(elm).closest('.fat-booking-container'),
            parent_step =  $(elm).closest('.fat-sb-list-brand'),
            brand_id =  $(elm).attr('data-brand-id');

        RevyBookingFlow.brand_id = brand_id;
        RevyBookingFlow.brand_name = $(elm).attr('data-name');
        RevyBookingFlow.bindModelToStep(RevyBookingFlow.device_id, RevyBookingFlow.brand_id, container);

    }

    RevyBookingFlow.modelOnClick = function(elm){
        var container = $(elm).closest('.fat-booking-container'),
            parent_step =  $(elm).closest('.fat-sb-list-model'),
            model_id =  $(elm).attr('data-model-id');

        RevyBookingFlow.model_id = model_id;
        RevyBookingFlow.model_name = $(elm).attr('data-name');

        RevyBookingFlow.resetServiceSelected(container);
        RevyBookingFlow.bindServices(RevyBookingFlow.model_id, container);
        parent_step.fadeOut(function () {
            RevyBookingFlow.goToTop(container);
            $('.fat-sb-list-services', container).fadeIn();
        })

    }

    RevyBookingFlow.serviceOnClick = function (elm) {
        var container = $(elm).closest('.fat-booking-container'),
            service_id = $(elm).attr('data-service-id'),
            service_name = $(elm).attr('data-service-name'),
            min_price = $(elm).attr('data-min-price'),
            min_price_format = $(elm).attr('data-min-price-format'),
            item = '';

        if(RevyBookingFlow.is_attribute_change==1){
            return;
        }
        if ($('.price-attribute .item', elm).length > 1 && !$(elm).hasClass('one-attribute') && !$(elm).hasClass('no-attribute')) {
            $('.ui.dropdown.attribute', elm).dropdown('show');
        } else {
            var attr_code = '',
                attr_title = '',
                attr_value = '';
            if($('.price-attribute .item', elm).length == 1){
                item =  $('.ui.dropdown.attribute .menu .item:first-child', elm);
                attr_code =  item.attr('data-code');
                attr_title =  item.attr('data-title');
                attr_value =  item.attr('data-value');
            }
            RevyBookingFlow.addToRepairList(service_id, service_name, min_price, min_price_format, container, attr_code, attr_title, attr_value);
        }
    };

    RevyBookingFlow.garageOnClick = function (elm) {
        var container = elm.closest('.fat-booking-container');
        RevyBookingFlow.garage_id = elm.attr('data-garage-id');
        RevyBookingFlow.garage_title = $('.item-title', elm).html();
        RevyBookingFlow.garage_address = $('.item-address', elm).html();
        RevyBookingFlow.garage_desc = $('.item-desc', elm).html();
        $('.fat-sb-list-garages', container).fadeOut(function () {
            RevyBookingFlow.bindRepairSummary(container);
            if(RevyBookingFlow.delivery_method==2){
                RevyBookingFlow.initWeeklyTimeSlot(container);
            }
            $('.repair-summary .time-meta', container).hide();

            $('.fat-sb-order-wrap', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        })
    }

    RevyBookingFlow.addToRepairList = function (service_id, service_name, price, price_format, container, attr_code, attr_title, attr_value) {
        var price = parseFloat(price);
        price_format = price > 0 ? price_format : '';

        //remove service in Repair list
        if ($('.fat-sb-repair-list a.fat-sb-remove-service[data-id="' + service_id + '"]', container).length == 1) {
            $('.fat-sb-repair-list a.fat-sb-remove-service[data-id="' + service_id + '"]', container).addClass('no-animation no-reset-default').trigger('click');
        }

        var item = $('<li><span class="service-name">' + service_name + '</span><br/>' +
            '<span class="model-name">' + RevyBookingFlow.model_name + '</span>' +
            '<span class="service-price">' + price_format + '<a href="javascript:;?>" class="fat-sb-remove-service" data-id="' + service_id + '" data-onClick="RevyBookingFlow.removeService">' +
            '<i class="trash alternate outline icon"></i></a> </span>');

        $('.fat-sb-list-services .fat-sb-repair-list ul', container).append(item);
        RevyMain_FE.registerOnClick(item);
        $('.fat-sb-repair-list button.fat-bt-next', container).removeClass('disabled');

        //add service to list selected
        var service = _.findWhere(revy_flow_data.services, {s_id: service_id});
        if (typeof service != 'undefined') {
            RevyBookingFlow.services_selected.push({
                s_name: service.s_name,
                s_id: service_id,
                s_tax: service.s_tax,
                s_duration: service.s_duration,
                s_break_time: service.s_break_time,
                s_max_slot: service.s_maximum_slot,
                s_price: price,
                s_garage_ids: service.s_garage_ids,
                s_attr_code: attr_code,
                s_attr_title: attr_title,
                s_attr_value: attr_value
            });
        }

        //auto go to repair list for mobile and tablet device
        if(container.width() < 768){
            RevyBookingFlow.goToRepairList(container);
        }

    };

    RevyBookingFlow.removeService = function (elm) {
        var container = $(elm).closest('.fat-booking-container'),
            service_id = $(elm).attr('data-id'),
            parent = $(elm).closest('li'),
            timeout = 400;

        if (!elm.hasClass('no-reset-default')) {
            $('.fat-sb-item[data-service-id="' + service_id + '"] .ui.dropdown.attribute', container).dropdown('restore defaults');
        }
        timeout = elm.hasClass('no-animation') ? 0: timeout;
        parent.fadeOut(timeout, function () {
            parent.remove();
            if ($('.fat-sb-repair-list ul li', container).length == 0) {
                $('.fat-sb-repair-list button.fat-bt-next', container).addClass('disabled');
            }else{
                $('.fat-sb-repair-list button.fat-bt-next', container).removeClass('disabled');
            }
            RevyBookingFlow.services_selected = _.filter(RevyBookingFlow.services_selected, function(item){
                return item.s_id != service_id;
            });

        });

    };

    RevyBookingFlow.brandOnChange = function (value, text, $choice, self) {
        if (value != '') {
            var container = $(self).closest('.fat-booking-container');
            $('.model-field', container).addClass('fadein');
            RevyBookingFlow.brand_id = value;
            RevyBookingFlow.brand_name = text;
            RevyBookingFlow.bindModelToDropdown(RevyBookingFlow.device_id, RevyBookingFlow.brand_id, container);
        }
    };

    RevyBookingFlow.modelOnChange = function (value, text, $choice, self) {
        if (value != '') {
            var container = $(self).closest('.fat-booking-container');
            $('.model-field', container).addClass('fadein');
            RevyBookingFlow.model_id = value;
            RevyBookingFlow.model_name = text;

            RevyBookingFlow.resetServiceSelected(container);
            RevyBookingFlow.bindServices(RevyBookingFlow.model_id, container);
            $('.fat-sb-list-devices', container).fadeOut(function () {
                RevyBookingFlow.goToTop(container);
                $('.fat-sb-list-services', container).fadeIn();
            })
        }
    };

    RevyBookingFlow.attributeOnChange = function (value, text, $choice, self) {
        if (typeof $choice != 'undefined') {
            var container = self.closest('.fat-booking-container'),
                service_item = self.closest('.fat-sb-item'),
                service_id = service_item.attr('data-service-id'),
                service_name = service_item.attr('data-service-name'),
                price = $choice.attr('data-price'),
                price_format = $choice.attr('data-price-format'),
                attr_code = $choice.attr('data-code'),
                attr_title = $choice.attr('data-title'),
                attr_value = $choice.attr('data-value');

            RevyBookingFlow.addToRepairList(service_id, service_name, price, price_format, container, attr_code, attr_title, attr_value);

            $('.fat-min-price', service_item).html(price_format);

            //prevent service on click
            RevyBookingFlow.is_attribute_change = 1;
            setTimeout(function(){
                RevyBookingFlow.is_attribute_change = 0;
            },500)
        }
    };

    RevyBookingFlow.bindBrandToDropdown = function (device_id, container) {
        var elm_brand = $('.ui.dropdown.brands', container),
            elm_brand_menu = $(' > .menu', elm_brand),
            brands = [];

        brands = _.filter(revy_flow_data.brands, function (item) {
            return _.indexOf(item.rb_device_ids.split(','), device_id) > -1;
        });

        elm_brand.addClass('fat-loading');
        elm_brand.append('<div class="ui button loading"></div>');
        elm_brand_menu.val('');
        $('.item', elm_brand_menu).remove();
        elm_brand.dropdown('clear');
        for (let brand of brands) {
            elm_brand_menu.append('<div class="item" data-value="' + brand.rb_id + '">' + brand.rb_name + '</div>');
        }
        elm_brand.removeClass('fat-loading');
        $('.ui.button.loading', elm_brand).remove();
    };

    RevyBookingFlow.bindBrandToStep = function (device_id, container) {
        var brand_step = $('.fat-sb-list-brand', container),
            brand_list = $('.fat-sb-item-inner-wrap', brand_step),
            brands = [];

        brands = _.filter(revy_flow_data.brands, function (item) {
            return _.indexOf(item.rb_device_ids.split(','), device_id) > -1;
        });

        var template = wp.template('fat-brand-item-template'),
            items = $(template(brands));

        brand_list.empty();
        brand_list.append(items);

        $('.fat-sb-list-devices').fadeOut(function(){
            brand_step.removeClass('fat-hidden-step').fadeIn();
            RevyMain_FE.registerOnClick(brand_step);
        })

    };

    RevyBookingFlow.bindModelToDropdown = function (device_id, brand_id, container) {
        var elm_model = $('.ui.dropdown.models', container),
            elm_model_menu = $(' > .menu', elm_model),
            models = [];

        models = _.filter(revy_flow_data.models, function (item) {
            return item.rm_brand_id == brand_id && item.rm_device_id == device_id;
        });

        elm_model.addClass('fat-loading');
        elm_model.append('<div class="ui button loading"></div>');
        elm_model_menu.val('');
        $('.item', elm_model_menu).remove();
        elm_model.dropdown('clear');
        for (let model of models) {
            elm_model_menu.append('<div class="item" data-value="' + model.rm_id + '">' + model.rm_name + '</div>');
        }
        elm_model.removeClass('fat-loading');
        $('.ui.button.loading', elm_model).remove();
    };

    RevyBookingFlow.bindModelToStep = function (device_id, brand_id, container) {
        var model_step = $('.fat-sb-list-model', container),
            model_list = $('.fat-sb-item-inner-wrap', model_step),
            models = [];

        models = _.filter(revy_flow_data.models, function (item) {
            return item.rm_brand_id == brand_id && item.rm_device_id == device_id;
        });
        var template = wp.template('fat-model-item-template'),
            items = $(template(models));

        model_list.empty();
        model_list.append(items);

        $('.fat-sb-list-brand',container).fadeOut(function(){
            model_step.removeClass('fat-hidden-step').fadeIn();
            RevyMain_FE.registerOnClick(model_step);
        })
    };

    RevyBookingFlow.bindServices = function (model_id, container) {
        var elm_list_services = $('.fat-sb-list-services', container),
            template = wp.template('fat-flow-service-item-template'),
            services = _.filter(revy_flow_data.services, {s_model_id: model_id}),
            iterm_inner = $('.fat-sb-list-services .fat-sb-item-inner-wrap', container),
            items = '';

        iterm_inner.empty();
        if (services.length > 0) {
            for (let se of services) {
                if (typeof se.s_min_price != 'undefined') {
                    se.s_min_price_format = RevyMain_FE.formatPrice(se.s_min_price);
                }
                se.s_min_price_class = 'pr-' + se.s_min_price.split('.')[0];
                if (typeof se['attrs'] != 'undefined') {
                    for (let at of se['attrs']) {
                        if (typeof at['s_price'] != 'undefined') {
                            at['s_price_format'] = RevyMain_FE.formatPrice(at['s_price']);
                        }
                    }
                }
            }
            items = $(template(services));
            iterm_inner.append(items);
            RevyMain_FE.registerOnClick(elm_list_services);
            RevyMain_FE.registerOnChange(elm_list_services);
        }

    };

    RevyBookingFlow.bindGarage = function (container) {
        var elm_list_garage = $('.fat-sb-list-garages', container),
            template = wp.template('fat-flow-garage-item-template'),
            iterm_inner = $('.fat-sb-item-inner-wrap', elm_list_garage),
            items = '',
            garages = [];

        iterm_inner.empty();
        $('.fat-sb-list-garages .fat-sb-item-not-found', container).remove();
        if( RevyBookingFlow.delivery_method == 3){ //Mail In
            // get garage
            var service_garage = [];
            for (let sv of RevyBookingFlow.services_selected) {
                service_garage = _.union(service_garage, sv.s_garage_ids.split(','));
            }
            for (let gr of revy_flow_data.garages) {
                if (_.indexOf(service_garage, gr.rg_id) > -1) {
                    garages.push(gr);
                }
            }
        }else{
            garages = RevyBookingFlow.garage_near_me;
        }

        if (garages.length > 0) {
            items = $(template(garages));
            iterm_inner.append(items);
            RevyMain_FE.registerOnClick(elm_list_garage);
        }else{
            $('.fat-sb-list-garages .fat-sb-item-inner-wrap', container).append('<div class="fat-sb-item-not-found">'+ revy_data.garage_not_found +'</div>');
        }
    };

    RevyBookingFlow.goBackBrand = function (elm) {
        var container = $(elm).closest('.fat-booking-container');
        $('.fat-sb-list-services', container).fadeOut(function () {
            $('.fat-sb-list-devices', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        });
    };

    RevyBookingFlow.goBackDevice = function (elm) {
        var container = $(elm).closest('.fat-booking-container');
        $('.brand-model-step', container).fadeOut(function () {
            $('.device-step', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        })
    };

    RevyBookingFlow.goBackDeviceStep = function (elm) {
        var container = $(elm).closest('.fat-booking-container');
        $('.fat-sb-list-brand', container).fadeOut(function () {
            $('.fat-sb-list-devices', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        })
    };

    RevyBookingFlow.goBackBrandStep = function (elm) {
        var container = $(elm).closest('.fat-booking-container');
        $('.fat-sb-list-model', container).fadeOut(function () {
            $('.fat-sb-list-brand', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        })
    };

    RevyBookingFlow.goBackModelStep = function(elm){
        var container = $(elm).closest('.fat-booking-container');
        $('.fat-sb-list-services', container).fadeOut(function () {
            $('.fat-sb-list-model', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        })
    }

    RevyBookingFlow.goBackServices = function (elm) {
        var container = elm.closest('.fat-booking-container');
        $('.fat-sb-list-delivery-method', container).fadeOut(function () {
            $('.fat-sb-list-services', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        });
    };

    RevyBookingFlow.goBackDeliveryMethod = function (elm) {
        var container = elm.closest('.fat-booking-container');
        $('.fat-sb-list-garages', container).fadeOut(function () {
            $('.fat-sb-list-delivery-method', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        });
    };

    RevyBookingFlow.gotoDeliveryMethod = function (elm) {
        var container = elm.closest('.fat-booking-container'),
            elm_title = $('.fat-sb-list-delivery-method .fat-sb-title', container),
            elm_subtitle = $('.fat-sb-list-delivery-method .fat-sb-subtitle', container),
            is_hide_map = container.hasClass('hide-map');

        RevyBookingFlow.garage_near_me = is_hide_map ? RevyBookingFlow.getGarages(revy_flow_data.garages) : RevyBookingFlow.getGarageNearMe(revy_flow_data.garages);

        if(RevyBookingFlow.garage_near_me.length > 0 || is_hide_map){
            elm_title.html(elm_title.attr('data-delivery-title'));
            elm_subtitle.html(elm_title.attr('data-delivery-subtitle'));
            $('.fat-sb-list-garages .fat-sb-go-back a span, .fat-sb-order-wrap a.fat-go-back-location span', container).html(revy_data.change_delivery_label);
            $('.postal-code-wrap', container).hide();
            $('.list-delivery-method .fat-sb-item', container).removeClass('disabled');
        }else{
            elm_title.html(elm_title.attr('data-location-title'));
            elm_subtitle.html(elm_title.attr('data-location-subtitle'));
            $('.postal-code-wrap', container).show();
            $('.fat-sb-list-garages .fat-sb-go-back a span, .fat-sb-order-wrap a.fat-go-back-location span', container).html(revy_data.change_location_label);
            $('.list-delivery-method .fat-sb-item.fixit-home,.list-delivery-method .fat-sb-item.carry-in', container).addClass('disabled');
        }
        $('.fat-sb-list-services', container).fadeOut(function () {
            RevyBookingFlow.goToTop(container);
            $('.fat-sb-list-delivery-method', container).fadeIn();
        });
    };

    RevyBookingFlow.goBackGarage = function (elm) {
        var container = elm.closest('.fat-booking-container');
        $('.fat-sb-order-wrap', container).fadeOut(function () {
            $('.fat-sb-list-garages', container).fadeIn();
        });
    };

    RevyBookingFlow.goBackLocationFixItHome = function (elm) {
        var container = $(elm).closest('.fat-booking-container');
        $('.fat-sb-order-wrap', container).fadeOut(function () {
            $('.fat-sb-list-delivery-method', container).fadeIn(function () {
                RevyBookingFlow.goToTop(container);
            });
        });
    };

    RevyBookingFlow.resetServiceSelected = function (container) {
        RevyBookingFlow.services_selected = [];
        $('.fat-sb-list-services .fat-sb-repair-list ul', container).empty();
        $('.fat-sb-repair-list button.fat-bt-next', container).addClass('disabled');
    }

    RevyBookingFlow.getLocationFromPostalCode = function (self) {
        var container = self.closest('.fat-booking-container'),
            post_code = $('input#postal_code', container).val();
        if (post_code != '') {
            var tab = self.closest('.fat-sb-tab-content'),
                api_url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + post_code + '.json?&types=postcode&limit=1&access_token=' + RevyMain_FE.data.map_api_key;

            $('#c_postal_code', container).val(post_code);
            $('.fat-sb-postal-code-message', container).fadeOut();
            RevyMain_FE.addLoading(container, self);
            $.ajax({
                url: api_url,
                type: 'GET',
                success: function (response) {
                    RevyMain_FE.removeLoading(container, self);
                    if (typeof response.features != 'undefined' && typeof response.features[0] != 'undefined') {
                        RevyMain_FE.client_longitude = response.features[0].center[0];
                        RevyMain_FE.client_latitude = response.features[0].center[1];
                        RevyBookingFlow.garage_near_me = RevyBookingFlow.getGarageNearMe(revy_flow_data.garages);
                        if (RevyBookingFlow.garage_near_me.length == 0) {
                            $('.fat-sb-postal-code-message', container).fadeIn();
                            $('.list-delivery-method .fixit-home,.list-delivery-method .carry-in', container).addClass('disabled');
                        } else {
                            $('.list-delivery-method .fixit-home,.list-delivery-method .carry-in', container).removeClass('disabled');
                        }
                    } else {
                        $('.list-delivery-method .fixit-home,.list-delivery-method .carry-in', container).addClass('disabled');
                        $('.fat-sb-postal-code-message', container).fadeIn();
                    }

                },
                error: function (response) {
                    RevyMain_FE.removeLoading(container, self);
                }
            });

        }
    }

    RevyBookingFlow.getGarageNearMe = function (garages) {
        var garage_near_me = [],
            from = turf.point([RevyMain_FE.client_longitude, RevyMain_FE.client_latitude]),
            to = '',
            options = {units: RevyMain_FE.data.distance_unit},
            distance = 0,
            service_garage = [];

        for (let sv of RevyBookingFlow.services_selected) {
            service_garage = _.union(service_garage, sv.s_garage_ids.split(','));
        }
        for (let gr of garages) {
            if (_.indexOf(service_garage, gr.rg_id) > -1) {
                to = turf.point([gr.rg_longitude, gr.rg_latitude]);
                distance = turf.distance(from, to, options);
                if (distance <= RevyMain_FE.data.distance_near_me) {
                    garage_near_me.push(gr);
                }
            }
        }
        return garage_near_me;
    }

    RevyBookingFlow.getGarages = function (garages) {
        var garage_near_me = [],
            service_garage = [];

        for (let sv of RevyBookingFlow.services_selected) {
            service_garage = _.union(service_garage, sv.s_garage_ids.split(','));
        }
        for (let gr of garages) {
            if (_.indexOf(service_garage, gr.rg_id) > -1) {
                garage_near_me.push(gr);
            }
        }
        return garage_near_me;
    }

    RevyBookingFlow.fixItHomeOnClick = function (elm) {
        var container = elm.closest('.fat-booking-container');
        $('.fat-sb-order-wrap', container).removeClass('no-appointment-section no-garage-section');
        RevyBookingFlow.delivery_method = 1; // FixIt at  Home
        $('.fat-sb-list-delivery-method', container).fadeOut(function () {
            $('.button-group button.ui.button:not(.next-delivery)',container).addClass('disabled');
            $('.fat-sb-order-wrap', container).addClass('no-garage-section');
            RevyBookingFlow.bindRepairSummary(container);
            RevyBookingFlow.showCustomerFormBaseOnDelivery(container);
            RevyBookingFlow.goToTop(container);
            RevyBookingFlow.initCalendar();
            $('.fat-sb-order-wrap', container).fadeIn();
        });
    };

    RevyBookingFlow.carryInOnClick = function (elm) {
        var container = elm.closest('.fat-booking-container');
        RevyBookingFlow.delivery_method = 2; // Carry In
        $('.fat-sb-list-delivery-method', container).fadeOut(function () {
            $('.fat-sb-order-wrap', container).removeClass('no-appointment-section  no-garage-section');
            $('.button-group button.ui.button:not(.next-delivery)', container).addClass('disabled');
            RevyBookingFlow.bindGarage(container);
            RevyBookingFlow.showCustomerFormBaseOnDelivery(container);
            RevyBookingFlow.goToTop(container);
            RevyBookingFlow.initCalendar();
            $('.fat-sb-list-garages', container).fadeIn();
        });
    };

    RevyBookingFlow.mailInOnClick = function (elm) {
        var container = elm.closest('.fat-booking-container');
        RevyBookingFlow.delivery_method = 3; // Mail In Delivery
        $('.fat-sb-order-wrap', container).removeClass('no-appointment-section  no-garage-section');
        $('.fat-sb-list-delivery-method', container).fadeOut(function () {
            $('.button-group button.ui.button:not(.next-delivery)', container).addClass('disabled');
            $('.button-group button.ui.button.fat-bt-payment', container).removeClass('disabled');
            $('.fat-sb-order-wrap', container).addClass('no-appointment-section ');
            RevyBookingFlow.bindGarage(container);
            RevyBookingFlow.showCustomerFormBaseOnDelivery(container);
            RevyBookingFlow.goToTop(container);
            RevyBookingFlow.initCalendar();
            $('.fat-sb-list-garages', container).fadeIn();
        });
    };

    RevyBookingFlow.dateOnClick = function (elm) {
        var container = $(elm).closest('.appointment-wrap'),
            date = elm.attr('data-date');
        if (!elm.hasClass('selected')) {
            $('.week-date span.selected', container).removeClass('selected');
            elm.addClass('selected');
            RevyBookingFlow.b_date = date;
            if(RevyBookingFlow.delivery_method==2){ //Carry In
                RevyBookingFlow.initTimeSlotCarryIn(date, container);
            }else{
                RevyBookingFlow.initTimeSlotBaseOnWorkingHour(date, container);
            }
        }
    };

    RevyBookingFlow.initTimeSlotBaseOnWorkingHour = function (date, container) {
        var dd_time_options = $('.ui.dropdown.time-options', container),
            day_of_week = 0;

        date = moment(date, 'YYYY-MM-DD');
        day_of_week = date.days();
        day_of_week = day_of_week == 0 ? 8 : (day_of_week + 1);
        day_of_week = day_of_week.toString();

        dd_time_options.addClass('loading');
        dd_time_options.dropdown('clear');
        $('.item', dd_time_options).remove();

        var working_hours = _.filter(revy_flow_data.working_hour, function (item) {
            return item.es_day == day_of_week && item.es_enable == '1';
        });

        var es_start = 0,
            es_end = 0,
            time_step = parseInt(revy_data.time_step),
            slots = revy_data.slots,
            time_items = '';

        for (let wh of working_hours[0].work_hours) {
            es_start = parseInt(wh.es_work_hour_start);
            es_end = parseInt(wh.es_work_hour_end);
            time_items = '';
            for (var $i = es_start; $i <= es_end; $i += time_step) {
                time_items += '<div class="item" data-value="' + $i + '">' + slots[$i] + '</div>';
            }
            $('.menu', dd_time_options).append(time_items);
        }
        dd_time_options.removeClass('loading');
    };

    RevyBookingFlow.showCustomerFormBaseOnDelivery = function (container) {
        if (RevyBookingFlow.delivery_method == 1) { // Fix It at Home
            $('a.fat-go-back-garage', container).addClass('fat-sb-hidden');
            $('a.fat-go-back-location', container).removeClass('fat-sb-hidden');

        } else {
            $('a.fat-go-back-garage', container).removeClass('fat-sb-hidden');
            $('a.fat-go-back-location', container).addClass('fat-sb-hidden');
        }

        if (RevyBookingFlow.delivery_method == 2) { // Carry In
            $('.customer-info-wrap .address-postal, .customer-info-wrap .city-country', container).hide();
        } else {
            $('.customer-info-wrap .address-postal, .customer-info-wrap .city-country', container).show();
        }
    };

    RevyBookingFlow.goToTop = function (container) {
        if(typeof RevyMain_FE.data.disable_scroll !='undefined' && RevyMain_FE.data.disable_scroll=='1'){
            return;
        }
        var top = container.offset().top - 50;
        $("html, body").animate({scrollTop: top}, 500);
    };

    RevyBookingFlow.goToRepairList = function (container) {
        var top = $('.fat-sb-repair-list',container).offset().top - 100;
        $("html, body").animate({scrollTop: top}, "slow");
    };

    RevyBookingFlow.resetValidateField = function (self) {
        if (self.val() != '') {
            self.closest('.field').removeClass('field-error');
        }
    };

    RevyBookingFlow.confirmOrderClick = function (elm) {
        var container = $(elm).closest('.fat-booking-container'),
            form = $('.ui.form', container),
            payment_method = $('.fat-list-gateway .gateway-item.selected', container).attr('data-value');

        RevyMain_FE.addLoading(container, elm);

        $('.repair-summary  .fat-sb-error-message', container).html('').addClass('fat-sb-hidden');

        $('#c_postal_code, #c_address, #c_city, #c_country', form).removeAttr('required');

        if (RevyBookingFlow.delivery_method == 1 || RevyBookingFlow.delivery_method == 3) {
            $('#c_postal_code, #c_address, #c_city, #c_country', form).prop('required', true);
        }
        if (RevyMain_FE.validateForm(form)) {
            var data = {
                c_first_name : $('#c_first_name', form).val(),
                c_last_name : $('#c_last_name', form).val(),
                c_email : $('#c_email', form).val(),
                c_phone_code : $('#phone_code', form).val(),
                c_phone : $('#c_phone', form).val(),
                b_customer_postal_code : $('#c_postal_code', form).val(),
                b_customer_address : $('#c_address', form).val(),
                b_customer_city : $('#c_city', form).val(),
                b_customer_country : $('#c_country', form).val(),
                b_garage_id: RevyBookingFlow.garage_id,
                b_device_id: RevyBookingFlow.device_id,
                b_brand_id: RevyBookingFlow.brand_id,
                b_model_id: RevyBookingFlow.model_id,
                b_date: RevyBookingFlow.b_date,
                b_time: RevyBookingFlow.b_time,
                b_delivery_method: RevyBookingFlow.delivery_method,
                b_gateway_type: payment_method
            };

            try {
                $.ajax({
                    url: revy_data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'save_booking_fe',
                        s_field: revy_data.ajax_s_field,
                        services: RevyBookingFlow.services_selected,
                        data: data
                    }),
                    success: function (response) {
                        response = $.parseJSON(response);
                        RevyMain_FE.removeLoading(container, elm);

                        if (response.result > 0) {

                            if(payment_method == 'stripe' && RevyBookingFlow.b_total_pay > 0){
                                RevyBookingFlow.stripePaymentIntents(response.result , self, container);
                                return;
                            }

                            if (typeof response.redirect_url != 'undefined' && response.redirect_url != '') {
                                window.location.href = response.redirect_url;
                                return;
                            }

                            if (payment_method == 'onsite' || payment_method == 'paypal' || RevyBookingFlow.b_total_pay == 0) {
                                //display success message
                                $('.fat-sb-order-wrap', container).fadeOut(function () {
                                    $('.fat-bt-add-icalendar', container).attr('data-id', response.result);
                                    $('.fat-bt-add-google-calendar', container).attr('data-id', response.result);
                                    RevyBookingFlow.goToTop(container);
                                    $('.fat-sb-appointment-booked-wrap', container).fadeIn();
                                });

                                RevyMain_FE.removeLoading(container, elm);

                                $.ajax({
                                    url: revy_data.ajax_url,
                                    type: 'POST',
                                    data: ({
                                        action: 'send_booking_fe_mail',
                                        s_field: revy_data.ajax_s_field,
                                        b_id: response.result,
                                    })
                                });

                            }

                        } else {
                            $('.repair-summary  .fat-sb-error-message', container).html(response.message).removeClass('fat-sb-hidden');
                        }
                    },
                    error: function (response) {
                        RevyMain_FE.removeLoading(container, elm);
                    }
                });
            } catch (err) {
            }

        }else{
            RevyMain_FE.removeLoading(container, elm);
            var top = $('.customer-info-wrap',container).offset().top - 100;
            $("html, body").animate({scrollTop: top}, "slow");
        }
    };

    RevyBookingFlow.gatewayOnClick = function(elm){
        var container = elm.closest('.fat-booking-container'),
            gateway = $(elm).attr('data-value')
        if(!elm.hasClass('selected')){
            $('.gateway-item.selected', container).removeClass('selected');
            $(elm).addClass('selected');
            if (gateway == 'stripe') {
                $('.fat-sb-order-stripe', container).removeClass('fat-sb-hidden');
                $('.fat-sb-order-stripe', container).fadeIn();
            } else {
                $('.fat-sb-order-stripe', container).addClass('fat-sb-hidden');
            }
        }

    }

    RevyBookingFlow.stripePaymentIntents = function($booking_id, self, container){
        var card = RevyBookingFlow.card;
        $.ajax({
            url: revy_data.ajax_url,
            type: 'POST',
            data: ({
                action: 'payment_intents',
                b_id: $booking_id
            }),
            success: function (paymentIntent) {
                paymentIntent = $.parseJSON(paymentIntent);
                if(typeof paymentIntent.error =='undefined'){
                    (async () => {
                        const response = await RevyBookingFlow.stripe.confirmCardPayment(
                            paymentIntent.client_secret,
                            {
                                payment_method: {
                                    card
                                }
                            }
                        );
                        if(typeof response['paymentIntent']!="undefined" ){
                            RevyBookingFlow.stripeHandlePayment ($booking_id, response, container, self);
                        }else{
                            RevyMain_FE.removeLoading(container, self);
                            if(typeof response.error !='undefined' && typeof response.error.message !='undefined'){
                                $('.repair-summary .fat-sb-error-message', container).html(response.error.message).removeClass('fat-sb-hidden');
                            }
                        }
                    })();

                }else{
                    RevyMain_FE.removeLoading(container, self);
                    $('.fat-sb-tab-content.appointment .fat-sb-error-message', container).html(paymentIntent.error).removeClass('fat-sb-hidden');
                }
            },
            error: function () {
                RevyMain_FE.removeLoading(container, self);
            }
        });
    };

    RevyBookingFlow.stripeHandlePayment = function ($booking_id, paymentResponse, container, self){
        $.ajax({
            url: revy_data.ajax_url,
            type: 'POST',
            data: ({
                action: 'payment_confirm',
                b_id: $booking_id,
                paymentResponse: paymentResponse['paymentIntent']
            }),
            success: function (response) {
                response =  $.parseJSON(response);
                RevyMain_FE.removeLoading(container, self);
                if(response.result > 0){
                    $.ajax({
                        url: revy_data.ajax_url,
                        type: 'POST',
                        data: ({
                            action: 'send_booking_fe_mail',
                            s_field: revy_data.ajax_s_field,
                            b_id: response.result,
                            paymentResponse: paymentResponse
                        })
                    });

                    //display success message
                    $('.fat-sb-order-wrap', container).fadeOut(function () {
                        $('.fat-bt-add-icalendar', container).attr('data-id', response.result);
                        $('.fat-bt-add-google-calendar', container).attr('data-id', response.result);
                        RevyBookingFlow.goToTop(container);
                        $('.fat-sb-appointment-booked-wrap', container).fadeIn();
                    });

                }else{
                    $('.repair-summary .fat-sb-error-message', container).html(response.message).removeClass('fat-sb-hidden');
                }
            },
            error: function () {
                RevyMain_FE.removeLoading(container, self);
            }
        });
    };

    RevyBookingFlow.bindRepairSummary = function(container){
        $('.repair-summary  .time-meta .mt-value', container).html('');
        $('.repair-summary  .garage-title', container).html(RevyBookingFlow.garage_title);
        $('.repair-summary  .garage-address', container).html(RevyBookingFlow.garage_address);
        $('.repair-summary  .garage-desc', container).html(RevyBookingFlow.garage_desc);
        $('.repair-summary  .device-meta .mt-value', container).html(RevyBookingFlow.brand_name + '-' + RevyBookingFlow.model_name);
        $('.repair-summary  .time-meta .mt-value', container).html('');

        var service = '',
            total = 0,
            tax = 0;
        for(let sv of RevyBookingFlow.services_selected){
            service += service=='' ? (sv.s_name) : (',' + sv.s_name);
            tax += parseFloat(sv.s_price) * parseFloat(sv.s_tax) / 100;
            total += parseFloat(sv.s_price) + tax;
        }
        RevyBookingFlow.b_total_pay = total;

        $('.service-meta .mt-value', container).html(service);
        if(tax>0){
            $('.tax-meta .mt-value', container).html(RevyMain_FE.formatPrice(tax));
            $('.tax-meta').show();
        }else{
            $('.tax-meta').hide();
        }

        $('.cost-meta .mt-value', container).html(RevyMain_FE.formatPrice(total));

    };

    RevyBookingFlow.timeOnChange = function(value, text, $choice, self){
        RevyBookingFlow.b_time = value;
        text = typeof text!='undefined' ? text : '';
        $('.time-meta .mt-value').html(RevyBookingFlow.b_date + ' ' + text);
        $('.time-meta').show();
        $('.button-group button.ui.button').removeClass('disabled');
    }

    RevyBookingFlow.initWeeklyTimeSlot = function(container){
        if( RevyBookingFlow.garage_id<=0){
            return;
        }
        var elm = $('.ui.dropdown.time-options', container);
        RevyMain_FE.addLoading(container, elm);
        try {
            var $s_ids = [],
                start_date = $('.fat-sb-calendar-wrap .week-day-header.sun .week-date span', container).attr('data-date'),
                end_date = $('.fat-sb-calendar-wrap .week-day-header.sat .week-date span', container).attr('data-date');
            for(let sev of RevyBookingFlow.services_selected){
                $s_ids.push(sev.s_id);
            }
            $.ajax({
                url: revy_data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'get_time_slot_weekly',
                    s_field: revy_data.ajax_s_field,
                    s_ids: $s_ids,
                    garage_id: RevyBookingFlow.garage_id,
                    date_start: start_date,
                    date_end: end_date
                }),
                success: function (response) {
                    RevyMain_FE.removeLoading(container, elm);
                    RevyBookingFlow.weekly_timeslot = $.parseJSON(response);
                },
                error: function (response) {
                    RevyMain_FE.removeLoading(container, elm);
                }
            });
        } catch (err) {
            RevyMain_FE.removeLoading(container, elm);
        }
    };

    RevyBookingFlow.initTimeSlotCarryIn = function(date_str, container){
        var dd_time_options = $('.ui.dropdown.time-options', container),
            date = new Date(date_str),
            booking_in_day = [],
            booking_service_in_day = [],
            time_slot = [],
            group_slot = [],
            days = [],
            cap = 1,
            min_cap = 1,
            max_cap = 1,
            range = 0,
            time = 0,
            end_time = 0,
            is_conflict = 0,
            time_step = parseInt(revy_data.time_step),
            now = RevyMain_FE.parseDateTime(RevyMain_FE.data.now),
            now_minute = now.getHours() * 60 + now.getMinutes(),
            weekly_timeslot = RevyBookingFlow.weekly_timeslot;

        RevyBookingFlow.garage_id = parseInt(RevyBookingFlow.garage_id);

        $('.fat-sb-time-notice', container).hide();

        for (let service of RevyBookingFlow.services_selected) {
            if(typeof weekly_timeslot[service.s_id] =='undefined'){
                return false;
            }
            booking_in_day = _.where(weekly_timeslot[service.s_id].booking, {b_date: date_str});
            days = _.findWhere(weekly_timeslot[service.s_id].days, {date: date_str});
            booking_service_in_day = _.where(weekly_timeslot[service.s_id].booking, {
                b_date: date_str,
                b_service_id: service.s_id.toString()
            });

            max_cap = parseInt(weekly_timeslot[service.s_id].max_cap);
            cap = max_cap;
            if (typeof days == 'undefined') {
                return false;
            }

            if (days.work_hour.length == 0) {
                return false;
            }

            for (let wh of days.work_hour) {
                wh.es_work_hour_end = parseInt(wh.es_work_hour_end);
                wh.es_work_hour_start = parseInt(wh.es_work_hour_start);
                range = (wh.es_work_hour_end - wh.es_work_hour_start) / time_step;
                service.s_duration = parseInt(service.s_duration);
                service.s_break_time = parseInt(service.s_break_time);

                for (var $i = 0; $i < range; $i++) {
                    time = wh.es_work_hour_start + $i * time_step;
                    end_time = time + service.s_duration + service.s_break_time;
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
                                if (bk.b_garage_id == RevyBookingFlow.garage_id && (max_cap - bk.total_device) >= min_cap) {
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
                            if (bk.b_time <= time && end_time <= bk.b_time_end && bk.b_garage_id == RevyBookingFlow.garage_id && bk.b_service_id == service.s_id) {
                                break;
                            } else {
                                is_conflict = !(end_time <= bk.b_time || time >= bk.b_time_end);
                            }
                            if (is_conflict) {
                                break;
                            }
                        }
                    }

                    if (RevyMain_FE.equalDay(now, date) && time <= now_minute) {
                        is_conflict = 1;
                    }
                    if (!is_conflict) {
                        group_slot.push(time);
                        time_slot.push({
                            s_id: service.s_id,
                            slot: time,
                            available: cap
                        });
                    }
                }
            }
        }

        var total_service = RevyBookingFlow.services_selected.length;
        group_slot = _.groupBy(group_slot);
        time_slot = _.filter(time_slot, function (item) {
            return typeof group_slot[item.slot] != 'undefined' && group_slot[item.slot].length == total_service;
        });

        dd_time_options.addClass('loading');
        dd_time_options.dropdown('clear');
        $('.item', dd_time_options).remove();


        if (time_slot.length > 0) {
            var time_items = '';
            for (let ts of time_slot) {
                time_items += '<div class="item" data-value="' + ts.slot + '">' + revy_data.slots[ts.slot] + '</div>';
            }
            $('.menu', dd_time_options).append(time_items);
        }else{
            $('.fat-sb-time-notice', container).fadeIn();
        }

        setTimeout(function(){
            dd_time_options.removeClass('loading');
        }, 300)

    };

    $(document).ready(function () {
        RevyBookingFlow.init();
    });

})(jQuery)