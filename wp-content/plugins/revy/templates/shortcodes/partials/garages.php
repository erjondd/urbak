<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

$db = Revy_DB_Garages::instance();
$garages = $db->get_garages_dic();
?>
<div class="fat-sb-head">
    <h4 class="fat-sb-title"><?php echo esc_html($setting['step_garage_title']);?></h4>
    <div class="fat-sb-subtitle"><?php echo esc_html($setting['step_garage_subtitle']);?></div>
</div>

<div class="fat-sb-item-inner-wrap flex-center">
    <?php foreach ($garages as $ga) { ?>
        <div class="fat-sb-item fat-align-center fat-on-click gr"
             data-garage-id="<?php echo esc_attr($ga->rg_id); ?>"
             data-onClick="RevyBookingFlow.garageOnClick">
            <div class="fat-it-inner">
                <img  class="img-default"  src="<?php echo esc_attr($ga->rg_image_url); ?>">
                <div class="item-title">
                    <?php echo esc_html($ga->rg_name); ?>
                </div>
                <div class="item-address">
                    <?php echo esc_html($ga->rg_address); ?>
                </div>
                <div class="item-desc">
                    <?php echo nl2br($ga->rg_description); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="fat-sb-go-back text-center">
    <a href="javascript:" data-onclick="RevyBookingFlow.goBackDeliveryMethod" data-prevent-event="1">
        <i class="arrow left icon"></i><span><?php echo esc_html__('Change Location','revy');?></span></a>
</div>

<script type="text/html" id="tmpl-fat-flow-garage-item-template">
    <# _.each(data, function(item){ #>
        <div class="fat-sb-item fat-align-center fat-on-click gr"
             data-garage-id="{{item.rg_id}}"
             data-onClick="RevyBookingFlow.garageOnClick">
            <div class="fat-it-inner">
                <img  class="img-default" src="{{item.rg_image_url}}">
                <div class="item-title">
                    {{item.rg_name}}
                </div>
                <div class="item-address">
                    {{item.rg_address}}
                </div>
                <div class="item-desc">
                    {{{item.rg_description}}}
                </div>
            </div>
        </div>
    <# }) #>
</script>
