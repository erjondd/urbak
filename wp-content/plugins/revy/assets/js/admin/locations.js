"use strict";
var RevyLocations = {};
(function ($) {
    RevyLocations.init = function(){
        RevyLocations.loadLocation();
        RevyMain.registerEventProcess($('.fat-sb-locations-container .toolbox-action-group'));
        RevyMain.initPopupToolTip();
    };

    RevyLocations.initMap = function(){
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

    RevyLocations.nameSearchOnKeyUp = function(self){
        var search_wrap = self.closest('.ui.input');
        if(self.val().length >=3 || self.val()==''){
            search_wrap.addClass('loading');
            RevyLocations.loadLocation(function(){
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

    RevyLocations.closeSearchOnClick = function(self){
        var search_wrap = self.closest('.ui.ui-search');
        $('input',search_wrap).val('');
        $('input',search_wrap).trigger('keyup');
    };

    RevyLocations.btAddNewOnClick = function(self){
        RevyMain.showProcess(self);
        RevyMain.showPopup('fat-sb-locations-template','', [],function(){
            RevyMain.closeProcess(self);
            RevyMain.registerEventProcess($('.fat-location-form'));
            RevyLocations.initMap();
        });
    };

    RevyLocations.loadLocation = function(callback){
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'GET',
            data: ({
                action: 'get_locations',
                loc_name: $('#loc_name_search').val()
            }),
            success: function(locations){
                locations = $.parseJSON(locations);

                var template = wp.template('fat-sb-location-item-template'),
                    items = $(template(locations)),
                    elm_location = $('.fat-sb-list-locations');

                $('.column',elm_location).remove();
                $('.fat-sb-not-found').remove();
                if(locations.length>0){
                    elm_location.append(items);
                    RevyMain.registerEventProcess($('.fat-sb-list-locations'));

                    $('.fat-item-bt-inline[data-title]','.fat-semantic-container').each(function(){
                        $(this).popup({
                            title : '',
                            content: $(this).attr('data-title'),
                            inline: true
                        });
                    });
                }else{
                    RevyMain.showNotFoundMessage(elm_location);
                }
                if(typeof callback=='function'){
                    callback();
                }
            },
            error: function(){}
        })
    };

    RevyLocations.showPopupLocation = function(elm){
        var loc_id = typeof elm.attr('data-id')!='undefined' ? elm.attr('data-id') : 0,
            popup_title = typeof loc_id !='undefined' ? RevyMain.data.modal_title.edit_location : '';
        RevyMain.showProcess(elm);
        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'get_location_by_id',
                loc_id: loc_id
            }),
            success: function (response) {
                RevyMain.closeProcess(elm);
                response = $.parseJSON(response);
                RevyMain.showPopup('fat-sb-locations-template', popup_title,response,function(){
                    RevyMain.registerEventProcess($('.fat-location-form'));
                    RevyLocations.initMap();
                });
            },
            error: function(){}
        });
    };

    RevyLocations.processSubmitLocation = function(self,callback){
        if(RevyMain.isFormValid){
            RevyMain.showProcess(self);
            var form = $('.fat-location-form .ui.form'),
                data = RevyMain.getFormData(form),
                image_url = $('#loc_image_id img').attr('src'),
                location_map = $('#loc_map').val(),
                latitude = '',
                longitude = '';


            if(typeof self.attr('data-id') !='undefined'){
                data['loc_id'] = self.attr('data-id');
            }
            if(typeof location_map!='undefined'){
                location_map = location_map.split(',');
                longitude = location_map[0];
                latitude = typeof location_map[1] !='undefined' ? location_map[1]: '';
            }
            data['loc_latitude'] = latitude;
            data['loc_longitude'] = longitude;

            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'save_location',
                    data: data
                }),
                success: function(response){
                    RevyMain.closeProcess(self);
                    self.closest('.ui.modal').modal('hide');

                    response = $.parseJSON(response);
                    if(response.result >= 0){
                        var item = $('.item[data-id="' + data.loc_id + '"]','.fat-sb-list-locations');

                        data.loc_image_url = typeof image_url != 'undefined' ? image_url : '';
                        if(item.length==0){
                            data.loc_id = response.result;
                            var template = wp.template('fat-sb-location-item-template'),
                                item = $(template([data]));
                            $('.fat-sb-not-found','.fat-sb-list-locations').remove();
                            $('.fat-sb-list-locations').append(item);
                            RevyMain.registerEventProcess(item);

                        }else{
                            $('.fat-loc-name',item).html(data.loc_name);
                            $('.fat-loc-address',item).html(data.loc_address);
                            $('.fat-loc-description',item).html(data.loc_description);
                            $('img', item).attr('src', data.loc_image_url);
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
        }
    };

    RevyLocations.processDelete = function(self){
        var loc_id = self.attr('data-id');
        RevyMain.showConfirmPopup(RevyMain.data.confirm_delete_title,RevyMain.data.confirm_delete_message,function($result, popup){
            if($result==1){
                var self = $('.fat-sb-bt-confirm.yes',popup);
                RevyMain.showProcess(self);
                $.ajax({
                    url: RevyMain.data.ajax_url,
                    type: 'POST',
                    data: ({
                        action: 'delete_location',
                        loc_id: loc_id
                    }),
                    success: function(response){
                        RevyMain.closeProcess(self);
                        popup.modal('hide');
                        try{
                            response = $.parseJSON(response);
                            if(response.result>0){
                                $('.item[data-id="' + loc_id + '"]','.fat-sb-list-locations').closest('.column').remove();
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
        RevyLocations.init();
    });
})(jQuery);