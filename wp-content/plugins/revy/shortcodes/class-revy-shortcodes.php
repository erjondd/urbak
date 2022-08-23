<?php
/**
 * Created by PhpStorm.
 * User: roniwp
 * Date: 6/25/2020
 * Time: 8:23 AM
 */
if (!class_exists('Revy_Shortcodes')) {
    class Revy_Shortcodes{
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function revy_shortcode($atts){

            $this->flow_enqueue_script($atts);
            ob_start();
            $template = REVY_DIR_PATH.'/templates/shortcodes/flow.php';
            if(is_readable($template)){
                include $template;
            }
            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        }

        public function history_shortcode($atts){
            $this->history_enqueue_script();
            ob_start();
            $template = REVY_DIR_PATH.'/templates/shortcodes/history.php';
            if(is_readable($template)){
                require $template;
            }
            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        }

        private function flow_enqueue_script($atts){
            wp_dequeue_script('jquery-ui-datepicker');
            wp_deregister_script( 'jquery-ui-datepicker' );
            wp_dequeue_script('bootstrap');
            wp_deregister_script('bootstrap');


            wp_enqueue_style('semantic', REVY_ASSET_URL . 'plugins/semantic/semantic.min.css', array(), '2.4.1');
            wp_enqueue_style('semantic-extra', REVY_ASSET_URL . 'plugins/semantic/semantic-extra.css', array(), '1.0.0');
            wp_enqueue_script('semantic', REVY_ASSET_URL . 'plugins/semantic/semantic.min.js', array('jquery'), '2.4.1', true);

            wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '4.7.0');
            wp_enqueue_style('revy', REVY_ASSET_URL . 'css/frontend/style.css', array(), '1.0.0');
            wp_enqueue_style('revy-flow', REVY_ASSET_URL . 'css/frontend/flow.css', array(), '1.0.0');

            wp_dequeue_script('moment');
            wp_deregister_script('moment');

            // air datepicker
            wp_enqueue_style('air-date-picker', REVY_ASSET_URL . 'plugins/air-datepicker/css/datepicker.min.css', array(), '2.2.3');
            wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('air-date-picker', REVY_ASSET_URL . 'plugins/air-datepicker/js/datepicker.min.js', array('jquery', 'moment'), '2.3.3', true);
            $locale = get_locale();
            $locale = explode('_',$locale)[0];
            $locale_file = REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.'.$locale.'.js';
            wp_enqueue_script('air-date-picker-lang', REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.en.js', array('jquery', 'air-date-picker'), '2.3.3', true);
            if($locale!='en'){
                wp_enqueue_script('air-date-picker-lang-'.$locale, $locale_file, array('jquery', 'air-date-picker'), '2.3.3', true);
            }

            $revy_setting = Revy_DB_Setting::instance();
            $currency = $revy_setting->get_currency_setting();
            $setting =  $revy_setting->get_setting();
            $working_hour = $revy_setting->get_working_hour_setting();
            $now = current_time( 'mysql', 0);
            $person_label = isset($setting['person_label']) && $setting['person_label'] ? $setting['person_label'] : esc_html__('person(s)','revy');
            $mapbox_api_key = isset($setting['mapbox_api_key']) ? $setting['mapbox_api_key'] : '';

            $loc_id = isset($atts['location']) ? $atts['location'] : 0;

            $db = Revy_DB_Brands::instance();
            $brands = $db->get_brands(1);

            $db = Revy_DB_Services::instance();
            $services = $db->get_services_filter($loc_id);

            $db = Revy_DB_Models::instance();
            $models = $db->get_models_dic('full');

            $db = Revy_DB_Garages::instance();
            $garages = $db->get_garages_dic();

            $revy_data = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_s_field' =>  wp_create_nonce("apoint-security-field" ),
                'error_message' => esc_html__('An error occurred during execution','revy'),
                'time_step' => isset($setting['time_step']) && $setting['time_step'] ? $setting['time_step'] : 15,
                'bt_no_label' => esc_html__('No','revy'),
                'bt_yes_label' => esc_html__('Yes','revy'),
                'loading_label' => esc_html__('Loading','revy'),
                'person_label' => $person_label,
                'change_location_label' => esc_html__('Change Location','revy'),
                'change_delivery_label' => esc_html__('Change Delivery Method','revy'),
                'coupon_validate' => esc_html__('Please select service and input coupon code','revy'),
                'empty_time_slot' => esc_html__('The appointments are fully booked. Please check again later or browse other day!','revy'),
                'empty_services' => esc_html__('Service not found.<br\/> You can select other location or category','revy'),
                'empty_payment_method' => esc_html__('You need to choose a payment method','revy'),
                'select_duration_message' => esc_html__('Please select duration','revy'),
                'now' => $now,
                'date_format' => get_option('date_format'),
                'time_format' => get_option('time_format'),
                'mon' => esc_html__('Mon','revy'),
                'tue' => esc_html__('Tue','revy'),
                'wed' => esc_html__('Wed','revy'),
                'thu' => esc_html__('Thu','revy'),
                'fri' => esc_html__('Fri','revy'),
                'sat' => esc_html__('Sat','revy'),
                'sun' => esc_html__('Sun','revy'),
                'durations' => Revy_Utils::getDurations(0,'duration_step'),
                'currency' => $currency['currency'],
                'number_of_decimals' => isset($setting['number_of_decimals']) && $setting['number_of_decimals']!='' ? $setting['number_of_decimals'] : 2,
                'symbol' => $currency['symbol'],
                'symbol_prefix' => $currency['symbol_position'] === 'before' ? $currency['symbol'] : '',
                'symbol_suffix' => $currency['symbol_position'] === 'after' ?  $currency['symbol'] : '',
                'symbol_position' => $currency['symbol_position'],
                'working_hour' => $working_hour,
                'slots' => Revy_Utils::getWorkHours(5),
                'select_date_message' => esc_html__('Please select a date to display free times','revy'),
                'model_not_found' => esc_html__('Model not found','revy'),
                'service_not_found' => esc_html__('Service not found','revy'),
                'garage_not_found' => esc_html__('Garages not found','revy'),
                'pickup_success_message' => esc_html__('Thank you! Your booking is complete. We will contact you as soon as possible.', 'revy'),
                'item_per_page' => 18,
                'map_api_key' => $mapbox_api_key,
                'distance_near_me' => isset($setting['distance_near_me']) && $setting['distance_near_me'] ? $setting['distance_near_me'] : 10,
                'distance_unit' => isset($setting['distance_unit']) && $setting['distance_unit'] ? $setting['distance_unit'] : 'kilometers',
                'disable_scroll' => isset($setting['disable_scroll_top']) ? $setting['disable_scroll_top'] : 0,

            );

            $revy_flow_data = array(
                'brands' => $brands,
                'services' => $services,
                'models' => $models,
                'garages' => $garages,
                'working_hour' => $working_hour['schedules']
            );
          
            if(isset($setting['stripe_enable']) && $setting['stripe_enable']=='1'){
                wp_enqueue_script('stripe', 'https://js.stripe.com/v3/', array('jquery'), false, false);
            }

            if($mapbox_api_key){
                wp_enqueue_style('mapbox-gl', 'https://api.tiles.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css', array(), '2.3.1');
                wp_enqueue_style('mapbox-gl-geocoder', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css', array(), '4.7.0');
                wp_enqueue_script('mapbox-gl', 'https://api.tiles.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js', array(), '1.3.0', true);
                wp_enqueue_script('mapbox-gl-geocoder', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js', array('mapbox-gl'), '4.7.0', true);
            }

            wp_enqueue_script('revy-turf', REVY_ASSET_URL . '/plugins/turf/turf.min.js', array('jquery'), REVY_PLUGIN_VERSION, false);
            wp_enqueue_script('revy-match-media', REVY_ASSET_URL . 'plugins/match-media/match-media.js', array('jquery'), false, true);
            wp_enqueue_script('revy-main-fe', REVY_ASSET_URL . 'js/frontend/main.js', array('jquery'), false, true);
            wp_localize_script('revy-main-fe', 'revy_data', $revy_data);
            wp_enqueue_script('revy-flow-fe', REVY_ASSET_URL . 'js/frontend/flow.js', array('jquery','wp-util','revy-main-fe','revy-turf'), REVY_PLUGIN_VERSION, true);
            wp_localize_script('revy-flow-fe', 'revy_flow_data', $revy_flow_data);
            do_action('revy_frontend_enqueue');
        }

        private function history_enqueue_script(){
            wp_dequeue_script('bootstrap');
            wp_deregister_script('bootstrap');

            wp_enqueue_style('semantic', REVY_ASSET_URL . 'plugins/semantic/semantic.min.css', array(), '2.4.1');
            wp_enqueue_style('semantic-extra', REVY_ASSET_URL . 'plugins/semantic/semantic-extra.css', array(), '1.0.0');
            wp_enqueue_script('semantic', REVY_ASSET_URL . 'plugins/semantic/semantic.min.js', array('jquery'), '2.4.1', false);

            wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '4.7.0');
            wp_enqueue_style('fat-sb', REVY_ASSET_URL . 'css/frontend/style.css', array(), REVY_PLUGIN_VERSION);

            $db_setting = Revy_DB_Setting::instance();
            $currency = $db_setting->get_currency_setting();
            $setting =  $db_setting->get_setting();
            $revy_data = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_s_field' =>  wp_create_nonce("fat-sb-security-field" ),
                'error_message' => esc_html__('An error occurred during execution','revy'),
                'not_found_message' => esc_html__('No records found','revy'),
                'not_edit_message' => esc_html__('You cannot cancel bookings in the past or after the booking is approved','revy'),
                'pending_label' => esc_html__('Pending','revy'),
                'approved_label' => esc_html__('Approved','revy'),
                'canceled_label' => esc_html__('Canceled','revy'),
                'rejected_label' => esc_html__('Rejected','revy'),
                'currency' => $currency['currency'],
                'symbol' => $currency['symbol'],
                'symbol_prefix' => $currency['symbol_position'] === 'before' ? $currency['symbol'] : '',
                'symbol_suffix' => $currency['symbol_position'] === 'after' ?  $currency['symbol'] : '',
                'symbol_position' => $currency['symbol_position'],
                'item_per_page' => isset($setting['item_per_page']) ? $setting['item_per_page'] : 10,
                'date_format' => get_option('date_format'),
                'apply_title' => esc_html__('Apply', 'revy'),
                'cancel_title' => esc_html__('Cancel', 'revy'),
                'from_title' => esc_html__('From', 'revy'),
                'to_title' => esc_html__('To', 'revy'),
            );

            wp_enqueue_style('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
            wp_enqueue_script('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

            wp_enqueue_script('revy-main-fe', REVY_ASSET_URL . 'js/frontend/main.js', array('jquery'), false, true);
            wp_localize_script('revy-main-fe', 'revy_data', $revy_data);
            wp_enqueue_script('revy-history', REVY_ASSET_URL . 'js/frontend/history.js', array('jquery','wp-util','revy-main-fe'), false, true);

        }
    }
}