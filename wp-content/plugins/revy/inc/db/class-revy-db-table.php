<?php

if (!class_exists('Revy_DB_Table')) {
    class Revy_DB_Table
    {
        private static $instance = NULL;

        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        function create_tables()
        {
            global $wpdb;
            $result = 0;
            $charset_collate = $wpdb->get_charset_collate();
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            //devices table
            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_devices(
                      rd_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      rd_order int(1) NOT NULL DEFAULT 1,
                      rd_image_id int(6) NOT NULL DEFAULT 0,
                      rd_icon varchar(50),
                      rd_name text,
                      rd_create_date datetime NOT NULL,
                      rd_active int(1) NOT NULL DEFAULT 1,
                      PRIMARY KEY  (rd_id)      
                    ) $charset_collate;";
            $result = dbDelta($tables);

            // brand
            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_brands(
                      rb_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      rb_order int(1) NOT NULL DEFAULT 1,
                      rb_image_id int(6) NOT NULL DEFAULT 0,
                      rb_name text,
                      rb_device_ids text,
                      rb_create_date datetime NOT NULL,
                      rb_active int(1) NOT NULL DEFAULT 1,
                      PRIMARY KEY  (rb_id)      
                    ) $charset_collate;";
            $result = dbDelta($tables);

            // brand
            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_models(
                      rm_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      rm_order int(1) NOT NULL DEFAULT 1,
                      rm_image_id int(6) NOT NULL DEFAULT 0,
                      rm_name text,
                      rm_device_id int,
                      rm_brand_id int,
                      rm_group varchar(200),
                      rm_create_date datetime NOT NULL,
                      rm_active int(1) NOT NULL DEFAULT 1,
                      PRIMARY KEY  (rm_id)      
                    ) $charset_collate;";
            $result = dbDelta($tables);

            // garages
            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_garages(
                      rg_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      rg_image_id int(6) NOT NULL DEFAULT 0,
                      rg_order int(1) NOT NULL DEFAULT 1,
                      rg_name varchar(200),
                      rg_email varchar(200),
                      rg_phone varchar(200),
                      rg_address varchar(200),
                      rg_map varchar(50),
                      rg_latitude varchar(50),
                      rg_longitude varchar(50),
                      rg_description text,
                      rg_active int(1) NOT NULL DEFAULT 1,
                      rg_create_date datetime NOT NULL,
                      PRIMARY KEY  (rg_id)
                    ) $charset_collate;";
            dbDelta($tables);

            // services
            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_services(
                      s_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      s_order int(1) NOT NULL DEFAULT 1,
                      s_image_id int(6) NOT NULL DEFAULT 0,
                      s_name text,
                      s_description text,
                      s_model_id int(6) NOT NULL DEFAULT 0,
                      s_tax decimal(10,2),
                      s_min_price decimal(10,2),
                      s_duration int(6),
                      s_break_time int(6),
                      s_maximum_slot int(6),     
                      s_garage_ids text,
                      s_allow_booking_online int(1) NOT NULL DEFAULT 1,
                      s_create_date datetime NOT NULL,
                      PRIMARY KEY  (s_id)      
                    ) $charset_collate;";
            $result = dbDelta($tables);

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_services_garage(
                      s_garage_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      s_id int(6) NOT NULL DEFAULT 0,
                      rg_id int(6) NOT NULL DEFAULT 0,
                      PRIMARY KEY  (s_garage_id)      
                    ) $charset_collate;";
            $result = dbDelta($tables);

            // services price
            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_services_price(
                      sp_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      s_id int,
                      s_attr_code varchar(100),
                      s_attr_title varchar(200),
                      s_attr_value varchar(200),
                      s_price decimal(10,2),  
                      PRIMARY KEY  (sp_id)      
                    ) $charset_collate;";
            $result = dbDelta($tables);

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_services_schedule(
                      ss_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      s_id int(6) NOT NULL DEFAULT 0,
                      ss_day int(1),
                      ss_work_hour_start varchar(4),
                      ss_work_hour_end varchar(4),
                      ss_enable int(1) NOT NULL DEFAULT 1,
                      ss_create_date datetime NOT NULL,
                      PRIMARY KEY  (ss_id)
                    ) $charset_collate;";
            dbDelta($tables);

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_services_day_off(
                      dof_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      s_id int(6),
                      dof_name varchar(200),
                      dof_start date,
                      dof_end date,
                      dof_create_date datetime NOT NULL,
                      PRIMARY KEY  (dof_id)
                    ) $charset_collate;";
            dbDelta($tables);

            // customer
            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_customers(
                      c_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      c_first_name varchar(200),
                      c_last_name varchar(200),
                      c_gender int(1) NOT NULL DEFAULT 0, /* 0: Male, 1: Female */ 
                      c_phone_code varchar(200),
                      c_phone varchar(200),
                      c_email varchar(200),
                      c_address varchar(500),
                      c_postal_code varchar(200),
                      c_city varchar(200),
                      c_country varchar(200),
                      c_dob date,
                      c_user_id int(6), /* ID of wordpress user */
                      c_description text,
                      c_last_booking datetime DEFAULT NULL,
                      c_create_date datetime NOT NULL,
                      c_code varchar(200),
                      PRIMARY KEY  (c_id)
                    ) $charset_collate;";
            dbDelta($tables);

            // Coupon
            $tables = " CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_coupons(
                      cp_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      cp_code varchar(200),
                      cp_description text,
                      cp_discount_type int(1),  
                      cp_amount decimal(10,2),
                      cp_start_date datetime,
                      cp_expire datetime,
                      cp_apply_to varchar(500),
                      cp_exclude varchar(500),
                      cp_times_use int(6),
                      cp_use_count int(6) NOT NULL DEFAULT 0,
                      cp_create_date datetime NOT NULL,
                      PRIMARY KEY  (cp_id)
                    ) $charset_collate;";

            // cp_discount_type: 1 -> percent, 2: fix discount
            dbDelta($tables);

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_coupon_logs(
                      cp_log_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      cp_id int,
                      c_email varchar(200),
                      s_id int(6),
                      cp_log_create_date datetime NOT NULL,
                      PRIMARY KEY  (cp_log_id) 
                    ) $charset_collate;";
            dbDelta($tables);

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_booking(
                      b_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      b_customer_id int(6),
                      b_customer_address varchar(500),
                      b_customer_city varchar(500),
                      b_customer_postal_code varchar(100),
                      b_customer_country varchar(100),
                      b_garage_id int(6),
                      b_device_id int(6),
                      b_brand_id int(6),
                      b_model_id int(6),
                      b_date date,
                      b_time int(6),
                      b_total_amount decimal(10,2), 
                      b_coupon_id int(6),
                      b_coupon_code text,
                      b_discount decimal(10,2) NOT NULL DEFAULT 0,
                      b_total_tax decimal(10,2) NOT NULL DEFAULT 0,
                      b_total_pay decimal(10,2) NOT NULL DEFAULT 0,
                      b_gateway_type varchar(50),
                      b_gateway_status varchar(100), 
                      b_gateway_id varchar(100), 
                      b_gateway_response varchar(500), 
                      b_gateway_execute_url varchar(200), 
                      b_description text,
                      b_pay_now int(1) NOT NULL DEFAULT 0,
                      b_process_status int(1),
                      b_create_date datetime NOT NULL,
                      b_send_notify int(1) NOT NULL DEFAULT 0,
                      b_status_note text,
                      b_canceled_by_client int(1) DEFAULT 0,
                      b_delivery_method int(6),
                      PRIMARY KEY  (b_id)
                    ) $charset_collate;";
            dbDelta($tables);
            //b_process_status : 0 -> Pending, 1 -> Approved, 2 -> Cancel, 3 -> Reject, -1 -> Pending for payment gateway
            //b_gateway_status : 0 -> Pending, 1 -> Payment, 2 -> Cancel, 3 -> Reject
            //b_delivery_method : 1-> FixItHome, 2: Carry In , 3: Mail-in Repair

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_booking_detail(
                      bd_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      b_id int(6),
                      b_service_id int(6),
                      b_service_duration int(6),
                      b_service_break_time int(6),
                      b_attr_title varchar(100),
                      b_attr_value varchar(100),
                      b_quantity int(6),
                      b_price decimal(10,2),
                      b_service_tax int(6) NOT NULL DEFAULT 0,
                      b_service_tax_amount decimal(10,2) NOT NULL DEFAULT 0,
                      PRIMARY KEY  (bd_id)
                    ) $charset_collate;";
            dbDelta($tables);

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_services_product(
                      sp_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      service_id int,
                      product_id int,
                      PRIMARY KEY  (sp_id) 
                    ) $charset_collate;";
            dbDelta($tables);

            $tables = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rp_wc_order_service(
                      wc_id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                      b_id int,
                      service_id int,
                      PRIMARY KEY  (wc_id) 
                    ) $charset_collate;";
            dbDelta($tables);

        }

    }
}
