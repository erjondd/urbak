<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

$enable_fix_at_home = isset($setting['enable_fix_at_home']) && $setting['enable_fix_at_home'] == '1';
$fix_at_home_img = isset($setting['fix_at_home_img_id']) && $setting['fix_at_home_img_id'] ? wp_get_attachment_url($setting['fix_at_home_img_id']) : (REVY_ASSET_URL . '/images/fixit-home.png');

$enable_carry_in = isset($setting['enable_carry_in']) && $setting['enable_carry_in'] == '1';
$carry_in_img_url = isset($setting['carry_in_img_id']) && $setting['carry_in_img_id'] ? wp_get_attachment_url($setting['carry_in_img_id']) : (REVY_ASSET_URL . '/images/carry-in.png');

$enable_mail_in = isset($setting['enable_mail_in']) && $setting['enable_mail_in'] == '1';
$mail_in_img_url = isset($setting['mail_in_img_id']) && $setting['mail_in_img_id'] ? wp_get_attachment_url($setting['mail_in_img_id']) : (REVY_ASSET_URL . '/images/mail-in.png');

?>
<div class="fat-sb-head">
    <h4 class="fat-sb-title" data-location-title="<?php echo esc_attr($setting['step_location_title']); ?>"
        data-delivery-title="<?php echo esc_attr($setting['step_delivery_title']); ?>">
        <?php echo esc_html($setting['step_delivery_title']); ?>
    </h4>
    <div class="fat-sb-subtitle" data-location-title="<?php echo esc_attr($setting['step_location_subtitle']); ?>"
         data-delivery-title="<?php echo esc_attr($setting['step_delivery_subtitle']); ?>">
        <?php echo esc_html($setting['step_delivery_subtitle']); ?></div>
</div>
<div class="fat-sb-delivery-method-inner">
    <div class="postal-code-wrap">
        <div class="ui form">
            <div class="one fields">
                <div class="field">
                    <label><?php echo esc_html__('My postal code', 'revy'); ?></label>
                    <div class="ui left input ">
                        <input type="text" name="postal_code" id="postal_code">
                    </div>
                    <div class="fat-sb-postal-code-message">
                        <div class="fat-sb-postal-code-message"><?php echo esc_html__('Can not get your location via postal code. Send your device to our mail-in repair facility ', 'revy'); ?></div>
                    </div>
                </div>

            </div>
            <div class="one fields">
                <div class="field">
                    <button class="ui primary button fat-bt-next"
                            data-onClick="RevyBookingFlow.getLocationFromPostalCode">
                        <?php echo esc_html__('Check availability', 'revy'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="list-delivery-method fat-sb-item-wrap">
        <div class="fat-sb-item-inner-wrap">
            <?php if ($enable_fix_at_home): ?>
                <div class="fat-sb-item fat-align-center fixit-home fat-on-click disabled">
                    <div class="fat-it-inner" data-onClick="RevyBookingFlow.fixItHomeOnClick">
                        <img src="<?php echo esc_url($fix_at_home_img); ?>"
                             alt="<?php echo esc_html__('Fix it home', 'revy'); ?>">
                        <div class="item-title">
                            <?php echo esc_html__('FixIt at home', 'revy'); ?>
                            <div class="item-desc"><?php echo esc_html__('We come to you and fix your most common appliance needs at home', 'revy'); ?></div>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <?php if ($enable_carry_in): ?>
                <div class="fat-sb-item fat-align-center carry-in fat-on-click disabled">
                    <div class="fat-it-inner" data-onClick="RevyBookingFlow.carryInOnClick">
                        <img src="<?php echo esc_url($carry_in_img_url); ?>"
                             alt="<?php echo esc_html__('Carry-In', 'revy'); ?>">
                        <div class="item-title"> <?php echo esc_html__('Carry-In / Curbside', 'revy'); ?>
                            <div class="item-desc"><?php echo esc_html__('Visit one of our garages where we can usually repair your device', 'revy'); ?></div>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <?php if ($enable_mail_in): ?>
                <div class="fat-sb-item fat-align-center mail-in fat-on-click">
                    <div class="fat-it-inner" data-onClick="RevyBookingFlow.mailInOnClick">
                        <img src="<?php echo esc_url($mail_in_img_url); ?>"
                             alt="<?php echo esc_html__('Mail-in Delivery', 'revy'); ?>">
                        <div class="item-title"> <?php echo esc_html__('Mail-in Delivery', 'revy'); ?>
                            <div class="item-desc"><?php echo esc_html__('Send your device to our mail-in repair facility and repair updates from our expert', 'revy'); ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="fat-sb-go-back text-center">
            <a href="javascript:" data-onclick="RevyBookingFlow.goBackServices" data-prevent-event="1">
                <i class="arrow left icon"></i><?php echo esc_html__('Change Service', 'revy'); ?></a>
        </div>
    </div>

</div>
