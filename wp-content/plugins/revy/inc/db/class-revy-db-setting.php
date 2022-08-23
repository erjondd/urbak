<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/7/2019
 * Time: 9:10 AM
 */
if (!class_exists('Revy_DB_Setting')) {
    class Revy_DB_Setting
    {
        private static $instance = NULL;
        private $option_key = 'rp_settings';
        private $working_hour_key = 'rp_working_hour_setting';
        private $custom_css_key = 'rp_custom_css_setting';
        private $email_template_key = 'rp_email_template_setting';

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_setting()
        {
            $setting_default = array(
                'enable_import' => 0,
                'booking_mode' =>'single',
                'duration_step' => 15,
                'time_step' => 15,
                'day_limit' => 365,
                'calendar_view' => 'month',
                'b_process_status' => 0,
                'allow_client_cancel' => 0,
                'limit_booking' => 0,
                'limited_time' => 0,
                'cancel_before' => 0,
                'item_per_page' => 10,
                'default_phone_code' => '+44',
                'service_tax' => 0,
                'service_available' => 1,
                'enable_modal_popup' => 0,
                'enable_time_slot_deactive' => 0,
                'bg_time_slot_not_active' => '#dddddd',
                'enable_datetime_picker' => 0,
                'device_label' => esc_html__('Device', 'revy'),
                'brand_label' => esc_html__('Choose your brand', 'revy'),
                'model_label' => esc_html__('Choose your model', 'revy'),
                'service_label' => esc_html__('Service', 'revy'),
                'garage_label' => esc_html__('Garage', 'revy'),
                'tax_label' => esc_html__('Tax', 'revy'),
                'appointment_time_label' => esc_html__('Appointment Time', 'revy'),
                'total_cost_label' => esc_html__('Total cost', 'revy'),
                'payment_method_label' => esc_html__('Payment method', 'revy'),
                'company_name' => '',
                'company_address' => '',
                'company_phone' => '',
                'company_email' => '',
                'mailer' => 'default',
                'smtp_host' => '',
                'smtp_port' => '',
                'smtp_encryption' => 'none',
                'smtp_username' => '',
                'smtp_password' => '',
                'send_from_name' => '',
                'send_from_name_label' => '',
                'employee_email' => '',
                'cc_to' => '',
                'bcc_to' => '',
                'success_page' => '',
                'error_page' => '',
                'currency' => 'USD',
                'number_of_decimals' => 2,
                'symbol_position' => 'after',
                'default_payment_method' => 'onsite',
                'hide_payment' => 0,
                'onsite_enable' => 1,
                'wc_enable' => 0,
                'price_package_enable' => 0,
                'paypal_enable' => 0,
                'paypal_sandbox' => 'test',
                'paypal_client_id' => '',
                'paypal_secret' => '',
                'stripe_enable' => 0,
                'stripe_sandbox' => 'test',
                'stripe_publish_key' => '',
                'stripe_secret_key' => '',
                'myPOS_enable' => 0,
                'myPOS_sandbox' => 'test',
                'myPOS_storeID' => '',
                'myPOS_client_number' => '',
                'myPOS_key_index' => '',
                'myPOS_private_key' => '',
                'myPOS_public_certificate' => '',
                'myPOS_success_page' => '',
                'myPOS_error_page' => '',
                'przelewy24_enable' => '',
                'p24_mode' => 'sandbox',
                'p24_merchant_id' => '',
                'p24_pos_id' => '',
                'p24_crc' => '',
                'przelewy24_success_page' => '',
                'przelewy24_error_page' => '',
                'google_map_api' => '',
                'allow_user_booking' => '',
                'sms_provider' => '',
                'sms_owner_phone_number' => '',
                'sms_sid' => '',
                'sms_token' => '',
                'enable_map' => 0,
                'mapbox_api_key' => '',
                'distance_near_me' => '10',
                'distance_unit' => 'kilometers',
                'hide_price' => 0,
                'step_device_title' => esc_html__('Tell us what device you have', 'revy'),
                'step_device_subtitle' => esc_html__('In order to determine which repair solution is best for you, tell us about your device', 'revy'),
                'step_brand_title' => esc_html__('Tell us what brand you have', 'revy'),
                'step_brand_subtitle' => esc_html__('In order to determine which repair solution is best for you, tell us about your brand', 'revy'),
                'step_model_title' => esc_html__('Tell us what model you have', 'revy'),
                'step_model_subtitle' => esc_html__('In order to determine which repair solution is best for you, tell us about your model', 'revy'),
                'step_service_title' => esc_html__('Tell us what\'s broken', 'revy'),
                'step_service_subtitle' => esc_html__('What seems to be the problem? If you don\'t know that is ok too', 'revy'),
                'step_delivery_title' => esc_html__('Let us know delivery', 'revy'),
                'step_delivery_subtitle' => esc_html__('Please choose delivery method', 'revy'),
                'step_location_title' => esc_html__('Let us know where you are', 'revy'),
                'step_location_subtitle' => esc_html__('Please enter your postal code to obtain a Technician / Repairer near you', 'revy'),
                'step_garage_title' => esc_html__('Choose a garage', 'revy'),
                'step_garage_subtitle' => esc_html__('Select a garage and we will get you in touch with an expert who can help with your repair', 'revy'),
                'step_schedule_title' => esc_html__('Let\'s Schedule Your Repair', 'revy'),
                'step_schedule_subtitle' => esc_html__('We just need a few more details to schedule your repair.', 'revy'),
                'step_booked_title' => esc_html__('Appointment booked', 'revy'),
                'step_booked_subtitle' => esc_html__(' Thank you! Your booking is complete. An email with detail of your booking has been send to you.', 'revy'),
                'enable_fix_at_home' => 1,
                'enable_carry_in' => 1,
                'enable_mail_in' => 1,
                'fix_at_home_img_id' => 0,
                'fix_at_home_img_url' => '',
                'carry_in_img_id' => 0,
                'carry_in_img_url' => '',
                'mail_in_img_id' => 0,
                'mail_in_img_url' => '',
                'disable_scroll_top' => 0
            );
            $setting = get_option($this->option_key, $setting_default);
            $setting = array_merge($setting_default, $setting);

            $setting['fix_at_home_img_url']= $setting['fix_at_home_img_id'] ? wp_get_attachment_url($setting['fix_at_home_img_id']) : '';
            $setting['carry_in_img_url']= $setting['carry_in_img_id'] ? wp_get_attachment_url($setting['carry_in_img_id']) : '';
            $setting['mail_in_img_url']= $setting['mail_in_img_id'] ? wp_get_attachment_url($setting['mail_in_img_id']) : '';

            return $setting;
        }

        public function get_currency_setting()
        {
            $setting = $this->get_setting();
            $currency = Revy_Utils::getCurrency();
            $symbol = '$';
            foreach ($currency as $c) {
                if ($c['code'] == $setting['currency']) {
                    $symbol = $c['symbol'];
                    break;
                }
            }
            return array(
                'currency' => $setting['currency'],
                'symbol' => $symbol,
                'symbol_position' => $setting['symbol_position'],
                'number_of_decimals' => $setting['number_of_decimals']
            );
        }

        public function formatCurrency($price){
            $currency = $this->get_currency_setting();
            $price = is_numeric($price) ? floatval($price) : 0;
            if($currency['symbol_position']=='after'){
                return $currency['symbol'].number_format($price,$currency['number_of_decimals']);
            }else{
                return number_format($price,$currency['number_of_decimals']).$currency['symbol'];
            }
        }

        public function get_working_hour_setting()
        {
            $default = array(
                'schedules' => array(
                    array(
                        'es_day' => '2',
                        'es_enable' => '1',
                        'work_hours' => array(
                            array(
                                'es_work_hour_start' => 480,
                                'es_work_hour_end' => 1020
                            )
                        )
                    ),
                    array(
                        'es_day' => '3',
                        'es_enable' => '1',
                        'work_hours' => array(
                            array(
                                'es_work_hour_start' => 480,
                                'es_work_hour_end' => 1020
                            )
                        )
                    ),
                    array(
                        'es_day' => '4',
                        'es_enable' => '1',
                        'work_hours' => array(
                            array(
                                'es_work_hour_start' => 480,
                                'es_work_hour_end' => 1020
                            )
                        )
                    ),
                    array(
                        'es_day' => '5',
                        'es_enable' => '1',
                        'work_hours' => array(
                            array(
                                'es_work_hour_start' => 480,
                                'es_work_hour_end' => 1020
                            )
                        )
                    ),
                    array(
                        'es_day' => '6',
                        'es_enable' => '1',
                        'work_hours' => array(
                            array(
                                'es_work_hour_start' => 480,
                                'es_work_hour_end' => 1020
                            )
                        )
                    ),
                    array(
                        'es_day' => '7',
                        'es_enable' => '0',
                    ),
                    array(
                        'es_day' => '8',
                        'es_enable' => '0',
                    ),
                )
            );
            $working_hour = get_option($this->working_hour_key, $default);
            return $working_hour;
        }

        public function save_setting()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                $setting = $this->get_setting();
                foreach ($setting as $key => $value) {
                    if (isset($data[$key])) {
                        $setting[$key] = $data[$key];
                    }
                }
                update_option($this->option_key, $setting);
                return array(
                    'result' => 1,
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input data for field', 'fat-services-booking')
                );
            }
        }

        public function save_user_role_setting()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                if(isset($data['warning_limit_user_message'])){
                    $data['warning_limit_user_message'] = stripslashes($data['warning_limit_user_message']);
                }
                if(isset($data['warning_message'])){
                    $data['warning_message'] = stripslashes($data['warning_message']);
                }
                update_option($this->user_role_setting_key, $data);
                return array(
                    'result' => 1,
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input data for field', 'fat-services-booking')
                );
            }
        }

        public function get_user_role_setting()
        {
            return get_option($this->user_role_setting_key, true);
        }

        public function save_working_hour_setting()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data) {
                update_option($this->working_hour_key, $data);
                return array(
                    'result' => 1,
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input data', 'fat-services-booking')
                );
            }
        }

        public function get_email_template()
        {
            $template_default = array(
                array(
                    'template' => 'pending',
                    'fixit_home_template_enable' => 1,
                    'fixit_home_subject' => '',
                    'fixit_home_message' => '',
                    'carry_in_template_enable' => 1,
                    'carry_in_subject' => '',
                    'carry_in_message' => '',
                    'mail_in_template_enable' => 1,
                    'mail_in_subject' => '',
                    'mail_in_message' => ''
                ),
                array(
                    'template' => 'approved',
                    'fixit_home_template_enable' => 1,
                    'fixit_home_subject' => '',
                    'fixit_home_message' => '',
                    'carry_in_template_enable' => 1,
                    'carry_in_subject' => '',
                    'carry_in_message' => '',
                    'mail_in_template_enable' => 1,
                    'mail_in_subject' => '',
                    'mail_in_message' => ''
                ),
                array(
                    'template' => 'rejected',
                    'fixit_home_template_enable' => 1,
                    'fixit_home_subject' => '',
                    'fixit_home_message' => '',
                    'carry_in_template_enable' => 1,
                    'carry_in_subject' => '',
                    'carry_in_message' => '',
                    'mail_in_template_enable' => 1,
                    'mail_in_subject' => '',
                    'mail_in_message' => ''
                ),
                array(
                    'template' => 'canceled',
                    'fixit_home_template_enable' => 1,
                    'fixit_home_subject' => '',
                    'fixit_home_message' => '',
                    'carry_in_template_enable' => 1,
                    'carry_in_subject' => '',
                    'carry_in_message' => '',
                    'mail_in_template_enable' => 1,
                    'mail_in_subject' => '',
                    'mail_in_message' => ''
                ),
                array(
                    'template' => 'get_customer_code',
                    'customer_code_subject' => 'Request customer code',
                    'customer_code_message' => '<p>Dear {customer_first_name} {customer_last_name}  </p> <p>Please use this code : {customer_code} to view booking history  </p> <p>Thank you</p>',
                )
            );
            $template = get_option($this->email_template_key, $template_default);
            $template = is_array($template) ? $template : $template_default;
            for ($i = 0; $i < count($template); $i++) {
                if(isset( $template[$i]['single_mode_message'])){
                    $template[$i]['single_mode_message'] = html_entity_decode($template[$i]['single_mode_message']);
                }
                if(isset( $template[$i]['group_mode_message'])){
                    $template[$i]['group_mode_message'] = html_entity_decode($template[$i]['group_mode_message']);
                }
                if(isset( $template[$i]['customer_code_message'])){
                    $template[$i]['customer_code_message'] = html_entity_decode($template[$i]['customer_code_message']);
                }
            }
            return $template;
        }

        public function save_email_template()
        {
            $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
            $template = isset($data['template']) ? $data['template'] : '';

            if ($template) {
                $email_template = $this->get_email_template();
                for ($i = 0; $i < count($email_template); $i++) {
                    if ($email_template[$i]['template'] == $template) {
                        if ($template == 'get_customer_code') {
                            $email_template[$i]['customer_code_subject'] = isset($data['customer_code_subject']) ? $data['customer_code_subject'] : '';
                            $email_template[$i]['customer_code_message'] = isset($data['customer_code_message']) ? $data['customer_code_message'] : '';
                        } else {
                            $email_template[$i]['fixit_home_enable'] = isset($data['fixit_home_enable']) ? $data['fixit_home_enable'] : 0;
                            $email_template[$i]['fixit_home_subject'] = isset($data['fixit_home_subject']) ? $data['fixit_home_subject'] : '';
                            $email_template[$i]['fixit_home_message'] = isset($data['fixit_home_message']) ? $data['fixit_home_message'] : '';

                            $email_template[$i]['carry_in_enable'] = isset($data['carry_in_enable']) ? $data['carry_in_enable'] : 0;
                            $email_template[$i]['carry_in_subject'] = isset($data['carry_in_subject']) ? $data['carry_in_subject'] : '';
                            $email_template[$i]['carry_in_message'] = isset($data['carry_in_message']) ? $data['carry_in_message'] : '';

                            $email_template[$i]['mail_in_enable'] = isset($data['mail_in_enable']) ? $data['mail_in_enable'] : 0;
                            $email_template[$i]['mail_in_subject'] = isset($data['mail_in_subject']) ? $data['mail_in_subject'] : '';
                            $email_template[$i]['mail_in_message'] = isset($data['mail_in_message']) ? $data['mail_in_message'] : '';
                        }
                    }
                }
                update_option($this->email_template_key, $email_template);
                return array(
                    'result' => 1,
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data is invalid', 'fat-services-booking')
                );
            }
        }

        public function test_send_email_template()
        {
            $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
            $template = isset($_REQUEST['template']) ? $_REQUEST['template'] : '';
            $send_to = isset($_REQUEST['send_to']) ? $_REQUEST['send_to'] : '';

            if ($template && $send_to) {
                $email_template = $this->get_email_template();
                $setting = $this->get_setting();
                $now = current_time('mysql', 0);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $now->modify('+1 days');
                $subject = $message = '';

                for ($i = 0; $i < count($email_template); $i++) {
                    if ($email_template[$i]['template'] == $template) {
                        $mail_info = array(
                            'c_code' => 'ahxyr132ays',
                            'c_first_name' => 'Jonh',
                            'c_last_name' => 'Smith',
                            'c_email' => $send_to,
                            'c_phone' => '+432 76548 876',
                            'b_customer_city' => 'Vix tation',
                            'b_customer_address' => 'Qui assum saperet ne',
                            'b_customer_country' => 'Netherlands',
                            'b_customer_postal_code' => '76583',
                            'rb_name' => 'Apple',
                            'rd_name' => 'Iphone',
                            'rm_name' => 'Iphone 12',
                            'rg_name' => 'IFix store',
                            'rg_address' => 'Soluta senserit pro te',
                            'rg_email' => 'ifix@gmail.com',
                            'rg_phone' => '+41 8376234',
                            's_name' => 'Possim recusabo',
                            'b_attr_title' => 'Mei scripta',
                            'b_attr_value' => '126 AVH',
                            's_link' => home_url(),
                            'b_service_duration' => 30,
                            'b_date' => $now->format('Y-m-d'),
                            'b_time' => 540,
                            'b_total_pay' => 25,
                            'b_description' => '',
                            'b_customer_number' => 1,
                            'b_coupon_code' => '',
                            'b_gateway_type' => 'onsite',
                            's_description' => 'Erant omnes eos et',
                            'b_delivery_method' => 1
                        );
                        $mail_info = (object)$mail_info;
                        $result = '';
                        $result_test = array(
                            'result_customer' => '',
                            'message_customer' => '',
                            'result_employee' => '',
                            'message_employee' => '',
                        );
                        if (isset($email_template[$i]['fixit_home_enable']) && $email_template[$i]['fixit_home_enable']) {
                            $subject = $email_template[$i]['fixit_home_subject'];
                            $message = $email_template[$i]['fixit_home_message'];
                            Revy_Utils::makeMailContent($subject, $message, $mail_info, $setting);
                            $result = Revy_Utils::sendMail(array(
                                'mailer' => $setting['mailer'],
                                'smtp_host' => $setting['smtp_host'],
                                'smtp_port' => $setting['smtp_port'],
                                'smtp_username' => $setting['smtp_username'],
                                'smtp_password' => $setting['smtp_password'],
                                'encryption' => $setting['smtp_encryption'],
                                'from_name' => $setting['send_from_name'],
                                'from_name_label' => isset($setting['send_from_name_label']) ? $setting['send_from_name_label'] : $setting['send_from_name'],
                                'send_to' => $mail_info->c_email,
                                'cc_email' => $setting['cc_to'],
                                'bcc_email' => $setting['bcc_to'],
                                'subject' => $subject,
                                'message' => $message
                            ));
                            $result_test['result_fixit_home'] = isset($result['result']) ? $result['result'] : 0;
                            $result_test['message_fixit_home'] = isset($result['message']) ? $result['message'] : '';
                        }

                        if (isset($email_template[$i]['carry_in_enable']) && $email_template[$i]['carry_in_enable']) {
                            $subject = $email_template[$i]['carry_in_subject'];
                            $message = $email_template[$i]['carry_in_message'];
                            Revy_Utils::makeMailContent($subject, $message, $mail_info, $setting);
                            $result = Revy_Utils::sendMail(array(
                                'mailer' => $setting['mailer'],
                                'smtp_host' => $setting['smtp_host'],
                                'smtp_port' => $setting['smtp_port'],
                                'smtp_username' => $setting['smtp_username'],
                                'smtp_password' => $setting['smtp_password'],
                                'encryption' => $setting['smtp_encryption'],
                                'from_name' => $setting['send_from_name'],
                                'from_name_label' => isset($setting['send_from_name_label']) ? $setting['send_from_name_label'] : $setting['send_from_name'],
                                'send_to' => $mail_info->c_email,
                                'cc_email' => $setting['cc_to'],
                                'bcc_email' => $setting['bcc_to'],
                                'subject' => $subject,
                                'message' => $message
                            ));
                            $result_test['result_carry_in'] = isset($result['result']) ? $result['result'] : 0;
                            $result_test['message_carry_in'] = isset($result['message']) ? $result['message'] : '';
                        }

                        if (isset($email_template[$i]['mail_in_enable']) && $email_template[$i]['mail_in_enable']) {
                            $subject = $email_template[$i]['mail_in_subject'];
                            $message = $email_template[$i]['mail_in_message'];
                            Revy_Utils::makeMailContent($subject, $message, $mail_info, $setting);
                            $result = Revy_Utils::sendMail(array(
                                'mailer' => $setting['mailer'],
                                'smtp_host' => $setting['smtp_host'],
                                'smtp_port' => $setting['smtp_port'],
                                'smtp_username' => $setting['smtp_username'],
                                'smtp_password' => $setting['smtp_password'],
                                'encryption' => $setting['smtp_encryption'],
                                'from_name' => $setting['send_from_name'],
                                'from_name_label' => isset($setting['send_from_name_label']) ? $setting['send_from_name_label'] : $setting['send_from_name'],
                                'send_to' => $mail_info->c_email,
                                'cc_email' => $setting['cc_to'],
                                'bcc_email' => $setting['bcc_to'],
                                'subject' => $subject,
                                'message' => $message
                            ));
                            $result_test['result_mail_in'] = isset($result['result']) ? $result['result'] : 0;
                            $result_test['message_mail_in'] = isset($result['message']) ? $result['message'] : '';
                        }

                        return $result_test;
                    }
                }
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data is invalid', 'fat-services-booking')
                );
            }
        }

        public function test_send_mail()
        {
            $setting = $this->get_setting();
            $send_to = isset($_REQUEST['send_to']) && $_REQUEST['send_to'] ? $_REQUEST['send_to'] : '';
            $subject = esc_html__('Test mail from Repair Booking plugin', 'fat-services-booking');
            $message = esc_html__('This is email from Repair Booking plugin. This send with purpose for test mail config', 'fat-services-booking');

            if (!$setting['mailer']) {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input data for notification setting and save before test', 'fat-services-booking')
                );
            }
            return Revy_Utils::sendMail(array(
                'mailer' => $setting['mailer'],
                'smtp_host' => $setting['smtp_host'],
                'smtp_port' => $setting['smtp_port'],
                'smtp_username' => $setting['smtp_username'],
                'smtp_password' => $setting['smtp_password'],
                'encryption' => $setting['smtp_encryption'],
                'from_name' => $setting['send_from_name'],
                'from_name_label' => isset($setting['send_from_name_label']) ? $setting['send_from_name_label'] : $setting['send_from_name'],
                'send_to' => $send_to,
                'subject' => $subject,
                'message' => $message
            ));
        }

    }
}