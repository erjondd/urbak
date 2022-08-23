<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 06/19/2020
 * Time: 10:10 AM
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!class_exists('Revy_Utils')) {
    class Revy_Utils
    {
        public static function getDurations($start = 1, $step_key = 'time_step')
        {
            $time = 0;
            $duration = array();
            $step = 15;
            $setting_db = Revy_DB_Setting::instance();
            $setting_db = $setting_db->get_setting();
            $step = isset($setting_db[$step_key]) && $setting_db[$step_key] ? $setting_db[$step_key] : $step;
            $counter = (24*60 / $step);
            if (!isset($start)) {
                $start = 1;
            }
            for ($i = $start; $i <= $counter; $i++) {
                $time = $i * $step;
                if ($time < 60) {
                    $duration[$time] = $time . esc_html__(' minutes', 'revy');
                } else {
                    if ($time % 60 == 0) {
                        $duration[$time] = ($time / 60) == 1 ? esc_html__('1 hour', 'revy') : ($time / 60) . esc_html__(' hours', 'revy');
                    } else {
                        $duration[$time] = floor($time / 60) . esc_html__(' hours', 'revy') . ' ' . ($time % 60) . esc_html__(' minutes', 'revy');
                    }
                }
            }
            $duration = apply_filters('apoint_duration', $duration);
            return $duration;
        }

        public static function getWorkHours($time_step = 15)
        {
            $time = 0;
            $work_hours = array();
            $hour = 0;
            $minute = 0;
            $ranger = (24 * 60) / $time_step - 1;
            $time_format = get_option('time_format');
            for ($i = 0; $i <= $ranger; $i++) {
                $time = $i * $time_step;
                $minute = $time % 60;
                $hour = floor($time / 60);
                $work_hours[$time] = date ($time_format,strtotime($hour.':'. $minute)); //  ($hour >= 10 ? $hour : '0' . $hour) . ':' . ($minute >= 10 ? $minute : '0' . $minute);
            }
            return $work_hours;
        }

        public static function getCoupon($coupon_code, $s_ids)
        {
            global $wpdb;
            $coupon = $wpdb->get_results("SELECT cp_amount, cp_apply_to, cp_code, cp_create_date, cp_discount_type, cp_exclude, cp_expire, cp_id, cp_start_date, cp_times_use, cp_use_count  
                                        FROM {$wpdb->prefix}rp_coupons 
                                        WHERE cp_code='{$coupon_code}'");
            if (count($coupon) > 0) {
                $coupon = $coupon[0];
                $now = current_time('mysql', 0);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $now = $now->format('Y-m-d H:i:s');
                $expire = $coupon->cp_expire;
                $start = $coupon->cp_start_date;
                if ($coupon->cp_times_use == $coupon->cp_use_count || (strtotime($now) - strtotime($expire)) > 0) {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('This coupons has expired', 'revy')
                    );
                }

                if ((strtotime($start) - strtotime($now)) > 0) {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('This coupon only apply from: ', 'revy') . DateTime::createFromFormat('Y-m-d H:i:s', $coupon->cp_start_date)->format('Y-F-j')
                    );
                }

                if ($coupon->cp_exclude) {
                    $exclude = explode(',', $coupon->cp_exclude);
                    foreach($s_ids as $s_id){
                        if (in_array($s_id, $exclude)) {
                            return array(
                                'result' => -1,
                                'message' => esc_html__('The coupon don\' apply for this service', 'revy')
                            );
                        }
                    }
                }
                if ($coupon->cp_apply_to) {
                    $apply_to = explode(',', $coupon->cp_apply_to);
                    foreach($s_ids as $s_id){
                        if (!in_array($s_id, $apply_to)) {
                            return array(
                                'result' => -1,
                                'message' => esc_html__('The coupon don\' apply for this service', 'revy')
                            );
                        }
                    }
                }

                return array(
                    'result' => 1,
                    'coupon_id' => $coupon->cp_id,
                    'discount_type' => $coupon->cp_discount_type == 1,
                    'amount' => $coupon->cp_amount,
                );

            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('The coupon is invalid', 'revy')
                );
            }
        }

        public static function makeMailContent(&$subject, &$message, $info, $setting)
        {
            $message = html_entity_decode($message);

            $subject = str_replace('{service_name}', $info->s_name, $subject);
            $subject = str_replace('{customer_first_name}', $info->c_first_name, $subject);
            $subject = str_replace('{customer_last_name}', $info->c_last_name, $subject);

            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $info->start = DateTime::createFromFormat('Y-m-d H:i:s', $info->b_date . ' 00:00:00');
            $info->start->modify("+{$info->b_time} minutes");
            $info->end = clone $info->start;
            if($info->b_service_duration > 0){
                $info->end->modify("+{$info->b_service_duration} minutes");
            }

            $db_setting = Revy_DB_Setting::instance();
            $currency = $db_setting->get_currency_setting();
            $currency_symbol = isset($currency['symbol']) ? $currency['symbol'] : '$';
            $b_total_pay = number_format($info->b_total_pay, 2);
            $b_total_pay = $setting['symbol_position'] == 'before' ? ($currency_symbol . $b_total_pay) : ($b_total_pay . $currency_symbol);

            if(isset($info->multiple_date_time)){
                $message = str_replace('{booking_time}', '', $message);
                $info->multiple_date_time = date_i18n($date_format, $info->start->format('U')) .' '.$info->start->format('H:i').' - '. $info->end->format('H:i'). ' </br> '. $info->multiple_date_time;
                $message = str_replace('{booking_date}', $info->multiple_date_time, $message);
            }else{
                $message = str_replace('{booking_time}', $info->start->format($time_format), $message);
                $message = str_replace('{booking_date}', date_i18n($date_format, $info->start->format('U')), $message);
            }

            $delivery_method = '';
            $delivery_method = isset($info->b_delivery_method) && $info->b_delivery_method==1 ? esc_html__('Fixit Home','revy') : $delivery_method;
            $delivery_method = isset($info->b_delivery_method) && $info->b_delivery_method==2 ? esc_html__('Carry In','revy') : $delivery_method;
            $delivery_method = isset($info->b_delivery_method) && $info->b_delivery_method==3 ? esc_html__('Mail In','revy') : $delivery_method;

            $message = str_replace('{garage_name}', $info->rg_name, $message);
            $message = str_replace('{garage_address}', $info->rg_address, $message);
            if(isset($info->rg_email)){
                $message = str_replace('{garage_email}', $info->rg_email, $message);
            }
            $message = str_replace('{garage_phone}', $info->rg_phone, $message);
            $message = str_replace('{brand_name}', $info->rb_name, $message);
            $message = str_replace('{device_name}', $info->rd_name, $message);
            $message = str_replace('{model_name}', $info->rm_name, $message);
            $message = str_replace('{delivery_method}', $delivery_method, $message);
            $message = str_replace('{booking_price}', $b_total_pay, $message);

            $message = isset($info->c_code) ? str_replace('{customer_code}', $info->c_code, $message) : $message;
            $message = str_replace('{customer_first_name}', $info->c_first_name, $message);
            $message = str_replace('{customer_last_name}', $info->c_last_name, $message);
            $message = str_replace('{customer_phone}', $info->c_phone, $message);
            $message = str_replace('{customer_email}', $info->c_email, $message);
            if(isset($info->b_customer_address)){
                $message = str_replace('{customer_address}', $info->b_customer_address, $message);
            }
            if(isset($info->b_customer_city)){
                $message = str_replace('{customer_city}', $info->b_customer_city, $message);
            }
            if(isset($info->b_customer_country)){
                $message = str_replace('{customer_country}', $info->b_customer_country, $message);
            }
            if(isset( $info->b_customer_postal_code)){
                $message = str_replace('{customer_postal_code}', $info->b_customer_postal_code, $message);
            }
            $message = str_replace('{company_phone}', $setting['company_phone'], $message);
            $message = str_replace('{company_name}', $setting['company_name'], $message);
            $message = str_replace('{company_address}', $setting['company_address'], $message);
            $message = str_replace('{company_email}', $setting['company_email'], $message);
            $message = str_replace('{note}', $info->b_description, $message);
            $message = str_replace('{coupon_code}', $info->b_coupon_code, $message);
            $message = str_replace('{s_description}', $info->s_description, $message);


            $service_info = '';
            $price = '';
            if(isset($info->s_info) && is_array($info->s_info)){
                foreach($info->s_info as $si){
                    $price =  number_format($si['b_price'], 2);
                    $price = $setting['symbol_position'] == 'before' ? ($currency_symbol . $price) : ($price . $currency_symbol);
                    $service_info .= esc_html__('Service:', 'revy'). ' '.$si['s_name']. ' ('.$si['b_attr_title'].' '. $si['b_attr_value'].')<br/>';
                    $service_info .= esc_html__('Duration:', 'revy'). ' '.$si['b_service_duration']. esc_html__(' minutes', 'revy').'<br/>';
                    $service_info .=esc_html__('Price:', 'revy'). ' '.$price.'<br/>';
                    $service_info .= '<p></p>';
                }
            }

            $message = str_replace('{service_info}', $service_info, $message);

            $info->b_gateway_type = ucfirst($info->b_gateway_type);
            $message = str_replace('{payment_method}',$info->b_gateway_type, $message);

        }

        public static function sendMail($args)
        {
            $args = array_merge(array(
                'mailer' => '',
                'smtp_host' => '',
                'smtp_port' => '',
                'smtp_username' => '',
                'smtp_password' => '',
                'encryption' => '',
                'from_name' => '',
                'from_name_label' => '',
                'send_to' => '',
                'cc_email' => '',
                'bcc_email' => '',
                'subject' => '',
                'message' => ''
            ), $args);

            if (!$args['send_to'] || !$args['message']) {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input value for send_to and message', 'revy')
                );
            }

            if($args['mailer'] === 'postSMTP'){
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=utf-8';
                if ( isset($args['cc_email']) && $args['cc_email']) {
                    $headers[] = 'CC: ' . $args['cc_email'];
                }
                if ( isset($args['bcc_email']) && $args['bcc_email']) {
                    $headers[] = 'BCC: ' . $args['bcc_email'];
                }
                $result = wp_mail($args['send_to'], $args['subject'], $args['message'], $headers);
                return array(
                    'result' => $result ? 1 : 0,
                    'message' => $result ?  esc_html__('Email has been send, please check your mailbox', 'revy') : esc_html__('An error occurred when sending mail', 'revy')
                );
            }

            if ($args['mailer'] === 'smtp') {
                if (!class_exists('PHPMailer\\PHPMailer\\Exception')) {
                    require REVY_DIR_PATH . 'libs/PHPMailer/src/Exception.php';
                }
                if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
                    require REVY_DIR_PATH . 'libs/PHPMailer/src/PHPMailer.php';
                }
                if (!class_exists('PHPMailer\\PHPMailer\\SMTP')) {
                    require REVY_DIR_PATH . 'libs/PHPMailer/src/SMTP.php';
                }

                if ($args['smtp_host'] == '' || $args['smtp_port'] == '' || $args['smtp_username'] == '' || $args['smtp_password'] == '') {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('Please input data for smtp host, smtp port, username, password and save before test', 'revy')
                    );
                }

                if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
                    $mail = new PHPMailer();
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Mailer = 'smtp';
                    $mail->Priority = 1;
                    $mail->isHTML(true);
                    //$mail->ClearCustomHeaders();
                    $mail->CharSet = PHPMailer::CHARSET_UTF8;

                    $mail->SMTPSecure = isset($args['encryption']) && $args['encryption'] ? $args['encryption'] : 'ssl';
                    $mail->Host = $args['smtp_host'];
                    $mail->Port = $args['smtp_port'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $args['smtp_username'];
                    $mail->Password = $args['smtp_password'];

                    if ($mail->SMTPSecure === 'ssl') {
                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        );
                    }

                    //Recipients
                    $mail->setFrom($args['smtp_username'], $args['from_name']);
                    $mail->addAddress($args['send_to'], $args['from_name']);
                    if (isset($args['cc_email']) && $args['cc_email']) {
                        $mail->addCC($args['cc_email']);
                    }
                    if (isset($args['bcc_email']) && $args['bcc_email']) {
                        $mail->addBCC($args['bcc_email']);
                    }

                    //Content
                    $mail->Subject = $args['subject'];
                    $mail->Body = preg_replace('/\n/', '<br/>', $args['message']);
                    $mail->Body = stripslashes($mail->Body);

                    //send the message, check for errors
                    if (!$mail->send()) {
                        return array(
                            'result' => -1,
                            'message' => "Mailer Error: " . $mail->ErrorInfo
                        );
                        //echo json_encode($result);
                    } else {
                        return array(
                            'result' => 1,
                            'message' => sprintf(esc_html__('Email has been send, please check your (%s) mailbox', 'revy'), $args['send_to'])
                        );
                        //echo json_encode($result);
                    }
                }
            } else {
                try {
                    $args['from_name_label'] = $args['from_name_label'] ? $args['from_name_label'] : $args['from_name'];
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=UTF-8';
                    $headers[] = 'From: ' .  $args['from_name_label'] .'<'.$args['from_name'].'>';
                    if (isset($args['cc_email']) && $args['cc_email']) {
                        $headers[] = 'CC: ' . $args['cc_email'];
                    }
                    if (isset($args['bcc_email']) && $args['bcc_email']) {
                        $headers[] = 'BCC: ' . $args['bcc_email'];
                    }
                    $result = mail($args['send_to'], $args['subject'], $args['message'], implode("\r\n", $headers));
                    if (!$result) {
                        return array(
                            'result' => -1,
                            'message' => esc_html__('An error occurred when sending mail', 'revy')
                        );
                    } else {
                        return array(
                            'result' => 1,
                            'message' => sprintf(esc_html__('Email has been send, please check your(%) mailbox', 'revy'), $args['send_to'])
                        );
                    }
                    //echo json_encode($result);

                } catch (Exception $err) {
                    $result = array(
                        'result' => -1,
                        'message' => esc_html__('An error occurred when sending mail', 'revy')
                    );
                    if (isset($is_return) && $is_return) {
                        return $result;
                    } else {
                        echo json_encode($result);
                    }
                }
            }
        }

        public static function getCurrency()
        {
            $currency = array(
                array('code' => 'AED', 'symbol' => 'د.إ', 'name' => 'UAE Dirham'),
                array('code' => 'AFN', 'symbol' => 'Af', 'name' => 'Afghani'),
                array('code' => 'ALL', 'symbol' => 'L', 'name' => 'Lek'),
                array('code' => 'AMD', 'symbol' => 'Դ', 'name' => 'Armenian Dram'),
                array('code' => 'AOA', 'symbol' => 'Kz', 'name' => 'Kwanza'),
                array('code' => 'ARS', 'symbol' => '$', 'name' => 'Argentine Peso'),
                array('code' => 'AUD', 'symbol' => '$', 'name' => 'Australian Dollar'),
                array('code' => 'AWG', 'symbol' => 'ƒ', 'name' => 'Aruban Guilder/Florin'),
                array('code' => 'AZN', 'symbol' => 'ман', 'name' => 'Azerbaijanian Manat'),
                array('code' => 'BAM', 'symbol' => 'КМ', 'name' => 'Konvertibilna Marka'),
                array('code' => 'BBD', 'symbol' => '$', 'name' => 'Barbados Dollar'),
                array('code' => 'BDT', 'symbol' => '৳', 'name' => 'Taka'),
                array('code' => 'BGN', 'symbol' => 'лв', 'name' => 'Bulgarian Lev'),
                array('code' => 'BHD', 'symbol' => 'ب.د', 'name' => 'Bahraini Dinar'),
                array('code' => 'BIF', 'symbol' => '₣', 'name' => 'Burundi Franc'),
                array('code' => 'BMD', 'symbol' => '$', 'name' => 'Bermudian Dollar'),
                array('code' => 'BND', 'symbol' => '$', 'name' => 'Brunei Dollar'),
                array('code' => 'BOB', 'symbol' => 'Bs.', 'name' => 'Boliviano'),
                array('code' => 'BRL', 'symbol' => 'R$', 'name' => 'Brazilian Real'),
                array('code' => 'BSD', 'symbol' => '$', 'name' => 'Bahamian Dollar'),
                array('code' => 'BTN', 'symbol' => '', 'name' => 'Ngultrum'),
                array('code' => 'BWP', 'symbol' => 'P', 'name' => 'Pula'),
                array('code' => 'BYR', 'symbol' => 'Br', 'name' => 'Belarussian Ruble'),
                array('code' => 'BZD', 'symbol' => '$', 'name' => 'Belize Dollar'),
                array('code' => 'CAD', 'symbol' => '$', 'name' => 'Canadian Dollar'),
                array('code' => 'CDF', 'symbol' => '₣', 'name' => 'Congolese Franc'),
                array('code' => 'CHF', 'symbol' => 'CHF', 'name' => 'Swiss Franc'),
                array('code' => 'CLP', 'symbol' => '$', 'name' => 'Chilean Peso'),
                array('code' => 'CNY', 'symbol' => '¥', 'name' => 'Yuan'),
                array('code' => 'COP', 'symbol' => '$', 'name' => 'Colombian Peso'),
                array('code' => 'CRC', 'symbol' => '₡', 'name' => 'Costa Rican Colon'),
                array('code' => 'CUP', 'symbol' => '$', 'name' => 'Cuban Peso'),
                array('code' => 'CVE', 'symbol' => '$', 'name' => 'Cape Verde Escudo'),
                array('code' => 'CZK', 'symbol' => 'Kč', 'name' => 'Czech Koruna'),
                array('code' => 'DJF', 'symbol' => '₣', 'name' => 'Djibouti Franc'),
                array('code' => 'DKK', 'symbol' => 'kr', 'name' => 'Danish Krone'),
                array('code' => 'DOP', 'symbol' => '$', 'name' => 'Dominican Peso'),
                array('code' => 'DZD', 'symbol' => 'د.ج', 'name' => 'Algerian Dinar'),
                array('code' => 'EGP', 'symbol' => '£', 'name' => 'Egyptian Pound'),
                array('code' => 'ERN', 'symbol' => 'Nfk', 'name' => 'Nakfa'),
                array('code' => 'ETB', 'symbol' => '', 'name' => 'Ethiopian Birr'),
                array('code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'),
                array('code' => 'FJD', 'symbol' => '$', 'name' => 'Fiji Dollar'),
                array('code' => 'FKP', 'symbol' => '£', 'name' => 'Falkland Islands Pound'),
                array('code' => 'GBP', 'symbol' => '£', 'name' => 'Pound Sterling'),
                array('code' => 'GEL', 'symbol' => 'ლ', 'name' => 'Lari'),
                array('code' => 'GHS', 'symbol' => '₵', 'name' => 'Cedi'),
                array('code' => 'GIP', 'symbol' => '£', 'name' => 'Gibraltar Pound'),
                array('code' => 'GMD', 'symbol' => 'D', 'name' => 'Dalasi'),
                array('code' => 'GNF', 'symbol' => '₣', 'name' => 'Guinea Franc'),
                array('code' => 'GTQ', 'symbol' => 'Q', 'name' => 'Quetzal'),
                array('code' => 'GYD', 'symbol' => '$', 'name' => 'Guyana Dollar'),
                array('code' => 'HKD', 'symbol' => '$', 'name' => 'Hong Kong Dollar'),
                array('code' => 'HNL', 'symbol' => 'L', 'name' => 'Lempira'),
                array('code' => 'HRK', 'symbol' => 'Kn', 'name' => 'Croatian Kuna'),
                array('code' => 'HTG', 'symbol' => 'G', 'name' => 'Gourde'),
                array('code' => 'HUF', 'symbol' => 'Ft', 'name' => 'Forint'),
                array('code' => 'IDR', 'symbol' => 'Rp', 'name' => 'Rupiah'),
                array('code' => 'ILS', 'symbol' => '₪', 'name' => 'New Israeli Shekel'),
                array('code' => 'INR', 'symbol' => '₹', 'name' => 'Indian Rupee'),
                array('code' => 'IQD', 'symbol' => 'ع.د', 'name' => 'Iraqi Dinar'),
                array('code' => 'IRR', 'symbol' => '﷼', 'name' => 'Iranian Rial'),
                array('code' => 'ISK', 'symbol' => 'Kr', 'name' => 'Iceland Krona'),
                array('code' => 'JMD', 'symbol' => '$', 'name' => 'Jamaican Dollar'),
                array('code' => 'JOD', 'symbol' => 'د.ا', 'name' => 'Jordanian Dinar'),
                array('code' => 'JPY', 'symbol' => '¥', 'name' => 'Yen'),
                array('code' => 'KES', 'symbol' => 'Sh', 'name' => 'Kenyan Shilling'),
                array('code' => 'KGS', 'symbol' => '', 'name' => 'Som'),
                array('code' => 'KHR', 'symbol' => '៛', 'name' => 'Riel'),
                array('code' => 'KPW', 'symbol' => '₩', 'name' => 'North Korean Won'),
                array('code' => 'KRW', 'symbol' => '₩', 'name' => 'South Korean Won'),
                array('code' => 'KWD', 'symbol' => 'د.ك', 'name' => 'Kuwaiti Dinar'),
                array('code' => 'KYD', 'symbol' => '$', 'name' => 'Cayman Islands Dollar'),
                array('code' => 'KZT', 'symbol' => '〒', 'name' => 'Tenge'),
                array('code' => 'LAK', 'symbol' => '₭', 'name' => 'Kip'),
                array('code' => 'LBP', 'symbol' => 'ل.ل', 'name' => 'Lebanese Pound'),
                array('code' => 'LKR', 'symbol' => 'Rs', 'name' => 'Sri Lanka Rupee'),
                array('code' => 'LRD', 'symbol' => '$', 'name' => 'Liberian Dollar'),
                array('code' => 'LSL', 'symbol' => 'L', 'name' => 'Loti'),
                array('code' => 'LYD', 'symbol' => 'ل.د', 'name' => 'Libyan Dinar'),
                array('code' => 'MAD', 'symbol' => 'د.م.', 'name' => 'Moroccan Dirham'),
                array('code' => 'MDL', 'symbol' => 'L', 'name' => 'Moldavian Leu'),
                array('code' => 'MGA', 'symbol' => '', 'name' => 'Malagasy Ariary'),
                array('code' => 'MKD', 'symbol' => 'ден', 'name' => 'Denar'),
                array('code' => 'MMK', 'symbol' => 'K', 'name' => 'Kyat'),
                array('code' => 'MNT', 'symbol' => '₮', 'name' => 'Tugrik'),
                array('code' => 'MOP', 'symbol' => 'P', 'name' => 'Pataca'),
                array('code' => 'MRO', 'symbol' => 'UM', 'name' => 'Ouguiya'),
                array('code' => 'MUR', 'symbol' => '₨', 'name' => 'Mauritius Rupee'),
                array('code' => 'MVR', 'symbol' => 'ރ.', 'name' => 'Rufiyaa'),
                array('code' => 'MWK', 'symbol' => 'MK', 'name' => 'Kwacha'),
                array('code' => 'MXN', 'symbol' => '$', 'name' => 'Mexican Peso'),
                array('code' => 'MYR', 'symbol' => 'RM', 'name' => 'Malaysian Ringgit'),
                array('code' => 'MZN', 'symbol' => 'MTn', 'name' => 'Metical'),
                array('code' => 'NAD', 'symbol' => '$', 'name' => 'Namibia Dollar'),
                array('code' => 'NGN', 'symbol' => '₦', 'name' => 'Naira'),
                array('code' => 'NIO', 'symbol' => 'C$', 'name' => 'Cordoba Oro'),
                array('code' => 'NOK', 'symbol' => 'kr', 'name' => 'Norwegian Krone'),
                array('code' => 'NPR', 'symbol' => '₨', 'name' => 'Nepalese Rupee'),
                array('code' => 'NZD', 'symbol' => '$', 'name' => 'New Zealand Dollar'),
                array('code' => 'OMR', 'symbol' => 'ر.ع.', 'name' => 'Rial Omani'),
                array('code' => 'PAB', 'symbol' => 'B/.', 'name' => 'Balboa'),
                array('code' => 'PEN', 'symbol' => 'S/.', 'name' => 'Nuevo Sol'),
                array('code' => 'PGK', 'symbol' => 'K', 'name' => 'Kina'),
                array('code' => 'PHP', 'symbol' => '₱', 'name' => 'Philippine Peso'),
                array('code' => 'PKR', 'symbol' => '₨', 'name' => 'Pakistan Rupee'),
                array('code' => 'PLN', 'symbol' => 'zł', 'name' => 'PZloty'),
                array('code' => 'PYG', 'symbol' => '₲', 'name' => 'Guarani'),
                array('code' => 'QAR', 'symbol' => 'ر.ق', 'name' => 'Qatari Rial'),
                array('code' => 'RON', 'symbol' => 'L', 'name' => 'Leu'),
                array('code' => 'RSD', 'symbol' => 'din', 'name' => 'Serbian Dinar'),
                array('code' => 'RUB', 'symbol' => 'р. ', 'name' => 'Russian Ruble'),
                array('code' => 'RWF', 'symbol' => '₣', 'name' => 'Rwanda Franc'),
                array('code' => 'SAR', 'symbol' => 'ر.س', 'name' => 'Saudi Riyal'),
                array('code' => 'SBD', 'symbol' => '$', 'name' => 'Solomon Islands Dollar'),
                array('code' => 'SCR', 'symbol' => '₨', 'name' => 'Seychelles Rupee'),
                array('code' => 'SDG', 'symbol' => '£', 'name' => 'Sudanese Pound'),
                array('code' => 'SEK', 'symbol' => 'kr', 'name' => 'Swedish Krona'),
                array('code' => 'SGD', 'symbol' => '$', 'name' => 'Singapore Dollar'),
                array('code' => 'SHP', 'symbol' => '£', 'name' => 'Saint Helena Pound'),
                array('code' => 'SLL', 'symbol' => 'Le', 'name' => 'Leone'),
                array('code' => 'SOS', 'symbol' => 'Sh', 'name' => 'Somali Shilling'),
                array('code' => 'SRD', 'symbol' => '$', 'name' => 'Suriname Dollar'),
                array('code' => 'STD', 'symbol' => 'Db', 'name' => 'Dobra'),
                array('code' => 'SYP', 'symbol' => 'ل.س', 'name' => 'Syrian Pound'),
                array('code' => 'SZL', 'symbol' => 'L', 'name' => 'Lilangeni'),
                array('code' => 'THB', 'symbol' => '฿', 'name' => 'Baht'),
                array('code' => 'TJS', 'symbol' => 'ЅМ', 'name' => 'Somoni'),
                array('code' => 'TMT', 'symbol' => 'm', 'name' => 'Manat'),
                array('code' => 'TND', 'symbol' => 'د.ت', 'name' => 'Tunisian Dinar'),
                array('code' => 'TOP', 'symbol' => 'T$', 'name' => 'Pa’anga'),
                array('code' => 'TRY', 'symbol' => '₤', 'name' => 'Turkish Lira'),
                array('code' => 'TTD', 'symbol' => '$', 'name' => 'Trinidad and Tobago Dollar'),
                array('code' => 'TWD', 'symbol' => '$', 'name' => 'Taiwan Dollar'),
                array('code' => 'TZS', 'symbol' => 'Sh', 'name' => 'Tanzanian Shilling'),
                array('code' => 'UAH', 'symbol' => '₴', 'name' => 'Hryvnia'),
                array('code' => 'UGX', 'symbol' => 'Sh', 'name' => 'Uganda Shilling'),
                array('code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar'),
                array('code' => 'UYU', 'symbol' => '$', 'name' => 'Peso Uruguayo'),
                array('code' => 'UZS', 'symbol' => '', 'name' => 'Uzbekistan Sum'),
                array('code' => 'VEF', 'symbol' => 'Bs F', 'name' => 'Bolivar Fuerte'),
                array('code' => 'VND', 'symbol' => '₫', 'name' => 'Dong'),
                array('code' => 'VUV', 'symbol' => 'Vt', 'name' => 'Vatu'),
                array('code' => 'WST', 'symbol' => 'T', 'name' => 'Tala'),
                array('code' => 'XAF', 'symbol' => '₣', 'name' => 'CFA Franc BCEAO'),
                array('code' => 'XCD', 'symbol' => '$', 'name' => 'East Caribbean Dollar'),
                array('code' => 'XPF', 'symbol' => '₣', 'name' => 'CFP Franc'),
                array('code' => 'YER', 'symbol' => '﷼', 'name' => 'Yemeni Rial'),
                array('code' => 'ZAR', 'symbol' => 'R', 'name' => 'Rand'),
                array('code' => 'ZMW', 'symbol' => 'ZK', 'name' => 'Zambian Kwacha'),
                array('code' => 'ZWL', 'symbol' => '$', 'name' => 'Zimbabwe Dollar')
            );
            $currency = apply_filters('fat-sb-currency', $currency);
            return $currency;
        }

        public static function getPhoneCountry()
        {
            return array(
                'Afghanistan,+93,af',
                'Albania,+355,al',
                'Algeria,+213,dz',
                'American Samoa,+1,as',
                'Andorra,+376,ad',
                'Angola,+244,ao',
                'Anguilla,+1,ai',
                'Antigua and Barbuda,+1,ag',
                'Argentina,+54,ar',
                'Armenia,+374,am',
                'Aruba,+297,aw',
                'Australia,+61,au',
                'Austria,+43,at',
                'Azerbaijan,+994,az',
                'Bahamas,+1,bs',
                'Bahrain,+973,bh',
                'Bangladesh,+880,bd',
                'Barbados,+1,bb',
                'Belarus,+375,by',
                'Belgium,+32,be',
                'Belize,+501,bz',
                'Benin,+229,bj',
                'Bermuda,+1,bm',
                'Bhutan,+975,bt',
                'Bolivia,+591,bo',
                'Bosnia and Herzegovina,+387,ba',
                'Botswana,+267,bw',
                'Brazil,+55,br',
                'Brunei,+673,bn',
                'Bulgaria,+359,bg',
                'Burkina Faso,+226,bf',
                'Burundi,+257,bi',
                'Cambodia,+855,kh',
                'Cameroon,+237,cm',
                'Canada,+1,ca',
                'Cape Verde,+238,cv',
                'Cayman Islands,+1345,ky',
                'Central Africa,+236,cf',
                'Chad,+235,td',
                'Chile,+56,cl',
                'China,+86,cn',
                'Colombia,+57,co',
                'Comoros,+269,km',
                'Congo,+242,cd',
                'Cook Islands,+682,ck',
                'Costa Rica,+506,cr',
                'Croatia,+385,hr',
                'Cuba,+53,cu',
                'Cyprus,+357,cy',
                'Czech Republic,+420,cz',
                'Denmark,+45,dk',
                'Djibouti,+253,dj',
                'Dominica,+1767,dm',
                'Dominican Republic,+1829,do',
                'DR Congo,+243,cd',
                'East Timor,+670,et',
                'Ecuador,+593,ec',
                'Egypt,+20,eg',
                'El Salvador,+503,sv',
                'Equatorial Guinea,+240,gq',
                'Eritrea,+291,er',
                'Estonia,+372,ee',
                'Ethiopia,+251,et',
                'Falkland Islands,+500,fk',
                'Faroe Islands,+298,fo',
                'Fiji,+679,fj',
                'Finland,+358,fi',
                'France,+33,fr',
                'French Guiana,+594,gf',
                'French Polynesia,+689,pf',
                'Gabon,+241,ga',
                'Gambia,+220,gm',
                'Georgia,+995,ge',
                'Germany,+49,de',
                'Ghana,+233,gh',
                'Gibraltar,+350,gi',
                'Greece,+30,gr',
                'Greenland,+299,gl',
                'Grenada,+1473,gd',
                'Guadeloupe,+590,gp',
                'Guam,+1671,gu',
                'Guatemala,+502,gt',
                'Guinea,+224,gn',
                'Guinea-Bissau,+245,gw',
                'Guyana,+592,gy',
                'Haiti,+509,ht',
                'Honduras,+504,hn',
                'Hong Kong,+852,hk',
                'Hungary,+36,hu',
                'Iceland,+354,is',
                'India,+91,in',
                'Indonesia,+62,id',
                'Iran,+98,ir',
                'Iraq,+964,iq',
                'Ireland,+353,ie',
                'Israel,+972,il',
                'Italy,+39,it',
                'Jamaica,+1876,jm',
                'Japan,+81,jp',
                'Jordan,+962,jo',
                'Kazakhstan,+7,kz',
                'Kenya,+254,ke',
                'Kiribati,+686,ki',
                'Korea Republic of,+82,kr',
                'Kosovo,+383,',
                'Kuwait,+965,kw',
                'Kyrgyzstan,+996,kg',
                'Laos PDR,+856,la',
                'Latvia,+371,lv',
                'Lebanon,+961,lb',
                'Lesotho,+266,ls',
                'Liberia,+231,lr',
                'Libya,+218,ly',
                'Liechtenstein,+423,li',
                'Lithuania,+370,lt',
                'Luxembourg,+352,lu',
                'Macau,+853,mo',
                'Macedonia,+389,mk',
                'Madagascar,+261,mg',
                'Malawi,+265,mw',
                'Malaysia,+60,my',
                'Maldives,+960,mv',
                'Mali,+223,ml',
                'Malta,+356,mt',
                'Marshall Islands,+692,mh',
                'Martinique,+596,mq',
                'Mauritania,+222,mr',
                'Mauritius,+230,mu',
                'Mexico,+52,mx',
                'Micronesia,+691,fm',
                'Moldova,+373,md',
                'Monaco,+377,mc',
                'Mongolia,+976,mn',
                'Montenegro,+382,me',
                'Montserrat,+1664,ms',
                'Morocco,+212,ma',
                'Mozambique,+258,mz',
                'Myanmar,+95,',
                'Namibia,+264,na',
                'Nepal,+977,np',
                'Netherlands,+31,nl',
                'Netherlands Antilles,+599,an',
                'New Caledonia,+687,nc',
                'New Zealand,+64,nz',
                'Nicaragua,+505,ni',
                'Niger,+227,ne',
                'Nigeria,+234,ng',
                'Niue,+683,nu',
                'Norfolk Island,+672,nf',
                'Norway,+47,no',
                'Oman,+968,om',
                'Pakistan,+92,pk',
                'Palau,+680,pw',
                'Palestinian Territory,+970,ps',
                'Panama,+507,pa',
                'Paraguay,+595,py',
                'Peru,+51,pe',
                'Philippines,+63,ph',
                'Poland,+48,pl',
                'Portugal,+351,pt',
                'Puerto Rico,+1,pr',
                'Qatar,+974,qa',
                'Reunion/Mayotte,+262,re',
                'Romania,+40,ro',
                'Russia,+7,ru',
                'Rwanda,+250,rw',
                'Samoa,+685,ws',
                'San Marino,+378,sm',
                'Saudi Arabia,+966,sa',
                'Senegal,+221,sn',
                'Serbia,+381,cs',
                'Seychelles,+248,sc',
                'Sierra Leone,+232,sl',
                'Singapore,+65,sg',
                'Slovakia,+421,sk',
                'Slovenia,+386,si',
                'Solomon Islands,+677,sb',
                'Somalia,+252,so',
                'South Africa,+27,za',
                'Spain,+34,es',
                'Sri Lanka,+94,lk',
                'St Kitts and Nevis,+1869,kn',
                'St Lucia,+1758,lc',
                'St Pierre and Miquelon,+508,pm',
                'St Vincent Grenadines,+1784,vc',
                'Sudan,+249,sd',
                'Suriname,+597,sr',
                'Swaziland,+268,sz',
                'Sweden,+46,se',
                'Switzerland,+41,ch',
                'Syria,+963,sy',
                'Taiwan,+886,tw',
                'Tajikistan,+992,tj',
                'Tanzania,+255,tz',
                'Thailand,+66,th',
                'Togo,+228,tg',
                'Tonga,+676,to',
                'Trinidad and Tobago,+1868,tt',
                'Tunisia,+216,tn',
                'Turkey,+90,tr',
                'Turkmenistan,+993,tm',
                'Uganda,+256,ug',
                'Ukraine,+380,ua',
                'United Arab Emirates,+971,ae',
                'United Kingdom,+44,uk',
                'United States,+1,us',
                'Uruguay,+598,uy',
                'Uzbekistan,+998,uz',
                'Vanuatu,+678,vu',
                'Venezuela,+58,ve',
                'Vietnam,+84,vn',
                'Virgin Islands - British,+1284,um',
                'Virgin Islands - U.S,+1340,vi',
                'Yemen,+967,ye',
                'Zambia,+260,zm',
                'Zimbabwe,+263,zw'
            );
        }

        public static function get_user_info(){
            $user = wp_get_current_user();
            $user_id = isset($user->ID) ? $user->ID: 0;
            $is_admin = in_array('administrator', $user->roles);
            $e_id = get_user_meta($user_id,'apoint_e_id', true);
            $user_info = array(
                'user_id' => $user_id,
                'e_id' => $e_id ? $e_id : 0,
                'is_admin' =>  in_array('administrator', $user->roles)
            );
            return apply_filters('apoint_user_info', $user_info);
        }

        public static function getDateDiffInDays($start, $end){
            $diff = strtotime($end) - strtotime($start);
            return abs(round($diff / 86400));
        }

        public static function validate_update($is_valid, $object_type){
            $use_info = Revy_Utils::get_user_info();
            if($use_info['is_admin']==0){
                global $wpdb;
                $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
                if($object_type=='booking'){
                    $b_id = isset($data['b_id']) && $data['b_id'] ? $data['b_id'] : 0;
                    if($b_id){
                        $sql = "SELECT b_id FROM {$wpdb->prefix}rp_booking WHERE b_id = %d AND b_employee_id = %d";
                        $sql = $wpdb->prepare($sql, $b_id, $use_info['e_id']);
                        $booking = $wpdb->get_results($sql);
                        if(!is_countable($sql) || count($booking)==0){
                            return array(
                                'result' => -1,
                                'message' => esc_html__('You cannot delete or update records assigned for others', 'revy')
                            );
                        }
                    }
                }
            }

            return $is_valid;
        }

        public static function image_resize($images_id, $width = NULL, $height = NULL, $crop = true, $retina = false)
        {
            $output = '';
            if(class_exists( 'Jetpack' )&& method_exists( 'Jetpack', 'get_active_modules' ) && in_array( 'photon', Jetpack::get_active_modules() ) ){
                $url = wp_get_attachment_image_src($images_id, 'full');
                $url = isset($url[0]) ? $url[0] : '';
                $args_crop = array(
                    'resize'   => $width.','.$height,
                    'crop'   => '0,0,'.$width.'px,'.$height.'px'
                );
                $url = jetpack_photon_url( $url, $args_crop );
                return array(
                    'url' => $url,
                    'width' => $width,
                    'height' => $height
                );
            }else{
                global $wpdb;

                $url =  wp_get_attachment_image_src($images_id, 'full');
                $url = isset($url[0]) ? $url[0] : '';

                /*if (empty($url))
                    return new WP_Error('no_image_url', esc_html__('No image URL has been entered.', 'cronus'), $url);*/

                // Get default size from database
                $width = ($width) ? $width : get_option('thumbnail_size_w');
                $height = ($height) ? $height : get_option('thumbnail_size_h');

                // Allow for different retina sizes
                $retina = $retina ? ($retina === true ? 2 : $retina) : 1;

                // Get the image file path
                $file_path = get_attached_file($images_id);

                // Destination width and height variables
                $dest_width = $width * $retina;
                $dest_height = $height * $retina;

                // File name suffix (appended to original file name)
                $suffix = "{$dest_width}x{$dest_height}";


                $info = pathinfo($file_path);
                if(isset($info['dirname'])){
                    $dir = $info['dirname'];
                }else{
                    return new WP_Error('not exists dirname', esc_html__('Undefined dirname.', 'cronus'), $url);
                }
                if(isset($info['extension'])){
                    $ext = $info['extension'];
                }else{
                    return new WP_Error('not exists extension', esc_html__('Undefined extension.', 'cronus'), $url);
                }
                $name = wp_basename($file_path, ".$ext");

                if ('bmp' == $ext) {
                    return new WP_Error('bmp_mime_type', esc_html__('Image is BMP. Please use either JPG or PNG.', 'cronus'), $url);
                }

                // Suffix applied to filename
                $suffix = "{$dest_width}x{$dest_height}";

                // Get the destination file name
                $dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";
                if (!file_exists($dest_file_name)) {

                    // Load WordPress Image Editor
                    $editor = wp_get_image_editor($file_path);
                    if (is_wp_error($editor)){
                        return array('url' => $url, 'width' => $width, 'height' => $height);
                    }

                    // Get the original image size
                    $size = $editor->get_size();
                    $orig_width = $size['width'];
                    $orig_height = $size['height'];

                    $src_x = $src_y = 0;
                    $src_w = $orig_width;
                    $src_h = $orig_height;

                    if ($crop) {

                        $cmp_x = $orig_width / $dest_width;
                        $cmp_y = $orig_height / $dest_height;

                        // Calculate x or y coordinate, and width or height of source
                        if ($cmp_x > $cmp_y) {
                            $src_w = round($orig_width / $cmp_x * $cmp_y);
                            $src_x = round(($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2);
                        } else if ($cmp_y > $cmp_x) {
                            $src_h = round($orig_height / $cmp_y * $cmp_x);
                            $src_y = round(($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2);
                        }

                    }

                    // Time to crop the image!
                    $editor->crop($src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height);

                    // Now let's save the image
                    $saved = $editor->save($dest_file_name);

                    // Get resized image information
                    $resized_url = str_replace(wp_basename($url), wp_basename($saved['path']), $url);
                    $resized_width = $saved['width'];
                    $resized_height = $saved['height'];
                    $resized_type = $saved['mime-type'];

                    // Add the resized dimensions to original image metadata (so we can delete our resized images when the original image is delete from the Media Library)
                    $metadata = wp_get_attachment_metadata($images_id);
                    if (isset($metadata['image_meta'])) {
                        $metadata['image_meta']['resized_images'][] = $resized_width . 'x' . $resized_height;
                        wp_update_attachment_metadata($images_id, $metadata);
                    }

                    // Create the image array
                    $image_array = array(
                        'url' => $resized_url,
                        'width' => $resized_width,
                        'height' => $resized_height,
                        'type' => $resized_type
                    );

                } else {
                    $image_array = array(
                        'url' => str_replace(wp_basename($url), wp_basename($dest_file_name), $url),
                        'width' => $dest_width,
                        'height' => $dest_height,
                        'type' => $ext
                    );
                }
                // Return image array
                return !is_wp_error($image_array) ? $image_array : array();
            }
        }
    }
}