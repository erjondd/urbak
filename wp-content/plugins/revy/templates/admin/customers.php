<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/20/2018
 * Time: 10:42 AM
 */

$setting = Revy_DB_Setting::instance();
$setting = $setting->get_setting();
$price_package_enable = isset($setting['price_package_enable']) && $setting['price_package_enable'] == '1' ? 1 : 0;
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Customers','revy');?></div>
</div>
<div class="fat-sb-customers-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left icon input ui-search fat-sb-search fat-no-margin">
                    <input type="text" id="c_name" placeholder="<?php echo esc_attr__('Search name or email ...','revy');?>"
                           data-onKeyUp="RevyCustomers.searchNameOnKeyUp" autocomplete="nope">
                    <i class="search icon"></i>
                    <a class="fat-close" data-onClick="RevyCustomers.closeSearchOnClick">
                        <i class="times icon"></i>
                    </a>
                </div>
                <div class="fat-sb-button-group">
                    <button class="ui primary basic button fat-bt-add" data-onClick="RevyCustomers.btAddOnClick">
                        <i class="user plus icon"></i>
                        <?php echo esc_html__('Add customer','revy');?>
                    </button>

                    <button class="ui negative basic button fat-bt-delete disabled" data-onClick="RevyCustomers.processDelete">
                        <i class="trash alternate outline icon"></i>
                        <?php echo esc_html__('Delete','revy');?>
                    </button>
                </div>
            </div>
        </div>
        <div class="content">
            <table class="ui single line table fat-sb-list-customers">
                <thead>
                <tr>
                    <th>
                        <div class="ui checkbox">
                            <input type="checkbox" name="example" class="table-check-all">
                            <label></label>
                        </div>
                    </th>
                    <th>
                        <?php echo esc_html__('Name','revy');?>
                        <span class="fat-sb-order-wrap" data-order-by="c_first_name">
                            <i class="caret up icon asc active" data-onClick="RevyCustomers.processOrder" data-order="asc"></i>
                            <i class="caret up icon revert desc "  data-onClick="RevyCustomers.processOrder" data-order="desc"></i>
                        </span>
                    </th>
                    <th><?php echo esc_html__('Phone','revy');?></th>
                    <th><?php echo esc_html__('Email','revy');?></th>
                    <th><?php echo esc_html__('Date of Birth','revy');?></th>
                    <th><?php echo esc_html__('Code','revy');?></th>
                    <?php if($price_package_enable==1): ?>
                        <th><?php echo esc_html__('Credit','revy');?></th>
                    <?php endif; ?>
                    <th><?php echo esc_html__('Notes','revy');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <tr class="fat-tr-not-found">
                        <td colspan="8">
                            <div class="ui fluid placeholder">
                                <div class="line"></div>
                                <div class="line"></div>
                                <div class="line"></div>
                                <div class="line"></div>
                                <?php if($price_package_enable==1): ?>
                                    <div class="line"></div>
                                <?php endif; ?>
                                <div class="line"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="fat-sb-pagination" data-obj="RevyCustomers" data-func="loadCustomer">

            </div>
        </div>
    </div>
</div>