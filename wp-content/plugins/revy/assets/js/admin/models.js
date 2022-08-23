"use strict";
var RevyModels = {};
(function ($) {
    RevyModels.init = function(){
        RevyMain.initCheckAll();
        RevyMain.initField($('.fat-semantic-container'));
        RevyModels.loadModel(1);
        RevyMain.registerEventProcess($('.fat-sb-models-container .toolbox-action-group'));
        RevyMain.registerOnClick($('.fat-sb-models-container .fat-sb-order-wrap'));
        RevyMain.initPopupToolTip();
    };

    /*
    event handler
    */
    RevyModels.searchNameOnKeyUp = function(self){
        var search_wrap = self.closest('.ui.input');
        if(self.val().length >=3 || self.val()==''){
            search_wrap.addClass('loading');
            RevyModels.loadModel(1,function(){
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

    RevyModels.closeSearchOnClick = function(self){
        var search_wrap = self.closest('.ui.ui-search');
        $('input',search_wrap).val('');

        $('input',search_wrap).trigger('keyup');
    };

    RevyModels.searchDropdownChange = function (self) {
        var dropdown = self.closest('.ui.dropdown');
        dropdown.addClass('loading');
        setTimeout(function () {
            RevyModels.loadModel(1,function(){
                dropdown.removeClass('loading');
            });
        }, 300);
    };

    RevyModels.btAddOnClick = function(elm){
        RevyMain.showProcess(elm);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'get_model_by_id',
                rm_id: 0
            }),
            success: function (response) {
                RevyMain.closeProcess(elm);
                response = $.parseJSON(response);
                RevyMain.showPopup('fat-sb-models-template','', response,function(){
                    RevyMain.registerEventProcess($('.fat-model-form'));
                });
            },
            error: function () {
            }
        });

    };

    RevyModels.loadModel = function(page, callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_models',
                rm_name: $('#rm_name','.toolbox-action-group').val(),
                rm_device_id:$('#rm_search_device_id','.toolbox-action-group').val(),
                rm_brand_id:$('#rm_search_brand_id','.toolbox-action-group').val(),
                page: typeof page!='undefined' && page!='' ? page: 1
            }),
            success: function(data){
                data = $.parseJSON(data);
                var total = data.total,
                    models = data.models;

                var template = wp.template('fat-sb-model-item-template'),
                    items = $(template(models)),
                    elm_models = $('.fat-sb-list-models tbody');

                $('tr',elm_models).remove();
                if(models.length>0){
                    elm_models.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-models'));
                }else{
                    RevyMain.showNotFoundMessage(elm_models,'<tr class="fat-tr-not-found"><td colspan="7">','</td></tr>');
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

    RevyModels.processSubmitModel = function(self){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var rm_id = self.attr('data-id'),
                form = $('.fat-model-form .ui.form'),
                callback = typeof self.attr('data-callback') != 'undefined' ? self.attr('data-callback').split('.') : '',
                data = RevyMain.getFormData(form),
                device_name = typeof data.rm_device_id !='undefined' && data.rm_device_id!='' ?  $('.rm-device.ui.dropdown').dropdown('get text') : '',
                brand_name = typeof data.rm_brand_id !='undefined' && data.rm_brand_id!='' ?  $('.rm-brand.ui.dropdown').dropdown('get text') : '';

            if(typeof rm_id !='undefined' && rm_id !=''){
                data['rm_id'] = self.attr('data-id');
            }
            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_model',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        self.closest('.ui.modal').modal('hide');
                        RevyMain.showMessage(self.attr('data-success-message'));
                        var item = $('tr[data-id="' + data.rm_id +'"]');
                        if(item.length==0){
                            data.rm_id = response.result;
                            data.rd_name = device_name;
                            data.rb_name = brand_name;
                            var template = wp.template('fat-sb-model-item-template'),
                                item = $(template([data]));
                            $('.fat-tr-not-found','.fat-sb-list-models').remove();
                            $('.fat-sb-list-models tbody').append(item);

                            RevyMain.initCheckAll();
                            RevyMain.registerEventProcess(item);
                            $('input.table-check-all','.fat-sb-list-models').prop("checked", false);

                            $('.fat-item-bt-inline[data-title]',item).each(function(){
                                $(this).popup({
                                    title : '',
                                    content: $(this).attr('data-title'),
                                    inline: true
                                });
                            });

                        }else{
                            if( $('.fat-rm-name',item).length > 0){
                                $('.fat-rm-name',item).html(data.rm_name);
                                $('.fat-rm-device',item).html(device_name);
                                $('.fat-rm-brand',item).html(brand_name);
                                $('.fat-rm-group',item).html(data.rm_group);
                                $('.fat-rm-status',item).html(data.rm_active == 1 ? 'Active' : 'Inactive');
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

    RevyModels.processClone = function(self){
        var rm_id = self.attr('data-id');
        RevyMain.showProcess(self);
        RevyModels.processShowPopupDetail(rm_id, RevyMain.data.modal_title.clone_model, function(){
            RevyMain.closeProcess(self);
            $('.fat-model-form .fat-submit-modal').attr('data-id','');
        });
    };

    RevyModels.processViewDetail = function(self){
        var rm_id = self.attr('data-id');
        RevyMain.showProcess(self);
        RevyModels.processShowPopupDetail(rm_id, RevyMain.data.modal_title.edit_model, function(){
            RevyMain.closeProcess(self);
        });
    };

    RevyModels.processShowPopupDetail = function(rm_id, popup_title, callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_model_by_id',
                rm_id :  rm_id
            }),
            success: function(model){
                model = $.parseJSON(model);
                RevyMain.showPopup('fat-sb-models-template', popup_title, model,function(){
                    RevyMain.registerEventProcess($('.fat-model-form'));
                    if(callback){
                        callback();
                    }
                });
            },
            error: function(){
                if(callback){
                    callback();
                }
            }
        });
    };

    RevyModels.processDelete = function(self){
        var btDelete = self;
        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title,RevyMain.data.confirm_delete_message,function($result, popup){
            if($result==1){
                var self = $('.fat-sb-bt-confirm.yes',popup),
                    rm_ids = [];
                RevyMain.showProcess(self);
                if(btDelete.hasClass('fat-item-bt-inline')){
                    rm_ids.push(btDelete.attr('data-id'));
                }else{
                    $('input.check-item[type="checkbox"]', 'table.fat-sb-list-models').each(function(){
                        if($(this).is(':checked')){
                            rm_ids.push($(this).attr('data-id'));
                        }
                    });
                }
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_model',
                        rm_ids: rm_ids
                    }),
                    success: function(response){
                        RevyMain.closeProcess(self);
                        popup.modal('hide');
                        try{
                            response = $.parseJSON(response);
                            if(response.result>0){
                                var rm_ids_delete = response.ids_delete.split(',');
                                for(var $i=0; $i< rm_ids_delete.length; $i++){
                                    $('tr[data-id="'+ rm_ids_delete[$i] +'"]','.fat-sb-list-models').remove();
                                }
                            }
                            if(response.result > 0 && typeof response.message!='undefined' && response.message!=''){
                                RevyMain.showMessage(response.message);
                                return;
                            }

                            if(response.result <0){
                                RevyMain.showMessage(RevyMain.data.error_message, 3);
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

    RevyModels.openImport = function (self){
        $('.fat-semantic-container .content.models').fadeOut(function(){
            $('.fat-semantic-container .content.fat-sb-import-section').fadeIn();
        })
    };

    RevyModels.closeImport = function (self){
        $('.fat-semantic-container .content.fat-sb-import-section').fadeOut(function(){
            $('.fat-semantic-container .content.models').fadeIn();
        })
    };

    $(document).ready(function () {
        if($('.fat-sb-models-container').length > 0){
            RevyModels.init();
        }
    });
})(jQuery);