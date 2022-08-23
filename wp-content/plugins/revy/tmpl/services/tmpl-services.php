<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/21/2018
 * Time: 9:49 AM
 */
$db_setting = Revy_DB_Setting::instance();
$currency = $db_setting->get_currency_setting();
$currency_symbol = isset($currency['symbol']) ? $currency['symbol'] : '$';
$symbol_position = isset($currency['symbol_position']) ? $currency['symbol_position'] : 'after';
$durations = Revy_Utils::getDurations(0, 'duration_step');

$setting = $db_setting->get_setting();
$time_step = isset($setting['time_step']) && $setting['time_step'] ? $setting['time_step'] : 15;
$work_hours = Revy_Utils::getWorkHours($time_step);

$db= Revy_DB_Garages::instance();
$garages = $db->get_garages_dic(0);

$db = Revy_DB_Services::instance();
$models = $db->get_filter_dic('rm_name');

$modal_class = "ui modal tiny fat-semantic-container fat-services-modal";
$modal_class .= isset($setting['hide_price']) && $setting['hide_price']=='1' ? ' hide-price' : '';

?>
<script type="text/html" id="tmpl-fat-sb-services-template">
    <div class="<?php echo esc_attr($modal_class);?>">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Add new service', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui pointing secondary menu tabular fat-tabs">
                <a class="active item detail" data-tab="detail"><?php echo esc_html__('Detail', 'revy'); ?></a>
                <a class="item attribute"
                   data-tab="attribute"><?php echo esc_html__('Price & Attribute', 'revy'); ?></a>
                <a class="item schedule" data-tab="schedule"><?php echo esc_html__('Schedule', 'revy'); ?></a>
                <a class="item schedule" data-tab="day-off"><?php echo esc_html__('Day off', 'revy'); ?></a>
            </div>

            <div class="ui active tab segment simple" data-tab="detail">
                <div class="ui form">
                    <div class="one fields">
                        <div class="ui image-field " id="s_image_id" data-image-id="{{data.services.s_image_id}}"
                             data-image-url="{{data.services.s_image_url}}">
                        </div>
                    </div>

                    <div class="two fields">
                        <div class="field ">
                            <label for="s_name"><?php echo esc_html__('Name', 'revy'); ?><span
                                        class="required"> *</span></label>
                            <div class="ui left icon input ">
                                <input type="text" name="s_name" id="s_name" value="{{data.services.s_name}}"
                                       tabindex="0"
                                       placeholder="<?php echo esc_attr__('Service name', 'revy'); ?>"
                                       required>
                                <i class="edit outline icon"></i>
                            </div>
                            <div class="field-error-message">
                                <?php echo esc_html__('Please enter name', 'revy'); ?>
                            </div>
                        </div>
                        <div class="field services-model">
                            <label for="s_model_id"><?php echo esc_html__('Apply for model', 'revy'); ?>
                                <span
                                        class="required"> *</span>
                            </label>
                            <div class="ui selection search dropdown top left pointing has-icon rm-model">
                                <i class="folder outline icon"></i>
                                <input type="hidden" name="s_model_id" id="s_model_id"
                                       value="{{data.services.s_model_id}}" tabindex="1"
                                       required>
                                <div class="text"><?php echo esc_html__('Select model'); ?></div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <?php foreach ($models as $md) { ?>
                                        <div class="item"
                                             data-value="<?php echo esc_attr($md->rm_id); ?>"><?php echo esc_html($md->rm_name); ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="field-error-message">
                                <?php echo esc_html__('Please select category', 'revy'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="two fields">
                        <div class="field ">
                            <label>
                                <?php echo esc_html__('Duration', 'revy'); ?>
                                <span
                                        class="required"> *</span>
                            </label>
                            <div class="ui selection dropdown search  top left pointing has-icon  ">
                                <i class="clock outline icon"></i>
                                <input type="hidden" name="s_duration" id="s_duration" required
                                       value="{{data.services.s_duration}}" tabindex="5">
                                <i class="dropdown icon"></i>
                                <div class="text"><?php echo esc_html__('Select duration', 'revy'); ?></div>
                                <div class="menu up">
                                    <?php foreach ($durations as $key => $value) { ?>
                                            <div class="item"
                                                 data-value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></div>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="field ">
                            <label>
                                <?php echo esc_html__('Break time', 'revy'); ?>
                                <div class="ui icon ui-tooltip"
                                     data-content="<?php echo esc_attr__('Time after the appointment (rest, clean up,...)', 'revy'); ?>">
                                    <i class="question circle icon"></i>
                                </div>
                            </label>
                            <div class="ui selection dropdown search  top left pointing has-icon  ">
                                <i class="clock outline icon"></i>
                                <input type="hidden" name="s_break_time" id="s_break_time"
                                       value="{{data.services.s_break_time}}" tabindex="5">
                                <i class="dropdown icon"></i>
                                <div class="text"><?php echo esc_html__('Select break time', 'revy'); ?></div>
                                <div class="menu up">
                                    <div class="item"
                                         data-value="0"><?php echo esc_html__('No break times', 'revy'); ?></div>
                                    <?php foreach ($durations as $key => $value) { ?>
                                        <div class="item"
                                             data-value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="two fields">
                        <div class="field ">
                            <label><?php echo esc_html__('Tax(%)', 'revy'); ?>
                                <span
                                        class="required"> *</span>
                            </label>
                            <div class="ui action input number has-button">
                                <button class="ui icon button number-decrease">
                                    <i class="minus-icon"></i>
                                </button>
                                <input type="text" name="s_tax" data-type="int" data-step="1" data-min="0"
                                       tabindex="7"
                                       id="s_tax" value="{{data.services.s_tax}}">
                                <button class="ui icon button number-increase">
                                    <i class="plus-icon"></i>
                                </button>
                            </div>
                            <div class="field-error-message">
                                <?php echo esc_html__('Please enter tax', 'revy'); ?>
                            </div>
                        </div>
                        <div class="field ">
                            <label><?php echo esc_html__('Maximum Capacity', 'revy'); ?>
                                <div class="ui icon ui-tooltip"
                                     data-content="<?php echo esc_attr__('Maximum number of device per one booking of this service', 'revy'); ?>">
                                    <i class="question circle icon"></i>
                                </div>
                            </label>
                            <div class="ui action input number has-button">
                                <button class="ui icon button number-decrease">
                                    <i class="minus-icon"></i>
                                </button>
                                <input type="text" name="s_maximum_slot" data-type="int" data-step="1" data-min="1"
                                       tabindex="7"
                                       id="s_maximum_slot" value="{{data.services.s_maximum_slot}}">
                                <button class="ui icon button number-increase">
                                    <i class="plus-icon"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="one fields">
                        <div class="field">
                            <label><?php echo esc_html__('Garages', 'revy'); ?> <span
                                        class="required"> *</span></label>

                            <div class="ui bottom left pointing multiple  selection dropdown fat-sb-garage-dic">
                                <input type="hidden" name="s_garage_ids" id="s_garage_ids" required
                                       value="{{data.services.s_garage_ids}}">
                                <i class="dropdown icon"></i>
                                <div class="text"><?php echo esc_html__('Select garage', 'revy'); ?></div>
                                <div class="menu">
                                    <?php foreach ($garages as $gr) { ?>
                                        <div class="item"
                                             data-value="<?php echo esc_attr($gr->rg_id); ?>"><?php echo esc_html($gr->rg_name); ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="field-error-message">
                                <?php echo esc_html__('Please enter garage', 'revy'); ?>
                            </div>
                        </div>


                    </div>

                    <div class="one fields">
                        <div class="field ">
                            <label><?php echo esc_html__('Order displayed', 'revy'); ?>
                                <div class="ui icon ui-tooltip"
                                     data-content="<?php echo esc_attr__('Order displayed on the list', 'revy'); ?>">
                                    <i class="question circle icon"></i>
                                </div>
                            </label>
                            <div class="ui action input number has-button">
                                <button class="ui icon button number-decrease">
                                    <i class="minus-icon"></i>
                                </button>
                                <input type="text" name="s_order" data-type="int" data-step="1" data-min="1"
                                       tabindex="7"
                                       id="s_order" value="{{data.services.s_order}}">
                                <button class="ui icon button number-increase">
                                    <i class="plus-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="one fields">
                        <div class="field">
                            <label><?php echo esc_html__('Description', 'revy'); ?></label>
                            <textarea rows="3" id="s_description"
                                      tabindex="13">{{data.services.s_description}}</textarea>
                        </div>
                    </div>
                    <div class="one fields">
                        <div class="field">
                            <div class="ui toggle checkbox">
                                <# if(data.services.s_allow_booking_online==1){ #>
                                <input type="checkbox" name="s_allow_booking_online" id="s_allow_booking_online"
                                       value="1"
                                       checked tabindex="14">
                                <# }else{ #>
                                <input type="checkbox" name="s_allow_booking_online" id="s_allow_booking_online"
                                       value="1"
                                       tabindex="14">
                                <# } #>
                                <label><?php echo esc_html__('Publish to frontend', 'revy'); ?>
                                    <div class="ui icon ui-tooltip"
                                         data-content="<?php echo esc_attr__('If checked, services will be displayed on booking form', 'revy'); ?>">
                                        <i class="question circle icon"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attribute & price tab -->
            <div class="ui tab segment simple fat-min-height-300 attribute" data-tab="attribute">
                <div class="fat-attribute-wrap">
                    <div class="fat-attribute-inner ui form">

                    </div>
                    <div class="fat-sb-bottom-action-group fat-mg-top-15">
                        <button class="ui basic simple button fat-bt-add-attribute"
                                data-onClick="RevyService.btAddAttributeOnClick">
                            <i class="plus square outline icon"></i>
                            <?php echo esc_html__('Add attribute & price', 'revy'); ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- schedule tab -->
            <div class="ui tab segment simple" data-tab="schedule">
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
                                        data-onClick="RevyService.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-monday"
                             data-depend="schedule_monday">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevyService.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
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
                                        data-onClick="RevyService.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-tuesday"
                             data-depend="schedule_tuesday">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevyService.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
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
                                        data-onClick="RevyService.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-wednesday"
                             data-depend="schedule_wednesday">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevyService.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
                                </button>
                                <button class="ui basic simple button fat-bt-add-break-time"
                                        data-onClick="RevyService.btAddBreakTimeOnClick">
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
                                        data-onClick="RevyService.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-thursday"
                             data-depend="schedule_thursday">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevyService.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
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
                                        data-onClick="RevyService.processCloneSchedule">
                                    <i class="clone outline icon"></i>
                                    <?php echo esc_html__('Applies', 'revy'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="fat-sb-work-hour-wrap fat-sb-hidden fat-mg-top-15 schedule-friday"
                             data-depend="schedule_friday">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevyService.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
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
                             data-depend="schedule_saturday">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevyService.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
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
                             data-depend="schedule_sunday">
                            <div class="fat-sb-work-hour-item-wrap">
                            </div>
                            <div class="fat-sb-break-time-item-wrap">
                            </div>
                            <div class="fat-sb-bottom-action-group fat-mg-top-15">
                                <button class="ui basic simple button fat-bt-add-work-hour"
                                        data-onClick="RevyService.btAddWorkHourOnClick">
                                    <i class="plus square outline icon"></i>
                                    <?php echo esc_html__('Add work hour', 'revy'); ?>
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
                                data-onClick="RevyService.btAddDayOfOnClick">
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

            <button class="ui blue button fat-submit-modal fat-bt-submit-service"
                    data-popup-id="popup_bt_update_service" data-loading-color="loading-blue"
                    data-onClick="RevyService.processSubmitService"
                    data-id="{{data.services.s_id}}"
                    data-success-message="<?php echo esc_attr__('Service has been saved', 'revy'); ?>">
                <i class="save outline icon"></i>
                <?php echo esc_html__('Save', 'revy'); ?>
            </button>

            <div class="ui flowing popup top left transition hidden fat-popup-submit-service-confirm"
                 data-popup-id="popup_bt_update_service">
                <h4 class="ui header">
                    <?php echo esc_html__('Your changes related to specific settings for each employee.', 'revy'); ?>
                    <br/>
                    <?php echo esc_html__('Do you want to update employee settings according to this setting ?', 'revy'); ?>
                </h4>
                <div>
                    <button class="ui mini button fat-bt-confirm-cancel">
                        <?php echo esc_html__('No', 'revy'); ?>
                    </button>
                    <button class="ui mini primary button fat-bt-confirm-ok fat-bt-confirm-enable">
                        <?php echo esc_html__('Yes', 'revy'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-service-item-template">
    <# _.each(data, function(item){ #>
    <tr class="item" data-id="{{item.s_id}}">
        <td>
            <div class="ui checkbox">
                <input type="checkbox" name="s_id" class="check-item" data-id="{{item.s_id}}">
                <label></label>
            </div>
        </td>
        <td class="fat-s-name" data-label="<?php echo esc_attr__('Name', 'revy'); ?>">
            {{item.s_name }}
        </td>
        <td class="fat-s-model" data-label="<?php echo esc_attr__('Model', 'revy'); ?>">
            {{item.rm_name}}
        </td>
        <td class="fat-s-duration" data-label="<?php echo esc_attr__('Duration', 'revy'); ?>">
            {{item.s_duration}}
        </td>
        <td class="fat-s-max-slot" data-label="<?php echo esc_attr__('Max Slot', 'revy'); ?>">
            {{item.s_maximum_slot}}
        </td>
        <td class="fat-s-status" data-label="<?php echo esc_attr__('Publish to frontend', 'revy'); ?>">
            <# if(item.s_allow_booking_online == 1){ #>
            <?php echo esc_html__('Yes', 'revy'); ?>
            <# }else{ #>
            <?php echo esc_html__('No', 'revy'); ?>
            <# } #>
        </td>
        <td>
            <div class="ps-relative">
                <button class="ui icon button fat-item-bt-inline fat-sb-delete" data-onClick="RevyService.processDeleteService"
                        data-id="{{item.s_id}}" data-title="<?php echo esc_attr__('Delete', 'revy'); ?>">
                    <i class="trash alternate outline icon"></i>
                </button>

                <button class="ui icon button fat-item-bt-inline fat-sb-clone" data-onClick="RevyService.processCloneService"
                        data-id="{{item.s_id}}" data-title="<?php echo esc_attr__('Clone', 'revy'); ?>">
                    <i class="clone outline icon"></i>
                </button>

                <button class="ui icon ui-tooltip button fat-item-bt-inline fat-sb-edit"
                        data-onClick="RevyService.processEditService"
                        data-id="{{item.s_id}}" data-title="<?php echo esc_attr__('Edit', 'revy'); ?>">
                    <i class="edit outline icon"></i>
                </button>
            </div>

        </td>
    </tr>
    <# }) #>
</script>

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

<script type="text/html" id="tmpl-fat-sb-attribute-template">
    <div class="fat-sb-attribute-item fat-mg-top-5">
        <div class="three fields">
            <div class="field ">
                <label><?php echo esc_html__('Attribute name', 'revy'); ?> <span class="required"> *</span>
                </label>
                <div class="ui input">
                    <input type="text" id="s_attr_title" name="s_attr_title" required placeholder="<?php echo esc_html__('Attribute title','revy');?>">
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please select attribute title', 'revy'); ?>
                </div>
            </div>

            <div class="field ">
                <label><?php echo esc_html__('Attribute value', 'revy'); ?> <span class="required"> *</span>
                </label>
                <div class="ui input">
                    <input type="text" id="s_attr_value" name="s_attr_value" required placeholder="<?php echo esc_html__('Attribute value','revy');?>">
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please select attribute value', 'revy'); ?>
                </div>
            </div>

            <div class="field ">
                <label><?php echo esc_html__('Price', 'revy'); ?> <span
                            class="required"> *</span></label>
                <div class="ui left icon input number">
                    <input type="text" name="s_price" data-type="decimal" id="s_price"
                           value="" tabindex="2"
                           placeholder="<?php echo esc_attr__('Service price', 'revy'); ?>" required>
                    <i class="dollar sign icon"></i>
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter price', 'revy'); ?>
                </div>
            </div>
        </div>
        <button class="ui basic simple button fat-mg-left-15 fat-hover-red fat-bt-remove-attribute">
            <i class="minus square outline icon"></i>
        </button>
    </div>
</script>