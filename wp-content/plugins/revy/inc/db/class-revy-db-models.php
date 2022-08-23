<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/7/2019
 * Time: 9:10 AM
 */
if (!class_exists('Revy_DB_Models')) {
    class Revy_DB_Models
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_models()
        {
            global $wpdb;
            $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            $sql = "SELECT rm_id, rm_name, rm_group, rd_name, rb_name, rm_active
                    FROM {$wpdb->prefix}rp_models as M 
                    LEFT JOIN {$wpdb->prefix}rp_devices AS D
                    ON M.rm_device_id = D.rd_id
                    LEFT JOIN {$wpdb->prefix}rp_brands AS B
                    ON M.rm_brand_id = B.rb_id
                    WHERE 1=%d ";

            if (isset($_REQUEST['rm_brand_id']) && $_REQUEST['rm_brand_id']) {
                $sql .= " AND rm_brand_id = '{$_REQUEST['rm_brand_id']}'";
            }

            if (isset($_REQUEST['rm_device_id']) && $_REQUEST['rm_device_id']) {
                $sql .= " AND rm_device_id = '{$_REQUEST['rm_device_id']}'";
            }

            if (isset($_REQUEST['rm_name']) && $_REQUEST['rm_name']) {
                $sql .= " AND rm_name LIKE '%{$_REQUEST['rm_name']}%'";
            }

            $sql.= " ORDER BY rm_order ASC";

            $sql = $wpdb->prepare($sql, 1);
            $models = $wpdb->get_results($sql);
            $total = count($models);

            $db_setting = Revy_DB_Setting::instance();
            $setting =  $db_setting->get_setting();

            $item_per_page = isset($setting['item_per_page']) ? $setting['item_per_page'] : 10;
            $number_of_page = $total / $item_per_page + ($total % $item_per_page > 0 ? 1 : 0);
            $page = $page > $number_of_page ? $number_of_page : $page;
            $page = ($page - 1) * $item_per_page;
            $models = array_slice($models, $page, $item_per_page);

            return array(
                'total' => $total,
                'models' => $models
            );
        }

        public function get_models_dic($image_size='thumbnail')
        {
            global $wpdb;
            $sql = "SELECT rm_id, rm_name, rm_image_id, rm_device_id, rm_brand_id
                    FROM {$wpdb->prefix}rp_models
                    WHERE rm_active = 1 ORDER BY rm_order ASC";
            $models = $wpdb->get_results($sql);
            foreach ($models as $md) {
                $md->rm_image_url = isset($md->rm_image_id) ? wp_get_attachment_image_src($md->rm_image_id, $image_size) : '';
                $md->rm_image_url = isset($md->rm_image_url[0]) ? $md->rm_image_url[0] : '';
            }
            return $models;

        }

        public function get_models_drop()
        {
            global $wpdb;
            $sql = "SELECT rm_id, rm_name, rm_image_id, rm_device_id, rm_brand_id
                    FROM {$wpdb->prefix}rp_models
                    WHERE rm_active = 1 ORDER BY rm_order ASC";
            return $wpdb->get_results($sql);

        }

        public function get_model_by_id()
        {
            $rm_id = isset($_REQUEST['rm_id']) ? $_REQUEST['rm_id'] : 0;
            global $wpdb;
            $model = [];
            if($rm_id){
                $sql = "SELECT rm_id, rm_order, rm_image_id, rm_name, rm_group, rm_active, rm_device_id, rm_brand_id FROM {$wpdb->prefix}rp_models WHERE rm_id=%d";
                $sql = $wpdb->prepare($sql, $rm_id);
                $model = $wpdb->get_results($sql);
                $model = is_countable($model) ? $model[0] : [];
                $model->rm_image_url = isset($model->rm_image_id) ? wp_get_attachment_image_src($model->rm_image_id, 'thumbnail') : '';
                $model->rm_image_url = isset($model->rm_image_url[0]) ? $model->rm_image_url[0] : '';
            }else{
                $sql = "SELECT MAX(rm_order) as rm_order FROM {$wpdb->prefix}rp_models";
                $max_order = $wpdb->get_results($sql);
                $model['rm_order'] = isset($max_order[0]) && isset($max_order[0]->rm_order) ? ($max_order[0]->rm_order+1) : 1;
                $model['rm_active'] = 1;
            }

            $sql = "SELECT rd_id, rd_name FROM {$wpdb->prefix}rp_devices";
            $devices = $wpdb->get_results($sql);

            $sql = "SELECT rb_id, rb_name FROM {$wpdb->prefix}rp_brands";
            $brands = $wpdb->get_results($sql);

            return array(
                'model' => $model,
                'devices' => $devices,
                'brands' => $brands
            );
        }

        public function get_filter_dic()
        {
            global $wpdb;
            $sql = "SELECT rd_id, rd_name FROM {$wpdb->prefix}rp_devices ORDER BY rd_order ASC";
            $devices = $wpdb->get_results($sql);

            $sql = "SELECT rb_id, rb_name FROM {$wpdb->prefix}rp_brands ORDER BY rb_order ASC";
            $brands = $wpdb->get_results($sql);

            return array(
                'devices' => $devices,
                'brands' => $brands
            );
        }

        public function save_model()
        {
            $data = isset($_REQUEST['data']) && $_REQUEST['data'] ? $_REQUEST['data'] : '';
            if ($data != '' && is_array($data)) {
                $rm_id = isset($data['rm_id']) && $data['rm_id'] != '' ? $data['rm_id'] : 0;
                global $wpdb;
                if ($rm_id > 0) {
                    $result = $wpdb->update($wpdb->prefix . 'rp_models', $data, array('rm_id' => $data['rm_id']));
                } else {
                    $data['rm_create_date'] = current_time( 'mysql', 0);
                    $result = $wpdb->insert($wpdb->prefix . 'rp_models', $data);
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

        public function delete_model()
        {
            $rm_ids = isset($_REQUEST['rm_ids']) ? $_REQUEST['rm_ids'] : '';
            if ($rm_ids) {
                global $wpdb;
                $rm_ids = implode(',', $rm_ids);
                $sql = "DELETE FROM {$wpdb->prefix}rp_models WHERE 1=%d AND rm_id IN ({$rm_ids}) ";
                $sql = $wpdb->prepare($sql, 1);
                $result = $wpdb->query($sql);
                return array(
                    'result' => $result,
                    'ids_delete' => $rm_ids,
                    'message' => $result > 0 ? $result . esc_html__(' model(s) have been deleted', 'revy') : '',

                );
            } else {
                return array(
                    'result' => 1,
                );
            }
        }

        public function get_models_group($brand_id){
            global $wpdb;

            $model_groups = [];
            $sql = "SELECT DISTINCT(rm_group) AS group_name FROM {$wpdb->prefix}rp_models WHERE rm_brand_id=%d AND rm_active=1 ";
            $sql = $wpdb->prepare($sql, $brand_id);
            $groups = $wpdb->get_results($sql);
            $gr_key = '';
            foreach ($groups as $gr){
                $gr->group_name = trim($gr->group_name);
                $gr_key = ucfirst($gr->group_name);
                $model_groups[$gr_key] = array();
            }


            $sql = "SELECT rm_id, rm_image_id, rm_name, rm_device_id, rm_brand_id, rm_group FROM {$wpdb->prefix}rp_models WHERE rm_brand_id=%d AND rm_active=1 ORDER BY rm_order ASC";
            $sql = $wpdb->prepare($sql, $brand_id);
            $models = $wpdb->get_results($sql);
            foreach ($models as $md) {
                $md->rm_group = trim($md->rm_group);
                $md->rm_group = ucfirst($md->rm_group );
                $md->rm_image_url = isset($md->rm_image_id) ? wp_get_attachment_image_src($md->rm_image_id, 'full') : '';
                $md->rm_image_url = isset($md->rm_image_url[0]) ? $md->rm_image_url[0] : '';
                $model_groups[$md->rm_group][] = $md;
            }

            return $model_groups;

        }

        public function get_groups($brand_id){
            global $wpdb;

            $model_groups = [];
            $sql = "SELECT DISTINCT(rm_group) AS group_name FROM {$wpdb->prefix}rp_models WHERE rm_brand_id=%d AND rm_active=1";
            $sql = $wpdb->prepare($sql, $brand_id);
            $groups = $wpdb->get_results($sql);
            $gr_key = '';
            foreach ($groups as $gr){
                $gr->group_name = trim($gr->group_name);
                $gr_key = ucfirst($gr->group_name);
                $model_groups[$gr_key] = $gr->group_name;
            }

        }

    }
}