<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/20/2018
 * Time: 10:42 AM
 */
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Coupons','revy');?></div>
</div>
<div class="fat-sb-coupons-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left icon input ui-search fat-sb-search fat-no-margin" >
                    <input type="text" id="cp_code" autocomplete="nope" data-onKeyUp="RevyCoupon.codeSearchKeyUp"
                           placeholder="<?php echo esc_attr__('Search code ...','revy');?>" autocomplete="nope">
                    <i class="search icon"></i>
                    <a class="fat-close" data-onClick="RevyCoupon.closeSearchOnClick">
                        <i class="times icon"></i>
                    </a>
                </div>
                <div class="fat-sb-button-group">
                    <button class="ui primary basic button fat-bt-add" data-onClick="RevyCoupon.btAddOnClick">
                        <i class="qrcode icon"></i>
                        <?php echo esc_html__('Add coupon','revy');?>
                    </button>

                    <button class="ui negative basic button fat-bt-delete disabled" data-onClick="RevyCoupon.processDelete">
                        <i class="trash alternate outline icon"></i>
                        <?php echo esc_html__('Delete','revy');?>
                    </button>
                </div>
            </div>
        </div>
        <div class="content">
            <table class="ui single line table fat-sb-list-coupons">
                <thead>
                <tr>
                    <th>
                        <div class="ui checkbox">
                            <input type="checkbox" name="example" class="table-check-all">
                            <label></label>
                        </div>
                    </th>
                    <th><?php echo esc_html__('Code','revy');?></th>
                    <th><?php echo esc_html__('Discount type','revy');?></th>
                    <th><?php echo esc_html__('Amount','revy');?></th>
                    <th><?php echo esc_html__('Start apply','revy');?></th>
                    <th><?php echo esc_html__('Expire','revy');?></th>
                    <th><?php echo esc_html__('Times to use','revy');?></th>
                    <th><?php echo esc_html__('Used','revy');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr class="fat-tr-not-found">
                    <td colspan="9">
                        <div class="ui fluid placeholder">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="fat-sb-pagination" data-obj="RevyCoupon" data-func="loadCoupon">

            </div>
        </div>
    </div>
</div>