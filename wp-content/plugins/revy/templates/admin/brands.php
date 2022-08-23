<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/20/2018
 * Time: 10:42 AM
 */
$setting = Revy_DB_Setting::instance();
$setting = $setting->get_setting();
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL . '/images/plugin_logo.png'); ?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Brands', 'revy'); ?></div>
</div>
<?php do_action('revy_import_notices'); ?>
<div class="fat-sb-brands-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="fat-sb-button-group">
                    <?php if ($setting['enable_import'] == '1'): ?>
                        <button class="ui primary basic button fat-bt-add" data-onClick="RevyBrands.openImport">
                            <i class="cloud upload icon"></i>
                            <?php echo esc_html__('Import Brand', 'rp-booking'); ?>
                        </button>
                    <?php endif; ?>

                    <button class="ui basic blue button fat-bt-add" data-onClick="RevyBrands.showPopupBrand">
                        <i class="icon user"></i>
                        <?php echo esc_html__('Add Brand', 'revy'); ?>
                    </button>
                </div>
            </div>
        </div>

        <div class="content brands">
            <div class="ui grid fat-sb-list-brands">
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
                        <input type="hidden" name="import_type" value="brand"/>
                        <?php wp_nonce_field('revy_import_nonce', 'revy_import_nonce'); ?>
                    </div>
                    <div class="fat-mg-top-30">
                        <button class="ui primary basic button fat-bt-import" type="submit" >
                            <i class="cloud upload icon"></i>
                            <?php echo esc_attr__('Import', 'revy'); ?>
                        </button>
                        <button class="ui basic button fat-bt-cancel" type="button"  data-onClick="Revy.closeImport">
                            <i class="close icon"></i>
                            <?php echo esc_attr__('Close', 'revy'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>