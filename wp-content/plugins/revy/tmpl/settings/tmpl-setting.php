<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 5/15/2019
 * Time: 2:04 PM
 */
$currency = Revy_Utils::getCurrency();

$db_setting = Revy_DB_Setting::instance();
$currency_setting = $db_setting->get_currency_setting();
$currency_symbol = isset($currency_setting['symbol']) ? $currency_setting['symbol'] : '$';
?>
<script type="text/html" id="tmpl-fat-sb-setting-general-template">
    <div class="ui modal tiny fat-semantic-container fat-setting-modal">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('General setting','revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Mapbox API Key','revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="bottom left"
                                 data-content="<?php echo esc_attr__('This is key that connect to Mapbox API. Set blank to skip Map','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="mapbox_api_key" id="mapbox_api_key" value="{{data.mapbox_api_key}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Mapbox API Key','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field ">
                        <label for="day_limit"><?php echo esc_html__('Distance near me','fat-services-booking'); ?><span
                                    class="required">*</span>
                        </label>
                        <div class="ui action input number has-button">
                            <button class="ui icon button number-decrease">
                                <i class="minus-icon"></i>
                            </button>
                            <input type="text" name="distance_near_me" data-type="int" data-step="5" data-min="1"
                                   tabindex="3" required
                                   id="distance_near_me" value="{{data.distance_near_me}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="field">
                        <label for="booking_mode"><?php echo esc_html__('Unit','revy'); ?>
                        </label>
                        <div class="ui floating dropdown labeled icon selection dropdown">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="distance_unit" id="distance_unit"
                                   value="{{data.distance_unit}}" tabindex="1">
                            <span class="text"><?php echo esc_html__('Select Unit','revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="miles">
                                    <?php echo esc_html__('Miles','revy'); ?>
                                </div>
                                <div class="item" data-value="kilometers">
                                    <?php echo esc_html__('Kilometers','revy'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="calendar_view"><?php echo esc_html__('Default calendar view','revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('Set up default view for calendar.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui floating dropdown labeled icon selection dropdown">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="calendar_view" id="calendar_view"
                                   value="{{data.calendar_view}}" tabindex="1">
                            <span class="text"><?php echo esc_html__('Select view','revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="month">
                                    <?php echo esc_html__('Month','revy'); ?>
                                </div>
                                <div class="item" data-value="agendaWeek">
                                    <?php echo esc_html__('Week','revy'); ?>
                                </div>
                                <div class="item" data-value="agendaDay">
                                    <?php echo esc_html__('Day','revy'); ?>
                                </div>
                                <div class="item" data-value="listWeek">
                                    <?php echo esc_html__('List','revy'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="b_process_status"><?php echo esc_html__('Default booking status','revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('Set up default booking status when add new booking.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui floating dropdown labeled icon selection dropdown">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="b_process_status" id="b_process_status"
                                   value="{{data.b_process_status}}" tabindex="1">
                            <span class="text"><?php echo esc_html__('Select status','revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="0">
                                    <div class="ui yellow empty circular label"></div>
                                    <?php echo esc_html__('Pending','revy'); ?>
                                </div>
                                <div class="item" data-value="1">
                                    <div class="ui green empty circular label"></div>
                                    <?php echo esc_html__('Approved','revy'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="allow_client_cancel"><?php echo esc_html__('Limit booking','revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('If select Yes, allow each customer to book only 1 time in a "Limited time"','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui floating dropdown  selection ">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="limit_booking" id="limit_booking"
                                   value="{{data.limit_booking}}" tabindex="1">
                            <span class="text"><?php echo esc_html__('Select option','revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="0">
                                    <?php echo esc_html__('No','revy'); ?>
                                </div>
                                <div class="item" data-value="1">
                                    <?php echo esc_html__('Yes','revy'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="day_limit"><?php echo esc_html__('Limited time (minutes)','fat-services-booking'); ?><span
                                    class="required">*</span>
                        </label>
                        <div class="ui action input number has-button">
                            <button class="ui icon button number-decrease">
                                <i class="minus-icon"></i>
                            </button>
                            <input type="text" name="limited_time" data-type="int" data-step="1" data-min="1"
                                   tabindex="3" required
                                   id="limited_time" value="{{data.limited_time}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="allow_client_cancel"><?php echo esc_html__('Allow client cancel booking','revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('If select Yes, client can be cancel booking from booking history.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui floating dropdown  selection ">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="allow_client_cancel" id="allow_client_cancel"
                                   value="{{data.allow_client_cancel}}" tabindex="1">
                            <span class="text"><?php echo esc_html__('Select option','revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="0">
                                    <?php echo esc_html__('No','revy'); ?>
                                </div>
                                <div class="item" data-value="1">
                                    <?php echo esc_html__('Yes','revy'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="day_limit"><?php echo esc_html__('Cancel booking before (hours)','fat-services-booking'); ?><span
                                    class="required">*</span>
                        </label>
                        <div class="ui action input number has-button">
                            <button class="ui icon button number-decrease">
                                <i class="minus-icon"></i>
                            </button>
                            <input type="text" name="cancel_before" data-type="int" data-step="1" data-min="1"
                                   tabindex="3" required
                                   id="cancel_before" value="{{data.cancel_before}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="service_tax"><?php echo esc_html__('Default service tax','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('Set up default value for Tax field in new service form.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui selection dropdown  ">
                            <i class="money bill alternate outline icon"></i>
                            <input type="hidden" name="service_tax" id="service_tax" value="{{data.service_tax}}"
                                   tabindex="2"
                                   required>
                            <i class="dropdown icon"></i>
                            <div class="text"><?php echo esc_html__('Select tax','revy'); ?></div>
                            <div class="menu">
                                <div class="item" data-value="0">0%</div>
                                <div class="item" data-value="5">5%</div>
                                <div class="item" data-value="10">10%</div>
                            </div>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter tax','revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="duration_step"><?php echo esc_html__('Duration step','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('Default is 15 minute, but you can setting from 5 minutes to 60 minutes.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui selection dropdown  ">
                            <i class="clock outline icon"></i>
                            <input type="hidden" name="duration_step" id="duration_step" value="{{data.duration_step}}"
                                   tabindex="2"
                                   required>
                            <i class="dropdown icon"></i>
                            <div class="text"><?php echo esc_html__('Select duration step','revy'); ?></div>
                            <div class="menu">
                                <div class="item" data-value="5">
                                    5 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="10">
                                    10 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="15">
                                    15 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="20">
                                    20 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="25">
                                    25 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="30">
                                    30 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="35">
                                    35 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="40">
                                    40 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="45">
                                    45 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="50">
                                    50 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="55">
                                    55 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="60">
                                    60 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="90">
                                    90 <?php echo esc_html__('minutes','revy'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="time_step"><?php echo esc_html__('Time step','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('Default is 15 minute, but you can setting from 5 minutes to 60 minutes.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui selection dropdown  ">
                            <i class="clock outline icon"></i>
                            <input type="hidden" name="time_step" id="time_step" value="{{data.time_step}}"
                                   tabindex="2"
                                   required>
                            <i class="dropdown icon"></i>
                            <div class="text"><?php echo esc_html__('Select time step','revy'); ?></div>
                            <div class="menu">
                                <div class="item" data-value="5">
                                    5 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="10">
                                    10 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="15">
                                    15 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="20">
                                    20 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="25">
                                    25 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="30">
                                    30 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="35">
                                    35 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="40">
                                    40 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="45">
                                    45 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="50">
                                    50 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="55">
                                    55 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="60">
                                    60 <?php echo esc_html__('minutes','revy'); ?></div>
                                <div class="item" data-value="90">
                                    90 <?php echo esc_html__('minutes','revy'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="day_limit"><?php echo esc_html__('Day limit','revy'); ?><span
                                    class="required">*</span>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('How far in the future the clients can book.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui action input number has-button">
                            <button class="ui icon button number-decrease">
                                <i class="minus-icon"></i>
                            </button>
                            <input type="text" name="day_limit" data-type="int" data-step="1" data-min="1"
                                   tabindex="3" required
                                   id="day_limit" value="{{data.day_limit}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="field ">
                        <label for="item_per_page"><?php echo esc_html__('Default items per page','revy'); ?>
                            <span class="required">*</span>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('Use to set up paging for list.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui action input number has-button">
                            <button class="ui icon button number-decrease">
                                <i class="minus-icon"></i>
                            </button>
                            <input type="text" name="item_per_page" data-type="int" data-step="1" data-min="5" required
                                   tabindex="4" id="item_per_page" value="{{data.item_per_page}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="item_per_page"><?php echo esc_html__('Default phone country code','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is the phone code shown by default in the booking form.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui fluid search selection dropdown phone-code">
                            <input type="hidden" name="default_phone_code" id="default_phone_code" autocomplete="nope" value="{{data.default_phone_code}}">
                            <i class="dropdown icon"></i>
                            <div class="default text"></div>
                            <div class="menu">
                                <?php
                                $phoneCode = Revy_Utils::getPhoneCountry();
                                foreach($phoneCode as $pc){
                                    $pc = explode(',',$pc);?>
                                    <div class="item"  data-value="<?php echo esc_attr($pc[1].','.$pc[2]);?>"><i class="<?php echo esc_attr($pc[2]);?> flag"></i><?php echo esc_html($pc[0]);?><span> (<?php echo esc_html($pc[1]);?>)</span></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Device step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_device_title" value="{{data.step_device_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Device step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Device step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_device_subtitle" value="{{data.step_device_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Device step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="brand"><?php echo esc_html__('Brand step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="brand" id="step_brand_title" value="{{data.step_brand_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Brand step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="brand_subtitle"><?php echo esc_html__('Brand step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="brand_subtitle" id="step_brand_subtitle" value="{{data.step_brand_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Brand step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="model"><?php echo esc_html__('Model step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model" id="step_model_title" value="{{data.step_model_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Model step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="model_subtitle"><?php echo esc_html__('Model step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_subtitle" id="step_model_subtitle" value="{{data.step_model_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Model step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Service step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_service_title" value="{{data.step_service_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Service step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Service step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_service_subtitle" value="{{data.step_service_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Service step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Delivery method step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_delivery_title" value="{{data.step_delivery_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Delivery step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Delivery method step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_delivery_subtitle" value="{{data.step_delivery_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Delivery step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Location step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_location_title" value="{{data.step_location_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Location step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Location step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_location_subtitle" value="{{data.step_location_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Location step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Garage step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_garage_title" value="{{data.step_garage_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Garage step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Garage step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_garage_subtitle" value="{{data.step_garage_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Garage step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Appointment step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_schedule_title" value="{{data.step_schedule_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Appointment step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Appointment step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_schedule_subtitle" value="{{data.step_schedule_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Appointment step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Completed step title','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_booked_title" value="{{data.step_booked_title}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Completed step title','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Completed step subtitle','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="step_booked_subtitle" value="{{data.step_booked_subtitle}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Completed step subtitle','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Device label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is label of device what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="device_label" id="device_label" value="{{data.device_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Device label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Brand label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is label of brand what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="brand_label" id="brand_label" value="{{data.brand_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Brand label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Model label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is model of brand what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="model_label" id="model_label" value="{{data.model_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Model label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Service label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is label of service what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="service_label" id="service_label" value="{{data.service_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Service label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Garage label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is label of garage what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="garage_label" id="garage_label" value="{{data.garage_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Garage label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Payment method label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is label of payment method what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="payment_method_label" id="payment_method_label"
                                   value="{{data.payment_method_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Payment method label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one field">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Appointment time label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is label of appointment what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="appointment_time_label" id="appointment_time_label"
                                   value="{{data.appointment_time_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Appointment time label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one field">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Tax label','revy'); ?>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="tax_label" id="tax_label"
                                   value="{{data.tax_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Tax label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one field">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Total cost label','revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('This is label of total cost what show up in booking form at frontend.','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="text" name="total_cost_label" id="total_cost_label"
                                   value="{{data.total_cost_label}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Total cost label','revy'); ?>">
                        </div>
                    </div>
                </div>

                <div class="one inline fields">
                    <label for="service_available"><?php echo esc_html__('Enable','revy'); ?>
                        <div class="ui icon ui-tooltip"
                             data-content="<?php echo esc_attr__('In some case, it have conflict between default modal popup and date time picker. With this case, you can enable use default modal popup or date time picker of theme','revy'); ?>">
                            <i class="question circle icon"></i>
                        </div>
                    </label>
                    <div class="field">
                        <div class="ui  checkbox">
                            <# if(data.enable_modal_popup==1){ #>
                            <input type="checkbox" name="enable_modal_popup" id="enable_modal_popup" value="1"
                                   checked="checked"
                                   tabindex="5">
                            <# }else{ #>
                            <input type="checkbox" name="enable_modal_popup" id="enable_modal_popup" value="1" tabindex="10">
                            <# } #>
                            <label><?php echo esc_html__('Modal popup','revy'); ?></label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui  checkbox">
                            <# if(data.enable_datetime_picker==1){ #>
                            <input type="checkbox" name="enable_datetime_picker" id="enable_datetime_picker" value="1"
                                   checked="checked"
                                   tabindex="6">
                            <# }else{ #>
                            <input type="checkbox" name="enable_datetime_picker" id="enable_datetime_picker" value="1" tabindex="6">
                            <# } #>
                            <label><?php echo esc_html__('Datetime picker','revy'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="one inline fields">
                    <label for="service_available"><?php echo esc_html__('Disable scroll animation','revy'); ?>
                        <div class="ui icon ui-tooltip"
                             data-content="<?php echo esc_attr__('Turn off scroll to top animation','revy'); ?>">
                            <i class="question circle icon"></i>
                        </div>
                    </label>
                    <div class="field">
                        <div class="ui  checkbox">
                            <# if(data.disable_scroll_top==1){ #>
                            <input type="checkbox" name="disable_scroll_top" id="disable_scroll_top" value="1"
                                   checked="checked"
                                   tabindex="5">
                            <# }else{ #>
                            <input type="checkbox" name="disable_scroll_top" id="disable_scroll_top" value="1" tabindex="10">
                            <# } #>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel','revy'); ?>
            </button>

            <button class="ui blue button fat-submit-modal"
                    data-onClick="RevySetting.submitOnClick"
                    data-success-message="<?php echo esc_attr__('Setting has been saved','revy'); ?>">
                <i class="save outline icon"></i>
                <?php echo esc_html__('Save','revy'); ?>
            </button>

        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-setting-company-template">
    <div class="ui modal tiny fat-semantic-container fat-setting-modal">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Company setting', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Name', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="company_name" id="company_name" value="{{data.company_name}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Company name', 'revy'); ?>"
                                   required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter company name', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Address', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="company_address" id="company_address"
                                   value="{{data.company_address}}" autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Address', 'revy'); ?>" required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter address', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="phone"><?php echo esc_html__('Phone', 'revy'); ?></label>
                        <div class="ui left icon input">
                            <input type="text" name="company_phone" id="company_phone" autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Phone', 'revy'); ?>"
                                   value="{{data.company_phone}}">
                            <i class="phone volume icon"></i>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="email"><?php echo esc_html__('Email', 'revy'); ?> <span
                                    class="required"> *</span></label>
                        <div class="ui left icon input">
                            <input type="email" name="company_email" id="company_email" value="{{data.company_email}}"
                                   autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Email', 'revy'); ?>" required>
                            <i class="envelope outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter email', 'revy'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel', 'revy'); ?>
            </button>

            <button class="ui blue button fat-submit-modal"
                    data-onClick="RevySetting.submitOnClick"
                    data-success-message="<?php echo esc_attr__('Setting has been saved', 'revy'); ?>">
                <i class="save outline icon"></i>
                <?php echo esc_html__('Save', 'revy'); ?>
            </button>

        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-setting-notification-template">
    <div class="ui modal tiny fat-semantic-container fat-setting-modal">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Notification setting', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="one fields">
                    <div class="field">
                        <label for="mailer"><?php echo esc_html__('Mailer', 'revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('Set up mail server which handler all outgoing email from your website.', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui floating dropdown icon selection dropdown">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="mailer" id="mailer" value="{{data.mailer}}" tabindex="1"
                                   data-onChange="RevySetting.dependFieldOnChange">
                            <span class="text"><?php echo esc_html__('Select mail server', 'revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="default">
                                    <?php echo esc_html__('Default (use mail server from your hosting) ', 'revy'); ?>
                                </div>
                                <div class="item" data-value="smtp">
                                    <?php echo esc_html__('SMTP', 'revy'); ?>
                                </div>
                                <div class="item" data-value="postSMTP">
                                    <?php echo esc_html__('Use config via Post SMTP plugin', 'revy'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="two fields" data-depend="mailer" data-depend-value="smtp" style="display: none;">
                    <div class="field">
                        <label for="smtp_host"><?php echo esc_html__('SMTP Host', 'revy'); ?></label>
                        <div class="ui left input ">
                            <input type="text" name="smtp_host" id="smtp_host" value="{{data.smtp_host}}"
                                   autocomplete="nope" tabindex="2"
                                   placeholder="<?php echo esc_attr__('SMTP Host', 'revy'); ?>">
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter SMTP Host', 'revy'); ?>
                        </div>
                    </div>

                    <div class="field">
                        <label for="smtp_port"><?php echo esc_html__('SMTP Port', 'revy'); ?></label>
                        <div class="ui left  input ">
                            <input type="text" name="smtp_port" id="smtp_port" value="{{data.smtp_port}}"
                                   autocomplete="nope" tabindex="3"
                                   placeholder="<?php echo esc_attr__('SMTP Port', 'revy'); ?>">
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter SMTP Port', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one inline fields" data-depend="mailer" data-depend-value="smtp" style="display: none">
                    <label for="smtp_encryption"><?php echo esc_html__('Encryption', 'revy'); ?></label>
                    <div class="field">
                        <div class="ui radio checkbox">
                            <# if(data.smtp_encryption=='none'){ #>
                            <input type="radio" name="smtp_encryption" id="smtp_encryption" value="none"
                                   checked="checked"
                                   tabindex="4">
                            <# }else{ #>
                            <input type="radio" name="smtp_encryption" id="smtp_encryption" value="none" tabindex="4">
                            <# } #>
                            <label><?php echo esc_html__('None', 'revy'); ?></label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui radio checkbox">
                            <# if(data.smtp_encryption=='tls'){ #>
                            <input type="radio" name="smtp_encryption" id="smtp_encryption" value="tls"
                                   checked="checked"
                                   tabindex="5">
                            <# }else{ #>
                            <input type="radio" name="smtp_encryption" id="smtp_encryption" value="tls" tabindex="5">
                            <# } #>
                            <label><?php echo esc_html__('TLS', 'revy'); ?></label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui radio checkbox">
                            <# if(data.smtp_encryption=='ssl'){ #>
                            <input type="radio" name="smtp_encryption" id="smtp_encryption" value="ssl"
                                   checked="checked"
                                   tabindex="6">
                            <# }else{ #>
                            <input type="radio" name="smtp_encryption" id="smtp_encryption" value="ssl" tabindex="6">
                            <# } #>
                            <label><?php echo esc_html__('SSL', 'revy'); ?></label>
                        </div>
                    </div>
                </div>


                <div class="two fields" data-depend="mailer" data-depend-value="smtp" style="display: none;">
                    <div class="field">
                        <label for="smtp_username"><?php echo esc_html__('SMTP Username', 'revy'); ?></label>
                        <div class="ui left input ">
                            <input type="text" name="smtp_username" id="smtp_username" value="{{data.smtp_username}}"
                                   autocomplete="off" tabindex="7"
                                   placeholder="<?php echo esc_attr__('SMTP Username', 'revy'); ?>">
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter SMTP Username', 'revy'); ?>
                        </div>
                    </div>

                    <div class="field">
                        <label for="smtp_password"><?php echo esc_html__('SMTP Password', 'revy'); ?></label>
                        <div class="ui left input ">
                            <input type="password" name="smtp_password" id="smtp_password"
                                   data-onChange="RevySetting.passwordOnChange"
                                   data-value="{{data.smtp_password}}"
                                   value="{{data.smtp_password}}" autocomplete="new-password" tabindex="7"
                                   placeholder="<?php echo esc_attr__('SMTP Password', 'revy'); ?>">
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter SMTP Password', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="send_from_name"><?php echo esc_html__('Send from mail', 'revy'); ?>
                            <span
                                    class="required"> *</span></label>
                        <div class="ui left input ">
                            <input type="text" name="send_from_name" id="send_from_name" value="{{data.send_from_name}}"
                                   autocomplete="nope" tabindex="8"
                                   placeholder="<?php echo esc_attr__('From mail', 'revy'); ?>"
                                   required>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter from email address', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="send_from_name"><?php echo esc_html__('Send from name', 'revy'); ?>
                            <span
                                    class="required"> *</span></label>
                        <div class="ui left input ">
                            <input type="text" name="send_from_name_label" id="send_from_name_label" value="{{data.send_from_name_label}}"
                                   autocomplete="nope" tabindex="8"
                                   placeholder="<?php echo esc_attr__('From name', 'revy'); ?>"
                                   required>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter from name', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="employee_email"><?php echo esc_html__('Employee email', 'revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('Notice will be sent to this email when the customer creates an appointment', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui left input ">
                            <input type="email" name="employee_email" id="employee_email" value="{{data.employee_email}}" tabindex="9"
                                   autocomplete="nope">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="cc_to"><?php echo esc_html__('CC to', 'revy'); ?></label>
                        <div class="ui left input ">
                            <input type="email" name="cc_to" id="cc_to" value="{{data.cc_to}}" tabindex="9"
                                   autocomplete="nope">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="bcc_to"><?php echo esc_html__('BCC to', 'revy'); ?></label>
                        <div class="ui left input ">
                            <input type="email" name="bcc_to" id="bcc_to" tabindex="10" value="{{data.bcc_to}}"
                                   autocomplete="nope">
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <a href="javascript:" class="fat-show-send-mail"
                           data-open="<?php echo esc_attr__('Test send mail', 'revy'); ?>"
                           data-close="<?php echo esc_attr__('Close test send mail', 'revy'); ?>"><?php echo esc_html__('Test send mail', 'revy'); ?></a>
                    </div>
                </div>
                <div class="one fields fat-test-send-mail-wrap fat-sb-hidden">
                    <div class="field">
                        <div class="ui left input ">
                            <input type="email" name="send_to" id="send_to" tabindex="11" autocomplete="nope"
                                   placeholder="Send mail to">
                        </div>
                        <button class="ui icon button" data-onClick="RevySetting.sendMailOnClick"
                                data-invalid-message="<?php echo esc_attr__('Please input valid email', 'revy'); ?>">
                            <?php echo esc_html__('Send mail', 'revy'); ?>
                        </button>
                    </div>
                </div>


            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel', 'revy'); ?>
            </button>

            <button class="ui blue button fat-submit-modal"
                    data-onClick="RevySetting.submitOnClick"
                    data-success-message="<?php echo esc_attr__('Setting has been saved', 'revy'); ?>">
                <i class="save outline icon"></i>
                <?php echo esc_html__('Save', 'revy'); ?>
            </button>

        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-setting-delivery-template">
    <div class="ui modal tiny fat-semantic-container fat-setting-modal fat-delivery-setting">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Delivery setting', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="one field">
                    <div class="ui toggle checkbox">
                        <# if(data.enable_fix_at_home==1){ #>
                        <input type="checkbox" name="enable_fix_at_home" id="enable_fix_at_home" value="1"
                               checked tabindex="4">
                        <# }else{ #>
                        <input type="checkbox" name="enable_fix_at_home" id="enable_fix_at_home" value="1"
                               tabindex="4">
                        <# } #>
                        <label><?php echo esc_html__('Enable fix at home', 'revy'); ?>
                        </label>
                    </div>

                </div>

                <div class="one fields" data-depend="enable_fix_at_home" data-depend-value="1" style="display: none;">
                    <div class="ui image-field " id="fix_at_home_img_id" data-image-id="{{data.fix_at_home_img_id}}"
                         data-image-url="{{data.fix_at_home_img_url}}">
                    </div>
                    <label for="fix_at_home_img_id" class="text-center"><?php echo esc_html__('Fix at home image','rp-booking'); ?>
                    </label>
                </div>

                <div class="one field">
                    <div class="ui toggle checkbox">
                        <# if(data.enable_carry_in==1){ #>
                        <input type="checkbox" name="enable_carry_in" id="enable_carry_in" value="1"
                               checked tabindex="4">
                        <# }else{ #>
                        <input type="checkbox" name="enable_carry_in" id="enable_carry_in" value="1"
                               tabindex="4">
                        <# } #>
                        <label><?php echo esc_html__('Enable Carry-In/Curbside', 'revy'); ?>
                        </label>
                    </div>

                </div>

                <div class="one fields" data-depend="enable_carry_in" data-depend-value="1" style="display: none;">
                    <div class="ui image-field " id="carry_in_img_id" data-image-id="{{data.carry_in_img_id}}"
                         data-image-url="{{data.carry_in_img_url}}">
                    </div>
                    <label for="carry_in_img_id" class="text-center"><?php echo esc_html__('Carry-In image','rp-booking'); ?>
                    </label>
                </div>

                <div class="one field">
                    <div class="ui toggle checkbox">
                        <# if(data.enable_mail_in==1){ #>
                        <input type="checkbox" name="enable_mail_in" id="enable_mail_in" value="1"
                               checked tabindex="4">
                        <# }else{ #>
                        <input type="checkbox" name="enable_mail_in" id="enable_mail_in" value="1"
                               tabindex="4">
                        <# } #>
                        <label><?php echo esc_html__('Enable Mail-In Delivery', 'revy'); ?>
                        </label>
                    </div>

                </div>

                <div class="one fields" data-depend="enable_mail_in" data-depend-value="1" style="display: none;">
                    <div class="ui image-field " id="mail_in_img_id" data-image-id="{{data.mail_in_img_id}}"
                         data-image-url="{{data.mail_in_img_url}}">
                    </div>
                    <label for="mail_in_img_id" class="text-center"><?php echo esc_html__('Carry-In image','rp-booking'); ?>
                    </label>
                </div>


            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel', 'revy'); ?>
            </button>

            <button class="ui blue button fat-submit-modal"
                    data-onClick="RevySetting.submitOnClick"
                    data-success-message="<?php echo esc_attr__('Setting has been saved', 'revy'); ?>">
                <i class="save outline icon"></i>
                <?php echo esc_html__('Save', 'revy'); ?>
            </button>

        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-setting-payment-template">
    <div class="ui modal tiny fat-semantic-container fat-setting-modal fat-setting-payment">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Payment setting', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">

                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.hide_price==1){ #>
                            <input type="checkbox" name="hide_price" id="hide_price" value="1"
                                   checked tabindex="4">
                            <# }else{ #>
                            <input type="checkbox" name="hide_price" id="hide_price" value="1"
                                   tabindex="4">
                            <# } #>
                            <label><?php echo esc_html__('Hide price for service', 'revy'); ?>
                                <div class="fat-field-description"><?php esc_html_e('If you select this, the plugin doesn\'t use price for service  and hide the payment method in the booking form ','revy');?></div>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="one fields" >
                    <div class="field ">
                        <label for="item_per_page"><?php echo esc_html__('Number of decimals','revy'); ?>
                            <span class="required">*</span>
                            <div class="ui icon ui-tooltip" data-position="bottom center"
                                 data-content="<?php echo esc_attr__('Specify the number of decimals. Ex: if price is 510 and currency decimal is 2, the price displayed will be 510.00','revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui action input number has-button">
                            <button class="ui icon button number-decrease">
                                <i class="minus-icon"></i>
                            </button>
                            <input type="text" name="number_of_decimals" data-type="int" data-step="1" data-min="0" required
                                   tabindex="4" id="number_of_decimals" value="{{data.number_of_decimals}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="mailer"><?php echo esc_html__('Currency', 'revy'); ?></label>
                        <div class="ui floating dropdown icon search selection dropdown fat-sb-currency-dic">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="currency" id="currency" value="{{data.currency}}" tabindex="1">
                            <span class="text"><?php echo esc_html__('Select currency', 'revy'); ?></span>
                            <div class="menu">
                                <div class="ui icon search input">
                                    <i class="search icon"></i>
                                    <input type="text"
                                           placeholder="<?php echo esc_attr__('Search currency...', 'revy'); ?> ">
                                </div>
                                <div class="scrolling menu">
                                    <?php foreach ($currency as $c) { ?>
                                        <div class="item" data-value="<?php echo esc_attr($c['code']); ?>">
                                            <span class="currency-name"><?php echo esc_html($c['name']); ?></span>
                                            <span class="currency-symbol"><?php echo esc_html($c['symbol']); ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="one fields">
                    <div class="field">
                        <label for="mailer"><?php echo esc_html__('Price symbol position', 'revy'); ?></label>
                        <div class="ui floating dropdown icon selection dropdown">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="symbol_position" id="symbol_position"
                                   value="{{data.symbol_position}}" tabindex="2">
                            <span class="text"><?php echo esc_html__('Select currency', 'revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="before">
                                    <?php echo esc_html__('Before ', 'revy').$currency_symbol.'50'; ?>
                                </div>
                                <div class="item" data-value="after">
                                    <?php echo esc_html__('After ', 'revy').'50'.$currency_symbol; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.hide_payment==1){ #>
                            <input type="checkbox" name="hide_payment" id="hide_payment" value="1"
                                   checked tabindex="4">
                            <# }else{ #>
                            <input type="checkbox" name="hide_payment" id="hide_payment" value="1"
                                   tabindex="4">
                            <# } #>
                            <label><?php echo esc_html__('Hide payment method', 'revy'); ?>
                                <div class="fat-field-description"><?php esc_html_e('If you select this, the payment method section on booking form will be hide','revy');?></div>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="default_payment_method"><?php echo esc_html__('Default payment method', 'revy'); ?></label>
                        <div class="ui floating dropdown icon selection dropdown fat-sb-payment-method-default">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="default_payment_method" id="default_payment_method"
                                   value="{{data.default_payment_method}}" tabindex="3">
                            <span class="text"><?php echo esc_html__('Select payment method', 'revy'); ?></span>
                            <div class="menu">
                                <div class="item" data-value="onsite">
                                    <?php echo esc_html__('Onsite payment', 'revy'); ?>
                                </div>

                                <div class="item" data-value="paypal">
                                    <?php echo esc_html__('Paypal', 'revy'); ?>
                                </div>

                                <div class="item" data-value="stripe">
                                    <?php echo esc_html__('Stripe', 'revy'); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.onsite_enable==1){ #>
                            <input type="checkbox" name="onsite_enable" id="onsite_enable" value="1"
                                   data-onChange="RevySetting.dependFieldOnChange"
                                   checked tabindex="4">
                            <# }else{ #>
                            <input type="checkbox" name="onsite_enable" id="onsite_enable" value="1"
                                   data-onChange="RevySetting.dependFieldOnChange"
                                   tabindex="4">
                            <# } #>
                            <label><?php echo esc_html('Onsite payment'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.paypal_enable==1){ #>
                            <input type="checkbox" name="paypal_enable" id="paypal_enable" value="1"
                                   data-onChange="RevySetting.dependFieldOnChange"
                                   checked tabindex="4">
                            <# }else{ #>
                            <input type="checkbox" name="paypal_enable" id="paypal_enable" value="1"
                                   data-onChange="RevySetting.dependFieldOnChange"
                                   tabindex="4">
                            <# } #>
                            <label><?php echo esc_html('Paypal'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="fat-section-wrap" data-depend="paypal_enable" data-depend-value="1" style="display: none;">
                    <div class="one fields">
                        <div class="field">
                            <label for="paypal_sandbox"><?php echo esc_html__('Paypal Mode', 'revy'); ?></label>
                            <div class="ui floating dropdown icon selection dropdown">
                                <i class="dropdown icon"></i>
                                <input type="hidden" name="paypal_sandbox" id="paypal_sandbox"
                                       value="{{data.paypal_sandbox}}" tabindex="5">
                                <span class="text"><?php echo esc_html__('Select mode', 'revy'); ?></span>
                                <div class="menu">
                                    <div class="item" data-value="test">
                                        <?php echo esc_html__('Sandbox mode', 'revy'); ?>
                                    </div>
                                    <div class="item" data-value="live">
                                        <?php echo esc_html__('Live mode', 'revy'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="one fields">
                        <div class="field ">
                            <label for="paypal_client_id"><?php echo esc_html__('Client ID', 'revy'); ?></label>
                            <div class="ui left input ">
                                <input type="text" name="paypal_client_id" id="paypal_client_id"
                                       value="{{data.paypal_client_id}}" tabindex="6"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="one fields">
                        <div class="field ">
                            <label for="paypal_secret"><?php echo esc_html__('Secret', 'revy'); ?></label>
                            <div class="ui left input ">
                                <input type="text" name="paypal_secret" id="paypal_secret"
                                       value="{{data.paypal_secret}}" tabindex="7"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields" data-depend="paypal_enable" data-depend-value="1" style="display: none">
                    <div class="field">
                        <label for="success_page"><?php echo esc_html__('Success page', 'revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('The page will be opened when payment success', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui bottom left pointing dropdown search icon selection dropdown">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="success_page" id="success_page" value="{{data.success_page}}"
                                   tabindex="17">
                            <span class="text"><?php echo esc_html__('Select success page', 'revy'); ?></span>
                            <div class="menu">
                                <div class="ui icon search input">
                                    <i class="search icon"></i>
                                    <input type="text" tabindex="17"
                                           placeholder="<?php echo esc_attr__('Fill page title...', 'revy'); ?>">
                                </div>
                                <div class="scrolling menu">
                                    <?php $pages = get_pages(array('post_status' => 'publish'));
                                    foreach ($pages as $page) { ?>
                                        <div class="item" data-value="<?php echo esc_attr($page->ID); ?>">
                                            <?php echo esc_html($page->post_title); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields" data-depend="paypal_enable" data-depend-value="1" style="display: none">
                    <div class="field">
                        <label for="error_page">
                            <?php echo esc_html__('Error page', 'revy'); ?>
                            <div class="ui icon ui-tooltip" data-position="right center"
                                 data-content="<?php echo esc_attr__('The page will be opened when payment fail', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui floating dropdown icon selection dropdown">
                            <i class="dropdown icon"></i>
                            <input type="hidden" name="error_page" id="error_page" value="{{data.error_page}}"
                                   tabindex="18">
                            <span class="text"><?php echo esc_html__('Select error page', 'revy'); ?></span>
                            <div class="menu">
                                <?php foreach ($pages as $page) { ?>
                                    <div class="item" data-value="<?php echo esc_attr($page->ID); ?>">
                                        <?php echo esc_html($page->post_title); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.stripe_enable==1){ #>
                            <input type="checkbox" name="stripe_enable" id="stripe_enable" value="1"
                                   data-onChange="RevySetting.dependFieldOnChange"
                                   checked tabindex="8">
                            <# }else{ #>
                            <input type="checkbox" name="stripe_enable" id="stripe_enable" value="1"
                                   data-onChange="RevySetting.dependFieldOnChange"
                                   tabindex="8">
                            <# } #>
                            <label><?php echo esc_html('Stripe'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="fat-section-wrap" data-depend="stripe_enable" data-depend-value="1" style="display: none;">
                    <div class="one fields">
                        <div class="field">
                            <label for="stripe_sandbox"><?php echo esc_html__('Stripe Mode', 'revy'); ?></label>
                            <div class="ui floating dropdown icon selection dropdown">
                                <i class="dropdown icon"></i>
                                <input type="hidden" name="stripe_sandbox" id="stripe_sandbox"
                                       value="{{data.stripe_sandbox}}" tabindex="9">
                                <span class="text"><?php echo esc_html__('Select mode', 'revy'); ?></span>
                                <div class="menu">
                                    <div class="item" data-value="test">
                                        <?php echo esc_html__('Test mode', 'revy'); ?>
                                    </div>
                                    <div class="item" data-value="live">
                                        <?php echo esc_html__('Live mode', 'revy'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="one fields">
                        <div class="field ">
                            <label for="stripe_publish_key"><?php echo esc_html__('Publish Key', 'revy'); ?></label>
                            <div class="ui left input ">
                                <input type="text" name="stripe_publish_key" id="stripe_publish_key"
                                       value="{{data.stripe_publish_key}}" tabindex="10"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="one fields">
                        <div class="field ">
                            <label for="stripe_secret_key"><?php echo esc_html__('Secret Key', 'revy'); ?></label>
                            <div class="ui left input ">
                                <input type="text" name="stripe_secret_key" id="stripe_secret_key"
                                       value="{{data.stripe_secret_key}}" tabindex="11"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel', 'revy'); ?>
            </button>

            <button class="ui blue button fat-submit-modal"
                    data-onClick="RevySetting.submitOnClick"
                    data-success-message="<?php echo esc_attr__('Payment setting has been saved', 'revy'); ?>">
                <i class="save outline icon"></i>
                <?php echo esc_html__('Save', 'revy'); ?>
            </button>

        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-setting-working-hour-template">
    <div class="ui modal tiny fat-semantic-container fat-setting-modal">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Working hours setting', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui pointing secondary menu tabular fat-tabs">
                <a class="item active"
                   data-tab="schedule"><?php echo esc_html__('Schedule', 'revy'); ?></a>
                <a class="item" data-tab="day-off"><?php echo esc_html__('Day off', 'revy'); ?></a>
            </div>

            <!-- Schedule -->
            <div class="ui active tab segment simple" data-tab="schedule">
                <div class="ui list">

                    <!-- Monday -->
                    <div class="item schedule-item">
                        <div class="ui toggle checkbox checked">
                            <input type="checkbox" name="schedule_monday" id="schedule_monday" checked="">
                            <label><?php echo esc_html__('Monday', 'revy'); ?></label>
                        </div>

                        <!-- popup clone for monday -->
                        <button class="ui basic simple button fat-bt-clone-work-hour fat-fl-right ui-popup"
                                data-position="bottom right">
                            <i class="clone outline icon"></i>
                            <?php echo esc_html__('Clone', 'revy'); ?>
                        </button>
                        <div class="ui flowing popup top left transition hidden fat-popup-work-hour-clone">
                            <div><?php echo esc_html__('Applies monday shedule to:', 'revy'); ?></div>
                            <div class="ui list">
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_tuesday" value="schedule_tuesday"
                                               checked="">
                                        <label><?php echo esc_html__('Tuesday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_wednesday" value="schedule_wednesday"
                                               checked="">
                                        <label><?php echo esc_html__('Wednesday', 'revy'); ?></label>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_thursday" value="schedule_thursday"
                                               checked="">
                                        <label><?php echo esc_html__('Thursday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_friday" value="schedule_friday"
                                               checked="">
                                        <label><?php echo esc_html__('Friday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_saturday" value="schedule_saturday"
                                               checked="">
                                        <label><?php echo esc_html__('Saturday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_sunday" checked=""
                                               value="schedule_sunday">
                                        <label><?php echo esc_html__('Sunday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <button class="ui mini primary  button fat-bt-applies-clone-work-hour fat-mg-top-15"
                                        data-onClick="RevySetting.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-monday"
                             data-depend="schedule_monday" data-depend-value="1">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevySetting.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevySetting.btAddBreakTimeOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add break time', 'revy'); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tuesday -->
                    <div class="item fat-mg-top-15 schedule-item">
                        <div class="ui toggle checkbox checked">
                            <input type="checkbox" name="schedule_tuesday" id="schedule_tuesday" checked="">
                            <label><?php echo esc_html__('Tuesday', 'revy'); ?></label>
                        </div>

                        <!-- popup clone for tuesday -->
                        <button class="ui basic simple button fat-bt-clone-work-hour fat-fl-right ui-popup"
                                data-position="left center">
                            <i class="clone outline icon"></i>
                            <?php echo esc_html__('Clone', 'revy'); ?>
                        </button>
                        <div class="ui flowing popup top left transition hidden fat-popup-work-hour-clone">
                            <div><?php echo esc_html__('Applies tuesday\'s schedule to:', 'revy'); ?></div>
                            <div class="ui list">
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_monday" value="schedule_monday"
                                               checked="">
                                        <label><?php echo esc_html__('Monday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_wednesday" value="schedule_wednesday"
                                               checked="">
                                        <label><?php echo esc_html__('Wednesday', 'revy'); ?></label>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_thursday" value="schedule_thursday"
                                               checked="">
                                        <label><?php echo esc_html__('Thursday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_friday" value="schedule_friday"
                                               checked="">
                                        <label><?php echo esc_html__('Friday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_saturday" value="schedule_saturday"
                                               checked="">
                                        <label><?php echo esc_html__('Saturday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_sunday" value="schedule_sunday"
                                               checked="">
                                        <label><?php echo esc_html__('Sunday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <button class="ui mini primary  button fat-bt-applies-clone-work-hour fat-mg-top-15"
                                        data-onClick="RevySetting.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-tuesday"
                             data-depend="schedule_tuesday" data-depend-value="1">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevySetting.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevySetting.btAddBreakTimeOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add break time', 'revy'); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Wednesday -->
                    <div class="item fat-mg-top-15 schedule-item">
                        <div class="ui toggle checkbox checked">
                            <input type="checkbox" name="schedule_wednesday" id="schedule_wednesday" checked="">
                            <label><?php echo esc_html__('Wednesday', 'revy'); ?></label>
                        </div>

                        <!-- popup clone for wednesday -->
                        <button class="ui basic simple button fat-bt-clone-work-hour fat-fl-right ui-popup"
                                data-position="left center">
                            <i class="clone outline icon"></i>
                            <?php echo esc_html__('Clone', 'revy'); ?>
                        </button>
                        <div class="ui flowing popup top left transition hidden fat-popup-work-hour-clone">
                            <div><?php echo esc_html__('Applies wednesday\'s schedule to:', 'revy'); ?></div>
                            <div class="ui list">
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_monday" value="schedule_monday"
                                               checked="">
                                        <label><?php echo esc_html__('Monday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_tuesday" value="schedule_tuesday"
                                               checked="">
                                        <label><?php echo esc_html__('Tuesday', 'revy'); ?></label>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_thursday" value="schedule_thursday"
                                               checked="">
                                        <label><?php echo esc_html__('Thursday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_friday" value="schedule_friday"
                                               checked="">
                                        <label><?php echo esc_html__('Friday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_saturday" value="schedule_saturday"
                                               checked="">
                                        <label><?php echo esc_html__('Saturday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_sunday" value="schedule_sunday"
                                               checked="">
                                        <label><?php echo esc_html__('Sunday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <button class="ui mini primary  button fat-bt-applies-clone-work-hour fat-mg-top-15"
                                        data-onClick="RevySetting.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-wednesday"
                             data-depend="schedule_wednesday" data-depend-value="1">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevySetting.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevySetting.btAddBreakTimeOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add break time', 'revy'); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Thursday -->
                    <div class="item fat-mg-top-15 schedule-item">
                        <div class="ui toggle checkbox checked">
                            <input type="checkbox" name="schedule_thursday" id="schedule_thursday" checked="">
                            <label><?php echo esc_html__('Thursday', 'revy'); ?></label>
                        </div>

                        <!-- popup clone for thursday -->
                        <button class="ui basic simple button fat-bt-clone-work-hour fat-fl-right ui-popup"
                                data-position="left center">
                            <i class="clone outline icon"></i>
                            <?php echo esc_html__('Clone', 'revy'); ?>
                        </button>
                        <div class="ui flowing popup top left transition hidden fat-popup-work-hour-clone">
                            <div><?php echo esc_html__('Applies thursday\'s schedule to:', 'revy'); ?></div>
                            <div class="ui list">
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_monday" value="schedule_monday"
                                               checked="">
                                        <label><?php echo esc_html__('Monday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_tuesday" value="schedule_tuesday"
                                               checked="">
                                        <label><?php echo esc_html__('Tuesday', 'revy'); ?></label>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_wednesday" value="schedule_wednesday"
                                               checked="">
                                        <label><?php echo esc_html__('Wednesday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_friday" value="schedule_friday"
                                               checked="">
                                        <label><?php echo esc_html__('Friday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_saturday" value="schedule_saturday"
                                               checked="">
                                        <label><?php echo esc_html__('Saturday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_sunday" value="schedule_sunday"
                                               checked="">
                                        <label><?php echo esc_html__('Sunday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <button class="ui mini primary  button fat-bt-applies-clone-work-hour fat-mg-top-15"
                                        data-onClick="RevySetting.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-thursday"
                             data-depend="schedule_thursday" data-depend-value="1">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevySetting.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevySetting.btAddBreakTimeOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add break time', 'revy'); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Friday -->
                    <div class="item fat-mg-top-15 schedule-item">
                        <div class="ui toggle checkbox checked">
                            <input type="checkbox" name="schedule_friday" id="schedule_friday" checked="">
                            <label><?php echo esc_html__('Friday', 'revy'); ?></label>
                        </div>

                        <!-- popup clone for thursday -->
                        <button class="ui basic simple button fat-bt-clone-work-hour fat-fl-right ui-popup"
                                data-position="left center">
                            <i class="clone outline icon"></i>
                            <?php echo esc_html__('Clone', 'revy'); ?>
                        </button>
                        <div class="ui flowing popup top left transition hidden fat-popup-work-hour-clone">
                            <div><?php echo esc_html__('Applies friday\'s schedule to:', 'revy'); ?></div>
                            <div class="ui list">
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_monday" value="schedule_monday"
                                               checked="">
                                        <label><?php echo esc_html__('Monday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_tuesday" value="schedule_tuesday"
                                               checked="">
                                        <label><?php echo esc_html__('Tuesday', 'revy'); ?></label>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_wednesday" value="schedule_wednesday"
                                               checked="">
                                        <label><?php echo esc_html__('Wednesday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_thursday" value="schedule_thursday"
                                               checked="">
                                        <label><?php echo esc_html__('Thursday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_saturday" value="schedule_saturday"
                                               checked="">
                                        <label><?php echo esc_html__('Saturday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui checkbox checked">
                                        <input type="checkbox" name="cb_apply_for_sunday" checked=""
                                               value="schedule_sunday">
                                        <label><?php echo esc_html__('Sunday', 'revy'); ?></label>
                                    </div>
                                </div>
                                <button class="ui mini primary button fat-bt-applies-clone-work-hour fat-mg-top-15"
                                        data-onClick="RevySetting.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-friday"
                             data-depend="schedule_friday" data-depend-value="1">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevySetting.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevySetting.btAddBreakTimeOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add break time', 'revy'); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Saturday -->
                    <div class="item fat-mg-top-15 schedule-item">
                        <div class="ui toggle checkbox ">
                            <input type="checkbox" name="schedule_saturday" id="schedule_saturday">
                            <label><?php echo esc_html__('Saturday', 'revy'); ?></label>
                        </div>
                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-saturday"
                             data-depend="schedule_saturday" data-depend-value="1">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevySetting.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevySetting.btAddBreakTimeOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add break time', 'revy'); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sunday -->
                    <div class="item fat-mg-top-15 schedule-item">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" name="schedule_sunday" id="schedule_sunday">
                            <label><?php echo esc_html__('Sunday', 'revy'); ?></label>
                        </div>
                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-sunday"
                             data-depend="schedule_sunday" data-depend-value="1">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevySetting.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevySetting.btAddBreakTimeOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add break time', 'revy'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Day off tab -->
            <div class="ui tab segment simple fat-min-height-300" data-tab="day-off">
                <div class="fat-day-off-wrap">
                    <div class="fat-day-off-inner">

                    </div>
                    <div class="fat-sb-bottom-action-group fat-mg-top-15">
                        <button class="ui basic simple button fat-bt-add-day-off"
                                data-onClick="RevySetting.btAddDayOfOnClick">
                            <i class="plus square outline icon"></i>
                            <?php echo esc_html__('Add day off', 'revy'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel', 'revy'); ?>
            </button>

            <button class="ui blue button fat-submit-modal"
                    data-onClick="RevySetting.submitWorkingHourOnClick"
                    data-invalid-message="<?php echo esc_attr__('Please select working hour before save', 'revy'); ?>"
                    data-success-message="<?php echo esc_attr__('Working hours setting has been saved', 'revy'); ?>">
                <i class="save outline icon"></i>
                <?php echo esc_html__('Save', 'revy'); ?>
            </button>

        </div>
    </div>
</script>

<?php $work_hours = Revy_Utils::getWorkHours(); ?>

<script type="text/html" id="tmpl-fat-sb-work-hour-template">
    <div class="fat-sb-work-hour-item fat-mg-top-5">
        <label><?php echo esc_html__('Work hour', 'revy'); ?></label>
        <div class="ui selection search dropdown top left pointing has-icon fat-time-dropdown fat-work-hour-start-dropdown">
            <i class="clock outline icon"></i>
            <input type="hidden" name="work_hour_start" id="work_hour_start" required>
            <div class="text"></div>
            <i class="dropdown icon"></i>

            <div class="menu">
                <?php foreach ($work_hours as $key => $value) { ?>
                    <div class="item" data-value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></div>
                <?php } ?>
            </div>
        </div>
        <div class="ui selection search dropdown top left pointing has-icon fat-time-dropdown fat-work-hour-end-dropdown">
            <i class="clock outline icon"></i>
            <input type="hidden" name="work_hour_end" id="work_hour_end" required>
            <div class="text"></div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <?php foreach ($work_hours as $key => $value) { ?>
                    <div class="item" data-value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></div>
                <?php } ?>
            </div>
        </div>

        <button class="ui basic simple button fat-mg-left-15 fat-hover-red fat-bt-remove-work-hour">
            <i class="minus square outline icon"></i>
        </button>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-break-time-template">
    <div class="fat-sb-break-time-item fat-mg-top-5">
        <label><?php echo esc_html__('Break time', 'revy'); ?></label>
        <div class="ui selection search dropdown top left pointing has-icon fat-time-dropdown fat-break-time-start-dropdown">
            <i class="clock outline icon"></i>
            <input type="hidden" name="break_time_start" id="break_time_start" required>
            <div class="text"></div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <?php foreach ($work_hours as $key => $value) { ?>
                    <div class="item" data-value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></div>
                <?php } ?>
            </div>
        </div>
        <div class="ui selection search dropdown top left pointing has-icon fat-time-dropdown fat-break-time-end-dropdown">
            <i class="clock outline icon"></i>
            <input type="hidden" name="break_time_end" id="break_time_end" required>
            <div class="text"></div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <?php foreach ($work_hours as $key => $value) { ?>
                    <div class="item" data-value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></div>
                <?php } ?>
            </div>
        </div>
        <button class="ui basic simple button fat-mg-left-15 fat-hover-red fat-bt-remove-break-time">
            <i class="minus square outline icon"></i>
        </button>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-day-off-template">
    <div class="fat-sb-day-off-item fat-mg-top-5">
        <div class="ui input">
            <input type="text" name="day_off_name" placeholder="Name of day off">
        </div>
        <div class="ui input">
            <input type="text" value="" class="date-range-picker" name="day_off_schedule">
        </div>

        <button class="ui basic simple button fat-mg-left-15 fat-hover-red fat-bt-remove-day-off">
            <i class="minus square outline icon"></i>
        </button>
    </div>
</script>