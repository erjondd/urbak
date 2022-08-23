<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/21/2018
 * Time: 9:49 AM
 */
?>
<script type="text/html" id="tmpl-fat-sb-models-template">
    <div class="ui modal tiny fat-semantic-container fat-model-form">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Add new model', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">

                <div class="one fields">
                    <div class="ui image-field " id="rm_image_id" data-image-id="{{data.model.rm_image_id}}"
                         data-image-url="{{data.model.rm_image_url}}">
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Model name', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="rm_name" id="rm_name" value="{{data.model.rm_name}}"
                                   placeholder="<?php echo esc_attr__('Model name', 'revy'); ?>"
                                   required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter model name', 'revy'); ?>
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
                            <input type="text" name="rm_order" data-type="int" data-step="1" data-min="1"
                                   tabindex="7"
                                   id="rm_order" value="{{data.model.rm_order}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Group (ex: Iphone, Ipad,...)', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="rm_group" id="rm_group" value="{{data.model.rm_group}}"
                                   placeholder="<?php echo esc_attr__('Group name', 'revy'); ?>"
                                   required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter group name', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label ><?php echo esc_html__('Device', 'revy'); ?></label>
                        <div class="ui selection rm-device dropdown top left pointing has-icon" data-direction="upward">
                            <i class="server icon"></i>
                            <input type="hidden" name="rm_device_id" id="rm_device_id"
                                   value="{{data.model.rm_device_id}}" tabindex="1"
                                   required>
                            <div class="text"><?php echo esc_html__('Select device', 'revy'); ?></div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <# _.each(data.devices, function(item){ #>
                                <div class="item"
                                     data-value="{{item.rd_id}}">{{item.rd_name}}
                                </div>
                                <# }) #>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label ><?php echo esc_html__('Brand', 'revy'); ?></label>
                        <div class="ui selection rm-brand dropdown top left pointing has-icon" data-direction="upward">
                            <i class="server icon"></i>
                            <input type="hidden" name="rm_brand_id" id="rm_brand_id"
                                   value="{{data.model.rm_brand_id}}" tabindex="1"
                                   required>
                            <div class="text"><?php echo esc_html__('Select brand', 'revy'); ?></div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <# _.each(data.brands, function(item){ #>
                                <div class="item"
                                     data-value="{{item.rb_id}}">{{item.rb_name}}
                                </div>
                                <# }) #>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.model.rm_active==1){ #>
                            <input type="checkbox" name="rm_active" id="rm_active"
                                   value="1"
                                   checked tabindex="14">
                            <# }else{ #>
                            <input type="checkbox" name="rm_active" id="rm_active"
                                   value="1"
                                   tabindex="14">
                            <# } #>
                            <label><?php echo esc_html__('Publish to frontend', 'revy'); ?>
                                <div class="ui icon ui-tooltip"
                                     data-content="<?php echo esc_attr__('If checked, model will be displayed on booking form', 'revy'); ?>">
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
                <div class="ui button fat-submit-modal" data-onClick="RevyModels.processSubmitModel"
                     data-id="{{data.model.rm_id}}"
                     data-success-message="<?php echo esc_attr__('Model has been saved', 'revy'); ?>">
                    <i class="save outline icon"></i>
                    <?php echo esc_html__('Save', 'revy'); ?>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-model-item-template">
    <# _.each(data, function(item){ #>
    <tr data-id="{{item.rm_id}}">
        <td>
            <div class="ui checkbox">
                <input type="checkbox" name="rm_id" class="check-item" data-id="{{item.rm_id}}">
                <label></label>
            </div>
        </td>
        <td class="fat-rm-id" data-label="<?php echo esc_attr__('ID', 'revy'); ?>">
            {{item.rm_id }}
        </td>

        <td class="fat-rm-name" data-label="<?php echo esc_attr__('Name', 'revy'); ?>">
            {{item.rm_name }}
        </td>

        <td class="fat-rm-group" data-label="<?php echo esc_attr__('Group', 'revy'); ?>">
            {{item.rm_group}}
        </td>

        <td class="fat-rm-device" data-label="<?php echo esc_attr__('Device', 'revy'); ?>">
            {{item.rd_name}}
        </td>

        <td class="fat-rm-brand" data-label="<?php echo esc_attr__('Brand', 'revy'); ?>">
            {{item.rb_name}}
        </td>

        <td class="fat-rm-status" data-label="<?php echo esc_attr__('Status', 'revy'); ?>">
            <# if(item.rm_active == 1){ #>
                <?php echo esc_html__('Active','revy');?>
            <# }else{ #>
                <?php echo esc_html__('InActive','revy');?>
            <# } #>
        </td>
        <td>
            <button class=" ui icon button fat-item-bt-inline fat-sb-delete" data-onClick="RevyModels.processDelete"
                    data-id="{{item.rm_id}}" data-title="<?php echo esc_attr__('Delete', 'revy'); ?>">
                <i class="trash alternate outline icon"></i>
            </button>

            <button class="ui icon button fat-item-bt-inline fat-sb-clone" data-onClick="RevyModels.processClone"
                    data-id="{{item.rm_id}}" data-title="<?php echo esc_attr__('Clone', 'revy'); ?>">
                <i class="clone outline icon"></i>
            </button>

            <button class=" ui icon button fat-item-bt-inline fat-sb-edit"
                    data-onClick="RevyModels.processViewDetail"
                    data-id="{{item.rm_id}}" data-title="<?php echo esc_attr__('Edit', 'revy'); ?>">
                <i class="edit outline icon"></i>
            </button>
        </td>
    </tr>
    <# }) #>
</script>