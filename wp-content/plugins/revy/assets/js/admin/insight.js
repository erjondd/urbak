"use strict";
var RevyInsight = {
    revenue_chart: null,
    service_emp_chart: null,
    customer_char: null
};

(function ($) {
    'use strict';

    RevyInsight.init = function () {
        RevyInsight.initField();
        RevyInsight.loadInsight();
        RevyMain.registerOnChange($('.fat-sb-insight-container'));
        RevyMain.bindGarageDic($('.fat-sb-insight-container .fat-sb-garage-dic'));
        RevyMain.initPopupToolTip();
    };

    RevyInsight.initField = function(){
        //date range picker
        if ($.isFunction($.fn.daterangepicker)) {
            var date_format = RevyMain.getDateFormat();

            $('input.date-range-picker').attr('autocomplete', 'off');
            $('input.date-range-picker').each(function () {
                var self = $(this),
                    locale = typeof self.attr('data-locale') !='undefined' && self.attr('data-locale')!='' ? self.attr('data-locale') : '',
                    start_date = self.attr('data-start-init'),
                    end_date = self.attr('data-end-init'),
                    autoUpdate = typeof self.attr('data-auto-update') != 'undefined' && self.attr('data-auto-update') == '1',
                    options = {
                        autoUpdateInput: autoUpdate,
                        autoApply: true,
                        locale: {
                            format: date_format,
                            applyLabel: RevyMain.data.apply_title,
                            cancelLabel: RevyMain.data.cancel_title,
                            fromLabel: RevyMain.data.from_title,
                            toLabel: RevyMain.data.to_title,
                            daysOfWeek: RevyMain.i18n_daysOfWeek(locale),
                            monthNames: RevyMain.i18n_monthName(locale)
                        }
                    };

                if(locale!=''){
                    moment.locale(locale);
                }
                if (typeof start_date != 'undefined' && start_date != '') {
                    options.startDate = RevyMain.moment_i18n(locale, start_date, date_format);
                }
                if (typeof end_date != 'undefined' && end_date != '') {
                    options.endDate = RevyMain.moment_i18n(locale, end_date, date_format);
                }

                self.daterangepicker(options, function (start, end, label) {
                    self.val(label);
                    self.attr('data-start', start.format('YYYY-MM-DD'));
                    self.attr('data-end', end.format('YYYY-MM-DD'));
                });
            });
        }

        //sumo dropdown select
        if ($.isFunction($.fn.SumoSelect)) {
            $('.fat-sb-sumo-select').each(function () {
                var self = $(this);
                self.SumoSelect({
                    search: true,
                    placeholder: self.attr('data-placeholder'),
                    captionFormat: '{0} ' + self.attr('data-caption-format'),
                    captionFormatAllSelected: '{0} ' + self.attr('data-caption-format'),
                    searchText: self.attr('data-search-text') != '' ? self.attr('data-search-text') : 'Search'
                });
            });
        }
    };

    RevyInsight.sumoSearchOnChange = function (self) {
        var sumoContainer = self.closest('.SumoSelect'),
            prev_value = self.attr('data-prev-value'),
            value = self.val();

        value = value != null ? value : '';

        if (value != prev_value) {
            $('.ui.loader', sumoContainer).remove();
            sumoContainer.addClass('fat-loading');
            sumoContainer.append('<div class="ui active tiny inline loader"></div>');
            self.attr('data-prev-value', value);
            RevyInsight.loadInsight(function () {
                $('.ui.loader', sumoContainer).remove();
                sumoContainer.removeClass('fat-loading');
            });
        }
    };

    RevyInsight.searchDateOnChange = function (self) {
        var date_picker = self.closest('.ui.date-input');
        $('.ui.loader', date_picker).remove();
        date_picker.addClass('fat-loading');
        date_picker.append('<div class="ui active tiny inline loader"></div>');
        RevyInsight.loadInsight(function () {
            $('.ui.loader', date_picker).remove();
            date_picker.removeClass('fat-loading');
        });
    };

    RevyInsight.loadInsight = function(callback){
        $.ajax({
            url: revy_data.ajax_url,
            type: 'GET',
            data:{
                action: 'get_insight',
                start_date: $('#date_insight').attr('data-start'),
                end_date: $('#date_insight').attr('data-end'),
                garage: $('#garage').val()
            },
            success: function(response){
                response = $.parseJSON(response);

                var currency = $('.booking-revenue').attr('data-currency');
                $('.booking-pending').text(response.booking_pending);
                $('.booking-approved').text(response.booking_approved);
                $('.booking_rejected').text(response.booking_rejected);
                $('.booking-cancelled').text(response.booking_canceled);
                $('.booking-revenue').text(response.total_revenue + currency);

                RevyInsight.initChart(response);
                if (callback) {
                    callback();
                }
            },
            error: function(){

            }
        })
    };

    RevyInsight.initChart = function(data){
        /* services & employee chart */
        var options = {
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    endingShape: 'rounded',
                    columnWidth: '55%',
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [{
                name: RevyMain.data.insight_employee,
                data: data.service_emp_chart.employees
            }, {
                name: RevyMain.data.insight_services,
                data: data.service_emp_chart.services
            }],
            xaxis: {
                categories: data.service_emp_chart.categories
            },
            yaxis: {
                title: {
                    text: ''
                }
            },
            fill: {
                opacity: 1

            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return  val
                    }
                }
            }
        };
        if(typeof RevyInsight.service_emp_chart!='undefined' && RevyInsight.service_emp_chart!=null){
            RevyInsight.service_emp_chart.destroy();
        }
        RevyInsight.service_emp_chart = new ApexCharts(
            document.querySelector("#service_employee_chart"),
            options
        );
        RevyInsight.service_emp_chart.render();

        /* chart revenue */

        var options = {
            chart: {
                height: 350,
                type: "line",
                stacked: false
            },
            dataLabels: {
                enabled: false
            },
            colors: ["#2185d0"],
            series: [
                {
                    name: RevyMain.data.insight_revenue,
                    data: data.revenue
                }
            ],
            stroke: {
                width: [4, 4]
            },
            plotOptions: {
                bar: {
                    columnWidth: "20%"
                }
            },
            xaxis: {
                categories:data.service_emp_chart.categories,
            },
            yaxis: [
                {
                    axisTicks: {
                        show: true
                    },
                    axisBorder: {
                        show: true,
                    },
                    title: {
                        text: RevyMain.data.insight_revenue
                    }
                },
            ],
            tooltip: {
                shared: false,
                intersect: true,
                x: {
                    show: false
                }
            },
            legend: {
                horizontalAlign: "left",
                offsetX: 40
            }
        };
        if(typeof RevyInsight.revenue_chart!='undefined' && RevyInsight.revenue_chart!=null){
            RevyInsight.revenue_chart.destroy();
        }
        RevyInsight.revenue_chart = new ApexCharts(
            document.querySelector("#revenue_chart"),
            options
        );
        RevyInsight.revenue_chart.render();

        /** init chart percent */
        var options = {
            chart: {
                type: 'donut',
            },
            colors: ["#2185d0","#00FF96"],
            series: [data.new_customer, data.return_customer],
            labels: [RevyMain.data.insight_new_customer, RevyMain.data.insight_return_customer],
        };
        if(data.new_customer == 0 && data.return_customer==0){
            options.colors = ['#808080','#2185d0','#00FF96'];
            options.series = [1,0,0];
            options.labels = ['',RevyMain.data.insight_new_customer, RevyMain.data.insight_return_customer];
            options.dataLabels = {enabled: false};
        }
        if(typeof RevyInsight.customer_char!='undefined' && RevyInsight.customer_char!=null){
            RevyInsight.customer_char.destroy();
        }
        RevyInsight.customer_char = new ApexCharts(
            document.querySelector("#customer_chart_percent"),
            options
        );

        RevyInsight.customer_char.render();
    };

    $(document).ready(function () {
        if($('.fat-sb-insight-container').length > 0){
            RevyInsight.init();
        }
    });
})(jQuery);