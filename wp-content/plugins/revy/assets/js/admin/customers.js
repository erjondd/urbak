"use strict";
var RevyCustomers = {
    order : '',
    order_by: ''
};
(function ($) {
    RevyCustomers.init = function(){
        RevyMain.initCheckAll();
        RevyCustomers.loadCustomer(1);
        RevyMain.registerEventProcess($('.fat-sb-customers-container .toolbox-action-group'));
        RevyMain.registerOnClick($('.fat-sb-customers-container .fat-sb-order-wrap'));
        RevyMain.initPopupToolTip();
    };

    /*
    event handler
    */
    RevyCustomers.searchNameOnKeyUp = function(self){
        var search_wrap = self.closest('.ui.input');
        if(self.val().length >=3 || self.val()==''){
            search_wrap.addClass('loading');
            RevyCustomers.loadCustomer(1,function(){
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

    RevyCustomers.closeSearchOnClick = function(self){
        var search_wrap = self.closest('.ui.ui-search');
        $('input',search_wrap).val('');
        $('input',search_wrap).trigger('keyup');
    };

    RevyCustomers.btAddOnClick = function(){
        RevyMain.showPopup('fat-sb-customers-template','', {c_phone_code: RevyMain.data.phone_code},function(){
            RevyMain.registerEventProcess($('.fat-customer-form'));
        });
    };

    RevyCustomers.loadCustomer = function(page, callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_customers',
                c_name: $('#c_name','.toolbox-action-group').val(),
                order: RevyCustomers.order,
                order_by: RevyCustomers.order_by,
                page: typeof page!='undefined' && page!='' ? page: 1
            }),
            success: function(data){
                data = $.parseJSON(data);
                var total = data.total,
                    customers = data.customers,
                    date_format =  RevyMain.getDateFormat();

                for(var $c_index = 0; $c_index< customers.length;$c_index++){
                    if(typeof customers[$c_index].c_dob !='undefined' && customers[$c_index].c_dob!=''){
                        customers[$c_index].c_dob = moment(customers[$c_index].c_dob,'YYYY-MM-DD').format(date_format);
                    }
                }
                var template = wp.template('fat-sb-customer-item-template'),
                    items = $(template(customers)),
                    elm_customers = $('.fat-sb-list-customers tbody');

                $('tr',elm_customers).remove();
                if(customers.length>0){
                    elm_customers.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-customers'));
                }else{
                    RevyMain.showNotFoundMessage(elm_customers,'<tr class="fat-tr-not-found"><td colspan="7">','</td></tr>');
                }
                RevyMain.initCheckAll();
                RevyMain.initPaging(total, page, $('.fat-sb-pagination'));

                $('.fat-item-bt-inline[data-title]','.fat-semantic-container').each(function(){
                    $(this).popup({
                        title : '',
                        content: $(this).attr('data-title'),
                        inline: true
                    });
                });

                if(typeof callback=='function'){
                    callback();
                }

            },
            error: function(){}
        })
    };

    RevyCustomers.processSubmitCustomer = function(self){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var form = $('.fat-customer-form .ui.form'),
                callback = typeof self.attr('data-callback') != 'undefined' ? self.attr('data-callback').split('.') : '',
                data = RevyMain.getFormData(form);

            if(typeof data['c_dob']=='undefined' || data['c_dob']==''){
                data['c_dob'] = $('#c_dob').attr('data-default');
            }
            if(typeof self.attr('data-id') !='undefined'){
                data['c_id'] = self.attr('data-id');
            }
            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_customer',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        self.closest('.ui.modal').modal('hide');
                        RevyMain.showMessage(self.attr('data-success-message'));
                        var item = $('tr[data-id="' + data.c_id +'"]');
                        if(item.length==0){
                            data.c_id = response.result;
                            var template = wp.template('fat-sb-customer-item-template'),
                                item = $(template([data]));
                            $('.fat-tr-not-found','.fat-sb-list-customers').remove();
                            $('.fat-sb-list-customers tbody').append(item);

                            RevyMain.initCheckAll();
                            RevyMain.registerEventProcess(item);
                            $('input.table-check-all','.fat-sb-list-customers').prop("checked", false);

                            $('.fat-item-bt-inline[data-title]',item).each(function(){
                                $(this).popup({
                                    title : '',
                                    content: $(this).attr('data-title'),
                                    inline: true
                                });
                            });

                        }else{
                            if( $('.fat-c-name',item).length > 0){
                                $('.fat-c-name',item).html(data.c_first_name+ ' ' + data.c_last_name);
                                $('.fat-c-phone',item).html(data.c_phone_code.split(',')[0] + ' ' + data.c_phone);
                                $('.fat-c-email',item).html(data.c_email);
                                $('.fat-c-note',item).html(data.c_description);
                                $('.fat-c-dob',item).html(data.c_dob);
                            }
                        }

                        if (callback != '') {
                            var obj = callback.length == 2 ? callback[0] : '',
                                func = callback.length == 2 ? callback[1] : callback[0];
                            if (obj != '') {
                                (typeof window[obj][func] != 'undefined' && window[obj][func] != null) ? window[obj][func](data) : '';
                            } else {
                                (typeof window[func] != 'undefined' && window[func] != null) ? window[func](data) : '';
                            }
                        }

                    }else{
                        if(typeof response.message!='undefined'){
                            RevyMain.showMessage(response.message, 3);
                        }else{
                            RevyMain.showMessage(RevyMain.data.error_message, 2);
                        }
                    }
                },
                error: function(){
                    RevyMain.closeProcess(self);
                    self.closest('.ui.modal').modal('hide');
                    RevyMain.showMessage(RevyMain.data.error_message, 2);
                }
            })
        }
    };

    RevyCustomers.processViewDetail = function(self){
        RevyMain.showProcess(self);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_customer_by_id',
                c_id :  self.attr('data-id')
            }),
            success: function(customer){
                RevyMain.closeProcess(self);
                customer = $.parseJSON(customer);
                if(typeof customer.c_dob !='undefined' && customer.c_dob!=null){
                    var date_format =  RevyMain.getDateFormat();
                    customer.c_dob = moment(customer.c_dob,"YYYY-MM-DD").format(date_format);
                }
                RevyMain.showPopup('fat-sb-customers-template',RevyMain.data.edit_customer,customer,function(){
                    RevyMain.registerEventProcess($('.fat-customer-form'));
                });
            },
            error: function(){
                RevyMain.closeProcess(self);
            }
        })
    };

    RevyCustomers.processDelete = function(self){
        var btDelete = self;
        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title,RevyMain.data.confirm_delete_message,function($result, popup){
            if($result==1){
                var self = $('.fat-sb-bt-confirm.yes',popup),
                    c_ids = [];
                RevyMain.showProcess(self);
                if(btDelete.hasClass('fat-item-bt-inline')){
                    c_ids.push(btDelete.attr('data-id'));
                }else{
                    $('input.check-item[type="checkbox"]', 'table.fat-sb-list-customers').each(function(){
                        if($(this).is(':checked')){
                            c_ids.push($(this).attr('data-id'));
                        }
                    });
                }
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_customer',
                        c_ids: c_ids
                    }),
                    success: function(response){
                        RevyMain.closeProcess(self);
                        popup.modal('hide');
                        try{
                            response = $.parseJSON(response);
                            if(response.result>0){
                                var c_ids_delete = response.ids_delete.split(',');
                                for(var $i=0; $i< c_ids_delete.length; $i++){
                                    $('tr[data-id="'+ c_ids_delete[$i] +'"]','.fat-sb-list-customers').remove();
                                }
                            }
                            if(typeof response.message_success!='undefined' && response.message_success!=''){
                                RevyMain.showMessage(response.message_success);
                                return;
                            }
                            if(typeof response.message_error!='undefined' && response.message_error!=''){
                                setTimeout(function(){
                                    RevyMain.showMessage(response.message_error,2);
                                },300);
                                return;
                            }

                            if(typeof response.message!='undefined' && response.result <0){
                                RevyMain.showMessage(response.message, 3);
                            }
                        }catch(err){
                            RevyMain.showMessage(RevyMain.data.error_message,2);
                        }
                    },
                    error: function(){
                        popup.modal('hide');
                        RevyMain.showMessage(RevyMain.data.error_message,2);
                        RevyMain.closeProcess(self);
                    }
                })
            }
        });
    };

    RevyCustomers.processOrder = function (elm) {
        if(!elm.hasClass('active')){
            var container = elm.closest('.fat-sb-order-wrap');
            RevyCustomers.order_by = container.attr('data-order-by');
            RevyCustomers.order = elm.attr('data-order');
            RevyCustomers.loadCustomer(1);
            $('.fat-sb-order-wrap i.icon.active', '.fat-sb-list-customers').removeClass('active');
            $('i.icon.' + RevyCustomers.order, container).addClass('active');
        }
    };

    RevyCustomers.processShowAddCredit = function(self){
        var btAddCredit = self,
            item_container = self.closest('tr'),
            data = {
                c_id: self.attr('data-id'),
                full_name: $('.fat-c-name', item_container).html(),
                email: $('.fat-c-email', item_container).html()
            };
        RevyMain.showPopup('fat-sb-customer-add-credit-template','',data,function(){
            RevyMain.registerEventProcess($('.fat-customer-add-credit-form'));
        });
    };

    RevyCustomers.processSubmitAddCredit = function(self){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var form = $('.fat-customer-add-credit-form .ui.form'),
                message = self.attr('data-message'),
                data = {
                    c_id: self.attr('data-id'),
                    pk_id: $('#pk_id', form).val(),
                    pko_description: $('#pko_description', form).val()
                };

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'add_credit',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        self.closest('.ui.modal').modal('hide');
                        RevyMain.showMessage(self.attr('data-success-message'));

                    }else{
                        if(typeof response.message!='undefined'){
                            RevyMain.showMessage(response.message, 3);
                        }else{
                            RevyMain.showMessage(RevyMain.data.error_message, 2);
                        }
                    }
                },
                error: function(){
                    RevyMain.closeProcess(self);
                    self.closest('.ui.modal').modal('hide');
                    RevyMain.showMessage(RevyMain.data.error_message, 2);
                }
            })
        }
    };

    $(document).ready(function () {
        if($('.fat-sb-customers-container').length > 0){
            RevyCustomers.init();
        }
    });
})(jQuery);