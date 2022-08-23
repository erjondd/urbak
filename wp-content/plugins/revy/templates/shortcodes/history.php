<?php
/**
 * Created by PhpStorm.
 * User: RoninWP
 * Date: 8/9/2019
 * Time: 9:50 AM
 */
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();
$current_user = wp_get_current_user();
$class_custom_code = $current_user->exists() ? 'fat-hidden' : '';

?>
<div class="fat-sb-booking-history <?php echo($current_user->exists() ? 'has-login' : ''); ?>">
    <div class="history-tool-box">
        <div class="fat-sb-customer-code">
            <label class="<?php echo esc_attr($class_custom_code); ?>"><?php echo esc_html__('Customer code:', 'revy'); ?></label>
            <input type="text" name="c_code" class="<?php echo esc_attr($class_custom_code); ?>"
                   data-error="<?php echo esc_attr__('Please input customer code before view history', 'revy'); ?>"
                   placeholder="<?php echo esc_attr__('Input customer code and click view history', 'revy'); ?>">
        </div>
        <div class="fat-sb-datetime">
            <label><?php echo esc_html__('Appointment date:', 'revy'); ?></label>
            <div class="ui transparent left date-input input no-border-radius">
                <?php
                $start_date = new DateTime();
                $start_date->modify('-6 day');
                $end_date = new DateTime();
                $date_format = get_option('date_format');
                $locale = get_locale();
                $locale = explode('_',$locale)[0];
                ?>
                <input type="text"  class="date-range-picker"  name="date_of_book" id="date_of_book" data-auto-update="1"
                       data-start="<?php echo esc_attr($start_date->format('Y-m-d'));?>" data-end="<?php echo esc_attr($end_date->format('Y-m-d'));?>"
                       data-locale="<?php echo esc_attr($locale);?>"
                       data-start-init="<?php echo date_i18n($date_format,$start_date->format('U'));?>"
                       data-end-init="<?php echo date_i18n($date_format,$end_date->format('U'));?>" >
            </div>

        </div>
        <div class="fat-sb-status">
            <label ><?php echo esc_html__('Status:', 'revy'); ?></label>
            <div class="ui floating dropdown labeled icon selection dropdown fat-mg-right-10">
                <i class="dropdown icon"></i>
                <input type="hidden" name="b_process_status" id="b_process_status" value="0">
                <span class="text"><?php echo esc_html__('Select status','revy');?></span>
                <div class="menu">

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

        <div class="fat-sb-history-button-group">
            <button class="ui blue button fat-sb-view-history" data-prevent-event="1" data-onClick="RevyBookingHistory.viewHistory">
                <i class="history icon"></i>
                <?php echo esc_html__('Get history', 'revy'); ?>
            </button>

            <button class="ui blue button " data-prevent-event="1" data-onClick="RevyBookingHistory.openPopupGetCustomerCode">
                <i class="qrcode icon"></i>
                <?php echo esc_html__('Get customer code', 'revy'); ?>
            </button>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th><?php echo esc_html__('Appointment Date', 'revy'); ?></th>
            <th><?php echo esc_html__('Model', 'revy'); ?></th>
            <th><?php echo esc_html__('Services', 'revy'); ?></th>
            <th><?php echo esc_html__('Attribute', 'revy'); ?></th>
            <th class="fat-sb-payment"><?php echo esc_html__('Payment', 'revy'); ?></th>
            <th class="fat-sb-status"><?php echo esc_html__('Status', 'revy'); ?></th>
            <th class="fat-sb-create-date"><?php echo esc_html__('Create date', 'revy'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="9">
                <div class="fat-sb-not-found">
                    <?php
                    if ($current_user->exists()) {
                        echo esc_html__('Please input customer code and click \'View History\' to display your booking history', 'revy');
                    } else {
                        echo esc_html__('Please click \'View History\' to display your booking history', 'revy');
                    } ?>
            </td>
        </tr>

        </tbody>
    </table>
    <div class="fat-sb-pagination" data-obj="RevyBookingHistory" data-func="loadHistory">

    </div>
</div>

<script type="text/html" id="tmpl-fat-sb-history-item-template">
    <# _.each(data, function(item){ #>
    <tr data-id="{{item.b_id}}" data-edit="{{item.editable}}">
        <td data-label="<?php echo esc_attr__('Appointment Date', 'revy'); ?>">{{item.b_date}} {{item.b_time_label}}</td>
        <td data-label="<?php echo esc_attr__('Model', 'revy'); ?>">
            {{item.rm_name}}
        </td>
        <td data-label="<?php echo esc_attr__('Services', 'revy'); ?>">{{item.s_name}}</td>
        <td data-label="<?php echo esc_attr__('Attribute', 'revy'); ?>">{{item.b_attr}}</td>
        <td class="fat-sb-payment" data-label="<?php echo esc_attr__('Payment', 'revy'); ?>">
            {{item.b_total_pay}}
        </td>
        <td class="fat-sb-status" data-label="<?php echo esc_attr__('Status', 'revy'); ?>">
            {{ item.b_status_display }}
        </td>
        <td data-label="<?php echo esc_attr__('Create Date', 'revy'); ?>">{{item.b_create_date}}</td>
        <td>
            <?php if ( isset($setting['allow_client_cancel']) && $setting['allow_client_cancel'] == 1): ?>
                <# if(item.is_cancel==1){ #>
                    <a href="#" data-prevent-event="1" class="fat-sb-cancel"
                       data-onClick="RevyBookingHistory.openPopupCancel"><?php echo esc_attr__('Cancel', 'revy'); ?></a>
                <# } #>
            <?php endif; ?>
        </td>

    </tr>
    <# }) #>
</script>

<script type="text/html" id="tmpl-fat-sb-get-customer-code-template">
    <div class="fat-sb-popup-modal">
        <div class="fat-sb-popup-modal-content" style="display: none">
            <label><?php echo esc_html__('Your email:', 'revy'); ?></label>
            <input type="email" name="c_email" id="c_email"
                   data-error="<?php echo esc_html__('Please input email before get code', 'revy'); ?>"/>
            <div class="fat-sb-popup-bt-group">
                <button class="ui blue button fat-bt-submit" data-prevent-event="1" data-onClick="RevyBookingHistory.getCustomerCode">
                    <i class="qrcode icon"></i>
                    <?php echo esc_html__('Get code', 'revy'); ?>
                </button>

                <button class="ui button fat-bt-cancel" data-prevent-event="1" data-onClick="RevyBookingHistory.closePopupModal">
                    <i class="close icon"></i>
                    <?php echo esc_html__('Cancel', 'revy'); ?>
                </button>

            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-popup-cancel-template">
    <div class="fat-sb-popup-modal">
        <div class="fat-sb-popup-modal-content fat-sb-cancel-booking-popup" style="display: none">
            <label><?php echo esc_html__('Your customer code:', 'revy'); ?></label>
            <input type="text" name="c_code" id="c_code" class="customer-code" placeholder="<?php echo esc_html__('Input customer code to cancel booking', 'revy'); ?>"
                   data-error="<?php echo esc_html__('Please input customer code to cancel booking', 'revy'); ?>"/>
            <div class="fat-sb-popup-bt-group">
                <button class="ui blue button fat-bt-submit" data-prevent-event="1" data-onClick="RevyBookingHistory.submitCancel">
                    <i class="trash alternate outline icon"></i>
                    <?php echo esc_html__('Cancel appointment', 'revy'); ?>
                </button>

                <button class="ui button fat-bt-cancel" data-prevent-event="1" data-onClick="RevyBookingHistory.closePopupModal">
                    <i class="close icon"></i>
                    <?php echo esc_html__('Close', 'revy'); ?>
                </button>
            </div>
        </div>
    </div>
</script>

