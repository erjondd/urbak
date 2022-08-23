<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

$device_label = isset($setting['device_label']) && $setting['device_label'] ? $setting['device_label'] : esc_html__('Device','revy');
$service_label = isset($setting['service_label']) && $setting['service_label'] ? $setting['service_label'] : esc_html__('Service','revy');
$garage_label  = isset($setting['garage_label']) && $setting['garage_label'] ? $setting['garage_label'] : esc_html__('Garage','revy');
$total_cost_label = isset($setting['total_cost_label']) && $setting['total_cost_label'] ? $setting['total_cost_label'] : esc_html__('Total cost','revy');
$appointment_time_label = isset($setting['appointment_time_label']) && $setting['appointment_time_label'] ? $setting['appointment_time_label'] : esc_html__('Appointment time','revy');
$payment_method_label = isset($setting['payment_method_label']) && $setting['payment_method_label'] ? $setting['payment_method_label'] : esc_html__('Payment method','revy');
$tax_label = isset($setting['tax_label']) && $setting['tax_label'] ? $setting['tax_label'] : esc_html__('Tax','revy');
?>
<div class="repair-summary ">
    <h4><?php echo esc_html__('Repair Summary:', 'revy'); ?></h4>

    <div class="location-section-wrap">
        <div class="location-section">
            <div class="fat-section-shadow">
                <div class="garage-title fat-fw-600">
                </div>
                <div class="garage-address fat-lh-1em fat-fs-small">
                </div>
                <div class="garage-desc fat-lh-14em fat-mg-top-15 fat-fs-small">
                </div>
            </div>
        </div>

        <div class="service-section fat-mg-top-30">
            <div class=" fat-section-shadow">
                <ul>
                    <li class="device-meta">
                        <label class="fat-fw-600"><?php echo esc_html($device_label); ?></label>
                        <div class="fat-fs-small mt-value"></div>
                    </li>
                    <li class="service-meta">
                        <label><?php echo esc_html($service_label); ?></label>
                        <div class="fat-fs-small mt-value"></div>
                    </li>
                    <li class="time-meta">
                        <label><?php echo  esc_html($appointment_time_label); ?></label>
                        <div class="fat-fs-small mt-value"></div>
                    </li>

                    <li class="tax-meta">
                        <label><?php echo  esc_html($tax_label); ?></label>
                        <div class="fat-fs-small mt-value"></div>
                    </li>

                    <li class="cost-meta">
                        <label><?php echo esc_html($total_cost_label); ?></label>
                        <div class="fat-fs-small mt-value"></div>
                    </li>
                </ul>

                <ul class="payment-method-wrap">
                    <li>
                        <label class="fat-fw-600"><?php echo esc_html($payment_method_label); ?></label>
                        <div class="fat-list-gateway">
                            <?php if (isset($setting['onsite_enable']) && $setting['onsite_enable'] == '1'): ?>
                                <div class="gateway-item selected" data-value="onsite" data-onClick="RevyBookingFlow.gatewayOnClick">
                                    <i class="credit card outline icon"></i>
                                    <?php echo esc_html__('Onsite', 'revy'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($setting['paypal_enable']) && $setting['paypal_enable'] == '1'): ?>
                                <div class="gateway-item" data-value="paypal" data-onClick="RevyBookingFlow.gatewayOnClick">
                                    <i class="cc paypal icon"></i>
                                    <?php echo esc_html__('Paypal', 'revy'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($setting['stripe_enable']) && $setting['stripe_enable'] == '1'): ?>
                                <div class="gateway-item" data-value="stripe" data-onClick="RevyBookingFlow.gatewayOnClick">
                                    <i class="cc stripe icon"></i>
                                    <?php echo esc_html__('Stripe', 'revy'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php if (isset($setting['stripe_enable']) && $setting['stripe_enable'] == '1'): ?>
                    <li>
                        <div class="fat-sb-order-stripe fat-sb-hidden">
                            <form method="post" class="stripe-form" id="stripe-payment-form"
                                  data-pk="<?php echo(isset($setting['stripe_publish_key']) ? $setting['stripe_publish_key'] : 'pk_test_9q3BpuszZDNlnc8uppYQYQH7'); ?>">
                                <div class="form-row">
                                    <div id="card-element-<?php echo uniqid(); ?>" class="card-element">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Used to display form errors. -->
                                    <div id="card-errors-<?php echo uniqid(); ?>" class="card-errors"
                                         role="alert"></div>
                                </div>
                                <button></button>
                            </form>
                        </div>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </div>

    <div class="button-group fat-mg-top-30 text-center">
        <div class="fat-sb-error-message fat-sb-hidden"></div>
        <button class="ui primary button fat-bt-payment" data-onclick="RevyBookingFlow.confirmOrderClick">
            <?php echo esc_html__('Book repair', 'revy'); ?>
        </button>
    </div>

    <div class="fat-sb-go-back text-center">
        <a href="javascript:" class="fat-go-back-garage" data-onclick="RevyBookingFlow.goBackGarage"
           data-prevent-event="1">
            <i class="arrow left icon"></i><?php echo esc_html__('Change ', 'revy'). esc_html($garage_label); ?></a>

        <a href="javascript:" class="fat-go-back-location fat-sb-hidden"
           data-onclick="RevyBookingFlow.goBackLocationFixItHome" data-prevent-event="1">
            <i class="arrow left icon"></i><span><?php echo esc_html__('Change location', 'revy'); ?></span></a>
    </div>
</div>

