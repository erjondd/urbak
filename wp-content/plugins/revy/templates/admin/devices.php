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
    <div class="fat-sb-header-title"><?php echo esc_html__('Devices','revy');?></div>
</div>
<div class="fat-sb-devices-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="fat-sb-button-group">
                    <button class="ui basic blue button fat-bt-add" data-onClick="RevyDevices.btAddNewOnClick">
                        <i class="icon user"></i>
                        <?php echo esc_html__('Add Device','revy');?>
                    </button>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="ui grid fat-sb-list-devices">
                <div class="sixteen wide column">
                    <div class="ui fluid placeholder">
                        <div class="image header">
                            <div class="medium line"></div>
                            <div class="full line"></div>
                        </div>
                        <div class="paragraph">
                            <div class="full line"></div>
                            <div class="medium line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>