<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

$db = Revy_DB_Devices::instance();
$devices = $db->get_devices(1);
$brand_label = isset($setting['brand_label']) && $setting['brand_label'] ? $setting['brand_label'] : esc_html__('Choose your brand', 'revy');
$model_label = isset($setting['model_label']) && $setting['model_label'] ? $setting['model_label'] : esc_html__('Choose your model', 'revy');

$deviceOnClick = !isset($atts['layout']) || $atts['layout'] != 'brand-model' ? 'RevyBookingFlow.deviceOnClick' : 'RevyBookingFlow.deviceBrandModelStepOnClick';

?>

<div class="fat-sb-head">
    <h4 class="fat-sb-title"><?php echo esc_html($setting['step_device_title']); ?></h4>
    <div class="fat-sb-subtitle"><?php echo esc_html($setting['step_device_subtitle']); ?></div>
</div>
<div class="fat-sb-item-inner-wrap device-step step-item flex-center">
    <?php foreach ($devices as $dv) { ?>
        <div class="fat-sb-item fat-align-center fat-on-click" data-device-id="<?php echo esc_attr($dv->rd_id); ?>" data-onClick="<?php echo esc_attr($deviceOnClick); ?>">
            <div class="fat-it-inner">
                <img src="<?php echo esc_attr($dv->rd_image_url); ?>">
                <div class="item-title">
                    <?php echo esc_html($dv->rd_name); ?>
                </div>
            </div>

        </div>
    <?php } ?>
</div>
<?php if (!isset($atts['layout']) || $atts['layout'] != 'brand-model') : ?>
    <div class="brand-model-step step-item">
        <div class="step-item-inner">
            <div class="fat-sb-col-left">
                <div class="fat-sb-item fat-align-center">

                </div>
            </div>
            <div class="fat-sb-col-right">
                <h5><?php echo esc_html__('Just a few more details', 'revy'); ?></h5>

                <div class="field brand-field">
                    <label class="fat-fw-400"><?php echo esc_html($brand_label); ?></label>
                    <div class="ui fluid search selection dropdown brands" data-onChange="RevyBookingFlow.brandOnChange">
                        <input type="hidden" name="b_brand_id" id="b_brand_id" autocomplete="nope" value="">
                        <i class="dropdown icon"></i>
                        <div class="default text"></div>
                        <div class="menu">

                        </div>
                    </div>
                </div>

                <div class="field model-field">
                    <label class="fat-fw-400"><?php echo esc_html($model_label); ?></label>
                    <div class="ui fluid search selection dropdown models clearable" data-onChange="RevyBookingFlow.modelOnChange">
                        <input type="hidden" name="b_model_id" id="b_model_id" autocomplete="nope">
                        <i class="dropdown icon"></i>
                        <div class="default text"></div>
                        <div class="menu">

                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="fat-sb-go-back text-center">
            <a href="javascript:" data-onclick="RevyBookingFlow.goBackDevice" data-prevent-event="1">
                <i class="arrow left icon"></i><?php echo esc_html__('Change Device Type', 'revy'); ?></a>
        </div>
    </div>
<?php endif; ?>