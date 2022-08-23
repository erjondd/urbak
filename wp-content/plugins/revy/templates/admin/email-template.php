<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/4/2019
 * Time: 3:54 PM
 */
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL . '/images/plugin_logo.png'); ?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Email Template','revy'); ?></div>
</div>
<div class="fat-sb-email-template-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content">
            <div class="ui grid">
                <div class="four wide column">
                    <div class="ui vertical pointing menu fat-sb-template-tab">
                        <a class="item active" data-onClick="RevyEmailTemplate.menuOnClick"
                           data-template="pending"
                           data-title-fixit-home="<?php esc_attr_e('Booking Pending Template for Customer (Fixit Home Delivery)','revy'); ?>"
                           data-title-carry-in="<?php esc_attr_e('Booking Pending Template for Customer (Carry In Delivery)','revy'); ?>"
                           data-title-carry-in="<?php esc_attr_e('Booking Pending Template for Customer (Mail In Delivery)','revy'); ?>" >
                            <?php esc_html_e('Booking Pending Template','revy'); ?>

                            <div class="ui icon ui-tooltip" data-position="top center"
                                 data-content="<?php echo esc_attr__('Template email notification when customers book at the homepage for pending status', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </a>
                        <a class="item" data-onClick="RevyEmailTemplate.menuOnClick"
                           data-template="approved"
                           data-title-fixit-home="<?php esc_attr_e('Booking Approved Template for Customer (Fixit Home Delivery)','revy'); ?>"
                           data-title-carry-in="<?php esc_attr_e('Booking Approved Template for Customer (Carry In Delivery)','revy'); ?>"
                           data-title-mail-in="<?php esc_attr_e('Booking Approved Template for Customer (Mail In Delivery)','revy'); ?>" >
                            <?php esc_html_e('Booking Approved Template','revy'); ?>

                            <div class="ui icon ui-tooltip" data-position="top center"
                                 data-content="<?php echo esc_attr__('Template email notification when customers book at the homepage for approved status', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </a>
                        <a class="item" data-onClick="RevyEmailTemplate.menuOnClick"
                           data-template="rejected"
                           data-title-fixit-home="<?php esc_attr_e('Booking Rejected Template for Customer (Fixit Home Delivery)','revy'); ?>"
                           data-title-carry-in="<?php esc_attr_e('Booking Rejected Template for Customer (Carry In Delivery)','revy'); ?>"
                           data-title-mail-in="<?php esc_attr_e('Booking Rejected Template for Customer (Mail In Delivery)','revy'); ?>" >
                            <?php esc_html_e('Booking Rejected Template','revy'); ?>

                            <div class="ui icon ui-tooltip" data-position="top center"
                                 data-content="<?php echo esc_attr__('Template email notification when admin reject booking', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </a>
                        <a class="item" data-onClick="RevyEmailTemplate.menuOnClick"
                           data-template="canceled"
                           data-title-fixit-home="<?php esc_attr_e('Booking Canceled Template for Customer (Fixit Home Delivery)','revy'); ?>"
                           data-title-carry-in="<?php esc_attr_e('Booking Canceled Template for Customer (Carry In Delivery)','revy'); ?>"
                           data-title-mail-in="<?php esc_attr_e('Booking Canceled Template for Customer (Mail In Delivery)','revy'); ?>" >
                            <?php esc_html_e('Booking Canceled Template','revy'); ?>

                            <div class="ui icon ui-tooltip" data-position="top center"
                                 data-content="<?php echo esc_attr__('Template email notification when admin cancel booking', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </a>
                        <a class="item fat-sb-customer-code" data-template="get_customer_code" data-onClick="RevyEmailTemplate.menuOnClick">
                            <?php esc_html_e('Get Customer Code Template','revy'); ?>

                            <div class="ui icon ui-tooltip" data-position="top center"
                                 data-content="<?php echo esc_attr__('Template email notification when client request get customer code', 'revy'); ?>">
                                <i class="question circle icon"></i>
                            </div>
                        </a>
                    </div>

                    <div class="fat-email-keys">
                        <h4><?php esc_html_e('Please use keys bellow for email template','revy');?></h4>
                        <ul class="list-email-key">
                            <li><span>{booking_time}</span> : <?php esc_html_e('time of booking','revy');?> </li>
                            <li><span>{booking_date}</span> : <?php esc_html_e('date of booking','revy');?> </li>
                            <li><span>{garage_name}</span> : <?php esc_html_e('name of garage','revy');?> </li>
                            <li><span>{garage_address}</span> : <?php esc_html_e('address of garage','revy');?> </li>
                            <li><span>{garage_email}</span> : <?php esc_html_e('email of garage','revy');?> </li>
                            <li><span>{garage_phone}</span> : <?php esc_html_e('phone of garage','revy');?> </li>
                            <li><span>{brand_name}</span> : <?php esc_html_e('name of brand','revy');?> </li>
                            <li><span>{device_name}</span> : <?php esc_html_e('name of device','revy');?> </li>
                            <li><span>{model_name}</span> : <?php esc_html_e('name of model','revy');?> </li>
                            <li><span>{delivery_method}</span> : <?php esc_html_e('delivery method','revy');?> </li>
                            <li><span>{service_info}</span> : <?php esc_html_e('This is service information (service name, service attribute, duration ...)','revy');?> </li>
                            <li><span>{booking_price}</span> : <?php esc_html_e('total price of booking','revy');?> </li>
                            <li><span>{customer_first_name}</span> : <?php esc_html_e('customer first name','revy');?> </li>
                            <li><span>{customer_last_name}</span> : <?php esc_html_e('customer last name','revy');?> </li>
                            <li><span>{customer_address}</span> : <?php esc_html_e('customer address','revy');?> </li>
                            <li><span>{customer_city}</span> : <?php esc_html_e('customer city','revy');?> </li>
                            <li><span>{customer_country}</span> : <?php esc_html_e('customer country','revy');?> </li>
                            <li><span>{customer_postal_code}</span> : <?php esc_html_e('customer postal code','revy');?> </li>
                            <li><span>{customer_phone}</span> : <?php esc_html_e('customer phone','revy');?> </li>
                            <li><span>{customer_email}</span> : <?php esc_html_e('customer email','revy');?> </li>
                            <li><span>{company_phone}</span> : <?php esc_html_e('company phone','revy');?> </li>
                            <li><span>{company_name}</span> : <?php esc_html_e('company name','revy');?> </li>
                            <li><span>{company_address}</span> : <?php esc_html_e('company address','revy');?> </li>
                            <li><span>{company_email}</span> : <?php esc_html_e('company email','revy');?> </li>
                            <li><span>{payment_method}</span> : <?php esc_html_e('payment method','revy');?> </li>
                        </ul>
                    </div>

                </div>
                <div class="twelve wide column">
                    <div class="fat-sb-pending-template">
                        <div class="fat-sb-checkbox-wrap right">
                            <h4 class="fat-sb-fixit-home-label"><?php esc_html_e('Booking Pending Template for Customer (Fixit Home Delivery)','revy'); ?></h4>
                            <div class="ui toggle checkbox" data-tooltip="<?php esc_attr_e('On/Off send email for customer with fixit home delivery','revy');?>" data-position="top right">
                                <input type="checkbox" name="fixit_home_template_enable" id="fixit_home_template_enable" data-onChange="RevyEmailTemplate.dependFieldOnChange"
                                       value="1" checked>
                                <label>&nbsp;</label>
                            </div>
                        </div>

                        <div class="fields fixit_home_template" data-depend="fixit_home_template_enable">
                            <div class="field">
                                <label><?php esc_html_e('Subject','revy'); ?></label>
                                <div class="ui input">
                                    <input type="text" id="fixit_home_subject" name="fixit_home_subject" autocomplete="off">
                                </div>
                            </div>
                            <div class="field fat-editor">
                                <label><?php esc_html_e('Message','revy'); ?></label>
                                <?php wp_editor('', 'fixit_home_template', array('textarea_rows' => 10, 'media_buttons' => false)); ?>
                            </div>
                        </div>

                        <div class="fat-sb-checkbox-wrap right">
                            <h4 class="fat-sb-carry-in-label"><?php esc_html_e('Booking Pending Template for Customer (Carry In Delivery)','revy'); ?></h4>
                            <div class="ui toggle checkbox" data-tooltip="<?php esc_attr_e('On/Off send email for customer with carry in delivery','revy');?>" data-position="top right">
                                <input type="checkbox" name="carry_in_template_enable" id="carry_in_template_enable" data-onChange="RevyEmailTemplate.dependFieldOnChange"
                                       value="1" checked>
                                <label>&nbsp;</label>
                            </div>
                        </div>

                        <div class="fields carry_in_template" data-depend="carry_in_template_enable">
                            <div class="field">
                                <label><?php esc_html_e('Subject','revy'); ?></label>
                                <div class="ui input">
                                    <input type="text" id="carry_in_subject" name=carry_in_subject" autocomplete="off">
                                </div>
                            </div>

                            <div class="field fat-editor">
                                <label><?php esc_html_e('Message','revy'); ?></label>
                                <?php wp_editor('', 'carry_in_template', array('textarea_rows' => 10, 'media_buttons' => false)); ?>
                            </div>
                        </div>

                        <div class="fat-sb-checkbox-wrap right">
                            <h4 class="fat-sb-mail-in-label"><?php esc_html_e('Booking Pending Template for Customer (Mail In Delivery)','revy'); ?></h4>
                            <div class="ui toggle checkbox" data-tooltip="<?php esc_attr_e('On/Off send email for customer with mail in delivery','revy');?>" data-position="top right">
                                <input type="checkbox" name="mail_in_template_enable" id="mail_in_template_enable" data-onChange="RevyEmailTemplate.dependFieldOnChange"
                                       value="1" checked>
                                <label>&nbsp;</label>
                            </div>
                        </div>

                        <div class="fields mail_in_template" data-depend="mail_in_template_enable">
                            <div class="field">
                                <label><?php esc_html_e('Subject','revy'); ?></label>
                                <div class="ui input">
                                    <input type="text" id="mail_in_subject" name=mail_in_subject" autocomplete="off">
                                </div>
                            </div>

                            <div class="field fat-editor">
                                <label><?php esc_html_e('Message','revy'); ?></label>
                                <?php wp_editor('', 'mail_in_template', array('textarea_rows' => 10, 'media_buttons' => false)); ?>
                            </div>
                        </div>

                        <div class="fields">
                            <div class="field fat-text-right">
                                <div class="ui basic button" data-onClick="RevyEmailTemplate.sendTestMailTemplateOnClick">
                                    <?php echo esc_html__('Send test mail','revy');?>
                                </div>

                                <div class="ui primary button" data-onClick="RevyEmailTemplate.submitTemplate"
                                     data-invalid-message="<?php echo esc_attr__('Please input data ','revy');?>"
                                     data-success-message="<?php esc_attr_e('Template have been saved','revy');?>" >
                                    <?php echo esc_html__('Save','revy');?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="fat-sb-get-customer-code-template fat-hidden">
                        <h4 ><?php esc_html_e('Get Customer Code Template','revy'); ?></h4>

                        <div class="fields customer-template">
                            <div class="field">
                                <label><?php esc_html_e('Subject','revy'); ?></label>
                                <div class="ui input">
                                    <input type="text" id="customer_code_subject" name="customer_subject" autocomplete="off">
                                </div>
                            </div>
                            <div class="field fat-editor">
                                <label><?php esc_html_e('Message','revy'); ?></label>
                                <?php wp_editor('', 'customer_code_template', array('textarea_rows' => 10, 'media_buttons' => false)); ?>
                                <p class="fat-field-description"><?php esc_html_e('Please use keyword {customer_code}, {customer_first_name}, {customer_last_name} in email template to display customer code in message','revy');?></p>
                            </div>
                        </div>

                        <div class="fields">
                            <div class="field fat-text-right">
                                <div class="ui primary button" data-onClick="RevyEmailTemplate.submitTemplate"
                                     data-invalid-message="<?php echo esc_attr__('Please input data ','revy');?>"
                                     data-success-message="<?php esc_attr_e('Template have been saved','revy');?>" >
                                    <?php echo esc_html__('Save','revy');?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>