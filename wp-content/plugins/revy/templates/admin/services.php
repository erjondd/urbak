<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/20/2018
 * Time: 10:42 AM
 */
$db = Revy_DB_Services::instance();
$models = $db->get_filter_dic();

$db = Revy_DB_Garages::instance();
$garages = $db->get_garages_dic(0);

$setting = Revy_DB_Setting::instance();
$setting = $setting->get_setting();
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Services','revy');?></div>
</div>
<?php do_action('revy_import_notices'); ?>
<div class="fat-sb-services-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left icon input ui-search fat-sb-search fat-no-margin">
                    <input type="text" id="s_name" placeholder="<?php echo esc_attr__('Search service name ...','revy');?>"
                           data-onKeyUp="RevyService.searchNameOnKeyUp" autocomplete="nope">
                    <i class="search icon"></i>
                    <a class="fat-close" data-onClick="RevyService.closeSearchOnClick">
                        <i class="times icon"></i>
                    </a>
                </div>

                <div class="ui selection search dropdown clearable top left pointing has-icon" >
                    <input type="hidden" name="rm_search_model_id" id="rm_search_model_id" tabindex="1" data-onChange="RevyService.searchDropdownChange">
                    <div class="text"><?php echo esc_html__('Select model', 'revy'); ?></div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?php foreach($models as $md) { ?>
                            <div class="item" data-value="<?php echo esc_attr($md->rm_id);?>>"> <?php echo esc_attr($md->rm_name);?> </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="ui selection search dropdown clearable top left pointing has-icon" >
                    <input type="hidden" name="search_garage_id" id="search_garage_id" tabindex="1" data-onChange="RevyService.searchDropdownChange">
                    <div class="text"><?php echo esc_html__('Select garages', 'revy'); ?></div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?php foreach($garages as $gr) { ?>
                            <div class="item" data-value="<?php echo esc_attr($gr->rg_id);?>"> <?php echo esc_attr($gr->rg_name);?> </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="fat-sb-button-group">

                    <?php if ($setting['enable_import'] == '1'): ?>
                        <button class="ui primary basic button fat-bt-add" data-onClick="RevyService.openImport">
                            <i class="cloud upload icon"></i>
                            <?php echo esc_html__('Import services', 'rp-booking'); ?>
                        </button>
                    <?php endif; ?>

                    <button class="ui primary basic button fat-bt-add" data-onClick="RevyService.processAddService">
                        <i class="wrench icon"></i>
                        <?php echo esc_html__('Add service','revy');?>
                    </button>

                    <button class="ui negative basic button fat-bt-delete disabled" data-onClick="RevyService.processDeleteService">
                        <i class="trash alternate outline icon"></i>
                        <?php echo esc_html__('Delete','revy');?>
                    </button>


                </div>
            </div>
        </div>
        <div class="content services">
            <table class="ui single line table fat-sb-list-services">
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
                    </th>
                    <th><?php echo esc_html__('Model','revy');?></th>
                    <th><?php echo esc_html__('Duration','revy');?></th>
                    <th><?php echo esc_html__('Max Slot','revy');?></th>
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
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="fat-sb-pagination" data-obj="RevyService" data-func="loadServices">

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
                        <input type="hidden" name="import_type" value="service"/>
                        <?php wp_nonce_field('revy_import_nonce', 'revy_import_nonce'); ?>
                    </div>
                    <div class="fat-mg-top-30">
                        <button class="ui primary basic button fat-bt-import" type="submit" >
                            <i class="cloud upload icon"></i>
                            <?php echo esc_attr__('Import', 'revy'); ?>
                        </button>
                        <button class="ui basic button fat-bt-cancel" type="button"  data-onClick="RevyService.closeImport">
                            <i class="close icon"></i>
                            <?php echo esc_attr__('Close', 'revy'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>