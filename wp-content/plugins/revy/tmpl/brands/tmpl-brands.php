<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/21/2018
 * Time: 9:49 AM
 */
?>
<script type="text/html" id="tmpl-fat-sb-brands-template">
    <div class="ui modal tiny fat-semantic-container fat-brands-form">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Add new brand', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="one fields">
                    <div class="ui image-field " id="rb_image_id" data-image-id="{{data.brand.rb_image_id}}"
                         data-image-url="{{data.brand.rb_image_url}}">
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label for="rb_name"><?php echo esc_html__('Name', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" value="{{data.brand.rb_name}}" name="rb_name" id="rb_name"
                                   placeholder="<?php echo esc_attr__('Brand name', 'revy'); ?>" required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter name', 'revy'); ?>
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
                            <input type="text" name="rb_order" data-type="int" data-step="1" data-min="1"
                                   tabindex="7"
                                   id="rb_order" value="{{data.brand.rb_order}}">
                            <button class="ui icon button number-increase">
                                <i class="plus-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field ">
                        <label ><?php echo esc_html__('Devices', 'revy'); ?></label>
                        <div class="ui multiple selection  dropdown top left pointing has-icon" data-direction="upward">
                            <i class="server icon"></i>
                            <input type="hidden" name="rb_device_ids" id="rb_device_ids"
                                   value="{{data.brand.rb_device_ids}}" tabindex="1"
                                   required>
                            <div class="text"><?php echo esc_html__('Select devices', 'revy'); ?></div>
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
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <# if(data.brand.rb_active==1){ #>
                            <input type="checkbox" name="rb_active" id="rb_active"
                                   value="1"
                                   checked tabindex="14">
                            <# }else{ #>
                            <input type="checkbox" name="rb_active" id="rb_active"
                                   value="1"
                                   tabindex="14">
                            <# } #>
                            <label><?php echo esc_html__('Publish to frontend', 'revy'); ?>
                                <div class="ui icon ui-tooltip"
                                     data-content="<?php echo esc_attr__('If checked, brand will be displayed on booking form', 'revy'); ?>">
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
                <div class="ui button fat-submit-modal" data-id="{{data.brand.rb_id}}"
                     data-onClick="RevyBrands.processSubmitBrand">
                    <i class="save outline icon"></i>
                    <?php echo esc_html__('Save', 'revy'); ?>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-brands-item-template">
    <# _.each(data, function(item){ #>
    <div class="four wide column">
        <div class="ui items ">
            <div class="item fat-pd-10 fat-border-spin fat-hover fat-hover-link" data-id="{{item.rb_id}}">
                <div class="ui tiny image">
                    <# if (item.rb_image_url!=''){ #>
                    <img class="fat-border-round fat-box-shadow fat-img-80" src="{{item.rb_image_url}}"
                         data-image-id="{{item.rb_image_id}}">
                    <# }else{ #>
                    <span class="fat-no-thumb fat-img-80"></span>
                    <# } #>
                </div>
                <div class="content">
                    <div class="header thin">
                        <span class="fat-brand-name">{{item.rb_name}} (ID: {{item.rb_id}} )</span>
                    </div>
                </div>
                <button class=" ui icon button fat-item-bt-inline fat-sb-delete"
                        data-onClick="RevyBrands.processDelete"
                        data-id="{{item.rb_id}}" data-title="<?php echo esc_attr__('Delete', 'revy'); ?>">
                    <i class="trash alternate outline icon"></i>
                </button>

                <button class=" ui icon button fat-item-bt-inline fat-sb-edit"
                        data-onClick="RevyBrands.showPopupBrand"
                        data-id="{{item.rb_id}}" data-title="<?php echo esc_attr__('Edit', 'revy'); ?>">
                    <i class="edit outline icon"></i>
                </button>
            </div>
        </div>
    </div>
    <# }) #>
</script>