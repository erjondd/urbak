<?php

if (!class_exists('Revy_Menu_Admin')) {
    class Revy_Menu_Admin
    {
        public function init_menu()
        {
            $user_info = Revy_Utils::get_user_info();
            $role = 'edit_posts';
            $role = apply_filters('revy_menu_role', $role);
            add_menu_page(
                esc_html__('Revy', 'revy'),
                esc_html__('Revy', 'revy'),
                $role,
                'revy',
                array($this, 'insight_page'),
                'dashicons-admin-tools',
                9
            );
            add_submenu_page(
                'revy',
                esc_html__('Insight', 'revy'),
                esc_html__('Insight', 'revy'),
                $role,
                'revy'
            );

            add_submenu_page(
                'revy',
                esc_html__('Devices', 'revy'),
                esc_html__('Devices', 'revy'),
                $role,
                'revy-device',
                array($this, 'device_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Brands', 'revy'),
                esc_html__('Brands', 'revy'),
                $role,
                'revy-brand',
                array($this, 'brand_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Models', 'revy'),
                esc_html__('Models', 'revy'),
                $role,
                'revy-model',
                array($this, 'model_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Garages', 'revy'),
                esc_html__('Garages', 'revy'),
                $role,
                'revy-garage',
                array($this, 'garage_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Services', 'revy'),
                esc_html__('Services', 'revy'),
                $role,
                'revy-services',
                array($this, 'services_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Customers', 'revy'),
                esc_html__('Customers', 'revy'),
                $role,
                'revy-customers',
                array($this, 'customers_page')
            );


            add_submenu_page(
                'revy',
                esc_html__('Booking', 'revy'),
                esc_html__('Booking', 'revy'),
                $role,
                'revy-booking',
                array($this, 'booking_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Calendar', 'revy'),
                esc_html__('Calendar', 'revy'),
                $role,
                'revy-calendar',
                array($this, 'calendar_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Email template', 'revy'),
                esc_html__('Email template', 'revy'),
                $role,
                'revy-email-template',
                array($this, 'email_template_page')
            );

            add_submenu_page(
                'revy',
                esc_html__('Settings', 'revy'),
                esc_html__('Settings', 'revy'),
                $role,
                'revy-setting',
                array($this, 'setting_page')
            );
        }

        public function intro_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/intro.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/intro.php');
            }
        }

        public function insight_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/insight.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/insight.php');
            }
        }

        public function services_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/services.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/services.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/services/tmpl-services.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/services/tmpl-services.php');
            }
        }

        public function setting_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/settings.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/settings.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/settings/tmpl-setting.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/settings/tmpl-setting.php');
            }
        }

        public function customers_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/customers.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/customers.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/customers/tmpl-customers.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/customers/tmpl-customers.php');
            }
        }

        public function garage_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/garages.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/garages.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/garages/tmpl-garages.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/garages/tmpl-garages.php');
            }
        }

        public function device_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/devices.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/devices.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/devices/tmpl-devices.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/devices/tmpl-devices.php');
            }
        }

        public function brand_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/brands.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/brands.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/brands/tmpl-brands.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/brands/tmpl-brands.php');
            }
        }

        public function model_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/models.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/models.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/models/tmpl-models.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/models/tmpl-models.php');
            }
        }

        public function booking_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/booking.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/booking.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/booking/tmpl-booking.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/booking/tmpl-booking.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/customers/tmpl-customers.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/customers/tmpl-customers.php');
            }
        }

        public function calendar_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/calendar.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/calendar.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/booking/tmpl-booking.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/booking/tmpl-booking.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/customers/tmpl-customers.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/customers/tmpl-customers.php');
            }
        }

        public function coupon_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/coupons.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/coupons.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/coupon/tmpl-coupon.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/coupon/tmpl-coupon.php');
            }
        }

        public function email_template_page()
        {
            if (is_readable(REVY_DIR_PATH . '/templates/admin/email-template.php')) {
                include_once(REVY_DIR_PATH . '/templates/admin/email-template.php');
            }
            if (is_readable(REVY_DIR_PATH . '/tmpl/settings/tmpl-email-template.php')) {
                include_once(REVY_DIR_PATH . '/tmpl/settings/tmpl-email-template.php');
            }
        }

        public function admin_enqueue_script()
        {
            $screen = get_current_screen();
            if (isset($screen->id)) {
                wp_enqueue_style('revy', REVY_ASSET_URL . 'css/admin/style.css', array(), REVY_PLUGIN_VERSION);

                $Revy_DB_Setting = Revy_DB_Setting::instance();
                $currency = $Revy_DB_Setting->get_currency_setting();
                $setting = $Revy_DB_Setting->get_setting();
                $now = current_time('mysql', 0);
                $date_now = DateTime::createFromFormat('Y-m-d H:i:s', $now);
                $phone_code_default = isset($setting['default_phone_code']) && $setting['default_phone_code'] ? $setting['default_phone_code'] : '+44,uk';

                $revy_data = array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'error_message' => esc_html__('An error occurred during execution', 'revy'),
                    'clipboard_message' => esc_html__('The shortcode has been copied to the clipboard', 'revy'),
                    'time_step' => isset($setting['time_step']) && $setting['time_step'] ? $setting['time_step'] : 15,
                    'bt_no_lable' => esc_html__('No', 'revy'),
                    'bt_yes_lable' => esc_html__('Yes', 'revy'),
                    'loading_label' => esc_html__('Loading', 'revy'),
                    'confirm_delete_title' => esc_html__('Confirm delete', 'revy'),
                    'confirm_delete_message' => esc_html__('Are you sure you want to delete this item ?', 'revy'),
                    'confirm_reschedule_title' => esc_html__('Confirm update appointment', 'revy'),
                    'confirm_reschedule_message' => esc_html__('Are you sure you want to reschedule this appointment ?', 'revy'),
                    'confirm_update_title' => esc_html__('Confirm update', 'revy'),
                    'confirm_service_update_message' => esc_html__('Your changes related to specific settings for each employee. Do you want to update employee settings according to this setting ?', 'revy'),
                    'not_found_message' => esc_html__('No records found', 'revy'),

                    'coupon_validate' => esc_html__('Please select service and input coupon code', 'revy'),
                    'min_value_message' => esc_html__('Value should be above ', 'revy'),
                    'max_value_message' => esc_html__('Value should be bellow  ', 'revy'),
                    'modal_title' => array(
                        'edit_device' => esc_html__('Edit device', 'revy'),
                        'edit_brand' => esc_html__('Edit brand', 'revy'),
                        'edit_model' => esc_html__('Edit model', 'revy'),
                        'clone_model' => esc_html__('Clone model', 'revy'),
                        'edit_category' => esc_html__('Edit category', 'revy'),
                        'edit_service' => esc_html__('Edit service', 'revy'),
                        'clone_service' => esc_html__('Clone service', 'revy'),
                        'edit_service_extra' => esc_html__('Edit service extra', 'revy'),
                        'edit_employee' => esc_html__('Edit employee', 'revy'),
                        'clone_employee' => esc_html__('Clone employee', 'revy'),
                        'edit_customer' => esc_html__('Edit customer', 'revy'),
                        'edit_location' => esc_html__('Edit location', 'revy'),
                        'edit_coupon' => esc_html__('Edit coupon', 'revy'),
                        'edit_booking' => esc_html__('Edit booking', 'revy'),
                        'edit_calendar' => esc_html__('Edit calendar', 'revy')
                    ),
                    'now' => $now,
                    'date_now' => $date_now->format('Y-m-d'),
                    'date_format' => get_option('date_format'),
                    //for datetime ranger picker
                    'day_of_week' => array(
                        esc_html__('Su', 'revy'),
                        esc_html__('Mo', 'revy'),
                        esc_html__('Tu', 'revy'),
                        esc_html__('We', 'revy'),
                        esc_html__('Th', 'revy'),
                        esc_html__('Fr', 'revy'),
                        esc_html__('Sa', 'revy')
                    ),
                    'month_name' => array(
                        esc_html__('January', 'revy'),
                        esc_html__('February', 'revy'),
                        esc_html__('March', 'revy'),
                        esc_html__('April', 'revy'),
                        esc_html__('May', 'revy'),
                        esc_html__('June', 'revy'),
                        esc_html__('July', 'revy'),
                        esc_html__('August', 'revy'),
                        esc_html__('September', 'revy'),
                        esc_html__('October', 'revy'),
                        esc_html__('November', 'revy'),
                        esc_html__('December', 'revy')
                    ),

                    'apply_title' => esc_html__('Apply', 'revy'),
                    'cancel_title' => esc_html__('Cancel', 'revy'),
                    'from_title' => esc_html__('From', 'revy'),
                    'to_title' => esc_html__('To', 'revy'),
                    'january' => esc_html__('January', 'revy'),
                    'february' => esc_html__('February', 'revy'),
                    'march' => esc_html__('March', 'revy'),
                    'april' => esc_html__('April', 'revy'),
                    'may' => esc_html__('May', 'revy'),
                    'june' => esc_html__('June', 'revy'),
                    'july' => esc_html__('July', 'revy'),
                    'august' => esc_html__('August', 'revy'),
                    'september' => esc_html__('September', 'revy'),
                    'october' => esc_html__('October', 'revy'),
                    'november' => esc_html__('November', 'revy'),
                    'december' => esc_html__('December', 'revy'),
                    'booking_color' => array(
                        '#fbbd08',
                        '#21ba45',
                        '#db2828',
                        '#b5b5b5'
                    ),
                    'durations' => Revy_Utils::getDurations(0,'duration_step'),
                    'item_per_page' => isset($setting['item_per_page']) ? $setting['item_per_page'] : 10,
                    'percentage_discount' => esc_html__('Percentage discount', 'revy'),
                    'fixed_discount' => esc_html__('Fixed discount', 'revy'),
                    'currency' => $currency['currency'],
                    'symbol' => $currency['symbol'],
                    'symbol_position' => $currency['symbol_position'],
                    'pending_label' => esc_html__('Pending', 'revy'),
                    'approved_label' => esc_html__('Approved', 'revy'),
                    'canceled_label' => esc_html__('Canceled', 'revy'),
                    'rejected_label' => esc_html__('Rejected', 'revy'),
                    'appointment_date_column' => esc_html__('Appointment Date', 'revy'),
                    'create_date_column' => esc_html__('Create Date', 'revy'),
                    'garage_name_column' => esc_html__('Garage', 'revy'),
                    'garage_address_column' => esc_html__('Garage Address', 'revy'),
                    'customer_column' => esc_html__('Customer', 'revy'),
                    'customer_email_column' => esc_html__('Customer Email', 'revy'),
                    'customer_phone_column' => esc_html__('Customer Phone', 'revy'),
                    'customer_address' => esc_html__('Customer Address', 'revy'),
                    'customer_city' => esc_html__('Customer City', 'revy'),
                    'customer_country' => esc_html__('Customer Country', 'revy'),
                    'customer_postal_code' => esc_html__('Customer Postal Code', 'revy'),
                    'attribute_column' => esc_html__('Attribute', 'revy'),
                    'model_column' => esc_html__('Model', 'revy'),
                    'services_column' => esc_html__('Services', 'revy'),
                    'start_time_column' => esc_html__('Start time', 'revy'),
                    'end_time_column' => esc_html__('End time', 'revy'),
                    'duration_column' => esc_html__('Duration', 'revy'),
                    'payment_column' => esc_html__('Payment', 'revy'),
                    'status_column' => esc_html__('Status', 'revy'),
                    'form_builder_column' => esc_html__('Custom fields', 'revy'),
                    'notice_payment_default' => esc_html__('You need enable at least one payment method', 'revy'),
                    'insight_new_customer' => esc_html__('New Customer', 'revy'),
                    'insight_return_customer' => esc_html__('Return Customer', 'revy'),
                    'insight_revenue' => esc_html__('Revenue', 'revy'),
                    'insight_employee' => esc_html__('Employee', 'revy'),
                    'insight_services' => esc_html__('Services', 'revy'),
                    'yes_label' => esc_html__('Yes','revy'),
                    'no_label' => esc_html__('No','revy'),
                    'phone_code' => $phone_code_default,
                    'attribute_label' => esc_html__('Attribute','revy'),
                    'number_of_decimal' => $setting['number_of_decimals'],
                    'slots' => Revy_Utils::getWorkHours(5),
                    'empty_time_slot' => esc_html__('Time slot not available', 'revy')
                );

                if (stripos($screen->id, 'revy-insight') !== FALSE || $screen->id == 'toplevel_page_revy' ) {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    wp_enqueue_style('jquery.sumoselect', REVY_ASSET_URL . 'plugins/jquery-sumo/sumoselect.min.css', array(), '3.0.3');
                    wp_enqueue_script('jquery.sumoselect', REVY_ASSET_URL . 'plugins/jquery-sumo/jquery.sumoselect.min.js', array('jquery', 'wp-util'), '3.0.3', true);

                    wp_enqueue_style('date-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
                    wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '2.24.0', true);
                    wp_enqueue_script('date-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

                    wp_enqueue_script('apex-charts', REVY_ASSET_URL . 'plugins/apex-charts/apexcharts.min.js', array(), false, true);

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);
                    wp_enqueue_script('revy-insight', REVY_ASSET_URL . 'js/admin/insight.js', array('jquery', 'wp-util', 'date-ranger-picker', 'revy-main','jquery.sumoselect'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;

                }

                if (stripos($screen->id, 'revy-device') !== FALSE || $screen->id == 'revy_page_revy-device') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();
                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-device', REVY_ASSET_URL . 'js/admin/devices.js', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-brand') !== FALSE || $screen->id == 'revy_page_revy-brand') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();
                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-brand', REVY_ASSET_URL . 'js/admin/brands.js', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-model') !== FALSE || $screen->id == 'revy_page_revy-model') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();
                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-model', REVY_ASSET_URL . 'js/admin/models.js', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-services') !== FALSE) {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();
                    wp_enqueue_style('revy-date-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
                    wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '2.24.0', true);
                    wp_enqueue_script('revy-date-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

                    wp_enqueue_style('owl-carousel', REVY_ASSET_URL . 'plugins/owl-carousel/assets/owl.carousel.min.css', array(), '2.3.4');
                    wp_enqueue_style('owl-carousel-theme-default', REVY_ASSET_URL . 'plugins/owl-carousel/assets/owl.theme.default.min.css', array(), '2.3.4');

                    wp_enqueue_script('image-loaded', REVY_ASSET_URL . 'plugins/image-loaded/imagesloaded.pkgd.min.js', array('jquery'), '3.1.8', false);
                    wp_enqueue_script('owl-carousel', REVY_ASSET_URL . 'plugins/owl-carousel/owl.carousel.min.js', array('jquery', 'image-loaded'), '2.3.4', false);

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-services', REVY_ASSET_URL . 'js/admin/services.js', array('jquery', 'wp-util', 'revy-date-ranger-picker'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-customers') !== FALSE || $screen->id == 'revy_page_revy-customers') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();
                    wp_enqueue_style('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
                    wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '2.24.0', true);
                    wp_enqueue_script('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-customers', REVY_ASSET_URL . 'js/admin/customers.js', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-garage') !== FALSE || $screen->id == 'revy_page_revy-garage') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    $mapbox_api_key = isset($setting['mapbox_api_key']) ? $setting['mapbox_api_key'] : '';
                    if($mapbox_api_key){
                        wp_enqueue_style('mapbox-gl', 'https://api.tiles.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css', array(), '2.3.1');
                        wp_enqueue_style('mapbox-gl-geocoder', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css', array(), '4.7.0');
                        wp_enqueue_script('mapbox-gl', 'https://api.tiles.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js', array(), '1.3.0', true);
                        wp_enqueue_script('mapbox-gl-geocoder', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js', array('mapbox-gl'), '4.7.0', true);
                    }

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-garage', REVY_ASSET_URL . 'js/admin/garages.js', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-calendar') !== FALSE || $screen->id == 'revy_page_revy-calendar') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    wp_enqueue_style('jquery.sumoselect', REVY_ASSET_URL . 'plugins/jquery-sumo/sumoselect.min.css', array(), '3.0.3');
                    wp_enqueue_script('jquery.sumoselect', REVY_ASSET_URL . 'plugins/jquery-sumo/jquery.sumoselect.min.js', array('jquery', 'wp-util'), '3.0.3', true);

                    wp_enqueue_style('air-date-picker', REVY_ASSET_URL . 'plugins/air-datepicker/css/datepicker.min.css', array(), '2.2.3');
                    wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '2.24.0', true);
                    wp_enqueue_script('air-date-picker', REVY_ASSET_URL . 'plugins/air-datepicker/js/datepicker.min.js', array('jquery', 'moment'), '2.3.3', true);
                    $locale = get_locale();
                    $locale = explode('_', $locale)[0];
                    $locale_file = REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.' . $locale . '.js';
                    $locale_path = REVY_DIR_PATH . 'assets/plugins/air-datepicker/js/i18n/datepicker.' . $locale . '.js';
                    if($locale=='pl'){
                        $locale_file = REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.pl-PL.js';
                        $locale_path = REVY_DIR_PATH . 'assets/plugins/air-datepicker/js/i18n/datepicker.pl-PL.js';
                    }
                    if (file_exists($locale_path)) {
                        wp_enqueue_script('air-date-picker-lang', $locale_file, array('jquery', 'air-date-picker'), '2.3.3', true);
                    } else {
                        wp_enqueue_script('air-date-picker-lang', REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.en.js', array('jquery', 'air-date-picker'), '2.3.3', true);
                    }

                    wp_enqueue_style('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
                    wp_enqueue_script('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

                    wp_enqueue_style('revy-full-calendar', REVY_ASSET_URL . 'plugins/full-calendar/fullcalendar.min.css', array(), '3.10.0');
                    wp_enqueue_script('revy-full-calendar', REVY_ASSET_URL . 'plugins/full-calendar/fullcalendar.min.js', array('jquery', 'moment'), '3.10.0', true);
                    wp_enqueue_script('revy-full-calendar-locale', REVY_ASSET_URL . 'plugins/full-calendar/locale-all.js', array('jquery', 'full-calendar'), '3.10.0', true);

                    //get service attribute
                    $db = Revy_DB_Services::instance();
                    $revy_data['service_attributes'] = $db->get_service_attributes();

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-customers', REVY_ASSET_URL . 'js/admin/customers.js', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);
                    wp_enqueue_script('revy-booking', REVY_ASSET_URL . 'js/admin/booking.js', array('jquery', 'wp-util', 'revy-customers'), REVY_PLUGIN_VERSION, true);

                    wp_enqueue_script('revy-calendar', REVY_ASSET_URL . 'js/admin/calendar.js', array('jquery', 'wp-util', 'revy-full-calendar', 'revy-customers', 'revy-booking'), false, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-coupon') !== FALSE || $screen->id == 'revy_page_revy-coupon') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    wp_enqueue_style('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
                    wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '2.24.0', true);
                    wp_enqueue_script('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-coupon', REVY_ASSET_URL . 'js/admin/coupon.js?v=1', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-setting') !== FALSE || $screen->id == 'revy_page_revy-setting') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    wp_enqueue_style('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
                    wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '2.24.0', true);
                    wp_enqueue_script('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-setting', REVY_ASSET_URL . 'js/admin/setting.js', array('jquery', 'wp-util', 'revy-data-ranger-picker'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-custom-css') !== FALSE || $screen->id == 'revy_page_revy-custom-css') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('ace-editor', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ace.js', array('jquery'), '1.3.3', true);
                    wp_enqueue_script('revy-custom-css', REVY_ASSET_URL . 'js/admin/custom-css.js', array('jquery', 'wp-util', 'ace-editor'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-email-template') !== FALSE || $screen->id == 'revy_page_revy-email-template') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    $setting = Revy_DB_Setting::instance();
                    $email_template = $setting->get_email_template();

                    wp_enqueue_script('he', REVY_ASSET_URL . 'js/admin/he.js', array('jquery'), REVY_PLUGIN_VERSION, true);
                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('wp-tinymce');
                    wp_register_script('revy-email-template', REVY_ASSET_URL . 'js/admin/email-template.js', array('jquery','wp-util','he'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-email-template', 'revy_email_data', $email_template);
                    wp_enqueue_script('revy-email-template');

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

                if (stripos($screen->id, 'revy-booking') !== FALSE || $screen->id == 'revy_page_revy-booking') {
                    $this->enqueue_general_script();
                    $this->deregister_script_conflict();

                    wp_enqueue_style('jquery.sumoselect', REVY_ASSET_URL . 'plugins/jquery-sumo/sumoselect.min.css', array(), '3.0.3');
                    wp_enqueue_script('jquery.sumoselect', REVY_ASSET_URL . 'plugins/jquery-sumo/jquery.sumoselect.min.js', array('jquery', 'wp-util'), '3.0.3', true);

                    wp_enqueue_style('air-date-picker', REVY_ASSET_URL . 'plugins/air-datepicker/css/datepicker.min.css', array(), '2.2.3');
                    wp_enqueue_script('moment', REVY_ASSET_URL . 'plugins/date-ranger/moment.min.js', array('jquery'), '2.24.0', true);
                    wp_enqueue_script('air-date-picker', REVY_ASSET_URL . 'plugins/air-datepicker/js/datepicker.min.js', array('jquery', 'moment'), '2.3.3', true);
                    $locale = get_locale();
                    $locale = explode('_', $locale)[0];

                    $locale_file = REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.' . $locale . '.js';
                    $locale_path = REVY_DIR_PATH . 'assets/plugins/air-datepicker/js/i18n/datepicker.' . $locale . '.js';
                    if($locale=='pl'){
                        $locale_file = REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.pl-PL.js';
                        $locale_path = REVY_DIR_PATH . 'assets/plugins/air-datepicker/js/i18n/datepicker.pl-PL.js';
                    }

                    if (file_exists($locale_path)) {
                        wp_enqueue_script('air-date-picker-lang', $locale_file, array('jquery', 'air-date-picker'), '2.3.3', true);
                    } else {
                        wp_enqueue_script('air-date-picker-lang', REVY_ASSET_URL . 'plugins/air-datepicker/js/i18n/datepicker.en.js', array('jquery', 'air-date-picker'), '2.3.3', true);
                    }
                    wp_enqueue_style('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.css', array(), '1.0.0');
                    wp_enqueue_script('revy-data-ranger-picker', REVY_ASSET_URL . 'plugins/date-ranger/daterangepicker.js', array('jquery', 'moment'), '1.0.0', true);

                    //get service attribute
                    $db = Revy_DB_Services::instance();
                    $revy_data['service_attributes'] = $db->get_service_attributes();

                    wp_enqueue_script('revy-main', REVY_ASSET_URL . 'js/admin/main.js', array('jquery', 'clipboard'), REVY_PLUGIN_VERSION, true);
                    wp_localize_script('revy-main', 'revy_data', $revy_data);

                    wp_enqueue_script('revy-customers', REVY_ASSET_URL . 'js/admin/customers.js', array('jquery', 'wp-util'), REVY_PLUGIN_VERSION, true);
                    wp_enqueue_script('revy-booking', REVY_ASSET_URL . 'js/admin/booking.js', array('jquery', 'wp-util', 'revy-customers'), REVY_PLUGIN_VERSION, true);

                    do_action('revy_admin_enqueue', $screen->id);
                    return;
                }

            }
        }

        private function enqueue_general_script()
        {
            wp_enqueue_style('semantic', REVY_ASSET_URL . 'plugins/semantic/semantic.min.css', array(), '2.4.1');
            wp_enqueue_style('semantic-extra', REVY_ASSET_URL . 'plugins/semantic/semantic-extra.css', array(), '1.0.0');

            wp_enqueue_script('semantic', REVY_ASSET_URL . 'plugins/semantic/semantic.min.js', array('jquery'), '2.4.1', false);

            wp_enqueue_script('clipboard', REVY_ASSET_URL . 'plugins/clipboard/clipboard.min.js', array('jquery'), '2.0.4', false);

            wp_enqueue_media();
        }

        public function deregister_script_conflict()
        {
            wp_dequeue_style('modal');

            wp_dequeue_script('jquery.simplemodal');
            wp_deregister_script('jquery.simplemodal');
            wp_dequeue_script('bootstrap');
            wp_dequeue_script('bootstrap-modal');
            wp_deregister_script('bootstrap-modal');
            wp_dequeue_script('jquery-ui-dialog');
            wp_deregister_script('jquery-ui-dialog');
            wp_dequeue_script('jquery-ui-datepicker');
            wp_deregister_script('jquery-ui-datepicker');
        }
    }
}
