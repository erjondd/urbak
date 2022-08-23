<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

$db = Revy_DB_Locations::instance();
$locations = $db->get_locations();
?>
<div class="fat-sb-head">
    <h4 class="fat-sb-title"><?php echo esc_html($setting['step_location_title']);?></h4>
    <div class="fat-sb-subtitle"><?php echo esc_html($setting['step_location_subtitle']);?></div>
</div>

<div class="fat-sb-item-inner-wrap flex-center">
    <?php foreach ($locations as $loc) { ?>
        <div class="fat-sb-item fat-align-center fat-on-click"
             data-device-id="<?php echo esc_attr($loc->loc_id); ?>"
             data-onClick="RevyBookingFlow.deviceOnClick">
            <div class="fat-it-inner">
                <img src="<?php echo esc_attr($loc->loc_image_url); ?>">
                <div class="item-title">
                    <?php echo esc_html($loc->loc_name); ?>
                </div>
            </div>

        </div>
    <?php } ?>
</div>

<div class="fat-sb-go-back text-center">
    <a href="javascript:" data-onclick="RevyBookingFlow.goBackBrand" data-prevent-event="1">
        <i class="arrow left icon"></i><?php echo esc_html__('Change Service Type','revy');?></a>
</div>
