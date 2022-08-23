<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 6/19/2020
 * Time: 10:46 AM
 */

if (!class_exists('Revy_DB_Services')) {
    class Revy_DB_Services
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_filter_dic($order_by = 'rm_order')
        {
            global $wpdb;
            if($order_by){
                $sql = "SELECT rm_id, rm_name FROM {$wpdb->prefix}rp_models ORDER BY {$order_by} ASC";
            }else{
                $sql = "SELECT rm_id, rm_name FROM {$wpdb->prefix}rp_models ORDER BY rm_order ASC";
            }
            return $wpdb->get_results($sql);
        }

        public function get_services()
        {
            $s_name = isset($_REQUEST['s_name']) ? $_REQUEST['s_name'] : '';
            $rm_id = isset($_REQUEST['rm_id']) ? $_REQUEST['rm_id'] : 0;
            $garage_id = isset($_REQUEST['garage_id']) ? $_REQUEST['garage_id'] : 0;
            $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            global $wpdb;
            $sql = "SELECT s_id, s_name, s_garage_ids, s_break_time, s_duration, s_maximum_slot, rm_name, s_allow_booking_online
                    FROM {$wpdb->prefix}rp_services AS S
                    LEFT JOIN  {$wpdb->prefix}rp_models AS M
                    ON S.s_model_id = M.rm_id
                    WHERE ( %d = 0 OR M.rm_id = %d ) ";
            if ($s_name) {
                $sql .= " AND ( s_name LIKE '%{$s_name}%' )";
            }

            if ($garage_id) {
                $sql .= " AND ( s_id IN (SELECT s_id FROM {$wpdb->prefix}rp_services_garage WHERE rg_id = {$garage_id}) )";
            }

            $sql .= " ORDER BY s_order ASC";
            $sql = $wpdb->prepare($sql, $rm_id, $rm_id);

            $services = $wpdb->get_results($sql);
            $total = count($services);
            $db_setting = Revy_DB_Setting::instance();
            $setting = $db_setting->get_setting();

            $item_per_page = isset($setting['item_per_page']) ? $setting['item_per_page'] : 10;
            $number_of_page = $total / $item_per_page + ($total % $item_per_page > 0 ? 1 : 0);
            $page = $page > $number_of_page ? $number_of_page : $page;
            $page = ($page - 1) * $item_per_page;
            $services = array_slice($services, $page, $item_per_page);

            return array(
                'total' => $total,
                'services' => $services
            );
        }

        public function get_services_hierarchy()
        {
            global $wpdb;
            $sql = "SELECT s_id, s_name, M.rm_id, M.rm_name
                                          FROM {$wpdb->prefix}rp_services AS S
                    LEFT JOIN  {$wpdb->prefix}rp_models AS M
                    ON S.s_model_id = M.rm_id 
                    ORDER BY s_order ASC";

            $services = $wpdb->get_results($sql);
            $service_hierarchy = array();
            foreach ($services as $service) {
                if (!isset($service_hierarchy[$service->rm_id])) {
                    $service_hierarchy[$service->rm_id] = array();
                }
                $service_hierarchy[$service->rm_id][] = $service;
            }
            return $service_hierarchy;
        }

        public function get_services_dic()
        {
            global $wpdb;
            $sql = "SELECT s_id, CONCAT(s_name,' - ',M.rm_name) AS s_name  
                    FROM {$wpdb->prefix}rp_services AS S
                    LEFT JOIN  {$wpdb->prefix}rp_models AS M
                    ON S.s_model_id = M.rm_id
                    ORDER BY s_order ASC";
            return $wpdb->get_results($sql);
        }

        public function get_service_attributes()
        {
            global $wpdb;
            $sql = "SELECT sp_id, s_id, s_attr_code, s_attr_title, s_attr_value, s_price
                    FROM {$wpdb->prefix}rp_services_price";
            return $wpdb->get_results($sql);
        }

        public function get_services_filter($garage_id = 0, $image_size = 'thumbnail')
        {
            global $wpdb;

            $sql = "SELECT s_id, s_attr_code, s_attr_title, s_attr_value, s_price
                    FROM {$wpdb->prefix}rp_services_price";
            $posts = $wpdb->get_results($sql);
            $attributes = array();
            foreach ($posts as $p) {
                if (!isset($attributes[$p->s_id])) {
                    $attributes[$p->s_id] = [];
                }
                $attributes[$p->s_id][] = $p;
            }

            if ($garage_id == 0) {
                $sql = "SELECT s_id, s_image_id, s_name, s_model_id, s_tax, s_min_price, s_duration, s_break_time, s_maximum_slot, s_garage_ids, s_description
                    FROM {$wpdb->prefix}rp_services AS S
                    WHERE s_allow_booking_online = 1
                    ORDER BY s_order ASC";
                $services = $wpdb->get_results($sql);
            } else {
                $sql = "SELECT s_id, s_image_id, s_name, s_description, s_model_id, s_tax, s_min_price, s_duration, s_break_time, s_maximum_slot, {$garage_id} as s_garage_ids
                    FROM {$wpdb->prefix}rp_services 
                    WHERE s_allow_booking_online = 1
                    AND s_id IN (SELECT s_id FROM {$wpdb->prefix}rp_services_garage WHERE rg_id = %d )
                    ORDER BY s_order ASC";
                $sql = $wpdb->prepare($sql, $garage_id);
                $services = $wpdb->get_results($sql);
            }

            foreach ($services as $sv) {
                $sv->s_image_url = isset($sv->s_image_id) ? wp_get_attachment_image_src($sv->s_image_id, $image_size) : '';
                $sv->s_image_url = isset($sv->s_image_url[0]) ? $sv->s_image_url[0] : '';
                $sv->attrs = isset($attributes[$sv->s_id]) ? $attributes[$sv->s_id] : [];
                $sv->atts_length = count($sv->attrs);
                $sv->s_item_class = $sv->atts_length == 0 ? 'no-attribute' : '';
                $sv->s_item_class = $sv->atts_length == 1 ? 'one-attribute' : $sv->s_item_class;
            }

            return $services;
        }

        public function get_services_drop($garage_id = 0)
        {
            global $wpdb;
            if($garage_id==0){
                $sql = "SELECT s_id, s_image_id, s_name, s_model_id, s_tax, s_min_price, s_duration, s_break_time, s_maximum_slot, s_garage_ids
                    FROM {$wpdb->prefix}rp_services 
                    WHERE s_allow_booking_online = 1
                    ORDER BY s_order ASC";
            }else{
                $sql = "SELECT s_id, s_image_id, s_name, s_model_id, s_tax, s_min_price, s_duration, s_break_time, s_maximum_slot,{$garage_id} as s_garage_ids
                    FROM {$wpdb->prefix}rp_services 
                    WHERE s_allow_booking_online = 1 AND s_id IN (SELECT s_id FROM {$wpdb->prefix}rp_services_garage WHERE rg_id = {$garage_id})
                    ORDER BY s_order ASC";
            }

            return $wpdb->get_results($sql);
        }

        public function get_services_by_brand($brand_id, $garage_id = 0, $model_id = 0, $image_size = 'thumbnail')
        {
            global $wpdb;

            if ($garage_id == 0) {
                $sql = "SELECT s_id, s_image_id, s_name, s_model_id, s_tax, s_duration, s_break_time, s_maximum_slot, s_garage_ids
                        FROM {$wpdb->prefix}rp_services AS RS
                        LEFT JOIN {$wpdb->prefix}rp_models AS RM
                        ON RS.s_model_id = RM.rm_id
                        WHERE s_allow_booking_online = 1 AND RM.rm_brand_id=%d AND (0=%d OR s_model_id = %d)
                        ORDER BY s_order ASC";
                $sql = $wpdb->prepare($sql, $brand_id, $model_id, $model_id);
            } else {
                $sql = "SELECT s_id, s_image_id, s_name, s_model_id, s_tax, s_duration, s_break_time, s_maximum_slot, {$garage_id} as s_garage_ids
                        FROM {$wpdb->prefix}rp_services AS RS
                        LEFT JOIN {$wpdb->prefix}rp_models AS RM
                        ON RS.s_model_id = RM.rm_id
                        WHERE s_allow_booking_online = 1 AND RM.rm_brand_id=%d AND (0=%d OR s_model_id = %d)
                        AND (0=%d OR s_id IN (SELECT s_id FROM {$wpdb->prefix}rp_services_garage WHERE rg_id=%d) )
                        ORDER BY s_order ASC";
                $sql = $wpdb->prepare($sql, $brand_id, $model_id, $model_id, $garage_id, $garage_id);
            }

            $services = $wpdb->get_results($sql);
            $s_ids = [];
            foreach ($services as $sv) {
                $sv->s_image_url = isset($sv->s_image_id) ? wp_get_attachment_image_src($sv->s_image_id, $image_size) : '';
                $sv->s_image_url = isset($sv->s_image_url[0]) ? $sv->s_image_url[0] : '';
                $s_ids[] = $sv->s_id;
            }

            $sql = "SELECT s_id, s_attr_code, s_attr_title, s_attr_value, s_price
                    FROM {$wpdb->prefix}rp_services_price
                    WHERE s_id IN (" . implode(',', $s_ids) . ")";
            $posts = $wpdb->get_results($sql);
            $attributes = array();
            foreach ($posts as $p) {
                if (!isset($attributes[$p->s_id])) {
                    $attributes[$p->s_id] = [];
                }
                $attributes[$p->s_id][] = $p;
            }

            return array(
                'services' => $services,
                'attributes' => $attributes
            );
        }

        public function get_service_by_id()
        {
            $s_id = isset($_REQUEST['s_id']) ? $_REQUEST['s_id'] : 0;
            global $wpdb;
            $result = array();

            $services = array();
            if ($s_id) {
                $sql = "SELECT s_id, s_order, s_image_id, s_name, s_model_id, s_description, s_garage_ids, s_tax,s_duration, s_break_time, s_maximum_slot,  s_allow_booking_online 
                    FROM {$wpdb->prefix}rp_services 
                    WHERE s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $services = $wpdb->get_results($sql);
            }

            if (count($services) > 0) {
                $services = $services[0];
                $services->s_image_url = isset($services->s_image_id) ? wp_get_attachment_image_src($services->s_image_id, 'thumbnail') : '';
                $services->s_image_url = isset($services->s_image_url[0]) ? $services->s_image_url[0] : '';
                $services->s_tax = $services->s_tax ? intval($services->s_tax) : 0;
            } else {
                $sql = "SELECT MAX(s_order) as s_order FROM {$wpdb->prefix}rp_services";
                $max_order = $wpdb->get_results($sql);
                $services = array(
                    's_order' => isset($max_order[0]) && isset($max_order[0]->s_order) ? ($max_order[0]->s_order + 1) : 1,
                    's_image_id' => 0,
                    's_maximum_slot' => 1,
                    's_allow_booking_online' => 1
                );
            }

            $sql = "SELECT rm_id, rm_name FROM {$wpdb->prefix}rp_models";
            $result['models'] = $wpdb->get_results($sql);

            $attributes = array();
            $schedule = array();
            $day_off = array();

            if ($s_id) {
                $sql = "SELECT s_attr_title, s_attr_value, s_price FROM {$wpdb->prefix}rp_services_price WHERE s_id = %d ";
                $sql = $wpdb->prepare($sql, $s_id);
                $attributes = $wpdb->get_results($sql);

                $sql = "SELECT ss_day, ss_enable, ss_work_hour_end, ss_work_hour_start FROM {$wpdb->prefix}rp_services_schedule WHERE s_id = %d ";
                $sql = $wpdb->prepare($sql, $s_id);
                $schedule = $wpdb->get_results($sql);

                $sql = "SELECT dof_name, dof_start, dof_end FROM {$wpdb->prefix}rp_services_day_off WHERE s_id = %d ";
                $sql = $wpdb->prepare($sql, $s_id);
                $day_off = $wpdb->get_results($sql);
            } else {
                //set schedule default base on working hour
                $setting = Revy_DB_Setting::instance();
                $working_hour = $setting->get_working_hour_setting();
                if (isset($working_hour['schedules']) && is_array($working_hour) ) {
                    $working_hour = $working_hour['schedules'];
                    foreach ($working_hour as $wh) {
                        if(isset($wh['work_hours'])){
                            foreach ($wh['work_hours'] as $sc) {
                                $schedule[] = array(
                                    'ss_day' => $wh['es_day'],
                                    'ss_enable' => $wh['es_enable'],
                                    'ss_work_hour_start' => $sc['es_work_hour_start'],
                                    'ss_work_hour_end' => $sc['es_work_hour_end']
                                );
                            }
                        }
                    }
                }
            }

            return array(
                'services' => $services,
                'day_off' => $day_off,
                'schedules' => $schedule,
                'attributes' => $attributes
            );
        }

        public function save_service()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            $services = isset($data['services']) ? $data['services'] : '';
            $upd_e = isset($_REQUEST['upd_e']) ? $_REQUEST['upd_e'] : 0;
            if ($data != '' && is_array($data) && $services) {
                global $wpdb;
                $schedules = isset($data['schedules']) ? $data['schedules'] : '';
                $day_off = isset($data['day_off']) ? $data['day_off'] : '';
                $attributes = isset($data['attributes']) ? $data['attributes'] : '';
                $create_date = current_time('mysql', 0);

                $s_id = 0;
                if (isset($data['s_id']) && $data['s_id']) {
                    $s_id = $data['s_id'];
                    $result = $wpdb->update($wpdb->prefix . 'rp_services', $services, array('s_id' => $data['s_id']));
                } else {
                    $services['s_create_date'] = $create_date;
                    $result = $wpdb->insert($wpdb->prefix . 'rp_services', $services);
                    $result = $result > 0 ? $wpdb->insert_id : $result;
                    $s_id = $result;
                }
                if ($s_id) {
                    //update service garage
                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_garage WHERE s_id=%d";
                    $sql = $wpdb->prepare($sql, $s_id);
                    $wpdb->query($sql);
                    $garages = $services['s_garage_ids'] ? explode(',', $services['s_garage_ids']) : array();
                    if (count($garages) > 0) {
                        foreach ($garages as $gr) {
                            $wpdb->insert($wpdb->prefix . 'rp_services_garage', array(
                                's_id' => $s_id,
                                'rg_id' => $gr
                            ));
                        }
                    }

                    //update services day off
                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_day_off WHERE s_id=%d";
                    $sql = $wpdb->prepare($sql, $s_id);
                    $wpdb->query($sql);
                    if (is_array($day_off) && count($day_off) > 0) {
                        foreach ($day_off as $dof) {
                            $wpdb->insert($wpdb->prefix . 'rp_services_day_off', array(
                                's_id' => $s_id,
                                'dof_name' => $dof['dof_name'],
                                'dof_start' => $dof['dof_start'],
                                'dof_end' => $dof['dof_end'],
                                'dof_create_date' => $create_date
                            ));
                        }
                    }

                    // update service schedule
                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_schedule WHERE s_id=%d";
                    $sql = $wpdb->prepare($sql, $s_id);
                    $wpdb->query($sql);
                    if (is_array($schedules) && count($schedules) > 0) {
                        foreach ($schedules as $sc) {
                            $wpdb->insert($wpdb->prefix . 'rp_services_schedule', array(
                                's_id' => $s_id,
                                'ss_day' => $sc['es_day'],
                                'ss_work_hour_start' => isset($sc['es_work_hour_start']) ? $sc['es_work_hour_start'] : 0,
                                'ss_work_hour_end' => isset($sc['es_work_hour_end']) ? $sc['es_work_hour_end'] : 0,
                                'ss_enable' => $sc['es_enable'],
                                'ss_create_date' => $create_date
                            ));
                        }
                    }

                    // update service price
                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_price WHERE s_id=%d";
                    $sql = $wpdb->prepare($sql, $s_id);
                    $wpdb->query($sql);
                    if (is_array($attributes) && count($attributes) > 0) {
                        $s_attr_code = '';
                        foreach ($attributes as $attr) {
                            $s_attr_code = uniqid();
                            $wpdb->insert($wpdb->prefix . 'rp_services_price', array(
                                's_id' => $s_id,
                                's_attr_code' => $s_attr_code,
                                's_attr_title' => $attr['s_attr_title'],
                                's_attr_value' => $attr['s_attr_value'],
                                's_price' => $attr['s_price']
                            ));
                        }
                    }

                }
                do_action('revy_after_save_service', $s_id, $data);

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

        public function delete_service()
        {
            $s_ids = isset($_REQUEST['s_ids']) && $_REQUEST['s_ids'] ? $_REQUEST['s_ids'] : '';
            if ($s_ids ) {
                global $wpdb;
                $s_ids = implode(',',$s_ids);
                $sql = "SELECT RB.b_id 
                        FROM {$wpdb->prefix}rp_booking as RB
                        INNER JOIN {$wpdb->prefix}rp_booking_detail as RBD
                        ON RB.b_id = RBD.b_id
                        WHERE RBD.b_service_id IN ({$s_ids}) AND b_process_status != %d";
                $status = -1;
                $booking = $wpdb->get_results($sql, $status);
             
                if(is_array($booking) && count($booking) > 0){
                    return array(
                        'result' => -1,
                        'message' => esc_html__('You can\'t delete service because an appointment has been made for this service. \r Please uncheck Publish to frontend ', 'revy')
                    );
                }

                $sql = "DELETE FROM {$wpdb->prefix}rp_services WHERE 1=%d AND s_id IN ({$s_ids}) ";
                $sql = $wpdb->prepare($sql, 1);
                $result = $wpdb->query($sql);
                if ($result) {

                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_day_off WHERE 1=%d AND s_id IN ({$s_ids}) ";
                    $sql = $wpdb->prepare($sql, 1);
                    $wpdb->query($sql);

                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_location WHERE 1=%d AND s_id IN ({$s_ids}) ";
                    $sql = $wpdb->prepare($sql, 1);
                    $wpdb->query($sql);

                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_price WHERE 1=%d AND s_id IN ({$s_ids}) ";
                    $sql = $wpdb->prepare($sql, 1);
                    $wpdb->query($sql);

                    $sql = "DELETE FROM {$wpdb->prefix}rp_services_schedule WHERE 1=%d AND s_id IN ({$s_ids}) ";
                    $sql = $wpdb->prepare($sql, 1);
                    $wpdb->query($sql);

                }
                return array(
                    'result' => 1//$result,
                );

            }
            return array(
                'result' => -1,
                'message' => esc_html__('Data is invalid', 'revy')
            );
        }

        public function get_time_slot_monthly()
        {
            $s_ids = isset($_REQUEST['s_ids']) ? $_REQUEST['s_ids'] : '';
            $garage_id = isset($_REQUEST['garage_id']) ? $_REQUEST['garage_id'] : 0;
            $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

            $time_slots = array();
            $slot = array();
            if ($s_ids  && $date) {
                foreach ($s_ids as $s_id) {
                    $slot = $this->get_time_slot_monthly_by_s_id($s_id, $garage_id, $date);
                    if (isset($slot['result']) && $slot['result'] == -1) {
                        return array(
                            'result' => -1
                        );
                    } else {
                        $time_slots[$s_id] = $slot;
                    }
                }
                return $time_slots;
            }

            return array(
                'result' => -1
            );

        }

        public function get_time_slot_weekly()
        {
            $s_ids = isset($_REQUEST['s_ids']) ? $_REQUEST['s_ids'] : '';
            $garage_id = isset($_REQUEST['garage_id']) ? $_REQUEST['garage_id'] : 0;
            $date_start = isset($_REQUEST['date_start']) ? $_REQUEST['date_start'] : '';
            $date_end = isset($_REQUEST['date_end']) ? $_REQUEST['date_end'] : '';

            $time_slots = array();
            $slot = array();
            if ($s_ids && $garage_id && $date_start && $date_end) {
                foreach ($s_ids as $s_id) {
                    $slot = $this->get_time_slot_weekly_by_s_id($s_id, $garage_id, $date_start,  $date_end);
                    if (isset($slot['result']) && $slot['result'] == -1) {
                        return array(
                            'result' => -1
                        );
                    } else {
                        $time_slots[$s_id] = $slot;
                    }
                }
                return $time_slots;
            }

            return array(
                'result' => -1
            );

        }

        public function get_time_slot_daily()
        {
            $s_ids = isset($_REQUEST['s_ids']) ? $_REQUEST['s_ids'] : '';
            $garage_id = isset($_REQUEST['garage_id']) ? $_REQUEST['garage_id'] : 0;
            $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

            $time_slots = array();
            $slot = array();
            if ($s_ids && $garage_id && $date) {
                foreach ($s_ids as $s_id) {
                    $slot = $this->get_time_slot_day_by_s_id($s_id, $garage_id, $date);
                    if (isset($slot['result']) && $slot['result'] == -1) {
                        return array(
                            'result' => -1
                        );
                    } else {
                        $time_slots[$s_id] = $slot;
                    }
                }
                return $time_slots;
            }

            return array(
                'result' => -1
            );
        }

        public function get_time_slot_day_by_s_id($s_id, $garage_id, $date)
        {
            if ($s_id && $garage_id && $date) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
                $now = current_time('mysql', 0);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $days = array();

                /** get days in month */
                if ($date <= $now) {
                    return array();
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

                /* get free time slot for days in month */
                global $wpdb;
                $sql = "SELECT ss_day, ss_work_hour_start, ss_work_hour_end FROM {$wpdb->prefix}rp_services_schedule WHERE ss_enable=1 AND s_id=%d AND ss_day=%d";
                $sql = $wpdb->prepare($sql, $s_id, $day_in_week);
                $s_work_hour = $wpdb->get_results($sql);
                if (is_countable($s_work_hour) && count($s_work_hour) == 0) {
                    return $days;
                }

                /* check day off */
                $sql = "SELECT dof_start, dof_end FROM {$wpdb->prefix}rp_services_day_off 
                        WHERE s_id=%d AND ( dof_start <=%s AND %s <= dof_end)";
                $sql = $wpdb->prepare($sql, $s_id, $date_str);
                $s_day_off = $wpdb->get_results($sql);

                if (is_countable($s_day_off) && count($s_day_off) > 0) {
                    $days_off = array();
                    foreach ($s_day_off as $edf) {
                        $days_off[] = array(
                            'dof_start' => DateTime::createFromFormat('Y-m-d', $edf->dof_start),
                            'dof_end' => DateTime::createFromFormat('Y-m-d', $edf->dof_end)
                        );
                    }
                    $date = '';
                    for ($i = 0; $i < count($days); $i++) {
                        $date = DateTime::createFromFormat('Y-m-d', $days[$i]['date']);
                        foreach ($days_off as $df) {
                            if ($df['dof_start'] <= $date && $date <= $df['dof_end']) {
                                $days[$i]['is_check'] = 1;
                                break;
                            }
                        }
                    }
                }

                $work_hour = array();
                foreach ($s_work_hour as $ewh) {
                    if (!isset($work_hour[$ewh->ss_day])) {
                        $work_hour[$ewh->ss_day] = array();
                    }
                    $work_hour[$ewh->ss_day][] = array(
                        'es_work_hour_start' => $ewh->ss_work_hour_start,
                        'es_work_hour_end' => $ewh->ss_work_hour_end
                    );
                }

                $ss_day = '';
                for ($i = 0; $i < count($days); $i++) {
                    $ss_day = $days[$i]['day_in_week'];
                    if (isset($work_hour[$ss_day]) && $days[$i]['is_check'] == 0) {
                        $days[$i]['work_hour'] = $work_hour[$ss_day];
                    }
                    $days[$i]['is_check'] = 1;
                }


                $sql = "SELECT s_maximum_slot FROM {$wpdb->prefix}rp_services WHERE s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $se = $wpdb->get_results($sql);
                $min_cap = 1;
                $max_cap = isset($se[0]) ? $se[0]->s_maximum_slot : 1;

                $sql = "SELECT b_service_id, b_garage_id, b_date, b_time, (b_time + b_service_duration + b_service_break_time) AS b_time_end, SUM(b_quantity) AS total_device
                        FROM {$wpdb->prefix}rp_booking AS RB
                        LEFT JOIN {$wpdb->prefix}rp_booking_detail AS RBD
                        ON RB.b_id = RBD.b_id
                        WHERE  b_delivery_method = 2 AND b_process_status IN (0,1) AND b_garage_id=%d AND b_date = %s
                        GROUP BY b_service_id, b_garage_id, b_date, b_time";
                $sql = $wpdb->prepare($sql, $garage_id, $date_str);
                $booking = $wpdb->get_results($sql);

                return array(
                    'days' => $days,
                    'booking' => $booking,
                    'min_cap' => $min_cap,
                    'max_cap' => $max_cap
                );
            }
        }

        public function get_time_slot_weekly_by_s_id($s_id, $garage_id, $date_start, $date_end)
        {
            if ($s_id && $garage_id && $date_start && $date_end) {
                $total_days = Revy_Utils::getDateDiffInDays($date_start, $date_end);
                if($total_days > 7){
                    return [];
                }
                $date_start = DateTime::createFromFormat('Y-m-d H:i:s', $date_start . ' 00:00:00');

                $now = current_time('mysql', 0);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $days = array();

                $start_date_in_week = $date_start->format('Y-m-d');
                $end_date_in_week = $date_end;

                $date_str = $date_start->format('Y-m-d');
                $day_in_week = intval(date('w', strtotime($date_str)));
                $day_in_week = $day_in_week == 0 ? 8 : ($day_in_week + 1);
                $days[] = array(
                    'date' => $date_str,
                    'day' => $date_start->format('d'),
                    'day_in_week' => $day_in_week,
                    'work_hour' => array(),
                    'is_check' => 0
                );

                for ($i = 1; $i <= $total_days; $i++) {
                    $date_start->modify('+1 day');
                    $date_str = $date_start->format('Y-m-d');
                    $day_in_week = intval(date('w', strtotime($date_str)));
                    $day_in_week = $day_in_week == 0 ? 8 : ($day_in_week + 1);
                    $days[] = array(
                        'date' => $date_str,
                        'day' => $date_start->format('d'),
                        'day_in_week' => $day_in_week,
                        'work_hour' => array(),
                        'is_check' => 0
                    );
                }

                /* get free time slot for days in month */
                global $wpdb;
                $sql = "SELECT ss_day, ss_work_hour_start, ss_work_hour_end FROM {$wpdb->prefix}rp_services_schedule WHERE ss_enable=1 AND s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $s_work_hour = $wpdb->get_results($sql);
                if (is_countable($s_work_hour) && count($s_work_hour) == 0) {
                    return $days;
                }

                /* check day off */
                $sql = "SELECT dof_start, dof_end FROM {$wpdb->prefix}rp_services_day_off 
                        WHERE s_id=%d AND (
                            ( dof_start >= '{$start_date_in_week}' AND dof_start <= '{$end_date_in_week}' ) OR
                            ( dof_end >= '{$start_date_in_week}' AND dof_end <= '{$end_date_in_week}' )
                         )";
                $sql = $wpdb->prepare($sql, $s_id);
                $s_day_off = $wpdb->get_results($sql);

                if (is_countable($s_day_off) && count($s_day_off) > 0) {
                    $days_off = array();
                    foreach ($s_day_off as $edf) {
                        $days_off[] = array(
                            'dof_start' => DateTime::createFromFormat('Y-m-d', $edf->dof_start),
                            'dof_end' => DateTime::createFromFormat('Y-m-d', $edf->dof_end)
                        );
                    }
                    $date = '';
                    for ($i = 0; $i < count($days); $i++) {
                        $date = DateTime::createFromFormat('Y-m-d', $days[$i]['date']);
                        foreach ($days_off as $df) {
                            if ($df['dof_start'] <= $date && $date <= $df['dof_end']) {
                                $days[$i]['is_check'] = 1;
                                break;
                            }
                        }
                    }
                }

                $work_hour = array();
                foreach ($s_work_hour as $ewh) {
                    if (!isset($work_hour[$ewh->ss_day])) {
                        $work_hour[$ewh->ss_day] = array();
                    }
                    $work_hour[$ewh->ss_day][] = array(
                        'es_work_hour_start' => $ewh->ss_work_hour_start,
                        'es_work_hour_end' => $ewh->ss_work_hour_end
                    );
                }

                $ss_day = '';
                for ($i = 0; $i < count($days); $i++) {
                    $ss_day = $days[$i]['day_in_week'];
                    if (isset($work_hour[$ss_day]) && $days[$i]['is_check'] == 0) {
                        $days[$i]['work_hour'] = $work_hour[$ss_day];
                    }
                    $days[$i]['is_check'] = 1;
                }


                $sql = "SELECT s_maximum_slot FROM {$wpdb->prefix}rp_services WHERE s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $se = $wpdb->get_results($sql);
                $min_cap = 1;
                $max_cap = isset($se[0]) ? $se[0]->s_maximum_slot : 1;

                $sql = "SELECT b_service_id, b_garage_id, b_date, b_time, (b_time + b_service_duration + b_service_break_time) AS b_time_end, SUM(b_quantity) AS total_device
                        FROM {$wpdb->prefix}rp_booking AS RB
                        LEFT JOIN {$wpdb->prefix}rp_booking_detail AS RBD
                        ON RB.b_id = RBD.b_id
                        WHERE b_delivery_method = 2 AND b_process_status IN (0,1) AND b_garage_id=%d AND b_date >= %s AND b_date <= %s 
                        GROUP BY b_service_id, b_garage_id, b_date, b_time";
                $sql = $wpdb->prepare($sql, $garage_id, $start_date_in_week, $end_date_in_week);
                $booking = $wpdb->get_results($sql);

                return array(
                    'days' => $days,
                    'booking' => $booking,
                    'min_cap' => $min_cap,
                    'max_cap' => $max_cap
                );
            }
        }

        public function get_time_slot_monthly_by_s_id($s_id, $garage_id, $date)
        {
            if ($s_id && $date) {
                $last_day = date("t", strtotime($date));
                $last_day = intval($last_day);
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');

                $current_day = intval($date->format('d'));
                $now = current_time('mysql', 0);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $days = array();

                /** get days in month */
                if ($date <= $now || ($date->format('m') == $now->format('m') && $date->format('Y') == $now->format('Y'))) {
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
                $sql = "SELECT ss_day, ss_work_hour_start, ss_work_hour_end FROM {$wpdb->prefix}rp_services_schedule WHERE ss_enable=1 AND s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $s_work_hour = $wpdb->get_results($sql);
                if (is_countable($s_work_hour) && count($s_work_hour) == 0) {
                    return $days;
                }

                /* check day off */
                $sql = "SELECT dof_start, dof_end FROM {$wpdb->prefix}rp_services_day_off 
                        WHERE s_id=%d AND (
                            ( dof_start >= '{$start_date_in_month}' AND dof_start <= '{$end_date_in_month}' ) OR
                            ( dof_end >= '{$start_date_in_month}' AND dof_end <= '{$end_date_in_month}' )
                         )";
                $sql = $wpdb->prepare($sql, $s_id);
                $s_day_off = $wpdb->get_results($sql);

                if (is_countable($s_day_off) && count($s_day_off) > 0) {
                    $days_off = array();
                    foreach ($s_day_off as $edf) {
                        $days_off[] = array(
                            'dof_start' => DateTime::createFromFormat('Y-m-d', $edf->dof_start),
                            'dof_end' => DateTime::createFromFormat('Y-m-d', $edf->dof_end)
                        );
                    }
                    $date = '';
                    for ($i = 0; $i < count($days); $i++) {
                        $date = DateTime::createFromFormat('Y-m-d', $days[$i]['date']);
                        foreach ($days_off as $df) {
                            if ($df['dof_start'] <= $date && $date <= $df['dof_end']) {
                                $days[$i]['is_check'] = 1;
                                break;
                            }
                        }
                    }
                }

                $work_hour = array();
                foreach ($s_work_hour as $ewh) {
                    if (!isset($work_hour[$ewh->ss_day])) {
                        $work_hour[$ewh->ss_day] = array();
                    }
                    $work_hour[$ewh->ss_day][] = array(
                        'es_work_hour_start' => $ewh->ss_work_hour_start,
                        'es_work_hour_end' => $ewh->ss_work_hour_end
                    );
                }

                $ss_day = '';
                for ($i = 0; $i < count($days); $i++) {
                    $ss_day = $days[$i]['day_in_week'];
                    if (isset($work_hour[$ss_day]) && $days[$i]['is_check'] == 0) {
                        $days[$i]['work_hour'] = $work_hour[$ss_day];
                    }
                    $days[$i]['is_check'] = 1;
                }


                $sql = "SELECT s_maximum_slot 
                        FROM {$wpdb->prefix}rp_services AS RS
                        LEFT JOIN  {$wpdb->prefix}rp_services_garage AS RGS
                        ON RS.s_id = RGS.s_id
                        WHERE RS.s_id = %d AND RGS.rg_id = %d";
                $sql = $wpdb->prepare($sql, $s_id, $garage_id);
                $se = $wpdb->get_results($sql);
                if(is_array($se) && count($se)>0){
                    $min_cap = 1;
                    $max_cap = isset($se[0]) ? $se[0]->s_maximum_slot : 1;
                }else{
                    $min_cap = 0;
                    $max_cap = 0;
                }

                if($garage_id > 0){
                    $sql = "SELECT b_service_id, b_garage_id, b_date, b_time, (b_time + b_service_duration + b_service_break_time) AS b_time_end, SUM(b_quantity) AS total_device
                        FROM {$wpdb->prefix}rp_booking AS RB
                        LEFT JOIN {$wpdb->prefix}rp_booking_detail AS RBD
                        ON RB.b_id = RBD.b_id
                        WHERE b_delivery_method = 2 AND b_process_status IN (0,1) AND b_garage_id=%d AND b_date >= %s AND b_date <= %s
                        GROUP BY b_service_id, b_garage_id, b_date, b_time";
                    $sql = $wpdb->prepare($sql, $garage_id, $start_date_in_month, $end_date_in_month);
                    $booking = $wpdb->get_results($sql);
                }else{
                    $booking = array();
                }

                return array(
                    'days' => $days,
                    'booking' => $booking,
                    'min_cap' => $min_cap,
                    'max_cap' => $max_cap
                );
            }
        }

        public function get_time_slot_by_date_ranger()
        {
            $s_id = isset($_REQUEST['s_id']) ? $_REQUEST['s_id'] : '';
            $garage_id = isset($_REQUEST['garage_id']) ? $_REQUEST['garage_id'] : 0;
            $date_start = isset($_REQUEST['date_start']) ? $_REQUEST['date_start'] : '';
            $date_end = isset($_REQUEST['date_end']) ? $_REQUEST['date_end'] : '';

            if ($s_id && $garage_id && $date_start && $date_end) {
                global $wpdb;

                /* get day off */
                $sql = "SELECT dof_start, dof_end FROM {$wpdb->prefix}rp_services_day_off 
                        WHERE s_id=%d ";
                $sql = $wpdb->prepare($sql, $s_id, $date_start, $date_end);
                $s_day_off = $wpdb->get_results($sql);

                /* get schedule */
                $sql = "SELECT ss_day, ss_work_hour_start, ss_work_hour_end FROM {$wpdb->prefix}rp_services_schedule WHERE ss_enable=1 AND s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $s_work_hour = $wpdb->get_results($sql);

                $sql = "SELECT s_maximum_slot FROM {$wpdb->prefix}rp_services WHERE s_id=%d";
                $sql = $wpdb->prepare($sql, $s_id);
                $se = $wpdb->get_results($sql);
                $min_cap = 1;
                $max_cap = isset($se[0]) ? $se[0]->s_maximum_slot : 1;

                $sql = "SELECT RDB.b_service_id, RB.b_garage_id, RB.b_date, RB.b_time, (RB.b_time + RDB.b_service_duration + RDB.b_service_break_time) AS b_time_end, SUM(RDB.b_quantity) AS total_device
                        FROM {$wpdb->prefix}rp_booking AS RB
                        INNER JOIN {$wpdb->prefix}rp_booking_detail AS RDB
                        ON RB.b_id = RDB.b_id
                        WHERE  b_delivery_method = 2 AND b_process_status IN (0,1) AND b_garage_id=%d AND b_date >= %s AND b_date <= %s
                        GROUP BY b_service_id, b_garage_id, b_date, b_time";
                $sql = $wpdb->prepare($sql, $garage_id, $date_start, $date_end);
                $booking = $wpdb->get_results($sql);

                return array(
                    'schedule' => $s_work_hour,
                    'day_off' => $s_day_off,
                    'booking' => $booking,
                    'min_cap' => $min_cap,
                    'max_cap' => $max_cap
                );
            }

            return array(
                'result' => -1
            );
        }
    }
}