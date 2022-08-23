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
    <div class="fat-sb-header-title"><?php echo esc_html__('Locations','revy');?></div>
</div>
<div class="fat-sb-locations-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left icon input ui-search fat-sb-search fat-no-margin">
                    <input type="text" id="loc_name_search" name="loc_name_search" data-onKeyUp="RevyLocations.nameSearchOnKeyUp"
                           placeholder="<?php echo esc_attr__('Search location ...','revy');?>">
                    <i class="search icon"></i>
                    <a class="fat-close" data-onClick="RevyLocations.closeSearchOnClick">
                        <i class="times icon"></i>
                    </a>
                </div>
                <div class="fat-sb-button-group">
                    <button class="ui basic blue button fat-bt-add" data-onClick="RevyLocations.btAddNewOnClick">
                        <i class="map marker alternate icon"></i>
                        <?php echo esc_html__('Add location','revy');?>
                    </button>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="ui grid fat-sb-list-locations">
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