<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();
?>
<div class="fat-sb-head">
    <h4 class="fat-sb-title"><?php echo esc_html($setting['step_service_title']); ?></h4>
    <div class="fat-sb-subtitle"><?php echo esc_html($setting['step_service_subtitle']); ?></div>
</div>
<div class="fat-sb-service-inner">
    <div class="fat-sb-item-inner-wrap flex-center">

    </div>
    <div class="fat-sb-repair-list">
        <h5><?php echo esc_html__('Repair List', 'revy'); ?></h5>
        <ul>

        </ul>

        <div class="button-group fat-mg-top-30">
            <button class="ui primary button fat-bt-next disabled next-delivery"
                    data-onclick="RevyBookingFlow.gotoDeliveryMethod">
                <?php echo esc_html__('Next', 'revy'); ?>
            </button>
        </div>
    </div>
</div>

<?php if (!isset($atts['layout']) || $atts['layout'] != 'brand-model'): ?>
    <div class="fat-sb-go-back text-center">
        <a href="javascript:" data-onclick="RevyBookingFlow.goBackBrand" data-prevent-event="1">
            <i class="arrow left icon"></i><?php echo esc_html__('Change Brand Type', 'revy'); ?></a>
    </div>
<?php else: ?>
    <div class="fat-sb-go-back text-center">
        <a href="javascript:" data-onclick="RevyBookingFlow.goBackModelStep" data-prevent-event="1">
            <i class="arrow left icon"></i><?php echo esc_html__('Change Model Type', 'revy'); ?></a>
    </div>
<?php endif; ?>


<script type="text/html" id="tmpl-fat-flow-service-item-template">
    <# _.each(data, function(item){ #>
    <div class="fat-sb-item fat-align-center {{item.s_item_class}} fat-on-click"
         data-service-id="{{item.s_id}}" data-service-name="{{item.s_name}}"
         data-garage-ids={{item.s_garage_ids}}
         data-min-price="{{item.s_min_price}}"
         data-min-price-format="{{item.s_min_price_format}}"
         data-onClick="RevyBookingFlow.serviceOnClick">
        <div class="fat-it-inner">
            <# if(item.s_description!=''){ #>
            <div class="desc-tooltip" data-tooltip="{{item.s_description}}">
                <i class="info circle icon"></i>
            </div>
            <# } #>
            <img src="{{item.s_image_url}}">
            <div class="item-title">
                {{item.s_name}}
                <div class="meta">
                    <div class="price-attribute">
                        <# if(item.atts_length > 0){ #>
                        <div class="ui inline dropdown attribute"
                             data-onChange="RevyBookingFlow.attributeOnChange">
                            <input type="hidden" name="s_attr" id="s_attr">
                            <span class="text"><?php echo esc_html__('Select options', 'revy'); ?></span>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <# _.each(item.attrs, function(att){ #>
                                <div class="item"
                                     data-code="{{att.s_attr_code}}"
                                     data-title="{{att.s_attr_title}}"
                                     data-value="{{att.s_attr_value}}"
                                     data-price-format="{{att.s_price_format}}"
                                     data-price="{{att.s_price}}">
                                    {{att.s_attr_title}} {{att.s_attr_value}}
                                </div>
                                <# }) #>
                            </div>
                        </div>
                        <# } #>
                    </div>
                </div>
            </div>
        </div>
        <div class="fat-min-price {{item.s_min_price_class}}">
            {{item.s_min_price_format}}
        </div>
    </div>
    <# }) #>
</script>
