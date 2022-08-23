<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/21/2018
 * Time: 9:49 AM
 */
?>
<script type="text/html" id="tmpl-fat-sb-coupon-template">
    <div class="ui modal tiny fat-semantic-container fat-coupon-form">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Add new coupon','revy');?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Coupon code','revy');?><span class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="cp_code" id="cp_code" value="{{data.cp_code}}" placeholder="<?php echo esc_attr__('Code','revy');?>" required >
                            <i class="qrcode icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter coupon code','revy');?>
                        </div>
                    </div>

                </div>

                <div class="two fields">
                    <div class="field">
                        <label><?php echo esc_html__('Discount type','revy'); ?> <span
                                class="required"> *</span></label>
                        <div class="ui selection dropdown  fat-sb-discount-type"  data-onChange="RevyCoupon.discountOnChange">
                            <input type="hidden" name="cp_discount_type" id="cp_discount_type" value="{{data.cp_discount_type}}" tabindex="3" required>
                            <i class="sliders horizontal icon"></i>
                            <i class="dropdown icon"></i>
                            <div class="text"><?php echo esc_html__('Select discount type','revy'); ?></div>
                            <div class="menu">
                                <div class="item" data-value="1"><?php echo esc_html__('Percentage discount','revy');?></div>
                                <div class="item" data-value="2"><?php echo esc_html__('Fixed discount','revy');?></div>
                            </div>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please select discount type','revy'); ?>
                        </div>
                    </div>

                    <div class="field ">
                        <label><?php echo esc_html__('Discount amount','revy'); ?> <span
                                class="required"> *</span></label>
                        <div class="ui left icon input number fat-sb-coupon-amount" >
                            <input type="text" name="cp_amount"  data-type="decimal" id="cp_amount" value="{{data.cp_amount}}" tabindex="2"
                                   placeholder="<?php echo esc_attr__('Amount','revy'); ?>" required>
                            <i class="dollar sign icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter discount amount','revy'); ?>
                        </div>
                    </div>


                </div>
                <div class="two fields">
                    <div class="field">
                        <label for="cp_start_date"><?php echo esc_html__('Start apply','revy');?></label>
                        <div class="date-picker-wrap has-icon">
                            <input type="text" value="{{data.cp_start_date}}" data-date="{{data.cp_start_date}}" class="date-picker" name="cp_start_date" id="cp_start_date">
                            <i class="calendar alternate outline icon"></i>
                        </div>

                    </div>
                    <div class="field">
                        <label for="cp_expire"><?php echo esc_html__('Expiry Date','revy');?></label>
                        <div class="date-picker-wrap has-icon">
                            <input type="text" value="{{data.cp_expire}}" data-date="{{data.cp_expire}}" class="date-picker" name="cp_expire" id="cp_expire">
                            <i class="calendar alternate outline icon"></i>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label>
                            <?php echo esc_html__('Apply for services','revy'); ?>
                        </label>
                        <div class="ui bottom left pointing multiple search selection dropdown fat-sb-apply-services">
                            <input type="hidden" name="cp_apply_to" id="cp_apply_to"  value="{{data.cp_apply_to}}" tabindex="8">
                            <i class="dropdown icon"></i>
                            <div class="text"><?php echo  esc_html__('Select service','revy');?></div>
                            <div class="menu">
                                <div class="ui icon search input">
                                    <i class="search icon"></i>
                                    <input type="text" placeholder="<?php echo esc_attr__('Search service...','revy');?> ">
                                </div>
                                <div class="scrolling menu">
                                    <# _.each(data.services, function(item){ #>
                                    <div class="item" data-value="{{item.s_id}}">
                                        {{item.s_name}}
                                    </div>
                                    <# }) #>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="one fields">
                    <div class="field">
                        <label>
                            <?php echo esc_html__('Exclude services','revy'); ?>
                        </label>
                        <div class="ui bottom left pointing multiple search selection dropdown fat-sb-exclude-services">
                            <input type="hidden" name="cp_exclude" id="cp_exclude"  value="{{data.cp_exclude}}" tabindex="9">
                            <i class="dropdown icon"></i>
                            <div class="text"><?php echo  esc_html__('Select service','revy');?></div>
                            <div class="menu">
                                <div class="ui icon search input">
                                    <i class="search icon"></i>
                                    <input type="text" placeholder="<?php echo esc_attr__('Search service...','revy');?> ">
                                </div>
                                <div class="scrolling menu">
                                    <# _.each(data.services, function(item){ #>
                                    <div class="item" data-value="{{item.s_id}}">
                                        {{item.s_name}}
                                    </div>
                                    <# }) #>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label><?php echo esc_html__('Times to use','revy'); ?> <span
                                class="required"> *</span></label>
                        <div class="ui left icon input number" >

                            <input type="text" name="cp_times_use"  data-type="decimal" id="cp_times_use" value="{{data.cp_times_use}}" tabindex="2"
                                   placeholder="<?php echo esc_attr__('Amount','revy'); ?>" required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter times to use','revy'); ?>
                        </div>
                    </div>
                </div>
                <div class="one fields">
                    <div class="field">
                        <label><?php echo esc_html__('Notes','revy');?></label>
                        <textarea rows="5" id="cp_description" name="cp_description">{{data.cp_description}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel','revy');?>
            </button>
            <div class="blue ui buttons">
                <div class="ui button fat-submit-modal" data-onClick="RevyCoupon.processSubmitCoupon"
                     data-id="{{data.cp_id}}" data-success-message="<?php echo esc_attr__('Coupon has been saved','revy'); ?>">
                    <i class="save outline icon"></i>
                    <?php echo esc_html__('Save','revy');?>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-coupon-item-template">
    <# _.each(data, function(item){ #>
    <tr data-id="{{item.cp_id}}">
        <td>
            <div class="ui checkbox">
                <input type="checkbox" name="cp_id" class="check-item"  data-id="{{item.cp_id}}">
                <label></label>
            </div>
        </td>
        <td class="fat-cp-code" data-label="<?php echo esc_attr__('Code','revy');?>">{{item.cp_code}}</td>
        <td class="fat-cp-discount-type" data-label="<?php echo esc_attr__('Discount type','revy');?>">
            <# if(item.cp_discount_type==1){ #>
                <?php echo esc_html__('Percentage discount','revy');?>
            <# }else{ #>
                <?php echo esc_html__('Fixed discount','revy');?>
            <# } #>
        </td>
        <td class="fat-cp-amount" data-label="<?php echo esc_attr__('Amount','revy');?>">{{item.cp_amount}}</td>
        <td class="fat-cp-start-date"  data-label="<?php echo esc_attr__('Start apply','revy');?>">{{item.cp_start_date}}</td>
        <td class="fat-cp-expire"  data-label="<?php echo esc_attr__('Expire','revy');?>">{{item.cp_expire}}</td>
        <td class="fat-cp-times-to-use" data-label="<?php echo esc_attr__('Times to use','revy');?>">{{item.cp_times_use}}</td>
        <td class="fat-cp-use-count" data-label="<?php echo esc_attr__('Used','revy');?>">{{item.cp_use_count}}</td>
        <td>
            <div class="ps-relative">
                <button class=" ui icon button fat-item-bt-inline fat-sb-delete" data-onClick="RevyCoupon.processDelete"
                        data-id="{{item.cp_id}}" data-title="<?php echo esc_attr__('Delete','revy');?>">
                    <i class="trash alternate outline icon"></i>
                </button>

                <button class=" ui icon button fat-item-bt-inline fat-sb-edit" data-onClick="RevyCoupon.processViewDetail"
                        data-id="{{item.cp_id}}" data-title="<?php echo esc_attr__('Edit','revy');?>">
                    <i class="edit outline icon"></i>
                </button>
            </div>
        </td>
    </tr>
    <# }) #>
</script>