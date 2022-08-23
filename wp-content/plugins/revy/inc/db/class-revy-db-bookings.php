<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 6/19/2020
 * Time: 10:38 AM
 */

if (!class_exists('Revy_DB_Bookings')) {
    class Revy_DB_Bookings
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_insight()
        {
            global $wpdb;
            $start_date = isset($_REQUEST['start_date']) && $_REQUEST['start_date'] ? $_REQUEST['start_date'] : '';
            $end_date = isset($_REQUEST['end_date']) && $_REQUEST['end_date'] ? $_REQUEST['end_date'] : '';
            $b_garage = isset($_REQUEST['garage']) && $_REQUEST['garage'] ? $_REQUEST['garage'] : '';

            if ($start_date == '') {
                $now = new DateTime();
                $start_date = $now->format('Y-m-d');
            }
            if ($end_date == '') {
                $now = new DateTime();
                $end_date = $now->modify('+6 day')->format('Y-m-d');
            }

            $sql = "SELECT b_id, b_date, b_time, b_gateway_status, b_gateway_type, b_total_pay, b_process_status, b_pay_now, c_create_date
                                        FROM {$wpdb->prefix}rp_booking 
                                        LEFT JOIN {$wpdb->prefix}rp_customers 
                                        ON b_customer_id = c_id
                                        WHERE b_process_status !=-1 AND DATE(b_date) BETWEEN %s AND %s";

            if ($b_garage && is_array($b_garage)) {
                $b_garage = implode(',', $b_garage);
                $sql .= " AND b_garage_id IN ({$b_garage})";
            }


            $sql .= " ORDER BY b_date";
            $sql = $wpdb->prepare($sql, $start_date, $end_date);
            $bookings = $wpdb->get_results($sql);
            $result = array(
                'revenue' => array(),
                'service_emp_chart' => array(
                    'employees' => array(),
                    'services' => array(),
                    'categories' => array()
                ),
                'new_customer' => 0,
                'return_customer' => 0,
                'booking_approved' => 0,
                'booking_pending' => 0,
                'booking_rejected' => 0,
                'booking_canceled' => 0,
                'total_revenue' => 0
            );

            $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $start_date . ' 00:00:00');
            $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $end_date . ' 23:59:59');

            $revenue = array();
            $services = array();
            $b_ids = array();
            foreach ($bookings as $b) {
                $b_ids[] = $b->b_id;
                if ($b->b_gateway_status == 1 || $b->b_pay_now==1) {
                    $result['total_revenue'] += $b->b_total_pay;

                    if (array_key_exists($b->b_date, $revenue)) {
                        $revenue[$b->b_date] += (float)$b->b_total_pay;
                    } else {
                        $revenue[$b->b_date] = (float)$b->b_total_pay;
                    }
                }

                if ($start_date <= $b->c_create_date && $b->c_create_date <= $start_date) {
                    $result['new_customer'] += 1;
                } else {
                    $result['return_customer'] += 1;
                }

                if ($b->b_process_status == 0) {
                    $result['booking_pending'] += 1;
                }

                if ($b->b_process_status == 1) {
                    $result['booking_approved'] += 1;
                }

                if ($b->b_process_status == 2) {
                    $result['booking_canceled'] += 1;
                }

                if ($b->b_process_status == 3) {
                    $result['booking_rejected'] += 1;
                }

            }

            if(count($b_ids) > 0){
                $b_ids = implode(',', $b_ids);
                $sql = "SELECT RB.b_date, RDB.b_service_id 
                    FROM {$wpdb->prefix}rp_booking AS RB  
                    LEFT JOIN {$wpdb->prefix}rp_booking_detail AS RDB
                    ON RB.b_id = RDB.b_id
                    WHERE RB.b_id IN ({$b_ids})";
                $booking_detail = $wpdb->get_results($sql);
                foreach ($booking_detail as $bd) {
                    if (!isset($services[$bd->b_date]) || !in_array($bd->b_service_id, $services[$bd->b_date])) {
                        $services[$bd->b_date][] = $bd->b_service_id;
                    }
                }
            }

            $diff_day = $end_date->diff($start_date)->days;
            $date = '';
            for ($i = 0; $i <= $diff_day; $i++) {
                $start_date = $i == 0 ? $start_date : $start_date->modify('+1 days');
                $date = $start_date->format('Y-m-d');
                if (!isset($revenue[$date])) {
                    $result['revenue'][] = 0;
                } else {
                    $result['revenue'][] = $revenue[$date];
                }

                $result['service_emp_chart']['services'][] = is_array($services) && isset($services[$date]) ? count($services[$date]) : 0;
                $result['service_emp_chart']['categories'][] = $date;
            }
            return $result;
        }

        public function get_booking()
        {
            global $wpdb;
            $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            $b_customer_name = isset($_REQUEST['b_customer_name']) && $_REQUEST['b_customer_name'] ? $_REQUEST['b_customer_name'] : '';
            $start_date = isset($_REQUEST['start_date']) && $_REQUEST['start_date'] ? $_REQUEST['start_date'] : '';
            $start_time = isset($_REQUEST['start_time']) && $_REQUEST['start_time'] ? $_REQUEST['start_time'] : '00:00';
            $end_date = isset($_REQUEST['end_date']) && $_REQUEST['end_date'] ? $_REQUEST['end_date'] : '';
            $end_time = isset($_REQUEST['end_time']) && $_REQUEST['end_time'] ? $_REQUEST['end_time'] : '23:59';
            $b_customer = isset($_REQUEST['b_customer']) && $_REQUEST['b_customer'] ? $_REQUEST['b_customer'] : '';
            $b_service = isset($_REQUEST['b_service']) && $_REQUEST['b_service'] ? $_REQUEST['b_service'] : '';
            $b_process_status = isset($_REQUEST['b_process_status']) ? $_REQUEST['b_process_status'] : '';
            $order = isset($_REQUEST['order']) && $_REQUEST['order'] ? $_REQUEST['order'] : 'DESC';
            $order_by = isset($_REQUEST['order_by']) && $_REQUEST['order_by'] ? $_REQUEST['order_by'] : 'b_date';
            $b_garage = isset($_REQUEST['garage']) && $_REQUEST['garage'] ? $_REQUEST['garage'] : '';
            $b_delivery_method = isset($_REQUEST['b_delivery_method']) && $_REQUEST['b_delivery_method'] ? $_REQUEST['b_delivery_method'] : '';

            $sql = "SELECT b_date, b_time, b_id, b_customer_id, c_first_name, c_last_name, c_email, c_phone_code, c_phone, rm_name, b_gateway_type, b_gateway_status, b_total_pay, b_process_status, b_create_date, b_delivery_method
                    FROM {$wpdb->prefix}rp_booking
                    LEFT JOIN {$wpdb->prefix}rp_customers ON b_customer_id = c_id
                    LEFT JOIN {$wpdb->prefix}rp_models ON b_model_id = rm_id
                    WHERE  b_process_status!=%d ";
            if ($b_customer_name) {
                $sql .= " AND (c_first_name LIKE '%{$b_customer_name}%' OR c_last_name LIKE '%{$b_customer_name}%' OR c_email LIKE '%{$b_customer_name}%') ";
            }

            if ($b_customer && is_array($b_customer)) {
                $b_customer = implode(',', $b_customer);
                $sql .= " AND b_customer_id IN ({$b_customer})";
            }

            if ($b_service && is_array($b_service)) {
                $b_service = implode(',', $b_service);
                $sql .= " AND b_id IN (SELECT b_id FROM {$wpdb->prefix}rp_booking_detail WHERE b_service_id IN ({$b_service}) )";
            }

            if ($b_garage && is_array($b_garage)) {
                $b_garage = implode(',', $b_garage);
                $sql .= " AND b_garage_id IN ({$b_garage})";
            }

            if ($b_delivery_method != '') {
                $sql .= " AND b_delivery_method = '{$b_delivery_method}'";
            }

            if ($b_process_status != '') {
                $sql .= " AND b_process_status = {$b_process_status}";
            }

            if ($start_date && $end_date) {
                $sql .= " AND DATE(b_date) BETWEEN '{$start_date}' AND '{$end_date}'";
            }

            $use_info = Revy_Utils::get_user_info();

            $sql .= " ORDER BY {$order_by} {$order}";
            $sql = $wpdb->prepare($sql, -1);
            $main_sql = $sql;
            $bookings = $wpdb->get_results($sql);
            $hours = Revy_Utils::getDurations(1, 'duration_step');

            $total_cancel = 0;
            $total_pending = 0;
            $total_reject = 0;
            $total_approved = 0;

            $b_date = '';
            $now = current_time('mysql', 0);
            $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);

            $start_date_time = DateTime::createFromFormat('Y-m-d H:i', $start_date . ' ' . $start_time);
            $end_date_time = DateTime::createFromFormat('Y-m-d H:i', $end_date . ' ' . $end_time);
            $bookings_filter = array();

            $delivery_method = array(
                1 => esc_html__('Fixit Home','revy'),
                2 => esc_html__('Carry In','revy'),
                3 => esc_html__('Mail In','revy')
            );
            foreach ($bookings as $booking) {
                $booking->c_phone_code = explode(',', $booking->c_phone_code)[0];
                $booking->b_delivery_method_label = $delivery_method[$booking->b_delivery_method];
                if ($booking->b_process_status == 0) {
                    $total_pending++;
                }
                if ($booking->b_process_status == 1) {
                    $total_approved++;
                }
                if ($booking->b_process_status == 2) {
                    $total_cancel++;
                }
                if ($booking->b_process_status == 3) {
                    $total_reject++;
                }

                $booking->editable = 1;
                /*if($booking->b_delivery_method == 2){ //Carry In
                    $booking->editable = 0;
                    $b_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking->b_date . ' 00:00:00');
                    $b_date->modify("+{$booking->b_time} minutes");
                    if ($b_date >= $start_date_time && $b_date <= $end_date_time) {
                        $booking->editable = $b_date > $now ? 1 : 0;
                    }
                }*/
                $bookings_filter[] = $booking;
            }

            $bookings = $bookings_filter;
            $total = count($bookings);

            $db_setting = Revy_DB_Setting::instance();
            $setting = $db_setting->get_setting();

            $item_per_page = isset($setting['item_per_page']) ? $setting['item_per_page'] : 10;
            $number_of_page = $total / $item_per_page + ($total % $item_per_page > 0 ? 1 : 0);
            $page = $page > $number_of_page ? $number_of_page : $page;
            $page = ($page - 1) * $item_per_page;
            $bookings = array_slice($bookings, $page, $item_per_page);

            $booking_detail = array();
            if (count($bookings) > 0) {
                $b_ids = array();
                foreach ($bookings as $bk) {
                    $b_ids[] = $bk->b_id;
                }
                $b_ids = implode(',', $b_ids);
                $sql = "SELECT RDB.b_id, RDB.b_service_id, S.s_name, RDB.b_service_duration, RDB.b_attr_title, RDB.b_attr_value, RDB.b_price, RDB.b_service_tax_amount  
                    FROM {$wpdb->prefix}rp_booking_detail AS RDB
                    LEFT JOIN {$wpdb->prefix}rp_services AS S
                    ON RDB.b_service_id = S.s_id
                    WHERE RDB.b_id IN ({$b_ids})";
                $booking_detail = $wpdb->get_results($sql);
            }

            return array(
                'total' => $total,
                'bookings' => $bookings,
                'booking_detail' => $booking_detail,
                'total_cancel' => $total_cancel,
                'total_approved' => $total_approved,
                'total_pending' => $total_pending,
                'total_reject' => $total_reject
            );
        }

        public function get_booking_export()
        {
            global $wpdb;
            $b_customer_name = isset($_REQUEST['b_customer_name']) && $_REQUEST['b_customer_name'] ? $_REQUEST['b_customer_name'] : '';
            $start_date = isset($_REQUEST['start_date']) && $_REQUEST['start_date'] ? $_REQUEST['start_date'] : '';
            $start_time = isset($_REQUEST['start_time']) && $_REQUEST['start_time'] ? $_REQUEST['start_time'] : '00:00';
            $end_date = isset($_REQUEST['end_date']) && $_REQUEST['end_date'] ? $_REQUEST['end_date'] : '';
            $end_time = isset($_REQUEST['end_time']) && $_REQUEST['end_time'] ? $_REQUEST['end_time'] : '23:59';
            $b_customer = isset($_REQUEST['b_customer']) && $_REQUEST['b_customer'] ? $_REQUEST['b_customer'] : '';
            $b_service = isset($_REQUEST['b_service']) && $_REQUEST['b_service'] ? $_REQUEST['b_service'] : '';
            $b_process_status = isset($_REQUEST['b_process_status']) ? $_REQUEST['b_process_status'] : '';
            $b_garage = isset($_REQUEST['garage']) && $_REQUEST['garage'] ? $_REQUEST['garage'] : '';
            $b_delivery_method = isset($_REQUEST['b_delivery_method']) && $_REQUEST['b_delivery_method'] ? $_REQUEST['b_delivery_method'] : '';

            $sql = "SELECT b_date, b_time, b_id, rm_name, G.rg_name, G.rg_address, b_customer_address, b_customer_city, b_customer_country, b_customer_postal_code, 
                            b_customer_id, c_first_name, c_last_name, c_email, c_phone, b_gateway_type, b_gateway_status, b_total_pay, b_process_status
                    FROM {$wpdb->prefix}rp_booking AS B
                    LEFT JOIN {$wpdb->prefix}rp_garages AS G
                    ON B.b_garage_id = G.rg_id
                    LEFT JOIN {$wpdb->prefix}rp_models AS M
                    ON B.b_model_id = M.rm_id
                    LEFT JOIN {$wpdb->prefix}rp_customers AS C 
                    ON B.b_customer_id = C.c_id
                    WHERE  b_process_status!=%d ";
            if ($b_customer_name) {
                $sql .= " AND (c_first_name LIKE '%{$b_customer_name}%' OR c_last_name LIKE '%{$b_customer_name}%' OR c_email LIKE '%{$b_customer_name}%') ";
            }
            if ($b_customer && is_array($b_customer)) {
                $b_customer = implode(',', $b_customer);
                $sql .= " AND b_customer_id IN ({$b_customer})";
            }
            if ($b_service && is_array($b_service)) {
                $b_service = implode(',', $b_service);
                $sql .= " AND b_service_id IN ({$b_service})";
            }
            if ($b_garage && is_array($b_garage)) {
                $b_garage = implode(',', $b_garage);
                $sql .= " AND b_garage_id IN ({$b_garage})";
            }
            if ($b_process_status != '') {
                $sql .= " AND b_process_status = {$b_process_status}";
            }

            if ($start_date && $end_date) {
                $sql .= " AND DATE(b_date) BETWEEN '{$start_date}' AND '{$end_date}'";
            }


            $sql .= " ORDER BY b_id DESC";
            $sql = $wpdb->prepare($sql, -1);
            $bookings = $wpdb->get_results($sql);

            $b_ids = array();
            foreach ($bookings as $bk) {
                $b_ids[] = $bk->b_id;
            }

            $b_ids = implode(',', $b_ids);
            $sql = "SELECT b_id, b_service_id, S.s_name, b_attr_title, b_attr_value, b_price
                    FROM {$wpdb->prefix}rp_booking_detail AS RDB
                    LEFT JOIN {$wpdb->prefix}rp_services AS S
                    ON RDB.b_service_id = S.s_id
                    WHERE b_id IN ({$b_ids})";
            $booking_detail = $wpdb->get_results($sql);
            foreach($booking_detail as $bd){
                $services[$bd->b_id][] = $bd;
            }

            return array(
                'booking' => $bookings,
                'booking_detail' => $booking_detail
            );
        }

        public function get_booking_calendar()
        {
            global $wpdb;
            $from_date = isset($_REQUEST['from_date']) && $_REQUEST['from_date'] ? $_REQUEST['from_date'] : (new DateTime())->format('Y-m-d');
            $to_date = isset($_REQUEST['to_date']) && $_REQUEST['to_date'] ? $_REQUEST['to_date'] : (new DateTime())->format('Y-m-d');
            $b_customer = isset($_REQUEST['customer']) && $_REQUEST['customer'] ? $_REQUEST['customer'] : '';
            $b_service = isset($_REQUEST['service']) && $_REQUEST['service'] ? $_REQUEST['service'] : '';
            $b_process_status = isset($_REQUEST['b_process_status']) && $_REQUEST['b_process_status'] ? $_REQUEST['b_process_status'] : '';
            $b_garage = isset($_REQUEST['garage']) && $_REQUEST['garage'] ? $_REQUEST['garage'] : '';

            $sql = "SELECT b_date, b_id, b_customer_id, c_first_name, c_last_name, c_email, rm_name, b_date, b_time, b_process_status, rg_name, rg_address
                    FROM {$wpdb->prefix}rp_booking AS RB
                    LEFT JOIN {$wpdb->prefix}rp_models ON b_model_id = rm_id
                    LEFT JOIN {$wpdb->prefix}rp_customers ON b_customer_id = c_id
                    LEFT JOIN {$wpdb->prefix}rp_garages ON b_garage_id = rg_id
                    WHERE  b_process_status!=-1 AND DATE(b_date) BETWEEN %s AND %s ";

            if ($b_customer && is_array($b_customer)) {
                $b_customer = implode(',', $b_customer);
                $sql .= " AND b_customer_id IN ({$b_customer}) ";
            }

            if ($b_service && is_array($b_service)) {
                $b_service = implode(',', $b_service);
                $sql .= " AND b_id IN (SELECT b_id FROM {$wpdb->prefix}rp_booking_detail WHERE b_service_id IN ({$b_service}) )";
            }

            if ($b_garage && is_array($b_garage)) {
                $b_garage = implode(',', $b_garage);
                $sql .= " AND b_garage_id IN ({$b_garage})";
            }

            if ($b_process_status) {
                $sql .= " AND b_process_status = {$b_process_status}";
            }

            $use_info = Revy_Utils::get_user_info();
            if ($use_info['is_admin'] == 0) {
                $sql .= " AND b_employee_id = " . $use_info['e_id'];
            }

            $sql = $wpdb->prepare($sql, $from_date, $to_date);
            $bookings = $wpdb->get_results($sql);
            $result = array();
            $color = array(
                0 => '#fbbd08',
                1 => '#21ba45',
                2 => '#db2828',
                3 => '#b5b5b5'
            );
            $now = current_time('mysql', 0);
            $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
            $b_ids = array();
            foreach ($bookings as $booking) {
                $b_ids[] = $booking->b_id;
                $start = DateTime::createFromFormat('Y-m-d H:i:s', $booking->b_date . ' 00:00:00');
                $start->modify("+{$booking->b_time} minutes");
                $end = clone $start;
                $result[] = array(
                    'id' => $booking->b_id,
                    'title' => $booking->c_first_name . ' ' . $booking->c_last_name . ' - ' . $booking->rm_name,
                    'model_name' => $booking->rm_name,
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'color' => isset($color[$booking->b_process_status]) ? $color[$booking->b_process_status] : $color[0],
                    //'service' => $booking->s_name,
                    'customer' => $booking->c_first_name . ' ' . $booking->c_last_name,
                    'time' =>  $start->format('H:i'),
                    'garage' => $booking->rg_name,
                    'garage_address' => $booking->rg_address,
                    'b_editable' => $start > $now ? 1 : 0
                );
            }

            $booking_detail = array();
            if (count($b_ids) > 0) {
                $b_ids = implode(',', $b_ids);
                $sql = "SELECT RDB.b_id, RDB.b_service_id, S.s_name, RDB.b_service_duration, RDB.b_attr_title, RDB.b_attr_value, RDB.b_price, RDB.b_service_tax_amount  
                    FROM {$wpdb->prefix}rp_booking_detail AS RDB
                    LEFT JOIN {$wpdb->prefix}rp_services AS S
                    ON RDB.b_service_id = S.s_id
                    WHERE RDB.b_id IN ({$b_ids})";
                $booking_detail = $wpdb->get_results($sql);
            }
            return array(
                'bookings' => $result,
                'booking_detail' => $booking_detail,
                'date' => $from_date
            );
        }

        public function get_booking_by_id()
        {
            $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : 0;
            global $wpdb;
            $result['booking'] = array(
                'b_id' => 0,
                'b_quantity' => 1,
                'b_gateway_type' => 'onsite'
            );
            if ($b_id) {
                $sql = "SELECT  b_id, rg_name, c_first_name, c_last_name, b_customer_address, b_customer_city, b_customer_country, b_customer_postal_code, b_garage_id, rd_name, 
                                rb_name, rm_name, b_date, b_time, b_total_tax, b_total_amount, b_coupon_code, b_discount, b_total_pay, b_gateway_type, b_gateway_status, 
                                b_process_status, b_description, b_create_date, b_pay_now, b_send_notify, b_status_note, b_canceled_by_client, b_delivery_method
                        FROM {$wpdb->prefix}rp_booking AS RB
                        LEFT JOIN {$wpdb->prefix}rp_models AS RM
                        ON RB.b_model_id = RM.rm_id
                        LEFT JOIN {$wpdb->prefix}rp_devices AS RD
                        ON RM.rm_device_id = RD.rd_id
                        LEFT JOIN {$wpdb->prefix}rp_brands AS B
                        ON RM.rm_brand_id = B.rb_id
                        LEFT JOIN  {$wpdb->prefix}rp_customers AS C
                        ON RB.b_customer_id = C.c_id
                        LEFT JOIN  {$wpdb->prefix}rp_garages AS G
                        ON RB.b_garage_id = G.rg_id
                        WHERE b_id=%d";

                $sql = $wpdb->prepare($sql, $b_id);
                $booking = $wpdb->get_results($sql);

                $setting = Revy_DB_Setting::instance();
                if (count($booking) > 0) {
                    $sql = "SELECT  S.s_name, S.s_id,  RBD.b_service_id, RBD.b_service_duration, RBD.b_service_break_time, RBD.b_attr_title, RBD.b_attr_value, RBD.b_quantity, 
                                    RBD.b_price, RBD.b_service_tax, RBD.b_service_tax_amount
                           FROM {$wpdb->prefix}rp_booking_detail AS RBD
                           LEFT JOIN {$wpdb->prefix}rp_services AS S
                            ON RBD.b_service_id = S.s_id
                            WHERE RBD.b_id = %d";
                    $sql = $wpdb->prepare($sql, $b_id);
                    $result['booking_detail'] = $wpdb->get_results($sql);
                    foreach ($result['booking_detail'] as $bk) {
                        $bk->b_price_label = $setting->formatCurrency($bk->b_price);
                        $bk->b_tax_label = $setting->formatCurrency($bk->b_service_tax_amount);
                    }

                    $booking = $booking[0];
                    $booking->b_sub_total = $booking->b_total_amount - $booking->b_total_tax;
                    $booking->b_sub_total_label =  $setting->formatCurrency($booking->b_sub_total);
                    $booking->b_total_amount_label = $setting->formatCurrency($booking->b_total_amount);
                    $booking->b_total_pay_label = $setting->formatCurrency($booking->b_total_pay);
                    $booking->b_discount_label = $setting->formatCurrency($booking->b_discount);
                    $booking->b_total_tax_label = $setting->formatCurrency($booking->b_total_tax);
                    $result['booking'] = $booking;
                }
            }

            return $result;
        }

        public function get_time_slot_available()
        {
            $booking_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : 0;
            $s_id = isset($_REQUEST['s_id']) ? $_REQUEST['s_id'] : '';
            $loc_id = isset($_REQUEST['loc_id']) ? $_REQUEST['loc_id'] : '';
            $day_in_week = isset($_REQUEST['day_in_week']) ? $_REQUEST['day_in_week'] : '';
            $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
            $number_of_device = isset($_REQUEST['number_of_device']) ? $_REQUEST['number_of_device'] : '';
            if ($s_id && $loc_id && $day_in_week && $date && $number_of_device) {
                global $wpdb;

                //get service
                $sql = "SELECT  s_maximum_slot, s_duration, s_break_time 
                        FROM {$wpdb->prefix}rp_services 
                        WHERE s_id = %d";
                $sql = $wpdb->prepare($sql, $s_id);
                $services = $wpdb->get_results($sql);
                $s_maximum_slot = isset($services[0]) ? $services[0]->s_maximum_slot : 1;
                $s_break_time = isset($services[0]) && $services[0]->s_break_time ? $services[0]->s_break_time : 0;
                $s_duration = isset($services[0]) && $services[0]->s_duration ? $services[0]->s_duration : 0;

                //check the date is day off
                $sql = "SELECT 1 FROM {$wpdb->prefix}rp_services_day_off WHERE s_id=%d AND dof_start <= %s AND dof_end >= %s";
                $sql = $wpdb->prepare($sql, $s_id, $date, $date);
                $day_off = $wpdb->get_results($sql);
                if (is_countable($day_off) && count($day_off) > 0) {
                    return array();
                }

                //get booking in this day
                $sql = "SELECT b_garage_id, b_service_id, b_date, b_time, b_service_duration, b_service_break_time, SUM(b_quantity) AS total_book
                        FROM {$wpdb->prefix}rp_booking
                        WHERE b_date = %s AND  b_service_id=%d";
                if ($booking_id) {
                    $sql .= " AND b_id !=" . $booking_id;
                }
                $sql .= " GROUP BY b_garage_id, b_service_id, b_date, b_time, b_service_duration, b_service_break_time ";
                $sql = $wpdb->prepare($sql, $date, $s_id);
                $booking = $wpdb->get_results($sql);

                //get work hour
                $sql = "SELECT ss_work_hour_start, ss_work_hour_end FROM {$wpdb->prefix}rp_services_schedule WHERE s_id=%d AND ss_day=%s";
                $sql = $wpdb->prepare($sql, $s_id, $day_in_week);
                $work_hours = $wpdb->get_results($sql);

                $db_setting = Revy_DB_Setting::instance();
                $db_setting = $db_setting->get_setting();
                $time_step = isset($db_setting['time_step']) ? $db_setting['time_step'] : 15;
                $duration_label = Revy_Utils::getWorkHours(5);
                $steps = Revy_Utils::getWorkHours(5);

                $time_slot = [];
                $wh_start = 0;
                $wh_end = 0;
                $now = current_time('mysql', 0);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $date_is_now = $now->format('Y-m-d') == $date;
                $now_minute = (intval($now->format('G')) * 60) + intval($now->format('i'));
                $end_day_minute = 24 * 60;
                $is_free = 1;
                $seat_available = 0;
                foreach ($work_hours as $wh) {
                    $wh_start = $wh->ss_work_hour_start;
                    $wh_end = $wh->ss_work_hour_end;
                    $is_free = 1;
                    $slot_end = 0;
                    for ($slot = $wh_start; $slot < $wh_end; $slot = $slot + $time_step + $s_break_time) {
                        if (($date_is_now && $slot < $now_minute) || (($slot_end + $s_break_time) >= $end_day_minute)) {
                            break;
                        }
                        $slot_end = $slot + $s_duration;
                        $seat_available = $s_maximum_slot;
                        foreach ($booking as $bk) {
                            if ($bk->b_service_id == $s_id && $bk->b_garage_id == $loc_id && $bk->b_time == $slot && ($bk->b_time + $bk->b_service_duration) == $slot_end) {
                                $seat_available = $s_maximum_slot - $bk->total_book;
                                $is_free = $seat_available >= 1;
                            } else {
                                $is_free = $slot >= ($bk->b_time + $bk->b_service_duration + $bk->b_service_break_time) || ($slot + $s_break_time + $s_duration) <= $bk->b_time;
                            }
                            if (!$is_free) {
                                break;
                            }
                        }
                        if ($is_free) {
                            $time_slot[] = array(
                                'slot' => $slot,
                                'min_seat' => 1,
                                'max_seat' => $seat_available,
                                'label' => $steps[$slot] . (isset($steps[$slot_end]) && $s_duration ? ' - ' . $steps[$slot_end] : '')
                            );
                        }
                    }
                }

                return $time_slot;
            }
        }

        public function save_booking()
        {
            $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '';
            $b_date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
            $b_time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '';
            $pay_now = isset($_REQUEST['pay_now']) ? $_REQUEST['pay_now'] : '';
            if ($b_id && $b_date!='' && $b_time && ($pay_now==1 || $pay_now==0)) {
                global $wpdb;
                $sql = "SELECT b_id, b_garage_id, b_pay_now, b_date, b_time, b_delivery_method FROM {$wpdb->prefix}rp_booking AS RB WHERE b_id = %d";
                $sql = $wpdb->prepare($sql, $b_id);
                $booking = $wpdb->get_results($sql);
                if (!isset($booking[0])) {
                    return;
                }
                $booking = $booking[0];

                //not change
                if ($booking->b_date == $b_date && $booking->b_time == $b_time && $booking->b_pay_now == $pay_now) {
                    return array(
                        'result' => 1
                    );
                }

                // update pay now
                if ($booking->b_date == $b_date && $booking->b_time == $b_time && $booking->b_pay_now != $pay_now) {
                    $b_gateway_status = $pay_now==1 ? 1 : 0;
                    $sql = "UPDATE {$wpdb->prefix}rp_booking SET b_pay_now=%d, b_gateway_status=%d WHERE b_id = %d";
                    $sql = $wpdb->prepare($sql, $pay_now, $pay_now, $b_id);
                    $result = $wpdb->query($sql);
                    return array(
                        'result' => $result,
                    );
                }

                //process update b_date, b_time and may be pay now
                $sql = "SELECT b_service_id, b_service_duration, b_service_break_time, S.s_maximum_slot, b_quantity
                        FROM {$wpdb->prefix}rp_booking_detail AS RDB
                         LEFT JOIN {$wpdb->prefix}rp_services AS S
                         ON RDB.b_service_id = S.s_id
                         WHERE b_id = %d";
                $sql = $wpdb->prepare($sql, $b_id);
                $booking_detail = $wpdb->get_results($sql);
                $data = array(
                    'b_date' => $b_date,
                    'b_time' => $b_time,
                    'c_email' => '', //ignore check limit customer
                );

                $is_valid_limit = $this->validate_booking($data);
                if (is_array($is_valid_limit) && $is_valid_limit['result'] < 0) {
                    return array(
                        'result' => -1,
                        'message' => $is_valid_limit['message']
                    );
                }

                //validate
                $is_valid_time_slot = array('valid' => true);
                foreach ($booking_detail as $bd) {
                    $is_valid_time_slot = $this->validate_booking_slot($b_id, $bd->b_service_id, $bd->b_service_break_time, $bd->b_service_duration,
                        $bd->s_maximum_slot, $booking->b_garage_id, $b_date, $b_time, $bd->b_quantity);
                    if (!$is_valid_time_slot['valid']) {
                        return array(
                            'result' => -1,
                            'message' => $is_valid_time_slot['message']
                        );
                    }
                }

                $sql = "UPDATE {$wpdb->prefix}rp_booking SET b_date=%s, b_time=%d, b_pay_now=%d, b_gateway_status=%d WHERE b_id = %d";
                $sql = $wpdb->prepare($sql, $b_date, $b_time, $pay_now, $pay_now, $b_id);
                $result = $wpdb->query($sql);
                return array(
                    'result' => $result,
                );


            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Invalid data', 'revy')
                );
            }

        }

        public function save_booking_fe()
        {
            $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
            $services_selected = isset($_REQUEST['services']) ? $_REQUEST['services'] : array();
            $booking_id = 0;
            if ($data && is_array($services_selected) && count($services_selected) > 0) {
                global $wpdb;
                $booking = array();
                $booking['b_gateway_type'] = isset($data['b_gateway_type']) ? $data['b_gateway_type'] : 'onsite';

                $setting_db = Revy_DB_Setting::instance();
                $setting = $setting_db->get_setting();

                if(isset($setting['wc_enable']) && $setting['wc_enable']=='1'){
                    $booking['b_gateway_type'] = 'woocommerce';
                }

                //customer
                $c_first_name = isset($data['c_first_name']) ? $data['c_first_name'] : '';
                $c_last_name = isset($data['c_last_name']) ? $data['c_last_name'] : '';
                $c_email = isset($data['c_email']) ? $data['c_email'] : '';
                $c_phone = isset($data['c_phone']) ? $data['c_phone'] : '';
                $c_phone_code = isset($data['c_phone_code']) ? $data['c_phone_code'] : '';
                $c_address = isset($data['b_customer_address']) ? $data['b_customer_address'] : '';
                $c_city = isset($data['b_customer_city']) ? $data['b_customer_city'] : '';
                $c_postal_code = isset($data['b_customer_postal_code']) ? $data['b_customer_postal_code'] : '';
                $c_country = isset($data['b_customer_country']) ? $data['b_customer_country'] : '';

                if ($c_first_name == '' || $c_last_name == '' || $c_email == '') {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('Please fill data for first name, last name and email', 'revy')
                    );
                }

                if($data['b_delivery_method'] != 3){ //delivery != Mail In
                    $is_valid_limit = $this->validate_booking($data);
                    if (is_array($is_valid_limit) && $is_valid_limit['result'] < 0) {
                        return $is_valid_limit;
                    }
                }


                $sql = "SELECT c_id, c_email FROM {$wpdb->prefix}rp_customers WHERE c_email=%s";
                $sql = $wpdb->prepare($sql, $c_email);
                $customer = $wpdb->get_results($sql);

                if (count($customer) > 0 && $customer[0]->c_id) {
                    $booking['b_customer_id'] = $customer[0]->c_id;
                } else {
                    $c_dob = new DateTime();
                    $c_code = uniqid();
                    $result_add_customer = $wpdb->insert($wpdb->prefix . 'rp_customers', array(
                        'c_first_name' => $c_first_name,
                        'c_last_name' => $c_last_name,
                        'c_email' => $c_email,
                        'c_gender' => 0,
                        'c_phone' => $c_phone,
                        'c_phone_code' => $c_phone_code,
                        'c_address' => $c_address,
                        'c_postal_code' => $c_postal_code,
                        'c_city' => $c_city,
                        'c_country' => $c_country,
                        'c_dob' => $c_dob->modify('-20 years')->format('Y-m-d'),
                        'c_code' => $c_code
                    ));
                    $booking['b_customer_id'] = $result_add_customer > 0 ? $wpdb->insert_id : $result_add_customer;
                }
                if (!isset($booking['b_customer_id']) || $booking['b_customer_id'] <= 0) {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('Cannot add customer information, please contact site admin for this error', 'revy')
                    );
                }

                $quantity = isset($data['b_quantity']) && $data['b_quantity'] ? $data['b_quantity'] : 1;
                $b_total_amount = 0;

                //service
                $s_ids = array();
                $s_attr_code = array();
                $service_name = '';
                foreach ($services_selected as $s) {
                    $s_ids[] = $s['s_id'];
                    if(isset($s['s_attr_code']) && $s['s_attr_code']!=''){
                        $s_attr_code[] = $s['s_attr_code'];
                    }
                    $service_name .= $s['s_name'] . '; ';
                }
                if(count($s_attr_code) > 0){
                    $sql = "SELECT S.s_id, S.s_name, S.s_tax, S.s_duration, S.s_break_time, S.s_maximum_slot, SP.s_attr_title, SP.s_attr_value, SP.s_price
                                FROM {$wpdb->prefix}rp_services AS S
                                LEFT JOIN {$wpdb->prefix}rp_services_price AS SP
                                ON S.s_id = SP.s_id
                                WHERE S.s_id IN (" . implode(',', $s_ids) . ") AND SP.s_attr_code IN ('" . implode("','", $s_attr_code) . "')";

                }else{
                    $sql = "SELECT  s_id,  s_name,  s_tax,  s_duration,  s_break_time, s_maximum_slot, '' AS s_attr_title, '' AS s_attr_value, 0 As s_price
                                FROM {$wpdb->prefix}rp_services
                                WHERE s_id IN (" . implode(',', $s_ids) . ") ";

                }
                $ser_info = $wpdb->get_results($sql);
                if (count($ser_info) < count($services_selected)) {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('Invalid data', 'revy')
                    );
                }

                $booking_detail = array();
                foreach ($ser_info as $s) {
                    $b_service_tax_amount = ($s->s_price * $quantity) * ($s->s_tax / 100);
                    $b_total_amount += $b_service_tax_amount + ($s->s_price * $quantity);
                    $booking_detail[] = array(
                        'b_service_id' => $s->s_id,
                        'b_service_duration' => $s->s_duration,
                        'b_service_break_time' => $s->s_break_time,
                        'b_attr_title' => $s->s_attr_title,
                        'b_attr_value' => $s->s_attr_value,
                        'b_quantity' => $quantity,
                        'b_price' => $s->s_price,
                        'b_service_tax' => $s->s_tax,
                        'b_service_tax_amount' => $b_service_tax_amount
                    );
                }

                $b_id = 0;
                $booking['b_customer_address'] = isset($data['b_customer_address']) ? $data['b_customer_address'] : '';
                $booking['b_customer_city'] = isset($data['b_customer_city']) ? $data['b_customer_city'] : '';
                $booking['b_customer_country'] = isset($data['b_customer_country']) ? $data['b_customer_country'] : '';
                $booking['b_customer_postal_code'] = isset($data['b_customer_postal_code']) ? $data['b_customer_postal_code'] : '';
                $booking['b_garage_id'] = isset($data['b_garage_id']) ? $data['b_garage_id'] : 0;
                $booking['b_device_id'] = isset($data['b_device_id']) ? $data['b_device_id'] : 0;
                $booking['b_brand_id'] = isset($data['b_brand_id']) ? $data['b_brand_id'] : 0;
                $booking['b_model_id'] = isset($data['b_model_id']) ? $data['b_model_id'] : 0;
                $booking['b_date'] = $data['b_date'];
                $booking['b_time'] = $data['b_time'];
                $booking['b_pay_now'] = 0;
                $booking['b_send_notify'] = 0;
                $booking['b_total_amount'] = $b_total_amount;
                $booking['b_delivery_method'] = $data['b_delivery_method'];


                //validate for Carry In
                if($booking['b_delivery_method'] == 2){
                    $is_valid_time_slot = array('valid' => true);
                    foreach ($ser_info as $s) {
                        $is_valid_time_slot = $this->validate_booking_slot($b_id, $s->s_id, $s->s_break_time, $s->s_duration,
                            $s->s_maximum_slot, $booking['b_garage_id'], $booking['b_date'], $booking['b_time'], $quantity);
                        if (!$is_valid_time_slot['valid']) {
                            return array(
                                'result' => -1,
                                'message' => $is_valid_time_slot['message']
                            );
                        }
                    }
                }

                //assign appointment date for mail in delivery
                if($booking['b_delivery_method'] == 3){
                    $now = current_time('mysql', 0);
                    $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                    $booking['b_date'] = $now->format('Y-m-d');
                    $booking['b_time'] = intval($now->format('G')) * 60 + intval($now->format('H'));
                }

                //coupon
                $booking['b_coupon_code'] = isset($data['b_coupon_code']) ? $data['b_coupon_code'] : '';
                $coupon = Revy_Utils::getCoupon($booking['b_coupon_code'], $s_ids);
                $discount = 0;
                $discount_type = '';
                if (isset($coupon['result']) && $coupon['result'] > 0 && isset($coupon['discount_type'])) {
                    $booking['b_discount'] = $coupon['amount'];
                    $discount_type = $coupon['discount_type'];
                    $booking['b_coupon_id'] = $coupon['coupon_id'];
                }

                $discount = 0;
                if ($discount_type == '1') { //percent
                    $discount = ($booking['b_total_amount'] * $booking['b_discount']) / 100;
                    $discount = number_format($discount, 2);
                } else {
                    $discount = isset($booking['b_discount']) ? $booking['b_discount'] : 0;
                }
                $discount = floatval($discount);

                $booking['b_total_pay'] = $booking['b_total_amount'] > $discount ? ($booking['b_total_amount'] - $discount) : 0;

                $booking['b_gateway_status'] = 0;
                $booking['b_description'] = isset($data['b_description']) ? $data['b_description'] : '';

                $db_setting = Revy_DB_Setting::instance();
                $setting = $db_setting->get_setting();
                $booking['b_process_status'] = isset($setting['b_process_status']) ? $setting['b_process_status'] : 0;

                $booking['b_create_date'] = current_time('mysql', 0);

                do_action('revy_before_add_booking', $booking);

                if ($booking['b_total_pay'] > 0 && ($booking['b_gateway_type'] === 'stripe' || $booking['b_gateway_type'] === 'paypal')) {
                    //temporary for payment gateway
                    $booking['b_process_status'] = -1;
                }

                $booking['b_total_tax'] = 0;
                foreach ($booking_detail as $bd) {
                    $booking['b_total_tax'] += $bd['b_service_tax_amount'];
                }

                error_log(serialize($booking));
                $result = $wpdb->insert($wpdb->prefix . 'rp_booking', $booking);
                $booking_id = $result > 0 ? $wpdb->insert_id : $result;

                // insert booking detail
                if ($booking_id > 0) {
                    foreach ($booking_detail as $bd) {
                        $bd['b_id'] = $booking_id;
                        $wpdb->insert($wpdb->prefix . 'rp_booking_detail', $bd);
                    }
                }

                if ($c_phone) {
                    $wpdb->update($wpdb->prefix . 'rp_customers', array('c_last_booking' => $booking['b_date'], 'c_phone' => $c_phone, 'c_phone_code' => $c_phone_code), array('c_id' => $booking['b_customer_id']));
                } else {
                    $wpdb->update($wpdb->prefix . 'rp_customers', array('c_last_booking' => $booking['b_date']), array('c_id' => $booking['b_customer_id']));
                }

                do_action('fat_after_add_booking', $booking_id, $booking);

                $approve_url = '';

                error_log('save booking fe:'.$booking_id);

                if ($booking_id > 0 && $booking['b_gateway_type'] === 'paypal') {
                    $payment_desc = esc_html__('Customer:', 'revy') . $c_first_name . ' ' . $c_last_name;
                    $payment_desc .= esc_html__('Service:', 'revy') . $service_name;
                    $time = $data['b_date_i18n'] . ' ' . $data['b_time_label'] . ',';
                    $payment_desc .= esc_html__('Time:', 'revy') . $time;
                    $url = esc_url(home_url());
                    $total_pay = $booking['b_total_pay'];
                    $customer = $c_first_name . ' ' . $c_last_name . '(' . $c_email . ')';
                    $service_name = is_array($service_name) ? implode(', ', $service_name) : $service_name;
                    if ($total_pay > 0) {
                        $payment = new Revy_Payment();
                        $payment_result = $payment->payment($booking_id, $customer, $service_name, $total_pay, 0, $setting['currency'], $payment_desc, $url);
                        if ($payment_result['result'] == -1) {
                            $sql = "DELETE FROM {$wpdb->prefix}rp_booking WHERE b_id = %d";
                            $sql = $wpdb->prepare($sql, $booking_id);
                            $wpdb->query($sql);
                            return array(
                                'result' => -1,
                                'message' => $payment_result['message']
                            );
                        } else {
                            $approve_url = $payment_result['approval_url'];
                        }
                    } else {
                        return array(
                            'result' => $booking_id,
                        );
                    }
                }

                if ($booking_id > 0 && $booking['b_gateway_type'] === 'stripe') {
                    return array(
                        'result' => $booking_id,
                        'payment_method' => $booking['b_gateway_type']
                    );
                }

                if ($booking_id && $booking['b_gateway_type'] === 'onsite') {
                    do_action('revy_booking_completed', $booking_id);
                }

                $result = array(
                    'result' => $booking_id,
                    'redirect_url' => isset($approve_url) ? $approve_url : ''
                );

                $result = apply_filters('Revy_Payment_booking', $result, $booking_id, $booking);

                return $result;

            } else {
                if ($booking_id) {
                    error_log('save booking fe error, now delete booking id:'.$booking_id);
                    global $wpdb;
                    $sql = "DELETE FROM {$wpdb->prefix}rp_booking WHERE b_id = %d";
                    $sql = $wpdb->prepare($sql, $booking_id);
                    $wpdb->query($sql);

                    $sql = "DELETE FROM {$wpdb->prefix}rp_booking_detail WHERE b_id = %d";
                    $sql = $wpdb->prepare($sql, $booking_id);
                    $wpdb->query($sql);
                }
                return array(
                    'result' => -1,
                    'message' => esc_html__('Invalid data', 'revy')
                );
            }
        }

        public function update_booking_process_status()
        {
            $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '';
            $b_process_status = isset($_REQUEST['b_process_status']) ? $_REQUEST['b_process_status'] : '';
            $status = array(0, 1, 2, 3);
            if ($b_id && $b_process_status != '' && in_array($b_process_status, $status)) {
                global $wpdb;

                $sql = "SELECT b_id, b_customer_id, b_garage_id, b_date, b_time, b_process_status FROM {$wpdb->prefix}rp_booking WHERE b_id=%d";
                $sql = $wpdb->prepare($sql, $b_id);

                $booking = $wpdb->get_results($sql);

                if (count($booking) == 0) {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('Cannot find this booking.Maybe it have been deleted', 'revy'),
                    );
                }

                do_action('revy_before_update_booking_status', $b_id, $b_process_status);
                $result = $wpdb->update($wpdb->prefix . 'rp_booking', array('b_process_status' => $b_process_status, 'b_send_notify' => 0, 'b_canceled_by_client' => 0),
                    array('b_id' => $b_id));
                do_action('revy_update_booking_status', $b_id, $b_process_status);
                return array(
                    'result' => $result,
                    'message' => $result ? esc_html__('Booking status have been updated', 'revy') : esc_html__('Cannot find this booking.Maybe it have been deleted', 'revy')
                );

            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Invalid data', 'revy')
                );
            }
        }

        public function send_booking_mail($b_id, $is_fe = 1)
        {
            global $wpdb;
            if ($b_id == '') {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data invalid', 'revy')
                );
            }
            $sql = "SELECT  b_id, c_code, c_first_name, c_last_name, c_email, c_phone,  rg_name, rg_email, rg_phone, rg_address,  
                            b_date, b_time,  b_process_status, b_customer_address, b_customer_city, b_customer_country, b_customer_postal_code,
                            b_total_pay, b_send_notify, b_description, b_coupon_code, b_delivery_method, '' as s_name, '' as b_attr_title, '' as b_attr_value, 
                            '' as b_service_duration, 1 as b_quantity, '' as s_description, '' as s_info, rb_name, rm_name, rd_name, b_gateway_type
                    FROM {$wpdb->prefix}rp_booking 
                    LEFT JOIN {$wpdb->prefix}rp_customers ON b_customer_id = c_id
                    LEFT JOIN {$wpdb->prefix}rp_garages ON b_garage_id = rg_id
                    LEFT JOIN {$wpdb->prefix}rp_brands  ON b_brand_id = rb_id
                    LEFT JOIN {$wpdb->prefix}rp_models ON b_model_id = rm_id
                    LEFT JOIN {$wpdb->prefix}rp_devices ON b_device_id = rd_id
                    WHERE b_id=%d";
            $sql = $wpdb->prepare($sql, $b_id);
            $mail_info = $wpdb->get_results($sql);

            $sql = "SELECT S.s_name, S.s_description, b_service_duration, b_service_break_time, b_quantity, b_price, b_attr_title, b_attr_value
                    FROM {$wpdb->prefix}rp_booking_detail AS RBD
                    LEFT JOIN {$wpdb->prefix}rp_services AS S
                    ON RBD.b_service_id = S.s_id
                    WHERE b_id=%d";
            $sql = $wpdb->prepare($sql, $b_id);
            $booking_detail = $wpdb->get_results($sql);

            if (count($mail_info) <= 0 || (isset($mail_info[0]->b_send_notify) && $mail_info[0]->b_send_notify == '1')) {
                return;
            }

            $mail_info = $mail_info[0];

            $mail_info->s_info = array();
            foreach ($booking_detail as $bd) {
                $mail_info->s_info[] = array(
                    's_name' => $bd->s_name,
                    'b_attr_title' => $bd->b_attr_title,
                    'b_attr_value' => $bd->b_attr_value,
                    'b_service_duration' => $bd->b_service_duration,
                    'b_quantity' => $bd->b_quantity,
                    's_description' => $bd->s_description,
                    'b_price' =>  $bd->b_price

                ) ;
            }

            $setting_db = Revy_DB_Setting::instance();
            $setting = $setting_db->get_setting();
            $email_templates = $setting_db->get_email_template();
            $template = '';

            $pending_key = $is_fe ? 'pending' : 'backend';
            $approved_key = $is_fe ? 'approved' : 'backend';
            foreach ($email_templates as $tmpl) {
                if ($mail_info->b_process_status == 0 && $tmpl['template'] === $pending_key) {
                    $template = $tmpl;
                    break;
                }

                if ($mail_info->b_process_status == 1 && $tmpl['template'] === $approved_key) {
                    $template = $tmpl;
                    break;
                }

                if ($mail_info->b_process_status == 2 && $tmpl['template'] === 'canceled') {
                    $template = $tmpl;
                    break;
                }

                if ($mail_info->b_process_status == 3 && $tmpl['template'] === 'rejected') {
                    $template = $tmpl;
                    break;
                }
            }

            $subject = $message = '';

            if(isset($setting['employee_email']) && $setting['employee_email']){
                $setting['cc_to'] = $setting['cc_to'] !='' ? ( $setting['cc_to'].','.$setting['employee_email']) : $setting['employee_email'];
            }

            error_log(serialize($mail_info));
            if ($mail_info->b_delivery_method== 1 && isset($template['fixit_home_enable']) && $template['fixit_home_enable'] ) {
                $subject = $template['fixit_home_subject'];
                $message = $template['fixit_home_message'];

                Revy_Utils::makeMailContent($subject, $message, $mail_info, $setting);
                return Revy_Utils::sendMail(array(
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
            }

            if ($mail_info->b_delivery_method== 2 && isset($template['carry_in_enable']) && $template['carry_in_enable'] ) {
                $subject = $template['carry_in_subject'];
                $message = $template['carry_in_message'];
                Revy_Utils::makeMailContent($subject, $message, $mail_info, $setting);
                return Revy_Utils::sendMail(array(
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
            }

            if ($mail_info->b_delivery_method== 3 && isset($template['mail_in_enable']) && $template['mail_in_enable'] ) {
                $subject = $template['mail_in_subject'];
                $message = $template['mail_in_message'];
                Revy_Utils::makeMailContent($subject, $message, $mail_info, $setting);
                return Revy_Utils::sendMail(array(
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
            }

        }

        public function delete_booking()
        {
            $b_ids = isset($_REQUEST['b_ids']) && $_REQUEST['b_ids'] != '' ? $_REQUEST['b_ids'] : '';
            if ($b_ids) {
                global $wpdb;
                $b_ids = implode(',', $b_ids);

                $sql = "DELETE FROM {$wpdb->prefix}rp_booking_detail WHERE  b_id IN ({$b_ids}) ";
                $wpdb->query($sql);

                $sql = "DELETE FROM {$wpdb->prefix}rp_booking WHERE  b_id IN ({$b_ids}) ";
                $wpdb->query($sql);

                return array(
                    'result' => 1,
                    'message' => esc_html__(' booking(s) have been deleted', 'revy')
                );

            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data is invalid', 'revy')
                );
            }
        }

        private function validate_booking_slot($b_id, $s_id, $s_duration, $s_break_time, $service_max_cap, $garage_id, $date, $time, $quantity)
        {
            global $wpdb;
            $invalid_message = esc_html__('The appointments are fully booked. Please check again later or browse other day !', 'revy');
            $time_end = $time + $s_duration + $s_break_time;

            //Check seats available for this service
            $sql = "SELECT SUM(RBD.b_quantity) as total_customer_number
                    FROM {$wpdb->prefix}rp_booking AS RB
                    LEFT JOIN {$wpdb->prefix}rp_booking_detail AS RBD
                    ON RB.b_id = RBD.b_id
                    WHERE   RB.b_id != %d
                            AND b_delivery_method = 2
                            AND b_process_status IN (0,1)
                            AND b_date = %s AND RBD.b_service_id = %d AND b_garage_id = %d
                            AND b_time <=%d AND %d <= (b_time + RBD.b_service_duration + RBD.b_service_break_time) ";
            $sql = $wpdb->prepare($sql, $b_id, $date, $s_id, $garage_id, $time, $time_end);
            $booking_in_time = $wpdb->get_results($sql);

            if (is_countable($booking_in_time) && count($booking_in_time) > 0 && !is_null($booking_in_time[0]->total_customer_number)) {
                $total_customer_number = $booking_in_time[0]->total_customer_number;
                if ($quantity > ($service_max_cap - $total_customer_number) || (1 > ($service_max_cap - $total_customer_number))) {
                    return array(
                        'valid' => false,
                        'message' => esc_html__('The number of device exceeds the number that can be serviced', 'fat-event')
                    );
                }
            }

            //Check conflict time slot with $s_id
            $sql = "SELECT RB.b_id
                    FROM {$wpdb->prefix}rp_booking AS RB
                    LEFT JOIN {$wpdb->prefix}rp_booking_detail AS RBD
                    ON RB.b_id = RBD.b_id
                    WHERE   RB.b_id != %d
                            AND RB.b_delivery_method = 2
                            AND b_process_status IN (0,1)
                            AND b_date = %s AND (
                               (  {$time} <= b_time AND b_time < {$time_end} AND {$time_end} < (b_time + RBD.b_service_break_time + RBD.b_service_duration) ) OR
                                    ( {$time} <= b_time AND (b_time + RBD.b_service_break_time + RBD.b_service_duration) < {$time_end} ) OR
                                    ( b_time <= {$time} AND {$time} < (b_time + RBD.b_service_break_time + RBD.b_service_duration) AND (b_time + RBD.b_service_break_time + RBD.b_service_duration) < {$time_end}) OR 
                                    ( b_time <= {$time} AND {$time_end} <= (b_time + RBD.b_service_duration + RBD.b_service_break_time) AND RBD.b_service_id = %d AND b_garage_id != %d)
                            )";
            $sql = $wpdb->prepare($sql, $b_id, $date, $s_id, $garage_id);
            $booking_conflict = $wpdb->get_results($sql);

            if (is_countable($booking_conflict) && count($booking_conflict)) {
                error_log('booking conflict');
                return array(
                    'valid' => false,
                    'message' => $invalid_message
                );
            }

            //check the date is day off
            $sql = "SELECT dof_id FROM {$wpdb->prefix}rp_services_day_off WHERE s_id=%d AND dof_start <= %s AND dof_end >= %s";
            $sql = $wpdb->prepare($sql, $s_id, $date, $date);
            $day_off = $wpdb->get_results($sql);
            if (is_countable($day_off) && count($day_off) > 0) {
                error_log('invalid day off');
                return array(
                    'valid' => false,
                    'message' => $invalid_message
                );
            }

            //check service schedule
            $date = DateTime::createFromFormat('Y-m-d', $date);
            $day_of_week = 2;

            switch ($date->format('D')) {
                case 'Mon':
                {
                    $day_of_week = 2;
                    break;
                }
                case 'Tue':
                {
                    $day_of_week = 3;
                    break;
                }
                case 'Wed':
                {
                    $day_of_week = 4;
                    break;
                }
                case 'Thu':
                {
                    $day_of_week = 5;
                    break;
                }
                case 'Fri':
                {
                    $day_of_week = 6;
                    break;
                }
                case 'Sat':
                {
                    $day_of_week = 7;
                    break;
                }
                case 'Sun':
                {
                    $day_of_week = 8;
                    break;
                }
            }
            $sql = "SELECT ss_work_hour_start, ss_work_hour_end, ss_enable
                    FROM {$wpdb->prefix}rp_services_schedule
                    WHERE s_id=%d AND ss_day=%d AND ss_enable=1";
            $sql = $wpdb->prepare($sql, $s_id, $day_of_week);
            $service_schedules = $wpdb->get_results($sql);
            if (is_countable($service_schedules) && count($service_schedules) > 0) {
                foreach ($service_schedules as $ss) {
                    if ($ss->ss_work_hour_start <= $time && ($time + $s_duration) <= $ss->ss_work_hour_end) {
                        return array(
                            'valid' => true,
                        );
                    }
                }
                error_log('invalid service schedule');
                return array(
                    'valid' => false,
                    'message' => $invalid_message
                );
            } else {
                return array(
                    'valid' => false,
                    'message' => $invalid_message
                );
            }

            return array(
                'valid' => true
            );
        }

        public function get_booking_history()
        {
            global $wpdb;
            $c_code = isset($_REQUEST['c_code']) ? $_REQUEST['c_code'] : '';
            $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            $status = isset($_REQUEST['status']) && $_REQUEST['status'] ? $_REQUEST['status'] : 0;
            $start_date = isset($_REQUEST['start_date']) && $_REQUEST['start_date'] ? $_REQUEST['start_date'] : '';
            $end_date = isset($_REQUEST['end_date']) && $_REQUEST['end_date'] ? $_REQUEST['end_date'] : '';
            $total = 0;
            if ($c_code) {
                $sql = "SELECT c_email FROM {$wpdb->prefix}rp_customers WHERE c_code=%s";
                $sql = $wpdb->prepare($sql, $c_code);
                $customer = $wpdb->get_results($sql);
                $user_email = count($customer) > 0 && isset($customer[0]->c_email) ? $customer[0]->c_email : '';

                if ($user_email) {
                    $sql = "SELECT  b_date, b_time, b_id, b_customer_id, c_first_name, c_last_name, c_email, b_gateway_type, b_gateway_status, b_total_pay, 
                                    b_process_status, b_create_date, rm_name, b_delivery_method
                                        FROM {$wpdb->prefix}rp_booking LEFT JOIN {$wpdb->prefix}rp_customers ON b_customer_id = c_id
                                        LEFT JOIN {$wpdb->prefix}rp_models ON b_model_id = rm_id
                                        WHERE c_email=%s AND b_canceled_by_client!=1 AND b_process_status = %s AND b_date >= %s AND b_date <= %s
                                        ORDER BY b_date DESC";
                    $sql = $wpdb->prepare($sql, $user_email, $status, $start_date, $end_date);
                    $bookings = $wpdb->get_results($sql);
                    $total = count($bookings);

                    $db_setting = Revy_DB_Setting::instance();
                    $setting = $db_setting->get_setting();

                    $item_per_page = isset($setting['item_per_page']) ? $setting['item_per_page'] : 10;
                    $number_of_page = $total / $item_per_page + ($total % $item_per_page > 0 ? 1 : 0);
                    $page = $page > $number_of_page ? $number_of_page : $page;
                    $page = ($page - 1) * $item_per_page;
                    $bookings = array_slice($bookings, $page, $item_per_page);

                    $b_date = '';
                    $now = current_time('mysql', 0);
                    $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                    $hours = Revy_Utils::getDurations(0, 'duration_step');
                    $steps = Revy_Utils::getWorkHours(5);
                    $status = array(
                        esc_html__('Pending', 'revy'),
                        esc_html__('Approved', 'revy'),
                        esc_html__('Cancel', 'revy'),
                        esc_html__('Rejected', 'revy')
                    );

                    $b_ids = array();
                    foreach ($bookings as $booking) {
                        $b_ids[] = $booking->b_id;
                        $b_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking->b_date . ' 00:00:00');
                        $b_date->modify("+{$booking->b_time} minutes");
                        $booking->editable = $b_date > $now ? 1 : 0;
                        if($booking->b_delivery_method == 1 || $booking->b_delivery_method == 3){
                            $booking->editable = 1;
                        }
                        $booking->is_cancel = $booking->editable && $booking->b_process_status == 0 ? 1 : 0;
                        $booking->b_time_label = $steps[$booking->b_time];
                    }

                    //get service name for each booking
                    $booking_detail = array();
                    if($total > 0){
                        $sql = "SELECT b_id, s_name, b_service_id, b_attr_title, b_attr_value
                            FROM {$wpdb->prefix}rp_booking_detail AS RBD
                            LEFT JOIN {$wpdb->prefix}rp_services AS RS
                            ON RBD.b_service_id = RS.s_id
                            WHERE b_id IN(". implode(',', $b_ids).")";
                        $booking_detail = $wpdb->get_results($sql);
                    }

                    return array(
                        'result' => 1,
                        'total' => $total,
                        'bookings' => $bookings,
                        'bookings_detail' => $booking_detail
                    );
                } else {
                    return array(
                        'result' => -1,
                        'message' => esc_html__('Customer code invalid', 'revy')
                    );
                }
            }

            return array(
                'result' => -1,
                'message' => esc_html__('Data invalid', 'revy')
            );
        }

        public function cancel_booking()
        {
            $setting_db = Revy_DB_Setting::instance();
            $setting = $setting_db->get_setting();
            if (isset($setting['allow_client_cancel']) && $setting['allow_client_cancel'] == 0) {
                return array(
                    'result' => -1,
                    'message' => esc_html__('The reservation cancellation function is locked', 'revy')
                );
            }
            global $wpdb;
            $c_code = isset($_REQUEST['c_code']) ? $_REQUEST['c_code'] : '';
            $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
            $cancel_before = isset($setting['cancel_before']) && $setting['cancel_before'] ? intval($setting['cancel_before']) : 0;

            $current_user = wp_get_current_user();
            $user_email = $current_user->exists() ? $current_user->user_email : '';
            if (($c_code || $user_email) && $id) {
                if ($c_code) {
                    $sql = "SELECT c_id FROM {$wpdb->prefix}rp_customers WHERE c_code!='' AND c_code=%s";
                    $sql = $wpdb->prepare($sql, $c_code);
                } else {
                    $sql = "SELECT c_id FROM {$wpdb->prefix}rp_customers WHERE c_email=%s";
                    $sql = $wpdb->prepare($sql, $user_email);
                }
                $customer = $wpdb->get_results($sql);
                if (count($customer) > 0 && isset($customer[0]->c_id)) {
                    $sql = "SELECT b_id, b_date, b_time, b_process_status FROM {$wpdb->prefix}rp_booking WHERE b_id = %d AND b_customer_id = %d";
                    $sql = $wpdb->prepare($sql, $id, $customer[0]->c_id);
                    $bookings = $wpdb->get_results($sql);
                    if (count($bookings) > 0) {
                        if ($bookings[0]->b_process_status != 0) {
                            return array(
                                'result' => -1,
                                'message' => esc_html__('You cannot cancel approved appointment', 'revy')
                            );
                        }

                        if ($cancel_before) {
                            $now = current_time('mysql', 0);
                            $now = strtotime($now);
                            $bookings[0]->b_time = intval($bookings[0]->b_time);
                            $hours = $bookings[0]->b_time / 60;
                            $hours = floor($hours);
                            $hours = $hours > 10 ? $hours : '0' . $hours;
                            $minute = $bookings[0]->b_time % 60;
                            $minute = $minute > 10 ? $minute : '0' . $minute;
                            $b_date_time = $bookings[0]->b_date . ' ' . $hours . ':' . $minute;
                            $b_date_time = strtotime($b_date_time);
                            $diff = $b_date_time - $now;
                            $hours_diff = $diff / (60 * 60);
                            if ($hours_diff < $cancel_before) {
                                return array(
                                    'result' => -1,
                                    'cancel_before' => $cancel_before,
                                    'b_date_time' => ($bookings[0]->b_date . ' ' . $hours . ':' . $minute),
                                    'hours_diff' => $hours_diff,
                                    'message' => esc_html__('Time limit for cancellation reservations has passed', 'revy')
                                );
                            }
                        }

                        $b_status_note = esc_html__('Canceled by client', 'revy');
                        $sql = "UPDATE {$wpdb->prefix}rp_booking SET b_canceled_by_client = 1, b_process_status=2, b_send_notify=0, b_status_note= %s WHERE b_process_status=0 AND b_id = %d AND b_customer_id = %d";
                        $sql = $wpdb->prepare($sql, $b_status_note, $id, $customer[0]->c_id);
                        $wpdb->query($sql);
                        return array(
                            'result' => 1,
                            'message' => esc_html__('Booking has been canceled', 'revy')
                        );
                    }
                }
            }

            return array(
                'result' => -1,
                'message' => esc_html__('Data invalid', 'revy')
            );
        }

        public function export_calendar()
        {
            if (isset($_REQUEST['b_id'])) {
                global $wpdb;
                $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '';

                $sql = "SELECT b_id, s_name, b_service_duration, 
                                            loc_name, loc_address, b_date, b_time
                                        FROM {$wpdb->prefix}rp_booking 
                                        LEFT JOIN {$wpdb->prefix}rp_services ON b_service_id = s_id
                                        LEFT JOIN {$wpdb->prefix}rp_garages ON b_garage_id = rg_id
                                        WHERE b_id=%d";
                $sql = $wpdb->prepare($sql, $b_id);
                $booking_info = $wpdb->get_results($sql);

                if (count($booking_info) > 0) {
                    $booking_info = $booking_info[0];
                    $u_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking_info->b_date . ' 00:00:00');
                    $u_start_date = $u_start_date->modify('+' . $booking_info->b_time . ' minute');
                    $u_end_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking_info->b_date . ' 00:00:00');
                    $u_end_date = $u_end_date->modify('+' . ($booking_info->b_time + $booking_info->b_service_duration) . ' minute');
                    $description = esc_html__('Service name:', 'revy') . $booking_info->s_name . ' \\n ';

                    $location = $booking_info->loc_name . ' ' . $booking_info->loc_address;

                    $setting_db = Revy_DB_Setting::instance();
                    $setting = $setting_db->get_setting();

                    $properties = array(
                        'dtstart' => $u_start_date->format('Y-m-d H:i'),
                        'dtend' => $u_end_date->format('Y-m-d H:i'),
                        'description' => $description,
                        'location' => $location,
                        'summary' => $booking_info->s_name,
                        'organizer' => $setting['company_name']
                    );
                    $ics = new ICS($properties);
                    return $ics->to_string();
                } else {
                    return esc_html__('Data invalid', 'revy');
                }
            }
        }

        public function export_google_calendar()
        {
            if (isset($_REQUEST['b_id'])) {
                global $wpdb;
                $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '';

                $link = '';

                $sql = "SELECT b_id,  s_name, b_service_duration, 
                                            loc_name, loc_address, b_date, b_time
                                        FROM {$wpdb->prefix}rp_booking 
                                        LEFT JOIN {$wpdb->prefix}rp_services ON b_service_id = s_id
                                        LEFT JOIN {$wpdb->prefix}rp_garages ON b_garage_id = rg_id
                                        WHERE b_id=%d";
                $sql = $wpdb->prepare($sql, $b_id);
                $booking_info = $wpdb->get_results($sql);
                if (count($booking_info) > 0) {
                    $booking_info = $booking_info[0];
                    $link = 'http://www.google.com/calendar/render?action=TEMPLATE';
                    $time_zone = wp_timezone();
                    $u_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking_info->b_date . ' 00:00:00', $time_zone);// ($booking_info->b_date;
                    $u_start_date = $u_start_date->modify('+' . $booking_info->b_time . ' minute');
                    $u_end_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking_info->b_date . ' 00:00:00', $time_zone);
                    $u_end_date = $u_end_date->modify('+' . ($booking_info->b_time + $booking_info->b_service_duration) . ' minute');

                    $link .= '&text=' . $booking_info->s_name;
                    $link .= '&dates=' . $u_start_date->format('Ymd') . 'T' . $u_start_date->format('His') . '/' . $u_end_date->format('Ymd') . 'T' . $u_end_date->format('His');
                    $link .= '&details=Service:' . $booking_info->s_name . ' Duration:' . $booking_info->b_service_duration;
                    $link .= '&location=' . $booking_info->loc_name . ' ' . $booking_info->loc_address;
                    $link .= '&trp=false&sprop=&sprop=name:';
                    return $link;
                } else {
                    return '';
                }
            }
        }

        public function validate_booking($data)
        {

            $setting_db = Revy_DB_Setting::instance();
            $setting = $setting_db->get_setting();

            //validate day limit
            $day_limit = isset($setting['day_limit']) && $setting['day_limit'] ? $setting['day_limit'] : 365;
            $b_date = DateTime::createFromFormat('Y-m-d H:i', $data['b_date'] . ' 00:00');
            $now = current_time('mysql', 0);
            $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
            if ($b_date->diff($now)->days > $day_limit) {
                return array(
                    'result' => -1,
                    'message' => sprintf(esc_html__('You cannot book service before %s days', 'revy'), $day_limit)
                );
            }
            $b_date_time = DateTime::createFromFormat('Y-m-d H:i', $data['b_date'] . ' 00:00');
            $b_date_time = $b_date_time->modify('+' . $data['b_time'] . ' minutes');
            if ($now >= $b_date_time) {
                return array(
                    'result' => -1,
                    'message' => esc_html__('You cannot set time in the past for ', 'revy') . $data['b_date_i18n'] . ' ' . $data['b_time_label']
                );
            }

            //validate limit in time period
            if ($data['c_email']!='' && isset($setting['limit_booking']) && $setting['limit_booking'] == 1 && isset($setting['limited_time']) && $setting['limited_time'] > 0) {
                global $wpdb;
                $limited_time = $setting['limited_time'];
                $sql = "SELECT b_create_date 
                        FROM {$wpdb->prefix}rp_booking AS RB
                        INNER JOIN {$wpdb->prefix}rp_customers AS RC 
                        ON RB.b_customer_id = RC.c_id
                        WHERE RC.c_email = %s AND RB.b_process_status!=-1";
                $sql = $wpdb->prepare($sql, $data['c_email']);
                $bk = $wpdb->get_results($sql);

                if (isset($bk[0]) && $bk[0]->b_create_date) {
                    $bk[0]->b_create_date = DateTime::createFromFormat('Y-m-d H:i:s', $bk[0]->b_create_date);
                    if ($now->diff($bk[0]->b_create_date) <= $limited_time) {
                        return array(
                            'result' => -1,
                            'message' => esc_html__('You can not make more appointment in ', 'revy') . $limited_time . esc_html__(' minutes', 'revy')
                        );
                    }
                }

            }

            return array(
                'result' => 1
            );

        }
    }
}