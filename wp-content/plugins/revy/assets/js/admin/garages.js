"use strict";
var RevyGarages = {};
(function ($) {
    RevyGarages.init = function(){
        RevyMain.initField($('.fat-semantic-container'));
        RevyGarages.loadGarage(1);
        RevyMain.registerEventProcess($('.fat-sb-garages-container .toolbox-action-group'));
        RevyMain.registerOnClick($('.fat-sb-garages-container .fat-sb-order-wrap'));
        RevyMain.initPopupToolTip();
    };

    RevyGarages.initMap = function(){
        $('.fat-mapbox-wrap').each(function(){
            var container = $(this);
            if(typeof mapboxgl !='undefined'){
                var elm_map = $('.fat-mapbox',container),
                    access_token = elm_map.attr('data-access-token'),
                    latitude = elm_map.attr('data-latitude'),
                    longitude = elm_map.attr('data-longitude'),
                    zoom = elm_map.attr('data-zoom');

                latitude = typeof latitude!='undefined' && latitude!='' ? latitude : -79.4512;
                longitude = typeof longitude!='undefined' && longitude!='' ? longitude : 43.6568;

                mapboxgl.accessToken = access_token;
                var map = new mapboxgl.Map({
                    container: elm_map.attr('id'),
                    style: 'mapbox://styles/mapbox/streets-v11',
                    center: [longitude, latitude],
                    zoom: zoom
                });

                var marker = new mapboxgl.Marker({
                    draggable: true
                }).setLngLat([longitude, latitude]).addTo(map);

                marker.on('dragend', function(){
                    var lngLat = marker.getLngLat();
                    $('input.fat-mapbox-location',container).val(lngLat.lng+',' + lngLat.lat);
                });

                var geocoder = new MapboxGeocoder({
                    accessToken: mapboxgl.accessToken,
                    mapboxgl: mapboxgl,
                    marker: false
                });
                map.addControl(geocoder);

                map.on('load', function() {
                    geocoder.on('result', function(ev) {
                        var coordinates = ev.result.geometry.coordinates;
                        if(coordinates.length ==2){
                            var marker = new mapboxgl.Marker({
                                draggable: true
                            }).setLngLat([coordinates[0], coordinates[1]]).addTo(map);

                            marker.on('dragend', function(){
                                var lngLat = marker.getLngLat();
                                $('input.fat-mapbox-location',container).val(lngLat.lng+',' + lngLat.lat);
                            });

                            $('input.fat-mapbox-location',container).val(coordinates[0] +',' + coordinates[1]);
                        }
                    });
                });
            }
        });
    };

    /*
    event handler
    */
    RevyGarages.searchNameOnKeyUp = function(self){
        var search_wrap = self.closest('.ui.input');
        if(self.val().length >=3 || self.val()==''){
            search_wrap.addClass('loading');
            RevyGarages.loadGarage(1,function(){
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

    RevyGarages.closeSearchOnClick = function(self){
        var search_wrap = self.closest('.ui.ui-search');
        $('input',search_wrap).val('');
        $('input',search_wrap).trigger('keyup');
    };

    RevyGarages.searchDropdownChange = function (self) {
        var dropdown = self.closest('.ui.dropdown');
        dropdown.addClass('loading');
        setTimeout(function () {
            RevyGarages.loadGarage(1,function(){
                dropdown.removeClass('loading');
            });
        }, 300);
    };

    RevyGarages.btAddOnClick = function(elm){
        RevyMain.showProcess(elm);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'get_garage_by_id',
                rg_id: 0
            }),
            success: function (response) {
                RevyMain.closeProcess(elm);
                response = $.parseJSON(response);
                RevyMain.showPopup('fat-sb-garages-template','', response,function(){
                    RevyMain.registerEventProcess($('.fat-garage-form'));
                    RevyGarages.initMap();
                });
            },
            error: function () {
            }
        });

    };

    RevyGarages.loadGarage = function(page, callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_garages',
                rg_name: $('#rg_name','.toolbox-action-group').val(),
                loc_id:$('#loc_id','.toolbox-action-group').val(),
                page: typeof page!='undefined' && page!='' ? page: 1
            }),
            success: function(data){
                data = $.parseJSON(data);
                var total = data.total,
                    garages = data.garages;

                var template = wp.template('fat-sb-garage-item-template'),
                    items = $(template(garages)),
                    elm_garages = $('.fat-sb-list-garages tbody');

                $('tr',elm_garages).remove();
                if(garages.length>0){
                    elm_garages.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-garages'));
                }else{
                    RevyMain.showNotFoundMessage(elm_garages,'<tr class="fat-tr-not-found"><td colspan="7">','</td></tr>');
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

    RevyGarages.processSubmitGarage = function(self){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var rg_id = self.attr('data-id'),
                form = $('.fat-garage-form .ui.form'),
                callback = typeof self.attr('data-callback') != 'undefined' ? self.attr('data-callback').split('.') : '',
                data = RevyMain.getFormData(form),
                rg_map = $('#rg_map').val(),
                latitude = '',
                longitude = '';

            if(typeof rg_id !='undefined' && rg_id !=''){
                data['rg_id'] = self.attr('data-id');
            }

            if(typeof rg_map!='undefined'){
                rg_map = rg_map.split(',');
                longitude = rg_map[0];
                latitude = typeof rg_map[1] !='undefined' ? rg_map[1]: '';
            }
            data['rg_latitude'] = latitude;
            data['rg_longitude'] = longitude;

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_garage',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        self.closest('.ui.modal').modal('hide');
                        RevyMain.showMessage(self.attr('data-success-message'));
                        var item = $('tr[data-id="' + data.rg_id +'"]');
                        if(item.length==0){
                            data.rg_id = response.result;
                            var template = wp.template('fat-sb-garage-item-template'),
                                item = $(template([data]));
                            $('.fat-tr-not-found','.fat-sb-list-garages').remove();
                            $('.fat-sb-list-garages tbody').append(item);
                            $('.fat-rg-address', item).html(data.rg_address);

                            RevyMain.initCheckAll();
                            RevyMain.registerEventProcess(item);
                            $('input.table-check-all','.fat-sb-list-garages').prop("checked", false);

                            $('.fat-item-bt-inline[data-title]',item).each(function(){
                                $(this).popup({
                                    title : '',
                                    content: $(this).attr('data-title'),
                                    inline: true
                                });
                            });

                        }else{
                            if( $('.fat-rg-name',item).length > 0){
                                $('.fat-rg-name',item).html(data.rg_name);
                                $('.fat-rg-address',item).html(data.rg_address);
                                $('.fat-rg-email',item).html(data.rg_email);
                                $('.fat-rg-phone',item).html(data.rg_phone);
                                $('.fat-rg-status',item).html(data.rg_active == 1 ? 'Active' : 'Inactive');
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

    RevyGarages.processViewDetail = function(self){
        var rg_id = self.attr('data-id');
        RevyMain.showProcess(self);
        RevyGarages.processShowPopupDetail(rg_id, RevyMain.data.modal_title.edit_garage, function(){
            RevyMain.closeProcess(self);
        });
    };

    RevyGarages.processShowPopupDetail = function(rg_id, popup_title, callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_garage_by_id',
                rg_id :  rg_id
            }),
            success: function(model){
                model = $.parseJSON(model);
                RevyMain.showPopup('fat-sb-garages-template', popup_title, model,function(){
                    RevyMain.registerEventProcess($('.fat-garage-form'));
                    RevyGarages.initMap();
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

    RevyGarages.processDelete = function(self){
        var btDelete = self,
            rg_id = btDelete.attr('data-id');

        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title,RevyMain.data.confirm_delete_message,function($result, popup){
            if($result==1){
                var self = $('.fat-sb-bt-confirm.yes',popup),
                    rm_ids = [];
                RevyMain.showProcess(self);
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_garage',
                        rg_id: rg_id
                    }),
                    success: function(response){
                        RevyMain.closeProcess(self);
                        popup.modal('hide');
                        try{
                            response = $.parseJSON(response);
                            if(response.result>0){
                                $('tr[data-id="'+ rg_id +'"]','.fat-sb-list-garages').remove();
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

    $(document).ready(function () {
        if($('.fat-sb-garages-container').length > 0){
            RevyGarages.init();
        }
    });
})(jQuery);