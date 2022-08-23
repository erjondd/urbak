"use strict";
var RevyBrands = {};
(function ($) {
    RevyBrands.init = function(){
        RevyBrands.loadBrands();
        RevyMain.registerEventProcess($('.fat-sb-brands-container .toolbox-action-group'));
        RevyMain.initPopupToolTip();
    };

    RevyBrands.btAddNewOnClick = function(self){
        RevyMain.showProcess(self);
        RevyMain.showPopup('fat-sb-brands-template','', {brand: {}, devices:[]},function(){
            RevyMain.closeProcess(self);
            RevyMain.registerEventProcess($('.fat-brands-form'));
        });
    };

    RevyBrands.loadBrands = function(callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_brands'
            }),
            success: function(brands){
                brands = $.parseJSON(brands);

                var template = wp.template('fat-sb-brands-item-template'),
                    items = $(template(brands)),
                    elm_brand = $('.fat-sb-list-brands');

                $('.column',elm_brand).remove();
                $('.fat-sb-not-found').remove();
                if(brands.length>0){
                    elm_brand.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-brands'));

                    $('.fat-item-bt-inline[data-title]','.fat-semantic-container').each(function(){
                        $(this).popup({
                            title : '',
                            content: $(this).attr('data-title'),
                            inline: true
                        });
                    });
                }else{
                    RevyMain.showNotFoundMessage(elm_brand);
                }
                if(typeof callback=='function'){
                    callback();
                }
            },
            error: function(){}
        })
    };

    RevyBrands.showPopupBrand = function(elm){
        var rb_id = typeof elm.attr('data-id')!='undefined' ? elm.attr('data-id') : 0,
            popup_title = typeof rb_id !='undefined' ? RevyMain.data.modal_title.edit_brand : '';
        RevyMain.showProcess(elm);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'get_brand_by_id',
                rb_id: rb_id
            }),
            success: function (response) {
                RevyMain.closeProcess(elm);
                response = $.parseJSON(response);
                RevyMain.showPopup('fat-sb-brands-template', popup_title,response,function(){
                    RevyMain.registerEventProcess($('.fat-brands-form'));
                });
            },
            error: function(){}
        });
    };

    RevyBrands.processSubmitBrand = function(self,callback){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var form = $('.fat-brands-form .ui.form'),
                data = RevyMain.getFormData(form),
                image_url = $('#rb_image_id img').attr('src');

            if(typeof self.attr('data-id') !='undefined'){
                data['rb_id'] = self.attr('data-id');
            }

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_brands',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    self.closest('.ui.modal').modal('hide');

                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        var item = $('.item[data-id="' + data.rb_id + '"]','.fat-sb-list-brands');

                        data.rb_image_url = typeof image_url != 'undefined' ? image_url : '';
                        if(item.length==0){
                            data.rb_id = response.result;
                            var template = wp.template('fat-sb-brands-item-template'),
                                item = $(template([data]));
                            $('.fat-sb-not-found','.fat-sb-list-brands').remove();
                            $('.fat-sb-list-brands').append(item);
                            RevyMain.registerEventProcess(item);

                        }else{
                            $('.fat-brand-name',item).html(data.rb_name + '(ID:' + data.rb_id + ')');
                            $('img', item).attr('src', data.rb_image_url);
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
                    elm.closest('.ui.modal').modal('hide');
                    RevyMain.showMessage(RevyMain.data.error_message, 2);
                }
            })
        }else{
            console.log('data invalid');
        }
    };

    RevyBrands.processDelete = function(self){
        var rb_id = self.attr('data-id');
        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title,RevyMain.data.confirm_delete_message,function($result, popup){
            if($result==1){
                var self = $('.fat-sb-bt-confirm.yes',popup);
                RevyMain.showProcess(self);
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_brand',
                        rb_id: rb_id
                    }),
                    success: function(response){
                        RevyMain.closeProcess(self);
                        popup.modal('hide');
                        try{
                            response = $.parseJSON(response);
                            if(response.result>0){
                                $('.item[data-id="' + rb_id + '"]','.fat-sb-list-brands').closest('.column').remove();
                            }else{
                                RevyMain.showMessage(response.message, 2);
                            }
                        }catch(err){
                            RevyMain.showMessage(RevyMain.data.error_message,2);
                        }
                    },
                    error: function(){
                        RevyMain.closeProcess(self);
                    }
                })
            }
        });
    };

    RevyBrands.openImport = function (self){
        $('.fat-semantic-container .content.brands').fadeOut(function(){
            $('.fat-semantic-container .content.fat-sb-import-section').fadeIn();
        })
    };

    RevyBrands.closeImport = function (self){
        $('.fat-semantic-container .content.fat-sb-import-section').fadeOut(function(){
            $('.fat-semantic-container .content.brands').fadeIn();
        })
    };

    $(document).ready(function () {
        RevyBrands.init();
    });
})(jQuery);
