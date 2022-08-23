<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/21/2018
 * Time: 9:49 AM
 */
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();
?>
<script type="text/html" id="tmpl-fat-sb-garages-template">
    <div class="ui modal tiny fat-semantic-container fat-garage-form">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Add new garage', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">

                <div class="one fields">
                    <div class="ui image-field " id="rg_image_id" data-image-id="{{data.rg_image_id}}"
                         data-image-url="{{data.rg_image_url}}">
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Garage name', 'revy'); ?><span
                                class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="rg_name" id="rg_name" value="{{data.rg_name}}"
                                   placeholder="<?php echo esc_attr__('Garage name', 'revy'); ?>"
                                   required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter garage name', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label><?php echo esc_html__('Order displayed', 'revy'); ?>
                            <div class="ui icon ui-tooltip"
                                 data-content="<?php echo esc_attr__('Order displayed on the list', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </label>
                        <div class="ui action input number has-button">
                            <button class="ui icon button number-decrease">
                                <i class="minus-icon"></i>
                            </button>
                            <input type="text" name="rg_order" data-type="int" data-step="1" data-min="1"
                                   tabindex="7"
                                   id="rg_order" value="{{data.rg_order}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Email', 'revy'); ?></label>
                        <div class="ui left icon input ">
                            <input type="email" name="rg_email" id="rg_email" value="{{data.rg_email}}"
                                   placeholder="<?php echo esc_attr__('Email', 'revy'); ?>">
                            <i class="edit outline icon"></i>
                        </div>
                    </div>
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Phone', 'revy'); ?></label>
                        <div class="ui left icon input ">
                            <input type="text" name="rg_email" id="rg_phone" value="{{data.rg_phone}}"
                                   placeholder="<?php echo esc_attr__('Phone', 'revy'); ?>">
                            <i class="phone icon"></i>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Address', 'revy'); ?></label>
                        <div class="ui left icon input ">
                            <input type="text" name="rg_address" id="rg_address" value="{{data.rg_address}}"
                                   placeholder="<?php echo esc_attr__('Address', 'revy'); ?>">
                            <i class="edit outline icon"></i>
                        </div>
                    </div>
                </div>

                <?php if (isset($setting['mapbox_api_key']) && $setting['mapbox_api_key']) : ?>
                    <div class="one fields">
                        <div class="field fat-mapbox-wrap">
                            <label for="rg_map"><?php echo esc_html__('Map', 'revy'); ?><span
                                        class="required"> *</span></label>
                            <input type="text" name="rg_map" value="{{data.rg_map}}" id="rg_map"
                                   class="fat-mapbox-location" />
                            <div class="fat-mapbox" id="fat_mapbox"
                                 data-latitude="{{data.rg_latitude}}"
                                 data-longitude="{{data.rg_longitude}}"
                                 data-access-token="<?php echo esc_attr($setting['mapbox_api_key']); ?>"
                                 data-map-type="roadmap"
                                 data-zoom="15"
                            >
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="one fields">
                    <div class="field">
                        <label><?php echo esc_html__('Description', 'revy'); ?></label>
                        <textarea rows="3" id="rg_description">{{data.rg_description}}</textarea>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.rg_active==1){ #>
                            <input type="checkbox" name="rg_active" id="rg_active"
                                   value="1"
                                   checked tabindex="14">
                            <# }else{ #>
                            <input type="checkbox" name="rg_active" id="rg_active"
                                   value="1"
                                   tabindex="14">
                            <# } #>
                            <label><?php echo esc_html__('Publish to frontend', 'revy'); ?>
                                <div class="ui icon ui-tooltip"
                                     data-content="<?php echo esc_attr__('If checked, garage will be displayed on booking form', 'revy'); ?>">
                                    <i class="question circle icon"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel', 'revy'); ?>
            </button>
            <div class="blue ui buttons">
                <div class="ui button fat-submit-modal" data-onClick="RevyGarages.processSubmitGarage"
                     data-id="{{data.rg_id}}"
                     data-success-message="<?php echo esc_attr__('Garage has been saved', 'revy'); ?>">
                    <i class="save outline icon"></i>
                    <?php echo esc_html__('Save', 'revy'); ?>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-garage-item-template">
    <# _.each(data, function(item){ #>
    <tr data-id="{{item.rg_id}}">

        <td class="fat-rg-id" data-label="<?php echo esc_attr__('ID', 'revy'); ?>">
            {{item.rg_id }}
        </td>

        <td class="fat-rg-name" data-label="<?php echo esc_attr__('Name', 'revy'); ?>">
            {{item.rg_name }}
        </td>

        <td class="fat-rg-address" data-label="<?php echo esc_attr__('Address', 'revy'); ?>">
            {{item.rg_address}}
        </td>

        <td class="fat-rg-email" data-label="<?php echo esc_attr__('Email', 'revy'); ?>">
            {{item.rg_email}}
        </td>

        <td class="fat-rg-phone" data-label="<?php echo esc_attr__('Phone', 'revy'); ?>">
            {{item.rg_phone}}
        </td>

        <td class="fat-rg-status" data-label="<?php echo esc_attr__('Status', 'revy'); ?>">
            <# if(item.rg_active == 1){ #>
            <?php echo esc_html__('Active','revy');?>
            <# }else{ #>
            <?php echo esc_html__('InActive','revy');?>
            <# } #>
        </td>
        <td>
            <button class=" ui icon button fat-item-bt-inline fat-sb-delete" data-onClick="RevyGarages.processDelete"
                    data-id="{{item.rg_id}}" data-title="<?php echo esc_attr__('Delete', 'revy'); ?>">
                <i class="trash alternate outline icon"></i>
            </button>

            <button class=" ui icon button fat-item-bt-inline fat-sb-edit"
                    data-onClick="RevyGarages.processViewDetail"
                    data-id="{{item.rg_id}}" data-title="<?php echo esc_attr__('Edit', 'revy'); ?>">
                <i class="edit outline icon"></i>
            </button>
        </td>
    </tr>
    <# }) #>
</script>