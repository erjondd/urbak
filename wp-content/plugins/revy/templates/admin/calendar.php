<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/20/2018
 * Time: 10:42 AM
 */
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Calendar','revy');?></div>
</div>
<div class="fat-sb-calendar-container fat-semantic-container fat-min-height-300 fat-pd-right-15">

    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left date-input input no-border-radius">
                    <?php
                    $start_date = new DateTime();
                    $end_date = new DateTime();
                    $end_date->modify('+7 day');
                    $date_format = get_option('date_format');
                    $locale = get_locale();
                    $locale = explode('_',$locale)[0];
                    ?>
                    <input type="text"  class="date-range-picker"  name="date_of_book" id="date_of_book" data-auto-update="1" data-onChange="RevyCalendar.dateOnChange"
                           data-locale="<?php echo esc_attr($locale);?>"
                           data-start="<?php echo esc_attr($start_date->format('Y-m-d'));?>" data-end="<?php echo esc_attr($end_date->format('Y-m-d'));?>"
                           data-start-init="<?php echo date_i18n($date_format,$start_date->format('U'));?>"
                           data-end-init="<?php echo date_i18n($date_format,$end_date->format('U'));?>" >
                </div>

                <div class="fat-checkbox-dropdown-wrap fat-mg-right-10 fat-sb-garage-dic">
                    <select multiple="multiple" name="garage" data-onChange="RevyCalendar.sumoSearchOnChange"
                            data-prev-value=""
                            data-placeholder="<?php echo esc_attr__('Select garage'); ?>"
                            data-caption-format="<?php echo esc_attr__('Garage selected'); ?>"
                            data-search-text="<?php echo esc_attr__('Enter garage\'s name'); ?>"
                            id="location" class="SumoUnder fat-sb-sumo-select" tabindex="-1">
                    </select>
                </div>

                <div class="fat-checkbox-dropdown-wrap fat-mg-right-10 fat-sb-customer-dic">
                    <select multiple="multiple" name="customers" data-onChange="RevyCalendar.sumoSearchOnChange"
                            data-prev-value=""
                            data-placeholder="<?php echo esc_attr__('Select customer'); ?>"
                            data-caption-format="<?php echo esc_attr__('Customer selected'); ?>"
                            data-search-text="<?php echo esc_attr__('Enter customer\'s name'); ?>"
                            id="customer" class="SumoUnder fat-sb-sumo-select" tabindex="-1">
                    </select>
                </div>

                <div class="fat-checkbox-dropdown-wrap fat-mg-right-10 fat-sb-service-dic">
                    <select multiple="multiple" name="services" data-onChange="RevyCalendar.sumoSearchOnChange"
                            data-prev-value = ""
                            data-placeholder="<?php echo esc_attr__('Select services'); ?>"
                            data-search-text="<?php echo esc_attr__('Enter service\'s name'); ?>"
                            data-caption-format="<?php echo esc_attr__('Services selected'); ?>"
                            id="services" class="SumoUnder fat-sb-sumo-select" tabindex="-1">
                    </select>
                </div>

                <div class="ui floating dropdown labeled icon selection dropdown fat-mg-right-10">
                    <i class="dropdown icon"></i>
                    <input type="hidden" name="b_process_status" id="b_process_status" data-onChange="RevyCalendar.searchStatusChange">
                    <span class="text"><?php echo esc_html__('Select status','revy');?></span>
                    <div class="menu">
                        <div class="item"  data-value="">
                            <div class="ui empty"></div>
                            <?php echo esc_html__('All status','revy');?>
                        </div>
                        <div class="item"  data-value="0">
                            <div class="ui yellow empty circular label"></div>
                            <?php echo esc_html__('Pending','revy');?>
                        </div>
                        <div class="item"  data-value="1">
                            <div class="ui green empty circular label"></div>
                            <?php echo esc_html__('Approved','revy');?>
                        </div>
                        <div class="item" data-value="2">
                            <div class="ui red empty circular label"></div>
                            <?php echo esc_html__('Cancel','revy');?>
                        </div>
                        <div class="item"  data-value="3">
                            <div class="ui empty empty circular label"></div>
                            <?php echo esc_html__('Reject','revy');?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="content">
            <?php $setting = Revy_DB_Setting::instance();
            $setting = $setting->get_setting();?>
            <div class="fat-sb-calendar" id='fat_sb_calendar' data-view="<?php echo (isset($setting['calendar_view']) ? $setting['calendar_view'] : 'month'); ?>"
            data-locale="<?php echo esc_attr($locale);?>">
                <div class="ui active inverted dimmer">
                    <div class="ui text loader"><?php echo esc_html__('Loading','revy');?></div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/html" id="tmpl-fat-sb-popup-calendar-template">
    <div data-popup-id="" class="fat-sb-calendar-popup ui flowing popup top left transition hidden">
        <h4 class="ui header">{{data.customer}}</h4>
        <div class="fat-sb-calendar-time">
           <i class="clock outline icon"></i><span>{{data.time}}</span>
        </div>
        <div class="fat-sb-calendar-model">
            <i class="info icon"></i><span>{{data.model_name}}</span>
        </div>
        <div class="fat-sb-calendar-service">
            <i class="wrench icon"></i>
            <span>
                 <# _.each(data.services, function(item){ #>
                    {{item.s_name}}.
                <# }) #>
            </span>
        </div>
        <div class="fat-sb-calendar-location">
           <i class="map marker alternate icon"></i><span>{{data.garage}}</span>
            <br/>
            <span class="garage-address">{{data.garage_address}}</span>
        </div>
        <div class="fat-sb-calendar-edit fat-text-right">
            <button class="circular ui icon primary button" data-id="{{data.id}}" data-onClick="RevyBooking.showPopupBooking"
                    data-submit-callback="RevyCalendar.addBookingToCalendar">
                <i class="edit outline icon"></i>
            </button>
        </div>
    </div>
</script>