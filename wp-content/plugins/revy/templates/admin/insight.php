<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 06/19/2020
 * Time: 3:08 PM
 */
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Insight','revy');?></div>
</div>
<div class="fat-sb-insight-container fat-semantic-container fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left date-input input no-border-radius">
                    <?php
                    $start_date = new DateTime();
                    $end_date = new DateTime();
                    $end_date->modify('+6 day');
                    $date_format = get_option('date_format');
                    $locale = get_locale();
                    $locale = explode('_',$locale)[0];
                    ?>
                    <input type="text"  class="date-range-picker"  name="date_insight" id="date_insight" data-auto-update="1" data-onChange="RevyInsight.searchDateOnChange"
                           data-start="<?php echo esc_attr($start_date->format('Y-m-d'));?>" data-end="<?php echo esc_attr($end_date->format('Y-m-d'));?>"
                           data-locale="<?php echo esc_attr($locale);?>"
                           data-start-init="<?php echo date_i18n($date_format,$start_date->format('U'));?>"
                           data-end-init="<?php echo date_i18n($date_format,$end_date->format('U'));?>" >
                </div>

                <div class="fat-checkbox-dropdown-wrap fat-mg-right-10 fat-sb-garage-dic">
                    <select multiple="multiple" name="garage" data-onChange="RevyInsight.sumoSearchOnChange"
                            data-prev-value=""
                            data-placeholder="<?php echo esc_attr__('Select garage','revy'); ?>"
                            data-caption-format="<?php echo esc_attr__('Garage selected','revy'); ?>"
                            data-search-text="<?php echo esc_attr__('Enter garage\'s name','revy'); ?>"
                            id="location" class="SumoUnder fat-sb-sumo-select" tabindex="-1">
                    </select>
                </div>

            </div>
        </div>
        <div class="content">
            <div class="ui grid fat-mg-top-15">
                <div class="row">
                    <div class="twelve wide column">
                        <div class="ui card full-width fat-pd-15">
                            <div id="revenue_chart">
                            </div>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui card full-width fat-pd-15">
                            <h3><?php esc_html_e('Summary','revy');?></h3>
                            <?php
                            $setting = Revy_DB_Setting::instance();
                            $currency = $setting->get_currency_setting();
                            ?>
                            <ul class="fat-booking-summary">
                                <li><span class="label"><?php echo esc_html_e('Pending','revy');?></span> <span class="booking-pending quantity">0</span></li>
                                <li><span class="label"><?php echo esc_html_e('Approved','revy');?></span> <span class="booking-approved quantity">0</span></li>
                                <li><span class="label"><?php echo esc_html_e('Rejected','revy');?></span> <span class="booking-rejected quantity">0</span></li>
                                <li><span class="label"><?php echo esc_html_e('Cancelled','revy');?></span> <span class="booking-cancelled quantity">0</span></li>
                                <li><span class="label"><?php echo esc_html_e('Revenue','revy');?></span>
                                    <span class="booking-revenue quantity" data-currency="<?php echo esc_attr($currency['symbol']);?>">0<?php echo esc_attr($currency['symbol']);?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="twelve wide column">
                        <div class="ui card full-width">
                            <div id="service_employee_chart">

                            </div>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui card full-width">
                            <div id="customer_chart_percent">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>