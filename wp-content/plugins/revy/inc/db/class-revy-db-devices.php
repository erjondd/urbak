<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/7/2019
 * Time: 9:10 AM
 */
if (!class_exists('Revy_DB_Devices')) {
    class Revy_DB_Devices
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_devices($active)
        {
            global $wpdb;
            $sql = "SELECT rd_id, rd_order, rd_name, rd_image_id, rd_icon, rd_create_date, rd_active FROM {$wpdb->prefix}rp_devices";
            if($active > -1){
                $sql .= " WHERE rd_active=%d ORDER BY rd_order ASC";
                $sql = $wpdb->prepare($sql,$active);
            }else{
                $sql .= " ORDER BY rd_order ASC";
            }
            $devices = $wpdb->get_results($sql);
            foreach ($devices as $dv) {
                $dv->rd_image_url = isset($dv->rd_image_id) ? wp_get_attachment_image_src($dv->rd_image_id, 'thumbnail') : '';
                $dv->rd_image_url = isset($dv->rd_image_url[0]) ? $dv->rd_image_url[0] : '';
            }
            return $devices;
        }

        public function get_device_by_id()
        {
            $rd_id = isset($_REQUEST['rd_id']) ? $_REQUEST['rd_id'] : 0;
            global $wpdb;
            $sql = "SELECT rd_id, rd_order, rd_image_id, rd_name, rd_active FROM {$wpdb->prefix}rp_devices WHERE rd_id=%d";
            $sql = $wpdb->prepare($sql, $rd_id);
            $devices = $wpdb->get_results($sql);
            $devices = is_array($devices) && count($devices) > 0 ? $devices[0] : array();
            $devices->rd_image_url = isset($devices->rd_image_id) ? wp_get_attachment_image_src($devices->rd_image_id, 'thumbnail') : '';
            $devices->rd_image_url = isset($devices->rd_image_url[0]) ? $devices->rd_image_url[0] : '';
            return $devices;
        }

        public function save_device()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                global $wpdb;
                if (isset($data['rd_id']) && $data['rd_id'] != '') {
                    $result = $wpdb->update($wpdb->prefix . 'rp_devices', $data, array('rd_id' => $data['rd_id']));
                } else {
                    $data['rd_create_date'] = current_time( 'mysql', 0);
                    $result = $wpdb->insert($wpdb->prefix . 'rp_devices', $data);
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

        public function delete_device()
        {
            $rd_id = isset($_REQUEST['rd_id']) && $_REQUEST['rd_id'] ? $_REQUEST['rd_id'] : '';
            global $wpdb;
            $sql = "DELETE FROM {$wpdb->prefix}rp_devices WHERE rd_id = %d";
            $sql = $wpdb->prepare($sql, $rd_id);
            $result = $wpdb->query($sql);
            return array(
                'result' => $result
            );
        }

    }
}