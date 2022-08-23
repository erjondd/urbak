<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

?>

<div class="fat-sb-head">
    <h4 class="fat-sb-title"><?php echo esc_html($setting['step_model_title']);?></h4>
    <div class="fat-sb-subtitle"><?php echo esc_html($setting['step_model_subtitle']);?></div>
</div>
<div class="fat-sb-item-inner-wrap model-step step-item flex-center">

</div>
<div class="fat-sb-go-back text-center">
    <a href="javascript:" data-onclick="RevyBookingFlow.goBackBrandStep" data-prevent-event="1">
        <i class="arrow left icon"></i><?php echo esc_html__('Change Brand Type','revy');?></a>
</div>

<script type="text/html" id="tmpl-fat-model-item-template">
    <# _.each(data, function(item){ #>
    <div class="fat-sb-item fat-align-center fat-on-click"
         data-model-id="{{item.rm_id}}" data-name="{{item.rm_name}}"
         data-onClick="RevyBookingFlow.modelOnClick">
        <div class="fat-it-inner">
            <img src="{{item.rm_image_url}}">
            <div class="item-title">
                {{item.rm_name}}
            </div>
        </div>
    </div>
    <# }) #>
</script>
