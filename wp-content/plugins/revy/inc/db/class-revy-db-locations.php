<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 6/20/2020
 * Time: 3:15 PM
 */

if (!class_exists('Revy_DB_Locations')) {
    class Revy_DB_Locations
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_locations($img_size='thumbnail')
        {
            global $wpdb;
            $loc_name = isset($_REQUEST['loc_name']) ? $_REQUEST['loc_name'] : '';

            $sql = "SELECT loc_id, loc_image_id, loc_name, loc_address, loc_description FROM {$wpdb->prefix}rp_locations WHERE 1=%d AND  loc_name LIKE '%{$loc_name}%'";
            $sql = $wpdb->prepare($sql, 1);
            $locations = $wpdb->get_results($sql);
            foreach ($locations as $loc) {
                $loc->loc_image_url = isset($loc->loc_image_id) ? wp_get_attachment_image_src($loc->loc_image_id, $img_size) : '';
                $loc->loc_image_url = isset($loc->loc_image_url[0]) ? $loc->loc_image_url[0] : '';
            }
            return $locations;
        }

        public function get_location_by_id()
        {
            $loc_id = isset($_REQUEST['loc_id']) ? $_REQUEST['loc_id'] : 0;
            global $wpdb;
            $sql = "SELECT loc_id, loc_image_id, loc_name, loc_address,  loc_description FROM {$wpdb->prefix}rp_locations WHERE loc_id=%d";
            $sql = $wpdb->prepare($sql, $loc_id);
            $locations = $wpdb->get_results($sql);
            $locations = is_array($locations) && count($locations) > 0 ? $locations[0] : array();
            $locations->loc_image_url = isset($locations->loc_image_id) ? wp_get_attachment_image_src($locations->loc_image_id, 'thumbnail') : '';
            $locations->loc_image_url = isset($locations->loc_image_url[0]) ? $locations->loc_image_url[0] : '';
            return $locations;
        }

        public function save_location()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                global $wpdb;
                if (isset($data['loc_id']) && $data['loc_id'] != '') {
                    $result = $wpdb->update($wpdb->prefix . 'rp_locations', $data, array('loc_id' => $data['loc_id']));
                } else {
                    $data['loc_create_date'] = current_time( 'mysql', 0);
                    $result = $wpdb->insert($wpdb->prefix . 'rp_locations', $data);
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

        public function delete_location()
        {
            $loc_id = isset($_REQUEST['loc_id']) && $_REQUEST['loc_id'] ? $_REQUEST['loc_id'] : '';
            global $wpdb;
            $sql = "DELETE FROM {$wpdb->prefix}rp_locations WHERE loc_id = %d";
            $sql = $wpdb->prepare($sql, $loc_id);
            $result = $wpdb->query($sql);
            return array(
                'result' => $result,
            );
        }
    }
}