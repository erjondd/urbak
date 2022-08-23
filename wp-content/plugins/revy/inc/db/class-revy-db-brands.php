<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/7/2019
 * Time: 9:10 AM
 */
if (!class_exists('Revy_DB_Brands')) {
    class Revy_DB_Brands
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_brands($active, $image_size='thumbnail')
        {
            global $wpdb;
            $sql = "SELECT rb_id, rb_name, rb_image_id, rb_device_ids, rb_create_date, rb_active FROM {$wpdb->prefix}rp_brands";
            if($active > -1){
                $sql .= " WHERE rb_active=%d ORDER BY rb_order";
                $sql = $wpdb->prepare($sql,$active);
            }else{
                $sql .= " ORDER BY rb_order";
            }
            $brands = $wpdb->get_results($sql);
            foreach ($brands as $brand) {
                $brand->rb_image_url = isset($brand->rb_image_id) ? wp_get_attachment_image_src($brand->rb_image_id, $image_size) : '';
                $brand->rb_image_url = isset($brand->rb_image_url[0]) ? $brand->rb_image_url[0] : '';
            }
            return $brands;
        }

        public function get_brand_by_id()
        {
            $rb_id = isset($_REQUEST['rb_id']) ? $_REQUEST['rb_id'] : 0;
            global $wpdb;

            $brands = array();
            if($rb_id > 0){
                $sql = "SELECT  rb_id, rb_order, rb_name, rb_image_id, rb_device_ids, rb_create_date, rb_active FROM {$wpdb->prefix}rp_brands WHERE rb_id=%d";
                $sql = $wpdb->prepare($sql, $rb_id);
                $brands = $wpdb->get_results($sql);
                $brands = is_array($brands) && count($brands) > 0 ? $brands[0] : array();
                $brands->rb_image_url = isset($brands->rb_image_id) ? wp_get_attachment_image_src($brands->rb_image_id, 'thumbnail') : '';
                $brands->rb_image_url = isset($brands->rb_image_url[0]) ? $brands->rb_image_url[0] : '';
            }else{
                $sql = "SELECT  MAX(rb_order) AS rb_order FROM {$wpdb->prefix}rp_brands";
                $max_order = $wpdb->get_results($sql);
                $brands['rb_order'] = isset($max_order[0]) && isset($max_order[0]->rb_order) ? ($max_order[0]->rb_order + 1) : 1;
                $brands['rb_active'] = 1;
            }

            $sql = "SELECT rd_id, rd_name FROM {$wpdb->prefix}rp_devices";
            $devices = $wpdb->get_results($sql);

            return array(
                'brand' => $brands,
                'devices' => $devices
            );
        }

        public function save_brand()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                global $wpdb;
                if (isset($data['rb_id']) && $data['rb_id'] != '') {
                    $result = $wpdb->update($wpdb->prefix . 'rp_brands', $data, array('rb_id' => $data['rb_id']));
                } else {
                    $data['rb_create_date'] = current_time( 'mysql', 0);
                    $result = $wpdb->insert($wpdb->prefix . 'rp_brands', $data);
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

        public function delete_brand()
        {
            $rb_id = isset($_REQUEST['rb_id']) && $_REQUEST['rb_id'] ? $_REQUEST['rb_id'] : '';
            global $wpdb;
            $sql = "DELETE FROM {$wpdb->prefix}rp_brands WHERE rb_id = %d";
            $sql = $wpdb->prepare($sql, $rb_id);
            $result = $wpdb->query($sql);
            return array(
                'result' => $result
            );
        }

    }
}