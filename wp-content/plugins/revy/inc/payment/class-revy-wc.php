<?php
/**
 * Created by PhpStorm.
 * User: PhuongTH
 * Date: 6/28/2020
 * Time: 2:14 PM
 */

if (!class_exists('Revy_WC')) {
    class Revy_WC
    {
        private static $instance = NULL;

        public static function getInstance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function init()
        {

            add_filter('Revy_Payment_booking', array($this, 'revy_wc_booking'), 10, 3);

            /** override check is product purchaseable */
            add_filter('woocommerce_is_purchasable', array($this, 'wc_is_purchasable'), 100, 2);

            /** remove product link detail to boat link detail in cart page */
            add_filter('woocommerce_cart_item_permalink', array($this, 'wc_cart_item_permalink'), 10, 3);

            /** hook for cart page */
            add_action('woocommerce_after_cart_item_name', array($this, 'wc_cart_item_name'), 10, 2);

            //set maximum for quantity in cart page
            add_filter('woocommerce_cart_item_quantity', array($this, 'wc_cart_item_quantity'), 10, 3);

            /**  Display booking meta to review order in checkout page */
            add_filter('woocommerce_cart_item_name', array($this, 'wc_checkout_item_name'), 10, 3);

            /** Add class to item in review order checkout page */
            add_filter('woocommerce_cart_item_class', array($this, 'wc_checkout_item_class'), 10, 3);

            /** Update service price in cart and checkout page */
            add_action('woocommerce_before_calculate_totals', array($this, 'wc_before_calculate_totals'), 20, 1);

            /** Update order meta in checkout page */
            add_action('woocommerce_checkout_update_order_meta', array($this, 'wc_checkout_update_order_meta'), 10, 2);

            /** Set quantity when apply coupon */
            add_filter('woocommerce_coupon_get_apply_quantity', array($this, 'wc_coupon_get_apply_quantity'), 10, 4);

            /** Process when click check out process button */
            add_action('woocommerce_checkout_order_processed', array($this, 'wc_checkout_order_processed'), 10, 3);

            /** Update booking order after payment */
            add_action('woocommerce_thankyou', array($this, 'wc_update_order_after_payment'), 100, 1);

            /** Display service meta for each item at backend order detail */
            add_action('woocommerce_after_order_itemmeta', array($this,'wc_after_order_itemmeta'), 10, 3);

            if (is_admin()) {
                add_filter('parse_query', array($this, 'admin_exclude_service_product_in_query'));
                add_filter('wp_count_posts', array($this, 'wp_count_posts'), 10, 3);
            }
        }


        function wc_is_purchasable($is_purchasable, $product)
        {
            if (!$is_purchasable) {
                global $wpdb;
                $sql = "SELECT service_id  
                    FROM {$wpdb->prefix}rp_services_product 
                    WHERE product_id=%d";

                $sql = $wpdb->prepare($sql, $product->get_id());
                $services = $wpdb->get_results($sql);
                $is_purchasable = is_countable($services) && count($services) > 0;

            }
            return $is_purchasable;
        }

        function wc_cart_item_name($cart_item, $cart_item_key)
        {
            if (isset($cart_item['revy_date']) && $cart_item['revy_s_date']) {
                echo '<div class="service-meta"><span style="font-size: 14px; font-weight: 600">' . esc_html__('Time: ', 'revy') . '</span><span style="font-size: 14px">' . $cart_item['revy_date'] . '</span></div>';
            }

            if (isset($cart_item['revy_attr_title']) && $cart_item['revy_attr_value'] && $cart_item['revy_model_name']) {
                echo '<div class="service-meta"><span style="font-size: 14px; font-weight: 600">' . $cart_item['revy_model_name'] . '</span>&nbsp;<span style="font-size: 14px">' . $cart_item['revy_attr_title'] . ' ' . $cart_item['revy_attr_value'] . '</span></div>';

            }

        }

        /**  Display booking meta to review order in checkout page */
        function wc_checkout_item_name($name, $cart_item, $cart_item_key)
        {
            if (is_checkout()) {
                if (isset($cart_item['revy_date']) && $cart_item['revy_s_date']) {
                    $name .= '<div class="service-meta"><span style="font-size: 14px; font-weight: 600">' . esc_html__('Time: ', 'revy') . '</span><span style="font-size: 14px">' . $cart_item['revy_date'] . '</span></div>';
                }

                if (isset($cart_item['revy_attr_title']) && $cart_item['revy_attr_value'] && $cart_item['revy_model_name']) {
                    $name .= '<div class="service-meta"><span style="font-size: 14px; font-weight: 600">' . $cart_item['revy_model_name'] . '</span> &nbsp;<span style="font-size: 14px">' . $cart_item['revy_attr_title'] . ' ' . $cart_item['revy_attr_value'] . '</span></div>';
                }
            }
            return $name;
        }

        /** Add class to item in review order checkout page */
        function wc_checkout_item_class($item_class, $cart_item, $cart_item_key)
        {
            if (isset($cart_item['revy_s_id'])) {
                $item_class .= ' fat-sb-item item-' . $cart_item['revy_s_id'];
            }
            return $item_class;
        }

        /** remove product link detail to boat link detail in cart page */
        function wc_cart_item_permalink($permalink, $cart_item, $cart_item_key)
        {
            if (isset($cart_item['revy_booking_id'])) {
                $permalink = '#';
            }
            return $permalink;
        }

        /** Update service price in cart and checkout page */
        function wc_before_calculate_totals($cart_obj)
        {
            foreach ($cart_obj->get_cart() as $cart_item) {
                if (isset($cart_item['revy_total_pay']) && $cart_item['revy_total_pay']) {
                    $cart_item['data']->set_price($cart_item['revy_total_pay']);
                }
            }
        }

        function wc_checkout_update_order_meta($order_id, $data)
        {

            $order = wc_get_order($order_id);
            $items = $order->get_items();
            $product_id = $order_item_id = 0;

            $item_meta = '';
            $service_order_meta = array();
            $s_ids = array();
            $booking_id = 0;
            $b_total_pay = 0;
            foreach ($items as $item) {
                $order_item_id = $item->get_id();
                $product_id = wc_get_order_item_meta($order_item_id, '_product_id', true);
                $item_meta = $this->get_item_revy_meta($product_id);
                if (isset($item_meta['revy_s_id'])) {
                    $s_ids[] = $item_meta['revy_s_id'];
                    $booking_id = isset($item_meta['revy_booking_id']) ? $item_meta['revy_booking_id'] : 0;
                    $b_total_pay += (floatval($item_meta['revy_price']) * intval($item_meta['revy_quantity']));
                    $service_order_meta['revy_model_name'] = isset($item_meta['revy_model_name']) ? $item_meta['revy_model_name'] : '';
                    $service_order_meta['revy_attr_title'] = isset($item_meta['revy_attr_title']) ? $item_meta['revy_attr_title'] : '';
                    $service_order_meta['revy_attr_value'] = isset($item_meta['revy_attr_value']) ? $item_meta['revy_attr_value'] : '';
                    $service_order_meta['revy_date'] = isset($item_meta['revy_date']) ? $item_meta['revy_date']: '';
                    $service_order_meta['revy_s_date'] = isset($item_meta['revy_s_date']) ? $item_meta['revy_s_date'] : '';
                    $service_order_meta['revy_s_name'] = isset($item_meta['revy_s_name']) ? $item_meta['revy_s_name'] : '';
                    $service_order_meta['revy_s_id'] = isset($item_meta['revy_s_id']) ? $item_meta['revy_s_id'] : '';
                    $service_order_meta['revy_booking_id'] = $booking_id;
                    $service_order_meta['revy_customer_id'] = isset($item_meta['revy_customer_id']) ? $item_meta['revy_customer_id']: '';

                    wc_update_order_item_meta($order_item_id, 'service_order_meta', $service_order_meta);
                }
            }

            //syn service from checkout page to revy_booking table
            global $wpdb;
            $sql = "DELETE FROM {$wpdb->prefix}rp_booking_detail WHERE b_id = %d AND b_service_id NOT IN (". implode(',', $s_ids). ")";
            $sql = $wpdb->prepare($sql, $booking_id);
            $result = $wpdb->query($sql);
            if($result > 0){
                $sql = "SELECT  SUM(b_price * b_quantity) AS b_total_pay, SUM(b_service_tax_amount) AS b_service_tax_amount
                        FROM {$wpdb->prefix}rp_booking_detail 
                        WHERE b_id=%d";
                $sql = $wpdb->prepare($sql, $booking_id);
                error_log($sql);
                $payment_info = $wpdb->get_results($sql);
                if(count($payment_info)>0){
                    $payment_info = $payment_info[0];
                    $b_total_amount = $payment_info->b_total_pay + $payment_info->b_service_tax_amount;
                    $sql = "UPDATE {$wpdb->prefix}rp_booking SET b_total_amount = %d, b_total_pay = (%d - b_discount), b_total_tax= %d WHERE b_id= %d";
                    $sql = $wpdb->prepare($sql, $b_total_amount, $b_total_amount, $payment_info->b_service_tax_amount, $booking_id);
                    error_log($sql);
                    $wpdb->query($sql);
                }

            }
        }

        function wc_coupon_get_apply_quantity($apply_quantity, $item, $coupon, $wc_discounts)
        {
            if (isset($item['revy_booking_id'])) {
                $apply_quantity = 1;
            }
            return $apply_quantity;
        }

        function wc_cart_item_quantity($product_quantity, $cart_item_key, $cart_item)
        {
            if (isset($cart_item['revy_booking_id'])) {
                $_product = wc_get_product($cart_item['product_id']);
                $product_quantity = woocommerce_quantity_input(array(
                    'input_name' => "cart[{$cart_item_key}][qty]",
                    'input_value' => $cart_item['quantity'],
                    'max_value' => 1,
                    'min_value' => 1,
                    'product_name' => $_product->get_name(),
                ), $_product, false);
                $product_quantity .= '<div class="service-quantity">1</div>';

            }
            return $product_quantity;
        }

        function wc_checkout_order_processed($order_id, $posted_data, $order)
        {
            global $wpdb;
            $items = $order->get_items();
            $setting = Revy_DB_Setting::instance();
            $setting = $setting->get_setting();
            $process_status = isset($setting['b_process_status']) ? $setting['b_process_status'] : 0;
            $s_ids = array();
            foreach ($items as $item_id => $item) {
                $product_id = $item->get_product_id();
                $service_order_meta = wc_get_order_item_meta($item_id, 'service_order_meta', true);
                $s_ids[] = $service_order_meta['revy_s_id'];
                if (isset($service_order_meta['revy_booking_id']) && $service_order_meta['revy_booking_id']) {
                    $wpdb->update($wpdb->prefix . 'rp_booking', array('b_process_status' => $process_status), array('b_id' => $service_order_meta['revy_booking_id']));
                }
            }
        }

        public function wc_update_order_after_payment($order_id)
        {
            global $wpdb;
            $order = wc_get_order($order_id);
            $items = $order->get_items();
            $sql = '';
            foreach ($items as $item_id => $item) {
                $product_id = $item->get_product_id();
                $service_order_meta = wc_get_order_item_meta($item_id, 'service_order_meta', true);
                if (isset($service_order_meta['revy_booking_id']) && $service_order_meta['revy_booking_id']) {
                    $booking_id = $service_order_meta['revy_booking_id'];

                    $setting = Revy_DB_Setting::instance();
                    $setting = $setting->get_setting();
                    $process_status = isset($setting['b_process_status']) ? $setting['b_process_status'] : 0;
                    $sql = "UPDATE {$wpdb->prefix}rp_booking SET b_pay_now=1, b_process_status=%d, b_gateway_id = %d, b_gateway_status = %d WHERE b_id = %d";
                    $sql = $wpdb->prepare($sql, $process_status, $order_id, 1, $booking_id);
                    $wpdb->query($sql);
                }
            }
        }

        public function admin_exclude_service_product_in_query($query)
        {
            global $pagenow, $wpdb;
            $type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
            if ($pagenow == 'edit.php' && $type == 'product') {
                $ids = array();
                $sql = "SELECT product_id FROM {$wpdb->prefix}rp_services_product WHERE 1=%d";
                $sql = $wpdb->prepare($sql, 1);
                $service_products = $wpdb->get_results($sql);
                foreach ($service_products as $p) {
                    $ids[] = $p->product_id;
                }
                $query->set('post__not_in', $ids);
            }
        }

        public function wp_count_posts($counts, $type, $perm)
        {
            if ($type === 'product') {
                global $wpdb;
                $sql = "SELECT product_id FROM {$wpdb->prefix}rp_services_product WHERE 1=%d";
                $sql = $wpdb->prepare($sql, 1);
                $service_products = $wpdb->get_results($sql);
                if (is_countable($service_products) && count($service_products) > 0) {
                    $counts->draft = $counts->draft - count($service_products);
                    $counts->draft = $counts->draft > 0 ? $counts->draft : 0;
                }
            }
            return $counts;
        }

        function wc_after_order_itemmeta($item_id, $item, $product ){
           $service_order_meta = wc_get_order_item_meta($item_id, 'service_order_meta', true);
           if(isset($service_order_meta['revy_model_name']) && isset($service_order_meta['revy_attr_title'])  && isset($service_order_meta['revy_attr_value']) ){
                echo '<div class="service-meta">'.
                        '<span style="font-weight: 600">'.$service_order_meta['revy_model_name'].'.</span>'.
                        '<span style="padding-left: 10px;">'. $service_order_meta['revy_attr_title'].'</span>'.
                        '<span style="padding-left: 5px;">'. $service_order_meta['revy_attr_value'].'</span>'.
                        '</div>';
               echo '<div class="service-meta"><span style="font-size: 14px; font-weight: 600">' . esc_html__('Time: ', 'revy') . '</span><span style="font-size: 14px">' . $service_order_meta['revy_date'] . '</span></div>';
               echo '<div class="service-meta"><span style="font-size: 14px; font-weight: 600">' . esc_html__('Booking ID: ', 'revy') . '</span><span style="font-size: 14px">' . $service_order_meta['revy_booking_id'] . '</span></div>';
           }
        }

        public function revy_wc_booking($result, $booking_id, $booking)
        {
            if ($booking['b_total_pay'] == 0 || $booking['b_gateway_type'] != 'woocommerce') {
                return $result;
            }

            if (!class_exists('WC')) {
                $result = array(
                    'result' => -1,
                    'message' => esc_html__('WooCommerce is not installed or activated', 'revy')
                );
            }
            if ($booking_id) {
                global $wpdb;

                $sql = "UPDATE {$wpdb->prefix}rp_booking SET b_process_status=-1 WHERE b_id = %d";
                $sql = $wpdb->prepare($sql, $booking_id);
                $wpdb->query($sql);


                $sql = "SELECT rm_name, s_image_id, s_name, b_service_id, b_attr_title, b_attr_value, b_quantity, b_price
                        FROM {$wpdb->prefix}rp_booking_detail AS RBD
                        LEFT JOIN {$wpdb->prefix}rp_services AS RS
                        ON RBD.b_service_id = RS.s_id
                        LEFT JOIN {$wpdb->prefix}rp_models AS RM
                        ON RS.s_model_id = RM.rm_id
                        WHERE b_id = %d";
                $sql = $wpdb->prepare($sql, $booking_id);
                $booking_detail = $wpdb->get_results($sql);

                $product_id = 0;
                $products = array();
                foreach ($booking_detail as $bd) {
                    $product_id = $this->getProductId($bd->b_service_id);
                    if (!$product_id) {
                        $product_id = $this->addProduct($bd->b_service_id, $bd->s_name, $bd->s_image_id);
                    }
                    $products[] = array(
                        'product_id' => $product_id,
                        'model_name' => $bd->rm_name,
                        's_name' => $bd->s_name,
                        's_id' => $bd->b_service_id,
                        'b_attr_title' => $bd->b_attr_title,
                        'b_attr_value' => $bd->b_attr_value,
                        'b_quantity' => $bd->b_quantity,
                        'b_price' => $bd->b_price
                    );
                }

                WC()->cart->empty_cart();
                $date = DateTime::createFromFormat('Y-m-d', $booking['b_date']);
                $date_format = get_option('date_format');

                $work_hours = Revy_Utils::getWorkHours(5);

                $date_label = isset($work_hours[$booking['b_time']]) ? $work_hours[$booking['b_time']] : '';
                $date_label .= ', ' . date_i18n($date_format, $date->format('U'));
                foreach ($products as $p) {
                    $result = WC()->cart->add_to_cart($p['product_id'], 1, 0, array(), array(
                        'revy_model_name' => $p['model_name'],
                        'revy_s_id' => $p['s_id'],
                        'revy_s_name' => $p['s_name'],
                        'revy_date' => $date_label,
                        'revy_s_date' => $booking['b_date'],
                        'revy_total_pay' => ($p['b_quantity'] * $p['b_price']),
                        'revy_attr_title' => $p['b_attr_title'],
                        'revy_attr_value' => $p['b_attr_value'],
                        'revy_price' => $p['b_price'],
                        'revy_customer_id' => $booking['b_customer_id'],
                        'revy_booking_id' => $booking_id,
                        'revy_quantity' => $p['b_quantity'],
                    ));
                }

                $message = !function_exists('wc_get_cart_url') ? esc_html__('Cart and checkout page not found', 'repair') : '';
                $message = $result ? $message : esc_html__('Can not add service to cart', 'revy');
                return array(
                    'result' => $result && function_exists('wc_get_cart_url') ? 1 : -1,
                    'redirect_url' => function_exists('wc_get_cart_url') ? wc_get_cart_url() : '',
                    'message' => $message
                );
            } else {
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data is invalid', 'revy')
                );
            }
        }

        public function getProductId($service_id)
        {
            global $wpdb;
            $sql = "SELECT product_id 
                    FROM {$wpdb->prefix}rp_services_product 
                    WHERE service_id=%d";

            $sql = $wpdb->prepare($sql, $service_id);
            $product = $wpdb->get_results($sql);
            if (is_countable($product) && count($product) > 0) {
                return $product[0]->product_id;
            }
            return 0;
        }

        public function addProduct($service_id, $service_name, $thumb_id)
        {
            global $wpdb;
            $post = array(
                'post_content' => '',
                'post_status' => "draft",
                'post_title' => $service_name,
                'post_parent' => '',
                'post_type' => "product",
            );

            $post_id = wp_insert_post($post);

            add_post_meta('revy_product_' . $service_id, $service_id, true);

            if ($post_id instanceof WP_Error) {
                return -1;
            }
            if ($thumb_id) {
                add_post_meta($post_id, '_thumbnail_id', $thumb_id);
            }
            wp_set_object_terms($post_id, 'simple', 'product_type');
            update_post_meta($post_id, '_visibility', 'visible');
            update_post_meta($post_id, '_stock_status', 'instock');
            update_post_meta($post_id, '_downloadable', 'no');
            update_post_meta($post_id, '_regular_price', 0);
            update_post_meta($post_id, '_price', 0);

            $wpdb->insert($wpdb->prefix . 'rp_services_product', array(
                'service_id' => $service_id,
                'product_id' => $post_id
            ));

            return $post_id;
        }

        function get_item_revy_meta($product_id)
        {
            $cart_data = WC()->session->get('cart');
            foreach ($cart_data as $key => $value) {
                if (isset($value['product_id']) && $value['product_id'] == $product_id) {
                    return $value;
                }
            }
            return;
        }

    }

    $wc = Revy_WC::getInstance();
    $wc->init();

}