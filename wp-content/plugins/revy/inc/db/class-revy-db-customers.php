<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/7/2019
 * Time: 9:10 AM
 */
if (!class_exists('Revy_DB_Customers')) {
    class Revy_DB_Customers
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_customers()
        {
            global $wpdb;
            $order = isset($_REQUEST['order']) && $_REQUEST['order'] ? $_REQUEST['order'] : 'ASC';
            $order_by = isset($_REQUEST['order_by']) && $_REQUEST['order_by'] ? $_REQUEST['order_by'] : 'c_first_name';
            $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            $sql = "SELECT c_id, c_first_name, c_last_name, c_email, c_phone_code, c_phone, c_description, c_dob, c_code
                                        FROM {$wpdb->prefix}rp_customers WHERE 1=%d ";

            if (isset($_REQUEST['c_name']) && $_REQUEST['c_name']) {
                $sql .= " AND c_first_name LIKE '%{$_REQUEST['c_name']}%' OR c_last_name LIKE '%{$_REQUEST['c_name']}%'  OR c_email LIKE '%{$_REQUEST['c_name']}%'";
            }
            $sql .= " ORDER BY {$order_by} {$order}";
            $sql = $wpdb->prepare($sql, 1);
            $customers = $wpdb->get_results($sql);
            $total = count($customers);

            $db_setting = Revy_DB_Setting::instance();
            $setting =  $db_setting->get_setting();

            $item_per_page = isset($setting['item_per_page']) ? $setting['item_per_page'] : 10;
            $number_of_page = $total / $item_per_page + ($total % $item_per_page > 0 ? 1 : 0);
            $page = $page > $number_of_page ? $number_of_page : $page;
            $page = ($page - 1) * $item_per_page;
            $customers = array_slice($customers, $page, $item_per_page);
            foreach($customers as $cus){
                $cus->c_phone_code = explode(',',$cus->c_phone_code)[0];
            }

            return array(
                'total' => $total,
                'customers' => $customers
            );
        }

        public function get_customers_dic()
        {
            global $wpdb;
            $sql = "SELECT c_id, c_first_name, c_last_name, c_email, c_phone, c_description, c_dob
                                        FROM {$wpdb->prefix}rp_customers";
            $customers = $wpdb->get_results($sql);
            return $customers;
        }

        public function save_customer()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                //$date_format = get_option('date_format');
                $data['c_dob'] = isset($data['c_dob']) && $data['c_dob'] ?  $data['c_dob'] : '';
                $c_id = isset($data['c_id']) && $data['c_id'] != '' ? $data['c_id'] : 0;
                global $wpdb;

                $sql = "SELECT c_id
                                        FROM {$wpdb->prefix}rp_customers
                                        WHERE c_id <> %d AND c_email=%s";
                $sql = $wpdb->prepare($sql, $c_id, $data['c_email']);
                $is_exist_mail = $wpdb->get_results($sql);

                if (count($is_exist_mail) > 0) {
                    return array(
                        'result' => -2,
                        'message' => esc_html__('This email has been used for another customer. Please use another email', 'revy')
                    );
                }

                if ($c_id > 0) {
                    $result = $wpdb->update($wpdb->prefix . 'rp_customers', $data, array('c_id' => $data['c_id']));
                } else {
                    $data['c_code'] = uniqid('apoint_');
                    $data['c_create_date'] = current_time( 'mysql', 0);
                    $result = $wpdb->insert($wpdb->prefix . 'rp_customers', $data);
                    $result = $result > 0 ? $wpdb->insert_id : $result;
                }
                return array(
                    'result' => $result,
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input data for field', 'revy')
                );
            }
        }

        public function get_customer_by_id()
        {
            $c_id = isset($_REQUEST['c_id']) ? $_REQUEST['c_id'] : 0;
            global $wpdb;
            if ($c_id) {
                $sql = "SELECT c_id, c_first_name, c_last_name, c_gender, c_phone_code, c_phone, c_email, c_user_id, c_dob, c_description
                                        FROM {$wpdb->prefix}rp_customers 
                                        WHERE c_id=%d";
                $sql = $wpdb->prepare($sql, $c_id);
                $customer = $wpdb->get_results($sql);
                if (count($customer) > 0) {
                    $customer = $customer[0];
                    $now = new DateTime();
                    $now = $now->format('YYYY-m-d');
                    $customer->c_dob = $customer->c_dob && $customer->c_dob!='0000-00-00' ? $customer->c_dob : $now;
                } else {
                    $customer = array(
                        'c_id' => 0,
                        'c_first_name' => '',
                        'c_last_name' => '',
                    );
                }
            } else {
                $customer = array(
                    'c_id' => 0,
                    'c_first_name' => '',
                    'c_last_name' => '',
                );
            }
            return $customer;
        }

        public function delete_customer()
        {
            $c_ids = isset($_REQUEST['c_ids']) ? $_REQUEST['c_ids'] : '';
            if ($c_ids) {
                global $wpdb;
                $number_c_detele = count($c_ids);
                $c_ids = implode(',', $c_ids);

                $sql = "SELECT b_customer_id
                                        FROM {$wpdb->prefix}rp_booking 
                                        WHERE 1=%d AND b_customer_id IN ({$c_ids})";
                $sql = $wpdb->prepare($sql, 1);
                $c_ids_booking = $wpdb->get_results($sql);

                if (count($c_ids_booking) == $number_c_detele) {
                    return array(
                        'result' => -1,
                        'message_error' => esc_html__('You cannot delete customer(s) because exist order for this customer(s)', 'revy')
                    );
                } else {
                    $c_ids = explode(',', $c_ids);
                    foreach ($c_ids_booking as $c_id) {
                        if(in_array($c_id, $c_ids)){
                            unset($c_ids[$c_id]);
                        }
                    }
                    $c_ids = implode(',', $c_ids);

                    $sql = "DELETE FROM {$wpdb->prefix}rp_customers WHERE 1=%d AND c_id IN ({$c_ids}) ";
                    $sql = $wpdb->prepare($sql, 1);
                    $result = $wpdb->query($sql);
                    return array(
                        'result' => $result,
                        'ids_delete' => $c_ids,
                        'message_success' => $result > 0 ? $result . esc_html__(' customer(s) have been deleted', 'revy') : '',
                        'message_error' => ($result < $number_c_detele && ($number_c_detele - $result) > 0) ? ($number_c_detele - $result) . esc_html__(' customer(s) can not delete because exist order for this customer(s)', 'revy') : ''
                    );
                }
            } else {
                return array(
                    'result' => 1,
                );
            }
        }

        public function get_customer_code(){
            $c_email = isset($_REQUEST['c_email']) ? $_REQUEST['c_email'] : '';
            if($c_email){
                global $wpdb;
                $sql = "SELECT c_id, c_code, c_first_name, c_last_name FROM {$wpdb->prefix}rp_customers WHERE c_email=%s";
                $sql = $wpdb->prepare($sql, $c_email);
                $customer = $wpdb->get_results($sql);
                if (count($customer) > 0 && (isset($customer[0]->c_code) || is_null($customer[0]->c_code) || $customer[0]->c_code=='') ) {
                    $c_code = $customer[0]->c_code;
                    try{
                        $setting_db = Revy_DB_Setting::instance();
                        $setting = $setting_db->get_setting();

                        $email_template = $setting_db->get_email_template();
                        $subject = esc_html__('Request customer code','revy');
                        $message = wp_kses_post("<p>Dear {customer_first_name} {customer_last_name}  </p> <p>Please use this code : {customer_code} to view booking history  </p> <p>Thank you</p>");
                        for($i=0; $i < count($email_template); $i++){
                            if($email_template[$i]['template']=='get_customer_code' && $email_template[$i]['customer_code_subject']!='' && $email_template[$i]['customer_code_message']!=''){
                                $subject = $email_template[$i]['customer_code_subject'];
                                $message = $email_template[$i]['customer_code_message'];
                            }
                        }
                        $message = str_replace('{customer_code}', $c_code, $message);
                        $message = str_replace('{customer_first_name}',$customer[0]->c_first_name, $message);
                        $message = str_replace('{customer_last_name}',$customer[0]->c_last_name, $message);
                        Revy_Utils::sendMail(array(
                            'mailer' => $setting['mailer'],
                            'smtp_host' => $setting['smtp_host'],
                            'smtp_port' => $setting['smtp_port'],
                            'smtp_username' => $setting['smtp_username'],
                            'smtp_password' => $setting['smtp_password'],
                            'encryption' => $setting['smtp_encryption'],
                            'from_name' => $setting['send_from_name'],
                            'from_name_label' => isset($setting['send_from_name_label']) ? $setting['send_from_name_label'] : $setting['send_from_name'],
                            'send_to' => $c_email,
                            'cc_email' => $setting['cc_to'],
                            'bcc_email' => $setting['bcc_to'],
                            'subject' => $subject,
                            'message' => $message
                        ));

                        return array(
                            'result' => 1,
                            'message' => sprintf(esc_html__('Customer code has been send to %s. Please check your mailbox', 'revy'), $c_email)
                        );
                    }catch(Exception $e){
                        return array(
                            'result' => -1,
                            'message' => esc_html__('An error occurred while sending mail','revy')
                        );
                    }

                }else{
                    return array(
                        'result' => -1,
                        'message' => esc_html__('This email does not exist with us. Please use the email ID that you used for booking','revy')
                    );
                }
            }else{
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input email to get code','revy')
                );
            }
        }

    }
}