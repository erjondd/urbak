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
    <div class="fat-sb-header-title"><?php echo esc_html__('Garages','revy');?></div>
</div>
<div class="fat-sb-garages-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left icon input ui-search fat-sb-search fat-no-margin">
                    <input type="text" id="rg_name" placeholder="<?php echo esc_attr__('Search garage name ...','revy');?>"
                           data-onKeyUp="RevyGarages.searchNameOnKeyUp" autocomplete="nope">
                    <i class="search icon"></i>
                    <a class="fat-close" data-onClick="RevyGarages.closeSearchOnClick">
                        <i class="times icon"></i>
                    </a>
                </div>

                <div class="fat-sb-button-group">
                    <button class="ui primary basic button fat-bt-add" data-onClick="RevyGarages.btAddOnClick">
                        <i class="warehouse icon"></i>
                        <?php echo esc_html__('Add garage','revy');?>
                    </button>

                </div>
            </div>
        </div>
        <div class="content">
            <table class="ui single line table fat-sb-list-garages">
                <thead>
                <tr>
                    <th>
                        <?php echo esc_html__('ID','revy');?>
                    </th>
                    <th>
                        <?php echo esc_html__('Name','revy');?>
                    </th>
                    <th>
                        <?php echo esc_html__('Location','revy');?>
                    </th>
                    <th><?php echo esc_html__('Email','revy');?></th>
                    <th><?php echo esc_html__('Phone','revy');?></th>
                    <th><?php echo esc_html__('Status','revy');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr class="fat-tr-not-found">
                    <td colspan="7">
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

            <div class="fat-sb-pagination" data-obj="RevyGarages" data-func="loadGarage">

            </div>
        </div>
    </div>
</div>