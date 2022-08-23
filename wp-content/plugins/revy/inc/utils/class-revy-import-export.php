<?php

if (!class_exists('Revy_Import_Export')) {
    class Revy_Import_Export
    {
        private static $instance = NULL;
        private $admin_notice = '';

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function import(){
            $type = isset($_POST['import_type']) && $_POST['import_type'] ? $_POST['import_type'] : '';
            if($type){
                if (!wp_verify_nonce($_POST['revy_import_nonce'], 'revy_import_nonce'))
                    return;

                $file_name = $_FILES['import_file']['name'];
                $file_name = explode('.', $file_name);
                $extension = end($file_name);

                if ($extension != 'csv') {
                    $this->admin_notice = esc_html__('Please upload a valid .csv file', 'revy');
                    add_action('revy_import_notices', array($this, 'notice_error'));
                } else {
                    $import_file = $_FILES['import_file']['tmp_name'];

                    if (empty($import_file)) {
                        wp_die(esc_html__('Please upload a file to import', 'revy'));
                    }

                    $result = $this->process_import($import_file, $type);
                    if ($result['result'] > 0) {
                        $this->admin_notice = esc_html__('Data has been imported', 'revy');
                        add_action('revy_import_notices', array($this, 'notice_success'));
                    } else {
                        $this->admin_notice = $result['message'];
                        add_action('revy_import_notices', array($this, 'notice_error'));
                    }
                }
            }
        }

        private function process_import($import_file, $type)
        {
            try {
                global $wpdb;

                $CSVfp = fopen($import_file, "r");
                if($CSVfp !== FALSE) {
                    $sql = '';
                    $s_id = 0;
                    $price_atts = $s_attr_code = '';
                    $atts = array();
                    $now = current_time('mysql', 0);

                    if( $type=='service'){
                        $setting = Revy_DB_Setting::instance();
                        $working_hour = $setting->get_working_hour_setting();
                        $schedules = array();
                        if (isset($working_hour['schedules']) && is_array($working_hour) ) {
                            $working_hour = $working_hour['schedules'];
                            foreach ($working_hour as $wh) {
                                if(isset($wh['work_hours'])){
                                    foreach ($wh['work_hours'] as $sc) {
                                        $schedules[] = array(
                                            'es_day' => $wh['es_day'],
                                            'es_enable' => $wh['es_enable'],
                                            'es_work_hour_start' => $sc['es_work_hour_start'],
                                            'es_work_hour_end' => $sc['es_work_hour_end']
                                        );
                                    }
                                }
                            }
                        }
                    }

                    while(! feof($CSVfp)) {
                        $row = fgetcsv($CSVfp, 0, ",");
                        if(!is_array($row) || $row[0]=='ID'){
                           continue;
                        }

                        if(is_array($row) && $type=='brand' && count($row)==6){
                            if($row[0]==''){
                                $sql = "INSERT INTO {$wpdb->prefix}rp_brands(rb_image_id, rb_name, rb_order, rb_device_ids, rb_active, rb_create_date) 
                                        VALUES(%d, %s, %d, %s, %d, %s)";
                                $sql= $wpdb->prepare($sql,$row[1], $row[2], $row[3], $row[4], $row[5],  $now);

                            }else{
                                $sql = "UPDATE {$wpdb->prefix}rp_brands
                                        SET rb_image_id = %d, rb_name = %s, rb_order = %d, rb_device_ids = %s, rb_active = %d
                                        WHERE rb_id = %d";
                                $sql= $wpdb->prepare($sql,$row[1], $row[2], $row[3], $row[4], $row[5],  $row[0]);
                            }
                            $wpdb->query($sql);

                        }

                        if(is_array($row) && $type=='model' && count($row)==8){
                            if($row[0]==''){
                                $sql = "INSERT INTO {$wpdb->prefix}rp_models(rm_image_id, rm_name, rm_order, rm_group, rm_device_id, rm_brand_id, rm_active, rm_create_date) 
                                        VALUES(%d, %s, %d, %s, %d, %s, %d, %s)";
                                $sql= $wpdb->prepare($sql,$row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7],  $now);

                            }else{
                                $sql = "UPDATE {$wpdb->prefix}rp_models
                                        SET rm_image_id = %d, rm_name = %s, rm_order = %d, rm_group = %s, rm_device_id = %d, rm_brand_id= %d, rm_active = %d
                                        WHERE rm_id = %d";
                                $sql= $wpdb->prepare($sql,$row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7],  $row[0]);
                            }
                            $wpdb->query($sql);

                        }

                        if(is_array($row) && $type=='service' && count($row)==14){
                            if($row[0]==''){

                                $sql = "INSERT INTO {$wpdb->prefix}rp_services(s_image_id, s_name, s_model_id, s_duration, s_break_time, s_tax,
                                                    s_maximum_slot, s_garage_ids, s_order, s_min_price, s_description, s_allow_booking_online, s_create_date) 
                                        VALUES(%d, %s, %d, %d, %d, %d, %d, %s, %d, %d, %s, %d, %s)";
                                $sql= $wpdb->prepare($sql,$row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[12], $row[13],  $now);
                                $s_id = $wpdb->query($sql);
                                $s_id = $s_id > 0 ? $wpdb->insert_id : $s_id;

                                // add service schedule
                                if (is_array($schedules) && count($schedules) > 0) {
                                    foreach ($schedules as $sc) {
                                        $wpdb->insert($wpdb->prefix . 'rp_services_schedule', array(
                                            's_id' => $s_id,
                                            'ss_day' => $sc['es_day'],
                                            'ss_work_hour_start' => isset($sc['es_work_hour_start']) ? $sc['es_work_hour_start'] : 0,
                                            'ss_work_hour_end' => isset($sc['es_work_hour_end']) ? $sc['es_work_hour_end'] : 0,
                                            'ss_enable' => $sc['es_enable'],
                                            'ss_create_date' => $now
                                        ));
                                    }
                                }

                            }else{
                                $s_id = $row[0];
                                $sql = "UPDATE {$wpdb->prefix}rp_services
                                        SET s_image_id = %d, s_name = %s, s_model_id = %d, s_duration = %d, s_break_time = %d, s_tax= %d, s_maximum_slot = %d,
                                        s_garage_ids = %s, s_order = %d, s_min_price = %d, s_description = %s, s_allow_booking_online = %d
                                        WHERE s_id = %d";
                                $sql= $wpdb->prepare($sql,$row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[12], $row[13], $s_id);
                                $wpdb->query($sql);
                            }

                            // update service price & attribute and garage
                            if($s_id){
                                //price & attributes
                                $sql = "DELETE FROM {$wpdb->prefix}rp_services_price WHERE s_id=%d";
                                $sql = $wpdb->prepare($sql, $s_id);
                                $wpdb->query($sql);

                                $price_atts = explode('|',$row[11]);
                                $atts = array();
                                foreach($price_atts as $pa){
                                    $atts = explode(':', $pa);
                                    $s_attr_code = uniqid();
                                    if(count($atts)==3){
                                        $sql = "INSERT INTO  {$wpdb->prefix}rp_services_price(s_id, s_attr_code, s_attr_title, s_attr_value, s_price)
                                                    VALUES(%d, %s, %s, %s, %d)";
                                        $sql = $wpdb->prepare($sql, $s_id, $s_attr_code, $atts[0], $atts[1], $atts[2]);
                                        $wpdb->query($sql);
                                    }
                                }

                                // garage services
                                $sql = "DELETE FROM {$wpdb->prefix}rp_services_garage WHERE s_id=%d";
                                $sql = $wpdb->prepare($sql, $s_id);
                                $wpdb->query($sql);

                                $garages = explode(',',$row[8]);
                                foreach($garages as $ga){
                                    $sql = "INSERT INTO  {$wpdb->prefix}rp_services_garage(s_id, rg_id)
                                                    VALUES(%d, %d)";
                                    $sql = $wpdb->prepare($sql, $s_id, $ga);
                                    $wpdb->query($sql);
                                }

                            }

                        }
                    }
                }
                fclose($CSVfp);

                return array(
                    'result' => 1
                );
            } catch (Exception  $err) {
                return array(
                    'result' => -1,
                    'message' => $err->getMessage()
                );

            }
        }

        public function export_services(){

        }


        function notice_error()
        {
            ?>
            <div class="notice notice-error repair-booking-notice">
                <p><?php echo esc_html($this->admin_notice); ?></p>
            </div>
            <?php
        }

        function notice_success()
        {
            ?>
            <div class="notice notice-success repair-booking-notice">
                <p><?php echo esc_html($this->admin_notice); ?></p>
            </div>
            <?php
        }
    }
}
