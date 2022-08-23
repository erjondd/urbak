<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

$container_class = 'fat-semantic-container fat-booking-container fat-sb-flow-layout';
$container_class .= isset($setting['hide_price']) && $setting['hide_price'] =='1'? ' hide-price' : '';
$container_class .= isset($setting['hide_payment']) && $setting['hide_payment'] =='1'? ' hide-payment' : '';
$container_class .= isset($setting['mapbox_api_key']) && $setting['mapbox_api_key'] !=''? '' : '  hide-map';
$container_class .= isset($atts['layout']) && $atts['layout']=='brand-model' ? ' brand-model-step' : '';


?>
<div class="<?php echo esc_attr($container_class);?>">
    <div class="fat-sb-list-devices fat-sb-item-wrap">
        <?php include REVY_SC_TEMPLATE_PATH . '/partials/devices.php'; ?>
    </div>

    <?php if(isset($atts['layout']) && $atts['layout']=='brand-model'): ?>
        <div class="fat-sb-list-brand fat-sb-item-wrap fat-hidden-step">
            <?php include REVY_SC_TEMPLATE_PATH . '/partials/brands.php'; ?>
        </div>
        <div class="fat-sb-list-model fat-sb-item-wrap fat-hidden-step">
            <?php include REVY_SC_TEMPLATE_PATH . '/partials/models.php'; ?>
        </div>
    <?php endif;?>

    <div class="fat-sb-list-services fat-sb-item-wrap fat-hidden-step">
        <?php include REVY_SC_TEMPLATE_PATH . '/partials/services.php'; ?>
    </div>

    <div class="fat-sb-list-delivery-method fat-sb-item-wrap fat-hidden-step">
        <?php include REVY_SC_TEMPLATE_PATH . '/partials/delivery-method.php'; ?>
    </div>

    <div class="fat-sb-list-garages fat-sb-item-wrap fat-hidden-step">
        <?php include REVY_SC_TEMPLATE_PATH . '/partials/garages.php'; ?>
    </div>

    <div class="fat-sb-order-wrap fat-hidden-step">
        <div class="fat-sb-head">
            <h4 class="fat-sb-title"><?php echo esc_html($setting['step_schedule_title']); ?></h4>
            <div class="fat-sb-subtitle"><?php echo esc_html($setting['step_schedule_subtitle']); ?></div>
        </div>
        <div class="order-wrap-inner">
            <div class="fat-sb-col-left">
                <?php include REVY_SC_TEMPLATE_PATH . '/partials/customer-info.php';
                include REVY_SC_TEMPLATE_PATH . '/partials/weekly-calendar.php'; ?>
            </div>
            <div class="fat-sb-col-right">
                <?php include REVY_SC_TEMPLATE_PATH . '/partials/repair-summary.php'; ?>
            </div>
        </div>
    </div>

    <div class="fat-sb-appointment-booked-wrap fat-hidden-step">
        <div class="fat-sb-head">
            <h4 class="fat-sb-title"><?php echo esc_html($setting['step_booked_title']); ?></h4>
            <div class="fat-sb-subtitle"><?php echo esc_html($setting['step_booked_subtitle']); ?></div>
        </div>
    </div>

</div>
