<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/21/2018
 * Time: 9:49 AM
 */
$setting = Revy_DB_Setting::instance();
$setting = $setting->get_setting();
$disable_customer_email =  isset($setting['disable_customer_email']) && $setting['disable_customer_email'] == '1' ? 1 : 0;
?>
<script type="text/html" id="tmpl-fat-sb-customers-template">
    <div class="ui modal tiny fat-semantic-container fat-customer-form">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Add new customer', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="two fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('First name', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="c_first_name" id="c_first_name" value="{{data.c_first_name}}"
                                   placeholder="<?php echo esc_attr__('First name', 'revy'); ?>"
                                   required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter first name', 'revy'); ?>
                        </div>
                    </div>
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Last name', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="text" name="c_last_name" id="c_last_name" value="{{data.c_last_name}}"
                                   placeholder="<?php echo esc_attr__('Last name', 'revy'); ?>"
                                   required>
                            <i class="edit outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter last name', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field ">
                        <label for="email"><?php echo esc_html__('Email', 'revy'); ?> <span
                                    class="required"> *</span></label>
                        <div class="ui left icon input">
                            <?php if($disable_customer_email): ?>
                                <input type="email" name="c_email" id="c_email" value="<?php echo (uniqid().'@no_email.com');?>" disabled="disabled"
                                       placeholder="<?php echo esc_attr__('Email', 'revy'); ?>" required>
                            <?php endif; ?>

                            <?php if(!$disable_customer_email): ?>
                                <input type="email" name="c_email" id="c_email" value="{{data.c_email}}"
                                       placeholder="<?php echo esc_attr__('Email', 'revy'); ?>" required>
                            <?php endif; ?>

                            <i class="envelope outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter email', 'revy'); ?>
                        </div>
                    </div>

                    <div class="field ">
                        <label for="phone"><?php echo esc_html__('Phone', 'revy'); ?></label>
                        <div class="ui fluid search selection dropdown phone-code">
                            <input type="hidden" name="c_phone_code" id="c_phone_code" autocomplete="nope" value="{{data.c_phone_code}}">
                            <i class="dropdown icon"></i>
                            <div class="default text"></div>
                            <div class="menu">
                                <?php
                                $phoneCode = Revy_Utils::getPhoneCountry();
                                foreach($phoneCode as $pc){
                                    $pc = explode(',',$pc);?>
                                    <div class="item"  data-value="<?php echo esc_attr($pc[1].','.$pc[2]);?>"><i class="<?php echo esc_attr($pc[2]);?> flag"></i><?php echo esc_html($pc[0]);?><span>(<?php echo esc_html($pc[1]);?>)</span></div>
                                <?php } ?>
                                <div class="item" data-value="other"><?php echo esc_html__('Other','revy');?></div>
                            </div>
                        </div>

                        <div class="ui left icon input phone-number">
                            <input type="text" name="c_phone" id="c_phone"
                                   placeholder="<?php echo esc_attr__('Phone', 'revy'); ?>"
                                   value="{{data.c_phone}}">
                            <i class="phone volume icon"></i>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label for="c_dob"><?php echo esc_html__('Date of birth', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <?php
                        $start_date = new DateTime();
                        $start_date = $start_date->modify('-18 years');
                        $date_format = get_option('date_format');
                        $locale = get_locale();
                        $locale = explode('_',$locale)[0];
                        ?>
                        <# if(data.c_dob!=null && data.c_dob!=''){ #>
                        <input type="text" value="{{data.c_dob}}" data-dropdown="1" class="date-picker" name="c_dob"  data-locale="<?php echo esc_attr($locale);?>"
                               id="c_dob" required>
                        <# }else{ #>
                        <input type="text" value="<?php echo $start_date->format('Y-m-d'); ?>"  data-default="<?php echo $start_date->format('Y-m-d'); ?>" data-dropdown="1" class="date-picker" name="c_dob"  data-locale="<?php echo esc_attr($locale);?>"
                               id="c_dob" data-start-init="<?php echo date_i18n($date_format, $start_date->format('U')); ?>" required>
                        <# } #>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label><?php echo esc_html__('Notes', 'revy'); ?></label>
                        <textarea rows="5" id="c_description" name="c_description">{{data.c_description}}</textarea>
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
                <div class="ui button fat-submit-modal" data-onClick="RevyCustomers.processSubmitCustomer"
                     data-id="{{data.c_id}}"
                     data-success-message="<?php echo esc_attr__('Customer has been saved', 'revy'); ?>">
                    <i class="save outline icon"></i>
                    <?php echo esc_html__('Save', 'revy'); ?>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-customer-item-template">
    <# _.each(data, function(item){ #>
    <tr data-id="{{item.c_id}}">
        <td>
            <div class="ui checkbox">
                <input type="checkbox" name="c_id" class="check-item" data-id="{{item.c_id}}">
                <label></label>
            </div>
        </td>
        <td class="fat-c-name" data-label="<?php echo esc_attr__('Name', 'revy'); ?>">
            {{item.c_first_name }} {{item.c_last_name}}
        </td>
        <td class="fat-c-phone" data-label="<?php echo esc_attr__('Phone', 'revy'); ?>">
            {{item.c_phone_code}} {{item.c_phone}}
        </td>
        <td class="fat-c-email" data-label="<?php echo esc_attr__('Email', 'revy'); ?>">
            {{item.c_email}}
        </td>
        <td class="fat-c-dob" data-label="<?php echo esc_attr__('Date of Birth', 'revy'); ?>">
            {{item.c_dob}}
        </td>
        <td class="fat-c-dob" data-label="<?php echo esc_attr__('Code', 'revy'); ?>">
            {{item.c_code}}
        </td>
        <td class="fat-c-note" data-label="<?php echo esc_attr__('Notes', 'revy'); ?>">
            {{item.c_description}}
        </td>
        <td>
            <div class="ps-relative">
                <button class=" ui icon button fat-item-bt-inline fat-sb-delete" data-onClick="RevyCustomers.processDelete"
                        data-id="{{item.c_id}}" data-title="<?php echo esc_attr__('Delete', 'revy'); ?>">
                    <i class="trash alternate outline icon"></i>
                </button>

                <button class=" ui icon button fat-item-bt-inline fat-sb-edit"
                        data-onClick="RevyCustomers.processViewDetail"
                        data-id="{{item.c_id}}" data-title="<?php echo esc_attr__('Edit', 'revy'); ?>">
                    <i class="edit outline icon"></i>
                </button>
            </div>

        </td>
    </tr>
    <# }) #>
</script>