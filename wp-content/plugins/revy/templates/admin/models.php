<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/20/2018
 * Time: 10:42 AM
 */
$model_db = Revy_DB_Models::instance();
$filter_dic = $model_db->get_filter_dic();

$setting = Revy_DB_Setting::instance();
$setting = $setting->get_setting();
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Models','revy');?></div>
</div>
<?php do_action('revy_import_notices'); ?>
<div class="fat-sb-models-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left icon input ui-search fat-sb-search fat-no-margin">
                    <input type="text" id="rm_name" placeholder="<?php echo esc_attr__('Search model name ...','revy');?>"
                           data-onKeyUp="RevyModels.searchNameOnKeyUp" autocomplete="nope">
                    <i class="search icon"></i>
                    <a class="fat-close" data-onClick="RevyModels.closeSearchOnClick">
                        <i class="times icon"></i>
                    </a>
                </div>

                <div class="ui selection dropdown clearable top left pointing has-icon" >
                    <input type="hidden" name="rm_search_brand_id" id="rm_search_brand_id" tabindex="1" data-onChange="RevyModels.searchDropdownChange">
                    <div class="text"><?php echo esc_html__('Select brand', 'revy'); ?></div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?php foreach($filter_dic['brands'] as $br) { ?>
                            <div class="item" data-value="<?php echo esc_attr($br->rb_id);?>>"> <?php echo esc_attr($br->rb_name);?> </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="ui selection dropdown clearable top left pointing has-icon" >
                    <input type="hidden" name="rm_search_device_id" id="rm_search_device_id" tabindex="1" data-onChange="RevyModels.searchDropdownChange">
                    <div class="text"><?php echo esc_html__('Select category', 'revy'); ?></div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?php foreach($filter_dic['devices'] as $dv) { ?>
                            <div class="item" data-value="<?php echo esc_attr($dv->rd_id);?>>"> <?php echo esc_attr($dv->rd_name);?> </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="fat-sb-button-group">
                    <?php if ($setting['enable_import'] == '1'): ?>
                        <button class="ui primary basic button fat-bt-add" data-onClick="RevyModels.openImport">
                            <i class="cloud upload icon"></i>
                            <?php echo esc_html__('Import model', 'rp-booking'); ?>
                        </button>
                    <?php endif; ?>

                    <button class="ui primary basic button fat-bt-add" data-onClick="RevyModels.btAddOnClick">
                        <i class="mobile alternate icon"></i>
                        <?php echo esc_html__('Add model','revy');?>
                    </button>

                    <button class="ui negative basic button fat-bt-delete disabled" data-onClick="RevyModels.processDelete">
                        <i class="trash alternate outline icon"></i>
                        <?php echo esc_html__('Delete','revy');?>
                    </button>
                </div>
            </div>
        </div>
        <div class="content models">
            <table class="ui single line table fat-sb-list-models">
                <thead>
                <tr>
                    <th>
                        <div class="ui checkbox">
                            <input type="checkbox" name="example" class="table-check-all">
                            <label></label>
                        </div>
                    </th>
                    <th>
                        <?php echo esc_html__('ID','revy');?>
                    </th>
                    <th>
                        <?php echo esc_html__('Name','revy');?>
                    </th>
                    <th>
                        <?php echo esc_html__('Group','revy');?>
                    </th>
                    <th><?php echo esc_html__('Device','revy');?></th>
                    <th><?php echo esc_html__('Brand','revy');?></th>
                    <th><?php echo esc_html__('Status','revy');?></th>
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
                                <div class="line"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="fat-sb-pagination" data-obj="RevyModels" data-func="loadModel">

            </div>
        </div>

        <div class="content fat-sb-import-section">
            <div class="ui icon header">
                <i class="cloud upload icon"></i>
                <div class="description"><?php esc_attr_e('Please select cvs file and click import button', 'revy'); ?></div>
            </div>
            <div class="field">
                <form method="post" enctype="multipart/form-data" id="form_import">
                    <div class="fat-mg-top-15">
                        <input type="file" name="import_file" accept=".csv, application/vnd.ms-excel"/>
                        <input type="hidden" name="revy_action" value="import"/>
                        <input type="hidden" name="import_type" value="model"/>
                        <?php wp_nonce_field('revy_import_nonce', 'revy_import_nonce'); ?>
                    </div>
                    <div class="fat-mg-top-30">
                        <button class="ui primary basic button fat-bt-import" type="submit" >
                            <i class="cloud upload icon"></i>
                            <?php echo esc_attr__('Import', 'revy'); ?>
                        </button>
                        <button class="ui basic button fat-bt-cancel" type="button"  data-onClick="RevyModels.closeImport">
                            <i class="close icon"></i>
                            <?php echo esc_attr__('Close', 'revy'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>