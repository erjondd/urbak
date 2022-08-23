<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/21/2018
 * Time: 9:49 AM
 */
?>
<script type="text/html" id="tmpl-fat-sb-devices-template">
    <div class="ui modal tiny fat-semantic-container fat-devices-form">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Add new devices','revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="one fields">
                    <div class="ui image-field " id="rd_image_id" data-image-id="{{data.rd_image_id}}"
                         data-image-url="{{data.rd_image_url}}">
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Name','revy');?><span class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" value="{{data.rd_name}}" name="rd_name" id="rd_name" placeholder="<?php echo esc_attr__('Device name','revy');?>" required >
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter name','revy');?>
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
                            <input type="text" name="rd_order" data-type="int" data-step="1" data-min="1"
                                   tabindex="7"
                                   id="rd_order" value="{{data.rd_order}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.rd_active==1){ #>
                            <input type="checkbox" name="rd_active" id="rd_active"
                                   value="1"
                                   checked tabindex="14">
                            <# }else{ #>
                            <input type="checkbox" name="rd_active" id="rd_active"
                                   value="1"
                                   tabindex="14">
                            <# } #>
                            <label><?php echo esc_html__('Publish to frontend', 'revy'); ?>
                                <div class="ui icon ui-tooltip"
                                     data-content="<?php echo esc_attr__('If checked, device will be displayed on booking form', 'revy'); ?>">
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
                <?php echo esc_html__('Cancel','revy');?>
            </button>
            <div class="blue ui buttons">
                <div class="ui button fat-submit-modal" data-id="{{data.rd_id}}" data-onClick="RevyDevices.processSubmitDevice">
                    <i class="save outline icon"></i>
                    <?php echo esc_html__('Save','revy');?>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-devices-item-template">
    <# _.each(data, function(item){ #>
        <div class="four wide column">
            <div class="ui items ">
                <div class="item fat-pd-10 fat-border-spin fat-hover fat-hover-link" data-id="{{item.rd_id}}">
                    <div class="ui tiny image">
                        <# if (item.rd_image_url!=''){ #>
                        <img class="fat-border-round fat-box-shadow fat-img-80" src="{{item.rd_image_url}}"
                             data-image-id="{{item.rd_image_id}}">
                        <# }else{ #>
                        <span class="fat-no-thumb fat-img-80"></span>
                        <# } #>
                    </div>
                    <div class="content">
                        <div class="header thin">
                          <span class="fat-device-name">{{item.rd_name}}</span>
                        </div>
                    </div>
                    <button class=" ui icon button fat-item-bt-inline fat-sb-delete" data-onClick="RevyDevices.processDelete"
                            data-id="{{item.rd_id}}" data-title="<?php echo esc_attr__('Delete','revy');?>">
                        <i class="trash alternate outline icon"></i>
                    </button>

                    <button class=" ui icon button fat-item-bt-inline fat-sb-edit" data-onClick="RevyDevices.showPopupDevice"
                            data-id="{{item.rd_id}}" data-title="<?php echo esc_attr__('Edit','revy');?>">
                        <i class="edit outline icon"></i>
                    </button>
                </div>
            </div>
        </div>
    <# }) #>
</script>