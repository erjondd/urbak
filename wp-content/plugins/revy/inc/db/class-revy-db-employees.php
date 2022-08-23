<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/7/2019
 * Time: 9:10 AM
 */
if (!class_exists('Revy_DB_Employees')) {
    class Revy_DB_Employees
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_employees()
        {
            global $wpdb;
            $e_name = isset($_REQUEST['e_name']) ? $_REQUEST['e_name'] : '';
            $loc_id = isset($_REQUEST['loc_id']) ? $_REQUEST['loc_id'] : '';
            $s_id = isset($_REQUEST['s_id']) ? $_REQUEST['s_id'] : '';

            $sql = "SELECT e_id, e_first_name, e_last_name, e_email, e_avatar_id, e_phone, e_enable, e_description FROM {$wpdb->prefix}rp_employees WHERE 1=%d ";

            if ($s_id && is_array($s_id)) {
                $s_id = implode(',', $s_id);
                $sql .= " AND e_id IN (SELECT e_id FROM {$wpdb->prefix}rp_services_employee WHERE  s_id IN ({$s_id}) ) ";
            }

            if ($e_name) {
                $sql .= " AND (e_first_name LIKE '%{$e_name}%' OR e_last_name LIKE '%{$e_name}%' OR e_email LIKE '%{$e_name}%' )";
            }

            $sql = $wpdb->prepare($sql, 1);
            $employees = $wpdb->get_results($sql);
            foreach ($employees as $emp) {
                $emp->e_avatar_url = isset($emp->e_avatar_id) ? wp_get_attachment_image_src($emp->e_avatar_id, 'thumbnail') : '';
                $emp->e_avatar_url = isset($emp->e_avatar_url[0]) ? $emp->e_avatar_url[0] : '';
            }
            return $employees;
        }

        public function get_employees_dic()
        {
            global $wpdb;
            $user_info = Revy_Utils::get_user_info();
            $sql = "SELECT e_id, e_first_name, e_last_name, e_email, e_avatar_id, e_phone, e_enable FROM {$wpdb->prefix}rp_employees";
            if(isset($user_info['is_admin']) && $user_info['is_admin']==0){
                $sql .= " WHERE e_id=".$user_info['e_id'];
            }
            $employees = $wpdb->get_results($sql);
            return $employees;
        }

        public function get_employee_by_id()
        {
            $e_id = isset($_REQUEST['e_id']) ? $_REQUEST['e_id'] : 0;
            global $wpdb;

            if ($e_id) {

                $sql = "SELECT e_id, e_avatar_id, e_user_id, e_create_date, e_description, e_email, e_enable, e_first_name, e_id, e_last_name, e_phone
                        FROM {$wpdb->prefix}rp_employees 
                        WHERE e_id=%d";
                $sql = $wpdb->prepare($sql, $e_id);
                $employee = $wpdb->get_results($sql);
                if (count($employee) > 0) {
                    $employee = $employee[0];
                    $employee->e_avatar_url = isset($employee->e_avatar_id) ? wp_get_attachment_image_src($employee->e_avatar_id, 'thumbnail') : '';
                    $employee->e_avatar_url = isset($employee->e_avatar_url[0]) ? $employee->e_avatar_url[0] : '';
                    return $employee;
                }
            }
            return  array(
                'e_id' => 0,
                'e_first_name' => '',
                'e_last_name' => '',
                'e_avatar_id' => '',
                'e_phone' => '',
                'e_email' => '',
            );
        }

        public function save_employee()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                global $wpdb;
                $create_wp_user = isset($data['e_create_use']) && $data['e_create_use']=='1' ? true : false;
                unset($data['e_create_use']);

                $e_id = 0;
                if (isset($data['e_id']) && $data['e_id']) {
                    $e_id = $data['e_id'];
                    $result = $wpdb->update($wpdb->prefix . 'rp_employees', $data, array('e_id' => $data['e_id']));
                } else {
                    $result = $wpdb->insert($wpdb->prefix . 'rp_employees', array(
                        'e_first_name' => $data['e_first_name'],
                        'e_last_name' => $data['e_last_name'],
                        'e_avatar_id' => $data['e_avatar_id'],
                        'e_phone' => $data['e_phone'],
                        'e_email' => $data['e_email'],
                        'e_description' => $data['e_description'],
                        'e_enable' => 1,
                        'e_create_date' => current_time( 'mysql', 0)
                    ));
                    $result = $result > 0 ? $wpdb->insert_id : $result;
                    $e_id = $result;
                }

                $user_id = 0;
                $create_wp_user = apply_filters('apoint_create_user', $create_wp_user);
                if($create_wp_user && $e_id){
                    $user_id = username_exists( $data['e_email'] );
                    if ( !$user_id and email_exists($data['e_email']) == false ) {
                        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                        $user_id = wp_create_user( $data['e_email'], $random_password, $data['e_email'] );
                        if($user_id){
                            update_user_meta($user_id,'apoint_e_id', $e_id);
                            wp_update_user( array( 'ID' => $user_id, 'first_name' => $data['e_first_name'], 'last_name' => $data['e_last_name'] ) );
                            $wpdb->update($wpdb->prefix . 'rp_employees', array('e_user_id' => $user_id), array('e_id' => $e_id));
                        }
                    }else{
                        return array(
                            'result' => -1,
                            'message' => esc_html__('User already exists. Please use another email','revy')
                        );
                    }
                }

                return array(
                    'result' => $result,
                    'user_id' => $user_id
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Please input data for field', 'revy')
                );
            }
        }

        public function enable_employee()
        {
            $e_id = isset($_REQUEST['e_id']) && $_REQUEST['e_id'] != '' ? $_REQUEST['e_id'] : '';
            $e_enable = isset($_REQUEST['e_enable']) && $_REQUEST['e_enable'] != '' ? $_REQUEST['e_enable'] : 1;
            if ($e_id) {
                global $wpdb;
                $result = $wpdb->update($wpdb->prefix . 'rp_employees', array('e_enable' => $e_enable), array('e_id' => $e_id));
                return array(
                    'result' => $result,
                    'message' => $e_enable == 1 ? esc_html__('Employee has been enabled', 'revy') : esc_html__('Employee has been disabled', 'revy')
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data is invalid', 'revy')
                );
            }
        }

        public function delete_employee()
        {
            $e_id = isset($_REQUEST['e_id']) && $_REQUEST['e_id'] != '' ? $_REQUEST['e_id'] : '';
            if ($e_id) {
                global $wpdb;

                $result = $wpdb->delete($wpdb->prefix . 'rp_employees', array('e_id' => $e_id));
                return array(
                    'result' => $result,
                    'message' => $result > 0 ? esc_html__('Employee has been deleted', 'revy') : esc_html__('Can not find employee, it may have been deleted by another user', 'revy')
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data is invalid', 'revy')
                );
            }
        }

        public function get_employee_time_slot_monthly()
        {
            $s_id = isset($_REQUEST['s_id']) ? $_REQUEST['s_id'] : '';
            $e_id = isset($_REQUEST['e_id']) ? $_REQUEST['e_id'] : '';
            $loc_id = isset($_REQUEST['loc_id']) ? $_REQUEST['loc_id'] : 0;
            $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

            if ($s_id && $e_id && $loc_id && $date) {
                $last_day = date("t", strtotime($date));
                $last_day = intval($last_day);
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
                $current_day = intval($date->format('d'));
                $now = current_time('mysql', 0);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $days = array();

                /** get days in month */
                if ($date <= $now || ($date->format('m') == $now->format('m') && $date->format('Y') == $now->format('Y') )) {
                    $date = $now;
                    $last_day = date("t", strtotime($date->format('Y-m-d')));
                    $last_day = intval($last_day);
                    $current_day = intval($date->format('d'));
                }
                $date_str = $date->format('Y-m-d');
                $day_in_week = intval(date('w', strtotime($date_str)));
                $day_in_week = $day_in_week == 0 ? 8 : ($day_in_week + 1);
                $days[] = array(
                    'date' => $date_str,
                    'day' => $date->format('d'),
                    'month' => $date->format('m'),
                    'year' => $date->format('Y'),
                    'day_in_week' => $day_in_week,
                    'work_hour' => array(),
                    'is_check' => 0
                );

                $start_date_in_month = $date->format('Y-m-d');
                $end_date_in_month = date("Y-m-t", strtotime($date->format('Y-m-d')));

                for ($i = 1; $i <= ($last_day - $current_day); $i++) {
                    $date->modify('+1 day');
                    $date_str = $date->format('Y-m-d');
                    $day_in_week = intval(date('w', strtotime($date_str)));
                    $day_in_week = $day_in_week == 0 ? 8 : ($day_in_week + 1);
                    $days[] = array(
                        'date' => $date_str,
                        'day' => $date->format('d'),
                        'month' => $date->format('m'),
                        'year' => $date->format('Y'),
                        'day_in_week' => $day_in_week,
                        'work_hour' => array(),
                        'is_check' => 0
                    );
                }

                /* get free time slot for days in month */
                global $wpdb;
                $sql = "SELECT ss_day, ss_work_hour_start, ss_work_hour_end FROM {$wpdb->prefix}apoint_services_schedule WHERE ss_enable=1 AND s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $s_work_hour = $wpdb->get_results($sql);
                if (is_countable($s_work_hour) && count($s_work_hour) == 0) {
                    return $days;
                }

                /* check day off */
                $sql = "SELECT dof_start, dof_end FROM {$wpdb->prefix}apoint_services_day_off 
                        WHERE s_id=%d AND (
                            ( dof_start >= '{$start_date_in_month}' AND dof_start <= '{$end_date_in_month}' ) OR
                            ( dof_end >= '{$start_date_in_month}' AND dof_end <= '{$end_date_in_month}' )
                         )";
                $sql = $wpdb->prepare($sql, $s_id);
                $s_day_off = $wpdb->get_results($sql);

                if(is_countable($s_day_off) && count($s_day_off) > 0){
                    $days_off = array();
                    foreach($s_day_off as $edf) {
                        $days_off[] = array(
                            'dof_start' => DateTime::createFromFormat('Y-m-d',$edf->dof_start),
                            'dof_end' => DateTime::createFromFormat('Y-m-d',$edf->dof_end)
                        );
                    }
                    $date = '';
                    for($i=0; $i< count($days); $i++){
                        $date = DateTime::createFromFormat('Y-m-d', $days[$i]['date']);
                        foreach($days_off as $df){
                            if($df['dof_start'] <= $date && $date <= $df['dof_end']){
                                $days[$i]['is_check'] = 1;
                                break;
                            }
                        }
                    }
                }

                $work_hour = array();
                foreach($s_work_hour as $ewh){
                    if(!isset($work_hour[$ewh->ss_day])){
                        $work_hour[$ewh->ss_day] = array();
                    }
                    $work_hour[$ewh->ss_day][] = array(
                        'es_work_hour_start' => $ewh->ss_work_hour_start,
                        'es_work_hour_end' => $ewh->ss_work_hour_end
                    );
                }

                $ss_day = '';
                for($i=0; $i< count($days); $i++){
                    $ss_day = $days[$i]['day_in_week'];
                    if(isset($work_hour[$ss_day]) && $days[$i]['is_check']==0){
                        $days[$i]['work_hour'] = $work_hour[$ss_day];
                    }
                    $days[$i]['is_check'] = 1;
                }


                $sql = "SELECT s_minimum_person, s_maximum_person FROM {$wpdb->prefix}apoint_services WHERE s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $se = $wpdb->get_results($sql);
                $min_cap = isset($se[0]) ? $se[0]->s_minimum_person : 0;
                $max_cap = isset($se[0]) ? $se[0]->s_maximum_person : 0;

                $sql = "SELECT b_service_id, b_garage_id, b_date, b_time, (b_time + b_service_duration + b_service_break_time) AS b_time_end, SUM(b_customer_number) AS total_person
                        FROM {$wpdb->prefix}rp_booking
                        WHERE b_process_status IN (0,1) AND b_employee_id = %d AND b_date >= %s AND b_date <= %s
                        GROUP BY b_service_id, b_garage_id, b_date, b_time";
                $sql = $wpdb->prepare($sql, $e_id, $start_date_in_month, $end_date_in_month);
                $booking = $wpdb->get_results($sql);

                return array(
                    'days' => $days,
                    'booking' => $booking,
                    'min_cap' => $min_cap,
                    'max_cap' => $max_cap
                );
            }

            return array(
                'result' => -1
            );

        }

        public function send_new_user_notifications(){
            $id = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : '';
            if($id){
                wp_send_new_user_notifications($id);
            }
        }

    }
}