<?php
$setting_db = Revy_DB_Setting::instance();
$setting = $setting_db->get_setting();

$phoneCode = Revy_Utils::getPhoneCountry();
$phone_code_default = isset($setting['default_phone_code']) && $setting['default_phone_code'] ? $setting['default_phone_code'] : '+44,uk';
?>
<div class="customer-info-wrap fat-section-shadow">
    <h4><?php echo esc_html__('Customer Information', 'revy'); ?></h4>
    <div class="ui form">
        <div class="two fields">
            <div class="field">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="c_first_name"><?php echo esc_html__('First name', 'revy'); ?>
                    <span class="required"> *</span></label>
                <div class="ui left input ">
                    <input type="text" name="c_first_name" id="c_first_name"
                           data-onChange="RevyBookingFlow.resetValidateField"
                           value=""
                           placeholder="<?php echo esc_attr__('First name', 'revy'); ?>"
                           required>
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter first name', 'revy'); ?>
                </div>
            </div>
            <div class="field ">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="c_last_name"><?php echo esc_html__('Last name', 'revy'); ?>
                    <span class="required"> *</span></label>
                <div class="ui left input ">
                    <input type="text" name="c_last_name" id="c_last_name"
                           data-onChange="RevyBookingFlow.resetValidateField"
                           value=""
                           placeholder="<?php echo esc_attr__('Last name', 'revy'); ?>"
                           required>
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter last name', 'revy'); ?>
                </div>
            </div>
        </div>
        <div class="two fields phone-email">
            <div class="field email-field">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="c_email"><?php echo esc_html__('Email', 'revy'); ?>
                    <span class="required"> *</span></label>
                <div class="ui left input">
                    <input type="email" name="c_email" id="c_email"
                           data-onChange="RevyBookingFlow.resetValidateField"
                           value=""
                           placeholder="<?php echo esc_attr__('Email', 'revy'); ?>"
                           required>
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter email', 'revy'); ?>
                </div>
            </div>
            <div class="field phone-field">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="phone_code"><?php echo esc_html__('Phone', 'revy'); ?>
                    <span class="required"> *</span></label>
                <div class="phone-code-wrap">
                    <div class="ui fluid search selection dropdown phone-code fat-small-dropdown">
                        <input type="hidden" name="phone_code" id="phone_code"
                               autocomplete="nope"
                               value="<?php echo esc_attr($phone_code_default); ?>">
                        <i class="dropdown icon"></i>
                        <div class="default text"></div>
                        <div class="menu">
                            <?php
                            foreach ($phoneCode as $pc) {
                                $pc = explode(',', $pc); ?>
                                <div class="item"
                                     data-value="<?php echo esc_attr($pc[1] . ',' . $pc[2]); ?>">
                                    <i
                                            class="<?php echo esc_attr($pc[2]); ?> flag"></i><?php echo esc_html($pc[0]); ?>
                                </div>
                            <?php } ?>
                            <div class="item"
                                 data-value="other"><?php echo esc_html__('Other', 'revy'); ?></div>
                        </div>
                    </div>

                    <div class="ui left input phone-number">
                        <input type="text" name="c_phone" id="c_phone"
                               data-onChange="RevyBookingFlow.resetValidateField" required
                               value=""
                               placeholder="<?php echo esc_attr__('Phone', 'revy'); ?>">
                    </div>
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter phone', 'revy'); ?>
                </div>
            </div>
        </div>
        <div class="two fields address-postal">
            <div class="field">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="c_postal_code"><?php echo esc_html__('Postal Code', 'revy'); ?>
                    <span class="required"> *</span></label>
                <div class="ui left input ">
                    <input type="text" name="c_postal_code" id="c_postal_code" value="" placeholder="<?php echo esc_attr__('Postal Code', 'revy'); ?>"
                           data-onChange="RevyBookingFlow.resetValidateField">
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter postal code', 'revy'); ?>
                </div>
            </div>
            <div class="field">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="c_address"><?php echo esc_html__('Address', 'revy'); ?>
                    <span class="required"> *</span></label>
                <div class="ui left input ">
                    <input type="text" name="c_address" id="c_address" value="" placeholder="<?php echo esc_attr__('Address', 'revy'); ?>"
                           data-onChange="RevyBookingFlow.resetValidateField">
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter address', 'revy'); ?>
                </div>
            </div>
        </div>
        <div class="two fields city-country">
            <div class="field city-field">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="c_city"><?php echo esc_html__('City', 'revy'); ?>
                    <span class="required"> *</span></label>
                <div class="ui left input ">
                    <input type="text" name="c_city" id="c_city" value="" placeholder="<?php echo esc_attr__('City', 'revy'); ?>"
                           data-onChange="RevyBookingFlow.resetValidateField">
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter city', 'revy'); ?>
                </div>
            </div>
            <div class="field country-field">
                <label class="fat-fw-400 fat-lh-1em fat-mg-bottom-10" for="c_country"><?php echo esc_html__('Country', 'revy'); ?>
                    <span
                            class="required"> *</span></label>
                <div class="ui left input ">
                    <input type="text" name="c_country" id="c_country" value="" placeholder="<?php echo esc_attr__('Last name', 'revy'); ?>"
                           data-onChange="RevyBookingFlow.resetValidateField" >
                </div>
                <div class="field-error-message">
                    <?php echo esc_html__('Please enter country', 'revy'); ?>
                </div>
            </div>
        </div>

    </div>
</div>
