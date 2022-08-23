"use strict";
var RevyCoupon = {};
(function ($) {
    RevyCoupon.init = function(){
        RevyMain.initCheckAll();
        RevyCoupon.loadCoupon(1);
        RevyMain.registerEventProcess($('.toolbox-action-group'));
        RevyMain.initPopupToolTip();
    };

    /*
    event handler
    */
    RevyCoupon.btAddOnClick = function(){
        RevyMain.showPopup('fat-sb-coupon-template','', [],function(){
            RevyCoupon.initField();
            RevyMain.bindServicesDic($('.fat-sb-apply-services,.fat-sb-exclude-services'));
            RevyMain.registerEventProcess($('.fat-coupon-form'));
        });
    };

    RevyCoupon.codeSearchKeyUp = function(self){
        var search_wrap = self.closest('.ui.input');
        if(self.val().length >=3 || self.val()==''){
            search_wrap.addClass('loading');
            RevyCoupon.loadCoupon(1,function(){
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

    RevyCoupon.closeSearchOnClick = function(self){
        var search_wrap = self.closest('.ui.ui-search');
        $('input',search_wrap).val('');
        $('input',search_wrap).trigger('keyup');
    };

    RevyCoupon.discountOnChange = function(value, text, $choice){
        if(value=='1'){ //percentage
            $('.fat-sb-coupon-amount i','.fat-coupon-form').removeClass('dollar sign');
            $('.fat-sb-coupon-amount i','.fat-coupon-form').addClass('percent');
        }
        if(value=='2'){ //fixed
            $('.fat-sb-coupon-amount i','.fat-coupon-form').removeClass('percent');
            $('.fat-sb-coupon-amount i','.fat-coupon-form').addClass('dollar sign');
        }
    };

    RevyCoupon.initField  = function(){
        if($('#cp_discount_type','.fat-coupon-form').val()=='1'){ //percentage
            $('.fat-sb-coupon-amount i','.fat-coupon-form').removeClass('dollar sign');
            $('.fat-sb-coupon-amount i','.fat-coupon-form').addClass('percent');
        }
        if($('#cp_discount_type','.fat-coupon-form').val()=='2'){ //fixed
            $('.fat-sb-coupon-amount i','.fat-coupon-form').removeClass('percent');
            $('.fat-sb-coupon-amount i','.fat-coupon-form').addClass('dollar sign');
        }

    };

    RevyCoupon.loadCoupon = function(page, callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_coupons',
                cp_code: $('#cp_code','.toolbox-action-group').val(),
                page: typeof page!='undefined' && page!='' ? page: 1
            }),
            success: function(data){
                data = $.parseJSON(data);
                var total = data.total,
                    coupons = data.coupons,
                    date_format =  RevyMain.getDateFormat();

                for(var $c_index = 0; $c_index< coupons.length;$c_index++){
                    if(typeof coupons[$c_index].cp_start_date !='undefined' && coupons[$c_index].cp_start_date!=''){
                        coupons[$c_index].cp_start_date = moment(coupons[$c_index].cp_start_date,'YYYY-MM-DD').format(date_format);

                    }
                    if(typeof coupons[$c_index].cp_expire !='undefined' && coupons[$c_index].cp_expire!=''){
                        coupons[$c_index].cp_expire = moment(coupons[$c_index].cp_expire,'YYYY-MM-DD').format(date_format);
                    }
                }
                var template = wp.template('fat-sb-coupon-item-template'),
                    items = $(template(coupons)),
                    elm_coupons = $('.fat-sb-list-coupons tbody');

                $('tr',elm_coupons).remove();
                if(coupons.length>0){
                    elm_coupons.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-coupons'));
                }else{
                    RevyMain.showNotFoundMessage(elm_coupons,'<tr class="fat-tr-not-found"><td colspan="9">','</td></tr>');
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

    RevyCoupon.processSubmitCoupon = function(self){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var form = $('.fat-coupon-form .ui.form'),
                data = RevyMain.getFormData(form);

            data.cp_start_date = $('#cp_start_date',form).attr('data-date');
            data.cp_expire = $('#cp_expire',form).attr('data-date');

            data.cp_start_date = typeof data.cp_start_date !='undefined' && data.cp_start_date!='' ? data.cp_start_date : RevyMain.data.date_now;
            data.cp_expire = typeof data.cp_expire !='undefined' && data.cp_expire !='' ? data.cp_expire : RevyMain.data.date_now;

            if(typeof self.attr('data-id') !='undefined'){
                data['cp_id'] = self.attr('data-id');
            }
            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_coupon',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        self.closest('.ui.modal').modal('hide');
                        RevyMain.showMessage(self.attr('data-success-message'));
                        var item = $('tr[data-id="' + data.cp_id +'"]');
                        if(item.length==0){
                            data.cp_id = response.result;
                            data.cp_use_count = 0;
                            var template = wp.template('fat-sb-coupon-item-template'),
                                item = $(template([data]));
                            $('.fat-tr-not-found','.fat-sb-list-coupons').remove();
                            $('.fat-sb-list-coupons tbody').append(item);

                            RevyMain.initCheckAll();
                            RevyMain.registerEventProcess(item);

                            $('input.table-check-all','.fat-sb-list-coupons').prop("checked", false);

                            $('.fat-item-bt-inline[data-title]',item).each(function(){
                                $(this).popup({
                                    title : '',
                                    content: $(this).attr('data-title'),
                                    inline: true
                                });
                            });
                        }else{
                            data.cp_discount_type = data.cp_discount_type==1 ? RevyMain.data.percentage_discount : RevyMain.data.fixed_discount;
                            $('.fat-cp-code',item).html(data.cp_code);
                            $('.fat-cp-discount-type',item).html(data.cp_discount_type);
                            $('.fat-cp-amount',item).html(data.cp_amount);
                            $('.fat-cp-start-date',item).html(data.cp_start_date);
                            $('.fat-cp-expire',item).html(data.cp_expire);
                            $('.fat-cp-times-to-use',item).html(data.c_times_use);
                        }
                        if(typeof callback=='function'){
                            callback();
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

    RevyCoupon.processViewDetail = function(self){
        RevyMain.showProcess(self);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_coupon_by_id',
                cp_id :  self.attr('data-id')
            }),
            success: function(coupon){

                coupon = $.parseJSON(coupon);

                var date_format =  RevyMain.getDateFormat();

                if(typeof coupon.cp_start_date !='undefined' && coupon.cp_start_date!=null){
                    coupon.cp_start_date = moment(coupon.cp_start_date,"YYYY-MM-DD").format(date_format);
                }
                if(typeof coupon.cp_expire !='undefined' && coupon.cp_expire!=null){
                    coupon.cp_expire = moment(coupon.cp_expire,"YYYY-MM-DD").format(date_format);
                }

                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'GET',
                    data: ({
                        action: 'get_services_dic'
                    }),
                    success: function (services) {
                        RevyMain.closeProcess(self);
                        coupon.services = $.parseJSON(services);

                        RevyMain.showPopup('fat-sb-coupon-template',RevyMain.data.edit_coupon,coupon,function(){
                            RevyCoupon.initField();
                            RevyMain.registerEventProcess($('.fat-coupon-form'));
                        });

                    },
                    error: function () {
                    }
                });


            },
            error: function(){
                RevyMain.closeProcess(self);
            }
        })
    };

    RevyCoupon.processDelete = function(self){
        var btDelete = self;
        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title,RevyMain.data.confirm_delete_message,function($result, popup){
            if($result==1){
                var self = $('.fat-sb-bt-confirm.yes',popup),
                    cp_ids = [];
                RevyMain.showProcess(self);
                if(btDelete.hasClass('fat-item-bt-inline')){
                    cp_ids.push(btDelete.attr('data-id'));
                }else{
                    $('input.check-item[type="checkbox"]', 'table.fat-sb-list-coupons').each(function(){
                        if($(this).is(':checked')){
                            cp_ids.push($(this).attr('data-id'));
                        }
                    });
                }
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_coupon',
                        cp_ids: cp_ids
                    }),
                    success: function(response){
                        RevyMain.closeProcess(self);
                        popup.modal('hide');
                        try{
                            response = $.parseJSON(response);
                            if(response.result>0){
                                var cp_ids_delete = response.ids_delete;
                                for(var $i=0; $i< cp_ids_delete.length; $i++){
                                    $('tr[data-id="'+ cp_ids_delete[$i] +'"]','.fat-sb-list-coupons').remove();
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

    $(document).ready(function () {
        if($('.fat-sb-coupons-container').length > 0){
            RevyCoupon.init();
        }
    });
})(jQuery);