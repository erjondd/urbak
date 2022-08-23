<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 2/21/2019
 * Time: 2:33 PM
 */
?>

<script type="text/html" id="tmpl-fat-sb-booking-template">
    <div class="ui modal tiny fat-semantic-container fat-sb-booking-form">
        <div class="header fat-sb-popup-title"><?php echo esc_attr('Booking detail', 'revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="two fields">
                    <div class="field ">
                        <label><?php echo esc_html__('Garage', 'revy'); ?></label>
                        <i class="map marker alternate icon"></i>
                        {{data.booking.rg_name}}
                    </div>

                    <div class="field ">
                        <label><?php echo esc_html__('Device', 'revy'); ?> </label>
                        <i class="folder outline icon"></i>
                        {{data.booking.rd_name}}
                    </div>

                </div>

                <div class="two fields">
                    <div class="field ">
                        <label><?php echo esc_html__('Brand', 'revy'); ?></label>
                        <i class="registered outline icon"></i>
                        {{data.booking.rb_name}}
                    </div>

                    <div class="field ">
                        <label><?php echo esc_html__('Model', 'revy'); ?></label>
                        <i class="mobile alternate icon"></i>
                        {{data.booking.rm_name}}
                    </div>

                </div>

                <div class="one fields">
                    <div class="field">
                        <ul class="list-service-attr">
                            <li class="item-head"><?php echo esc_html__('Service','revy');?> </li>
                            <li class="item-head"><?php echo esc_html__('Attribute','revy');?> </li>
                            <li class="item-head"><?php echo esc_html__('Price','revy');?> </li>
                            <li class="item-head"><?php echo esc_html__('Tax','revy');?> </li>
                            <# _.each(data.booking_detail, function(item){ #>
                                <li>{{item.s_name}}</li>
                                <li>{{item.b_attr_title}} : {{item.b_attr_value}}</li>
                                <li>{{item.b_price_label}}</li>
                                <li>{{item.b_tax_label}}</li>
                            <# }) #>
                        </ul>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label><?php echo esc_html__('Customers', 'revy'); ?></label>
                        <i class="user outline icon"></i> {{data.booking.c_first_name}} {{data.booking.c_last_name}}
                        <div class="customer-address">
                            <# if(data.booking.b_customer_address!=''){ #>
                            <span class="c-address"><?php echo esc_html__('Address','revy') ?>:</span> {{data.booking.b_customer_address}}
                            <# } #>
                            <# if(data.booking.b_customer_city!=''){ #>
                            <span class="c-address"><?php echo esc_html__('City','revy') ?>:</span> {{data.booking.b_customer_city}}
                            <# } #>
                            <# if(data.booking.b_customer_country!=''){ #>
                            <span class="c-address"><?php echo esc_html__('Country','revy') ?>:</span> {{data.booking.b_customer_country}}
                            <# } #>
                            <# if(data.booking.b_customer_postal_code!=''){ #>
                            <span class="c-address"><?php echo esc_html__('Postal Code','revy') ?>:</span> {{data.booking.b_customer_postal_code}}
                            <# } #>

                        </div>
                    </div>
                </div>

                <?php
                $locale = get_locale();
                $locale = explode('_', $locale)[0];
                $locale_path = REVY_DIR_PATH . 'assets/plugins/air-datepicker/js/i18n/datepicker.' . $locale . '.js';
                if ($locale == 'pl') {
                    $locale_path = REVY_DIR_PATH . 'assets/plugins/air-datepicker/js/i18n/datepicker.pl-PL.js';
                }
                if (!file_exists($locale_path)) {
                    $locale = 'en';
                }
                ?>
                <div class="two fields date-time-fields">
                    <div class="field date-field">
                        <label for="b_date"><?php echo esc_html__('Date', 'revy'); ?> <span
                                    class="required"> *</span></label>
                        <div class="fat-sb-booking-date-wrap">
                            <input type="text" class="air-date-picker datepicker-here"
                                   data-locale="<?php echo esc_attr($locale); ?>" data-date="{{data.booking.b_date}}"
                                   required autocomplete="off" name="b_date" id="b_date">
                        </div>
                    </div>

                    <div class="field time-field">
                        <label><?php echo esc_html__('Time', 'revy'); ?><span
                                    class="required"> *</span></label>
                        <div class="ui pointing selection dropdown has-icon fat-sb-booking-time-wrap">
                            <i class="clock outline icon"></i>
                            <input type="hidden" name="b_time" id="b_time" required value="{{data.booking.b_time}}">
                            <i class="dropdown icon"></i>
                            <div class="text"
                                 data-no-time-slot="<?php echo esc_attr__('Don\'t have free time slot', 'revy'); ?>"
                                 data-text="<?php echo esc_attr__('Select time', 'revy'); ?>">
                                <?php echo esc_html__('Select time', 'revy'); ?>
                            </div>
                            <div class="menu">
                                <div class="scrolling menu">
                                </div>
                            </div>
                        </div>

                        <div class="field-error-message">
                            <?php echo esc_html__('Please select time', 'revy'); ?>
                        </div>
                    </div>
                </div>

                <div class="one fields">
                    <div class="field">
                        <label><?php echo esc_html__('Notes', 'revy'); ?></label>
                        <textarea rows="2" id="b_description"
                                  name="b_description">{{data.booking.b_description}}</textarea>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <div class="ui checkbox fat-sb-pay-now fat-fullwidth">
                            <# if(data.booking.b_pay_now==1){ #>
                            <input type="checkbox" name="pay_now" id="pay_now" checked>
                            <# }else{ #>
                            <input type="checkbox" name="pay_now" id="pay_now">
                            <# } #>
                            <label for="pay_now">
                                <?php echo esc_html__('Paynow', 'revy'); ?>
                                <div class="ui icon ui-tooltip" data-position="right center"
                                     data-content="<?php echo esc_attr__('Click here if you want update payment to Paid, uncheck to update payment to Pending', 'revy'); ?>">
                                    <i class="question circle icon"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <?php
                    $setting_db = Revy_DB_Setting::instance();
                    $setting = $setting_db->get_setting();
                    ?>
                    <div class="field">
                        <label><?php echo esc_html__('Payment method', 'revy'); ?>
                        </label>
                        <div class="ui pointing selection dropdown has-icon fat-sb-payment-method-dic">
                            <i class="dollar sign icon"></i>
                            <input type="hidden" name="b_gateway_type" id="b_gateway_type"
                                   value="{{data.booking.b_gateway_type}}">
                            <i class="dropdown icon"></i>
                            <div class="text"><?php echo esc_html__('Select payment method', 'revy'); ?></div>
                            <div class="menu">
                                <# if(data.booking.b_gateway_type == 'onsite'){ #>
                                <div class="item"
                                     data-value="onsite"><?php esc_html_e('Onsite payment', 'revy'); ?></div>
                                <# } #>

                                <# if(data.booking.b_gateway_type == 'paypal'){ #>
                                <div class="item" data-value="paypal"><?php esc_html_e('Paypal', 'revy'); ?></div>
                                <# } #>

                                <# if(data.booking.b_gateway_type == 'stripe'){ #>
                                <div class="item" data-value="stripe"><?php esc_html_e('Stripe', 'revy'); ?></div>
                                <# } #>

                                <# if(data.booking.b_gateway_type == 'woocommerce'){ #>
                                <div class="item" data-value="woocommerce"><?php esc_html_e('WooCommerce', 'revy'); ?></div>
                                <# } #>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="two fields fat-sb-payment-booking-info">
                    <div class="field">
                    </div>
                    <div class="field">
                        <ul>
                            <li>
                                <label><strong><?php echo esc_html__('Service Tax:', 'revy'); ?></strong></label>
                                <span class="tax" >{{data.booking.b_total_tax_label}}</span>
                            </li>

                            <li>
                                <label><strong><?php echo esc_html__('Subtotal:', 'revy'); ?></strong></label>
                                <span class="sub-total">{{data.booking.b_sub_total_label}}</span>
                            </li>
                            <li>
                                <label><strong><?php echo esc_html__('Discount:', 'revy'); ?></strong></label>
                                <span class="discount">{{data.booking.b_discount_label}}</span>
                            </li>
                            <li>
                                <label><strong><?php echo esc_html__('Total:', 'revy'); ?></strong></label>
                                <span class="total">{{data.booking.b_total_pay_label}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <# if(data.booking.b_canceled_by_client == 1 && data.booking.b_process_status == 2){ #>
            <span class="fat-fl-left fat-text-red fat-fw-bold">
                    <?php esc_html_e('Canceled by client', 'revy'); ?>
                </span>
            <# } #>
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel', 'revy'); ?>
            </button>
            <div class="blue ui buttons">
                <div class="ui button fat-submit-modal" data-id="{{data.booking.b_id}}"
                     data-onClick="RevyBooking.processSubmitBooking"
                     data-success-message="<?php echo esc_attr__('Booking has been saved', 'revy'); ?>">
                    <i class="save outline icon"></i>
                    <?php echo esc_html__('Save', 'revy'); ?>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-fat-sb-booking-item-template">
    <# _.each(data, function(item){ #>
    <tr data-id="{{item.b_id}}">
        <td class="check-item">
            <div class="ui checkbox">
                <input type="checkbox" name="check-item" class="check-item" data-id="{{item.b_id}}">
                <label></label>
            </div>
        </td>
        <td data-label="<?php echo esc_attr__('Appointment Date', 'revy'); ?>">
            <span class="expand-detail" data-tooltip="<?php echo esc_html__('View list services', 'revy'); ?>"
                  data-onClick="RevyBooking.onExpandDetailClick" data-id="{{item.b_id}}">
                <i class="plus square outline icon"></i>
                <i class="minus square outline icon"></i>
            </span>
            {{item.b_date_display}}
        </td>
        <td data-label="<?php echo esc_attr__('Create Date', 'revy'); ?>">{{item.b_create_date}}</td>
        <td data-label="<?php echo esc_attr__('Customer', 'revy'); ?>">
            {{item.c_first_name}} {{item.c_last_name}}
            <span class="extra-info">{{item.c_email}}</span>
            <span class="extra-info">{{item.c_phone_code}} {{item.c_phone}}</span>
        </td>
        <td data-label="<?php echo esc_attr__('Models', 'revy'); ?>">
            {{ item.rm_name }}
        </td>
        <td data-label="<?php echo esc_attr__('Delivery method', 'revy'); ?>">
            {{ item.b_delivery_method_label }}
        </td>
        <td class="fat-sb-payment" data-label="<?php echo esc_attr__('Payment', 'revy'); ?>">
            {{item.b_total_pay}}
        </td>
        <td class="fat-sb-status" data-label="<?php echo esc_attr__('Status', 'revy'); ?>">

            <# if (item.editable== 1) { #>
            <div class="ui floating dropdown labeled icon selection">
                <# }else{ #>
                <div class="ui floating dropdown labeled icon selection disabled">
                    <# } #>
                    <input type="hidden" name="b_process_status" value="{{item.b_process_status}}"
                           data-value="{{item.b_process_status}}"
                           data-onChange="RevyBooking.processUpdateProcessStatus" data-id="{{item.b_id}}">
                    <i class="dropdown icon"></i>
                    <span class="text"><div
                                class="ui yellow empty circular label"></div> <?php echo esc_html__('Pending', 'revy'); ?></span>
                    <div class="menu">
                        <div class="item" data-value="2">
                            <div class="ui red empty circular label"></div>
                            <?php echo esc_html__('Canceled', 'revy'); ?>
                        </div>
                        <div class="item" data-value="1">
                            <div class="ui green empty circular label"></div>
                            <?php echo esc_html__('Approved', 'revy'); ?>
                        </div>
                        <div class="item" data-value="0">
                            <div class="ui yellow empty circular label"></div>
                            <?php echo esc_html__('Pending', 'revy'); ?>
                        </div>
                        <div class="item" data-value="3">
                            <div class="ui empty empty circular label"></div>
                            <?php echo esc_html__('Rejected', 'revy'); ?>
                        </div>
                    </div>
                </div>
        </td>
        <td>
            <div class="ps-relative">
                <button class=" ui icon button fat-item-bt-inline fat-sb-edit-booking"
                        data-onClick="RevyBooking.showPopupBooking"
                        data-id="{{item.b_id}}" data-title="<?php echo esc_attr__('Edit', 'revy'); ?>">
                    <i class="edit outline icon"></i>
                </button>

                <button class=" ui icon button fat-item-bt-inline fat-sb-delete"
                        data-onClick="RevyBooking.processDeleteBooking"
                        data-id="{{item.b_id}}" data-title="<?php echo esc_attr__('Delete', 'revy'); ?>">
                    <i class="trash alternate outline icon"></i>
                </button>
            </div>
        </td>
    </tr>
    <# }) #>
</script>

<script type="text/html" id="tmpl-fat-sb-booking-detail-item-template">
    <tr class="booking-detail-item">
        <td colspan="9">
            <div class="dt-item-wrap">
                <ul class="dt-item item-head">
                    <li><?php echo esc_html__('Service', 'revy'); ?></li>
                    <li><?php echo esc_html__('Duration', 'revy'); ?></li>
                    <li><?php echo esc_html__('Attribute', 'revy'); ?></li>
                    <li><?php echo esc_html__('Price', 'revy'); ?></li>
                </ul>
                <# _.each(data, function(item){ #>
                <ul class="dt-item">
                    <li>{{item.s_name}}</li>
                    <li>{{item.b_service_duration_label}}</li>
                    <li>{{item.b_attr_title}} : {{item.b_attr_value}}</li>
                    <li>{{item.b_price_label}}</li>
                </ul>
                <# }) #>
            </div>
        </td>
    </tr>
</script>



