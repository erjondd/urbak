"use strict";
var RevyDevices = {};
(function ($) {
    RevyDevices.init = function(){
        RevyDevices.loadDevices();
        RevyMain.registerEventProcess($('.fat-sb-devices-container .toolbox-action-group'));
        RevyMain.initPopupToolTip();
    };

    RevyDevices.btAddNewOnClick = function(self){
        RevyMain.showProcess(self);
        RevyMain.showPopup('fat-sb-devices-template','', {rd_active:1},function(){
            RevyMain.closeProcess(self);
            RevyMain.registerEventProcess($('.fat-devices-form'));
        });
    };

    RevyDevices.loadDevices = function(callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_devices'
            }),
            success: function(devices){
                devices = $.parseJSON(devices);

                var template = wp.template('fat-sb-devices-item-template'),
                    items = $(template(devices)),
                    elm_device = $('.fat-sb-list-devices');

                $('.column',elm_device).remove();
                $('.fat-sb-not-found').remove();
                if(devices.length>0){
                    elm_device.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-devices'));

                    $('.fat-item-bt-inline[data-title]','.fat-semantic-container').each(function(){
                        $(this).popup({
                            title : '',
                            content: $(this).attr('data-title'),
                            inline: true
                        });
                    });
                }else{
                    RevyMain.showNotFoundMessage(elm_device);
                }
                if(typeof callback=='function'){
                    callback();
                }
            },
            error: function(){}
        })
    };

    RevyDevices.showPopupDevice = function(elm){
        var rd_id = typeof elm.attr('data-id')!='undefined' ? elm.attr('data-id') : 0,
            popup_title = typeof rd_id !='undefined' ? RevyMain.data.modal_title.edit_device : '';
        RevyMain.showProcess(elm);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'get_device_by_id',
                rd_id: rd_id
            }),
            success: function (response) {
                RevyMain.closeProcess(elm);
                response = $.parseJSON(response);
                RevyMain.showPopup('fat-sb-devices-template', popup_title,response,function(){
                    RevyMain.registerEventProcess($('.fat-devices-form'));
                });
            },
            error: function(){}
        });
    };

    RevyDevices.processSubmitDevice = function(self,callback){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var form = $('.fat-devices-form .ui.form'),
                data = RevyMain.getFormData(form),
                image_url = $('#rd_image_id img').attr('src');

            if(typeof self.attr('data-id') !='undefined'){
                data['rd_id'] = self.attr('data-id');
            }

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_devices',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    self.closest('.ui.modal').modal('hide');

                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        var item = $('.item[data-id="' + data.rd_id + '"]','.fat-sb-list-devices');

                        data.rd_image_url = typeof image_url != 'undefined' ? image_url : '';
                        if(item.length==0){
                            data.rd_id = response.result;
                            var template = wp.template('fat-sb-devices-item-template'),
                                item = $(template([data]));
                            $('.fat-sb-not-found','.fat-sb-list-devices').remove();
                            $('.fat-sb-list-devices').append(item);
                            RevyMain.registerEventProcess(item);

                        }else{
                            $('.fat-device-name',item).html(data.rd_name);
                            $('img', item).attr('src', data.rd_image_url);
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

    RevyDevices.processDelete = function(self){
        var rd_id = self.attr('data-id');
        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title,RevyMain.data.confirm_delete_message,function($result, popup){
            if($result==1){
                var self = $('.fat-sb-bt-confirm.yes',popup);
                RevyMain.showProcess(self);
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_device',
                        rd_id: rd_id
                    }),
                    success: function(response){
                        RevyMain.closeProcess(self);
                        popup.modal('hide');
                        try{
                            response = $.parseJSON(response);
                            if(response.result>0){
                                $('.item[data-id="' + rd_id + '"]','.fat-sb-list-devices').closest('.column').remove();
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

    $(document).ready(function () {
        RevyDevices.init();
    });
})(jQuery);
