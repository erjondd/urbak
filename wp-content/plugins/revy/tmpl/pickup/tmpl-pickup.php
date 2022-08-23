<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 2/21/2019
 * Time: 2:33 PM
 */
?>

<script type="text/html" id="tmpl-fat-sb-pickup-item-template">
    <# _.each(data, function(item){ #>
    <tr data-id="{{item.b_id}}">
        <td>
            <div class="ui checkbox">
                <input type="checkbox" name="check-item" class="check-item"  data-id="{{item.b_id}}">
                <label></label>
            </div>
        </td>
        <td data-label="<?php echo esc_attr__('Create Date','revy');?>">{{item.b_create_date}}</td>
        <td data-label="<?php echo esc_attr__('Customer','revy');?>">
            {{item.c_first_name}} {{item.c_last_name}}
            <span class="extra-info">{{item.c_email}}</span>
            <span class="extra-info">{{item.c_phone_code}} {{item.c_phone}}</span>
        </td>
        <td data-label="<?php echo esc_attr__('Model','revy');?>">{{item.rm_name}}</td>
        <td data-label="<?php echo esc_attr__('Services','revy');?>">{{item.s_name}}</td>
        <td data-label="<?php echo esc_attr__('Attribute','revy');?>">{{item.b_attr_title}} {{item.b_attr_value}} </td>
        <td data-label="<?php echo esc_attr__('Duration','revy');?>">
            {{ item.b_service_duration_display }}
        </td>
        <td class="fat-sb-status" data-label="<?php echo esc_attr__('Status','revy');?>">
            <div class="ui floating dropdown labeled icon selection" >
                <input type="hidden" name="b_process_status" value="{{item.b_process_status}}" data-value="{{item.b_process_status}}"
                       data-onChange="RepairPickup.processUpdateProcessStatus" data-id="{{item.b_id}}">
                <i class="dropdown icon"></i>
                <span class="text"><div class="ui yellow empty circular label"></div> <?php echo esc_html__('Pending','revy'); ?></span>
                <div class="menu">
                    <div class="item" data-value="2">
                        <div class="ui red empty circular label"></div>
                        <?php  echo esc_html__('Canceled','revy'); ?>
                    </div>
                    <div class="item" data-value="1">
                        <div class="ui green empty circular label"></div>
                        <?php  echo esc_html__('Approved','revy'); ?>
                    </div>
                    <div class="item" data-value="0">
                        <div class="ui yellow empty circular label"></div>
                        <?php echo esc_html__('Pending','revy'); ?>
                    </div>
                    <div class="item" data-value="3">
                        <div class="ui empty empty circular label"></div>
                        <?php  echo esc_html__('Rejected','revy'); ?>
                    </div>
                </div>
        </td>
        <td>
            <button class=" ui icon button fat-item-bt-inline fat-sb-delete" data-onClick="RevyBooking.processDeleteBooking"
                    data-id="{{item.b_id}}" data-title="<?php echo esc_attr__('Delete','revy');?>">
                <i class="trash alternate outline icon"></i>
            </button>
        </td>
    </tr>
    <# }) #>
</script>



