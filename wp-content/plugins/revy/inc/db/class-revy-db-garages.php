<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/7/2019
 * Time: 9:10 AM
 */
if (!class_exists('Revy_DB_Garages')) {
    class Revy_DB_Garages
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_garages_dic($is_get_image = 1)
        {
            global $wpdb;
            $sql = "SELECT rg_id, rg_image_id, rg_name, rg_address, rg_latitude, rg_longitude , rg_description, rg_phone, rg_email
                    FROM {$wpdb->prefix}rp_garages";
            $garages = $wpdb->get_results($sql);
            if($is_get_image){
                foreach ($garages as $gr) {
                    if($gr->rg_image_id){
                        $img_size = Revy_Utils::image_resize($gr->rg_image_id, 328, 190);
                        $gr->rg_image_url = !is_wp_error($img_size) && isset($img_size['url']) ?$img_size['url'] : '';
                    }else{
                        $gr->rg_image_url = '';
                    }
                    $gr->rg_description = nl2br($gr->rg_description);
                }
            }
            return $garages;
        }

        public function get_garages()
        {
            global $wpdb;
            $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            $sql = "SELECT rg_id, rg_name, rg_email, rg_phone, rg_active, rg_address
                    FROM {$wpdb->prefix}rp_garages
                    WHERE 1=%d ";

            if (isset($_REQUEST['rg_name']) && $_REQUEST['rg_name']) {
                $sql .= " AND rg_name LIKE '%{$_REQUEST['rg_name']}%'";
            }

            $sql.= " ORDER BY rg_order ASC";

            $sql = $wpdb->prepare($sql, 1);
            $garages = $wpdb->get_results($sql);
            $total = count($garages);

            $db_setting = Revy_DB_Setting::instance();
            $setting =  $db_setting->get_setting();

            $item_per_page = isset($setting['item_per_page']) ? $setting['item_per_page'] : 10;
            $number_of_page = $total / $item_per_page + ($total % $item_per_page > 0 ? 1 : 0);
            $page = $page > $number_of_page ? $number_of_page : $page;
            $page = ($page - 1) * $item_per_page;
            $garages = array_slice($garages, $page, $item_per_page);

            return array(
                'total' => $total,
                'garages' => $garages
            );
        }

        public function get_garages_filter($loc_id)
        {
            global $wpdb;
            $sql = "SELECT RG.rg_id, rg_name, rg_email, rg_phone, rg_image_id, rg_map, rg_latitude, rg_longitude, rg_address
                    FROM {$wpdb->prefix}rp_garages
                    WHERE rg_active = 1";
            if($loc_id){
                $sql .= " AND RL.loc_id = %d";
                $sql = $wpdb->prepare($sql, $loc_id);
            }
            $garages = $wpdb->get_results($sql);
            foreach ($garages as $gr) {
                $gr->rg_image_url = isset($gr->rg_image_id) ? wp_get_attachment_image_src($gr->rg_image_id, 'thumbnail') : '';
                $gr->rg_image_url = isset($gr->rg_image_url[0]) ? $gr->rg_image_url[0] : '';
            }
            return $garages;

        }

        public function get_garage_by_id()
        {
            $rg_id = isset($_REQUEST['rg_id']) ? $_REQUEST['rg_id'] : 0;
            global $wpdb;
            $garage = [];
            if($rg_id){
                $sql = "SELECT rg_id, rg_order, rg_image_id, rg_name, rg_email, rg_phone, rg_address, rg_description, rg_map, rg_latitude, rg_longitude, rg_active
                        FROM {$wpdb->prefix}rp_garages 
                        WHERE rg_id=%d";
                $sql = $wpdb->prepare($sql, $rg_id);
                $garage = $wpdb->get_results($sql);
                $garage = is_countable($garage) ? $garage[0] : [];
                $garage->rg_image_url = isset($garage->rg_image_id) ? wp_get_attachment_image_src($garage->rg_image_id, 'thumbnail') : '';
                $garage->rg_image_url = isset($garage->rg_image_url[0]) ? $garage->rg_image_url[0] : '';
            }else{
                $sql = "SELECT MAX(rg_order) as rg_order FROM {$wpdb->prefix}rp_garages";
                $max_order = $wpdb->get_results($sql);
                $garage['rg_order'] = isset($max_order[0]) && isset($max_order[0]->rg_order) ? ($max_order[0]->rg_order+1) : 1;
                $garage['rg_active'] = 1;
            }
            return $garage;
        }

        public function save_garage()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                $rg_id = isset($data['rg_id']) && $data['rg_id'] != '' ? $data['rg_id'] : 0;
                global $wpdb;
                if ($rg_id > 0) {
                    $result = $wpdb->update($wpdb->prefix . 'rp_garages', $data, array('rg_id' => $data['rg_id']));
                } else {
                    $data['rg_create_date'] = current_time( 'mysql', 0);
                    $result = $wpdb->insert($wpdb->prefix . 'rp_garages', $data);
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

        public function delete_garage()
        {
            $rg_id = isset($_REQUEST['rg_id']) ? $_REQUEST['rg_id'] : '';
            if ($rg_id) {
                global $wpdb;

                $sql = "SELECT b_id FROM {$wpdb->prefix}rp_booking WHERE b_garage_id = %d AND b_process_status != -1";
                $sql = $wpdb->prepare($sql, $rg_id);
                $booking = $wpdb->get_results($sql, $rg_id);
                if(is_array($booking) && count($booking) > 0){
                    return array(
                        'result' => -1,
                        'message' => esc_html__('You need to delete appointments related to this garage', 'revy')
                    );
                }

                $sql = "DELETE FROM {$wpdb->prefix}rp_garages WHERE rg_id = %d ";
                $sql = $wpdb->prepare($sql, $rg_id);
                $result = $wpdb->query($sql);
                return array(
                    'result' => $result,
                    'message' => $result > 0 ? esc_html__('Model have been deleted', 'revy') : '',
                );
            } else {
                return array(
                    'result' => 1,
                );
            }
        }
    }
}