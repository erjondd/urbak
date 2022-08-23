<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 4/23/2019
 * Time: 2:20 PM
 */

if (!class_exists('Revy_Ajax_Handlers')) {
    class Revy_Ajax_Handlers
    {
        public function setup_ajax_handler()
        {
            $ajax_callbacks = array(
                /* devices */
                'get_devices' => 'get_devices',
                'get_device_by_id' => 'get_device_by_id',
                'save_devices' => 'save_devices',
                'delete_device' => 'delete_device',

                /* brands */
                'get_brands' => 'get_brands',
                'get_brand_by_id' => 'get_brand_by_id',
                'save_brands' => 'save_brands',
                'delete_brand' => 'delete_brand',

                /* model */
                'get_models' => 'get_models',
                'get_model_by_id' => 'get_model_by_id',
                'save_model' => 'save_model',
                'delete_model' => 'delete_model',

                /* garages */
                'get_garages' => 'get_garages',
                'get_garages_dic' => 'get_garages_dic',
                'get_garage_by_id' => 'get_garage_by_id',
                'save_garage' => 'save_garage',
                'delete_garage' => 'delete_garage',

                /* services */
                'get_service_category' => 'get_service_category',
                'get_services' => 'get_services',
                'get_services_dic' => 'get_services_dic',
                'get_service_by_id' => 'get_service_by_id',
                'save_service_category' => 'save_service_category',
                'delete_service_category' => 'delete_service_category',
                'save_service' => 'save_service',
                'delete_service' => 'delete_service',
                'get_services_hierarchy' => 'get_services_hierarchy',

                /* customers*/
                'get_customers' => 'get_customers',
                'get_customers_dic' => 'get_customers_dic',
                'save_customer' => 'save_customer',
                'get_customer_by_id' => 'get_customer_by_id',
                'delete_customer' => 'delete_customer',

                /* coupons*/
                'get_coupons' => 'get_coupons',
                'save_coupon' => 'save_coupon',
                'get_coupon_by_id' => 'get_coupon_by_id',
                'delete_coupon' => 'delete_coupon',
                'get_coupon_discount' => 'get_coupon_discount',

                /* setting */
                'get_setting' => 'get_setting',
                'get_working_hour_setting' => 'get_working_hour_setting',
                'save_setting' => 'save_setting',
                'save_working_hour_setting' => 'save_working_hour_setting',
                'save_custom_css' => 'save_custom_css',
                'test_send_mail' => 'test_send_mail',
                'save_email_template' => 'save_email_template',
                'test_send_email_template' => 'test_send_email_template',

                /* bookings */
                'get_booking' => 'get_booking',
                'get_booking_export' => 'get_booking_export',
                'get_booking_by_id' => 'get_booking_by_id',
                'get_booking_slot' => 'get_booking_slot',
                'save_booking' => 'save_booking',
                'delete_booking' => 'delete_booking',
                'update_booking_status' => 'update_booking_process_status',
                'get_booking_calendar' => 'get_booking_calendar',
                'get_booking_calendar_by_id' => 'get_booking_calendar_by_id',
                'send_booking_mail' => 'send_booking_mail',
                'get_insight' => 'get_insight',
                'get_time_slot_monthly' => 'get_time_slot_monthly',

                /* pickup */
                'get_pickup' => 'get_pickup',
                'update_pickup_status' => 'update_pickup_status',
                'get_pickup_export' => 'get_pickup_export',
                'delete_pickup' => 'delete_pickup'

            );
            foreach ($ajax_callbacks as $ajax_func => $callback_func) {
                add_action('wp_ajax_' . $ajax_func, array($this, $callback_func));
            }
        }

        public function setup_fe_ajax_handler()
        {
            $ajax_callbacks = array(
                'get_time_slot_weekly' => 'get_time_slot_weekly',
                'get_time_slot_by_date_ranger' => 'get_time_slot_by_date_ranger',
                'get_coupon_fe_discount' => 'get_coupon_fe_discount',
                'save_booking_fe' => 'save_booking_fe',
                'save_pickup_fe' => 'save_pickup_fe',
                'send_booking_fe_mail' => 'send_booking_fe_mail',
                'export_calendar' => 'export_calendar',
                'export_google_calendar' => 'export_google_calendar',
                'get_booking_history' => 'get_booking_history',
                'get_customer_code' => 'get_customer_code',
                'cancel_booking' => 'cancel_booking',
                'payment_intents' => 'payment_intents',
                'payment_confirm' => 'payment_confirm'
            );
            foreach ($ajax_callbacks as $ajax_func => $callback_func) {
                add_action('wp_ajax_' . $ajax_func, array($this, $callback_func));
                add_action('wp_ajax_nopriv_' . $ajax_func, array($this, $callback_func));
            }
        }

        /* Devices */
        public function get_devices()
        {
            $device_db = Revy_DB_Devices::instance();
            $result = $device_db->get_devices(-1);
            echo json_encode($result);
            wp_die();
        }

        public function get_device_by_id()
        {
            $device_db = Revy_DB_Devices::instance();
            $result = $device_db->get_device_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function save_devices()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $device_db = Revy_DB_Devices::instance();
            $result = $device_db->save_device();
            echo json_encode($result);
            wp_die();
        }

        public function delete_device()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $device_db = Revy_DB_Devices::instance();
            $result = $device_db->delete_device();
            echo json_encode($result);
            wp_die();
        }

        /* Brands */
        public function get_brands()
        {
            $brand_db = Revy_DB_Brands::instance();
            $result = $brand_db->get_brands(-1);
            echo json_encode($result);
            wp_die();
        }

        public function get_brand_by_id()
        {
            $brand_db = Revy_DB_Brands::instance();
            $result = $brand_db->get_brand_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function save_brands()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $brand_db = Revy_DB_Brands::instance();
            $result = $brand_db->save_brand();
            echo json_encode($result);
            wp_die();
        }

        public function delete_brand()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $brand_db = Revy_DB_Brands::instance();
            $result = $brand_db->delete_brand();
            echo json_encode($result);
            wp_die();
        }

        /* Models */
        public function get_models()
        {
            $model_db = Revy_DB_Models::instance();
            $result = $model_db->get_models();
            echo json_encode($result);
            wp_die();
        }

        public function get_model_by_id()
        {
            $model_db = Revy_DB_Models::instance();
            $result = $model_db->get_model_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function save_model()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $model_db = Revy_DB_Models::instance();
            $result = $model_db->save_model();
            echo json_encode($result);
            wp_die();
        }

        public function delete_model()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $model_db = Revy_DB_Models::instance();
            $result = $model_db->delete_model();
            echo json_encode($result);
            wp_die();
        }

        /* Service */

        public function get_services()
        {
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_services();
            echo json_encode($result);
            wp_die();
        }

        public function get_services_hierarchy()
        {
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_services_hierarchy();
            echo json_encode($result);
            wp_die();
        }

        public function get_services_dic()
        {
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_services_dic();
            echo json_encode($result);
            wp_die();
        }

        public function get_service_by_id()
        {
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_service_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function save_service()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->save_service();
            echo json_encode($result);
            wp_die();
        }

        public function delete_service()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->delete_service();
            echo json_encode($result);
            wp_die();
        }

        /* Locations */
        public function get_locations()
        {
            $location_db = Revy_DB_Locations::instance();
            $result = $location_db->get_locations();
            echo json_encode($result);
            wp_die();
        }

        public function get_location_by_id()
        {
            $location_db = Revy_DB_Locations::instance();
            $result = $location_db->get_location_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function save_location()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $location_db = Revy_DB_Locations::instance();
            $result = $location_db->save_location();
            echo json_encode($result);
            wp_die();
        }

        public function delete_location()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $location_db = Revy_DB_Locations::instance();
            $result = $location_db->delete_location();
            echo json_encode($result);
            wp_die();
        }

        /* Garages */
        public function get_garages()
        {
            $garage_db = Revy_DB_Garages::instance();
            $result = $garage_db->get_garages();
            echo json_encode($result);
            wp_die();
        }

        public function get_garages_dic()
        {
            $garage_db = Revy_DB_Garages::instance();
            $result = $garage_db->get_garages_dic(0);
            echo json_encode($result);
            wp_die();
        }

        public function get_garage_by_id()
        {
            $garage_db = Revy_DB_Garages::instance();
            $result = $garage_db->get_garage_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function save_garage()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $garage_db = Revy_DB_Garages::instance();
            $result = $garage_db->save_garage();
            echo json_encode($result);
            wp_die();
        }

        public function delete_garage()
        {
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }
            $garage_db = Revy_DB_Garages::instance();
            $result = $garage_db->delete_garage();
            echo json_encode($result);
            wp_die();
        }


        /* Customers*/
        public function get_customers(){
            $customer_db = Revy_DB_Customers::instance();
            $result = $customer_db->get_customers();
            echo json_encode($result);
            wp_die();
        }

        public function get_customers_dic(){
            $customer_db = Revy_DB_Customers::instance();
            $result = $customer_db->get_customers_dic();
            echo json_encode($result);
            wp_die();
        }

        public function save_customer(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $customer_db = Revy_DB_Customers::instance();
            $result = $customer_db->save_customer();
            echo json_encode($result);
            wp_die();
        }

        public function get_customer_by_id(){
            $customer_db = Revy_DB_Customers::instance();
            $result = $customer_db->get_customer_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function delete_customer(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $customer_db = Revy_DB_Customers::instance();
            $result = $customer_db->delete_customer();
            echo json_encode($result);
            wp_die();
        }

        /* Coupons */
        public function get_coupons(){
            $coupons_db = Revy_DB_Coupons::instance();
            $result = $coupons_db->get_coupons();
            echo json_encode($result);
            wp_die();
        }

        public function save_coupon(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $coupons_db = Revy_DB_Coupons::instance();
            $result = $coupons_db->save_coupon();
            echo json_encode($result);
            wp_die();
        }

        public function get_coupon_by_id(){
            $coupons_db = Revy_DB_Coupons::instance();
            $result = $coupons_db->get_coupon_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function delete_coupon(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $coupons_db = Revy_DB_Coupons::instance();
            $result = $coupons_db->delete_coupon();
            echo json_encode($result);
            wp_die();
        }

        public function get_coupon_discount(){
            $coupons_db = Revy_DB_Coupons::instance();
            $result = $coupons_db->get_coupon_discount();
            echo json_encode($result);
            wp_die();
        }

        /* Setting */
        public function get_setting(){
            $setting_db = Revy_DB_Setting::instance();
            $result = $setting_db->get_setting();
            echo json_encode($result);
            wp_die();
        }

        public function get_working_hour_setting(){
            $setting_db = Revy_DB_Setting::instance();
            $result = $setting_db->get_working_hour_setting();
            echo json_encode($result);
            wp_die();
        }

        public function save_setting(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $setting_db = Revy_DB_Setting::instance();
            $result = $setting_db->save_setting();
            echo json_encode($result);
            wp_die();
        }

        public function save_working_hour_setting(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $setting_db = Revy_DB_Setting::instance();
            $result = $setting_db->save_working_hour_setting();
            echo json_encode($result);
            wp_die();
        }

        public function save_email_template(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid);
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $setting_db = Revy_DB_Setting::instance();
            $result = $setting_db->save_email_template();
            echo json_encode($result);
            wp_die();
        }

        public function test_send_email_template(){
            $setting_db = Revy_DB_Setting::instance();
            $result = $setting_db->test_send_email_template();
            echo json_encode($result);
            wp_die();
        }

        public function test_send_mail(){
            $setting_db = Revy_DB_Setting::instance();
            $result = $setting_db->test_send_mail();
            echo json_encode($result);
            wp_die();
        }

        /* Booking */
        public function get_time_slot_monthly(){
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_time_slot_monthly();
            echo json_encode($result);
            wp_die();
        }

        public function get_booking(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_booking();
            echo json_encode($result);
            wp_die();
        }

        public function get_booking_export(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_booking_export();
            echo json_encode($result);
            wp_die();
        }

        public function get_booking_calendar(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_booking_calendar();
            echo json_encode($result);
            wp_die();
        }

        public function get_booking_by_id(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_booking_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function get_booking_slot(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_booking_slot();
            echo json_encode($result);
            wp_die();
        }

        public function get_booking_calendar_by_id(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_booking_calendar_by_id();
            echo json_encode($result);
            wp_die();
        }

        public function save_booking(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid, 'booking');
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->save_booking();
            echo json_encode($result);
            wp_die();
        }

        public function delete_booking(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_delete_data',$is_valid, 'booking');
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->delete_booking();
            echo json_encode($result);
            wp_die();
        }

        public function update_booking_process_status(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid, 'booking');
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->update_booking_process_status();
            echo json_encode($result);
            wp_die();
        }

        public function send_booking_mail(){
            $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '';
            $is_fe = isset($_REQUEST['is_fe']) && $_REQUEST['is_fe'] ? $_REQUEST['is_fe'] : 0;
            if($b_id){
                $booking_db = Revy_DB_Bookings::instance();
                $booking_db->send_booking_mail($b_id, $is_fe);
            }
            wp_die();
        }

        public function get_insight(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_insight();
            echo json_encode($result);
            wp_die();
        }

        public function get_time_slot_daily(){
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_time_slot_daily();
            echo json_encode($result);
            wp_die();
        }

        public function get_employee_time_slot_monthly(){
            $employee_db = Revy_DB_Employees::instance();
            $result = $employee_db->get_employee_time_slot_monthly();
            echo json_encode($result);
            wp_die();
        }

        public function cancel_booking(){
            $is_valid = 1;
            $is_valid = apply_filters('revy_save_data',$is_valid, 'booking');
            if(is_array($is_valid)){
                echo json_encode($is_valid);
                wp_die();
            }

            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->cancel_booking();
            echo json_encode($result);
            wp_die();
        }

        public function get_customer_code(){
            $customer_db = Revy_DB_Customers::instance();
            $result = $customer_db->get_customer_code();
            echo json_encode($result);
            wp_die();
        }

        public function cancel_send_mail(){
            $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '';
            $booking_db = Revy_DB_Bookings::instance();
            $booking_db->send_booking_mail($b_id, 1);
            wp_die();
        }

        // frontend
        public function get_time_slot_weekly(){
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_time_slot_weekly();
            echo json_encode($result);
            wp_die();
        }

        public function get_time_slot_by_date_ranger(){
            $service_db = Revy_DB_Services::instance();
            $result = $service_db->get_time_slot_by_date_ranger();
            echo json_encode($result);
            wp_die();
        }

        public function get_coupon_fe_discount(){
            $coupons_db = Revy_DB_Coupons::instance();
            $result = $coupons_db->get_coupon_discount();
            echo json_encode($result);
            wp_die();
        }

        public function save_booking_fe(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->save_booking_fe();
            echo json_encode($result);
            wp_die();
        }

        public function save_pickup_fe(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->save_pickup_fe();
            echo json_encode($result);
            wp_die();
        }

        public function send_booking_fe_mail(){
            $b_id = isset($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '';
            if($b_id){
                $booking_db = Revy_DB_Bookings::instance();
                $result = $booking_db->send_booking_mail($b_id, 1);
                echo json_encode($result);
            }
            wp_die();
        }

        public function get_booking_history(){
            $booking_db = Revy_DB_Bookings::instance();
            $result = $booking_db->get_booking_history();
            echo json_encode($result);
            wp_die();
        }

        public function export_calendar(){
            $booking_db = Revy_DB_Bookings::instance();
            echo sprintf('%s', $booking_db->export_calendar());
            wp_die();
        }

        public function export_google_calendar(){
            $booking_db = Revy_DB_Bookings::instance();
            echo sprintf('%s',$booking_db->export_google_calendar());
            wp_die();
        }

        public function payment_intents(){
            $b_id = isset($_REQUEST['b_id']) && $_REQUEST['b_id'] ? $_REQUEST['b_id'] : 0;
            if ($b_id) {
                $payment = new Revy_Payment();
                $result = $payment->stripe_payment_create($b_id);
                echo json_encode($result);
            } else {
                $result = array('error' => esc_html__('Data invalid','revy'));
                echo $result;
            }
            wp_die();
        }

        function payment_confirm()
        {
            $b_id = isset($_REQUEST['b_id']) && $_REQUEST['b_id'] ? $_REQUEST['b_id'] : 0;
            $payment_response = isset($_REQUEST['paymentResponse']) && $_REQUEST['paymentResponse'] ? $_REQUEST['paymentResponse'] : '';
            if ($b_id && isset($payment_response['id'])) {
                $payment = new Revy_Payment();
                $result = $payment->stripe_payment_confirm($b_id, $payment_response);
                echo json_encode($result);
            } else {
                $result = array('message' => esc_html__('Data invalid','revy'));
                echo json_encode($result);
            }
            wp_die();
        }

    }
}