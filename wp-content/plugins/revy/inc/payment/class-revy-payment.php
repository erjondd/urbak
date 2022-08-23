<?php
/**
 * Created by PhpStorm.
 * User: RoninWP
 * Date: 6/13/2019
 * Time: 4:52 PM
 */

use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

if (!class_exists('Revy_Payment')) {
    class Revy_Payment{
        function __construct()
        {

        }

        /**
         * Get paypal access token
         * @param $url
         * @param $postArgs
         * @return mixed
         */
        private function get_paypal_access_token($url, $postArgs)
        {
            $setting_db = new Revy_DB_Setting();
            $setting = $setting_db->get_setting();
            $client_id = isset($setting['paypal_client_id']) ? $setting['paypal_client_id'] : '';
            $secret_key = isset($setting['paypal_secret']) ? $setting['paypal_secret'] : '';

            if($client_id && $secret_key){
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_USERPWD, $client_id . ":" . $secret_key);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
                $response = curl_exec($curl);
                if (empty($response)) {
                    curl_close($curl);
                    return array(
                        'code' => -1,
                        'message' => curl_error($curl)
                    );

                } else {
                    $info = curl_getinfo($curl);
                    curl_close($curl);
                    if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                        return array(
                            'code' => -1,
                            'message' => $response
                        );
                    }
                }
                $response = json_decode($response);
                return array(
                    'code' => 1,
                    'access_token' => $response->access_token
                );
            }else{
                return array(
                    'code' => -1,
                    'message' => esc_html__('Please input Paypal Client ID and Secret','revy')
                );
            }
        }

        /**
         * Execute paypal request
         * @param $url
         * @param $jsonData
         * @param $access_token
         * @return array|mixed|object
         */
        private function execute_paypal_request($url, $jsonData, $access_token)
        {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
                'Accept: application/json',
                'Content-Type: application/json'
            ));

            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
            $response = curl_exec($curl);
            if (empty($response)) {
                curl_close($curl);
                die(curl_error($curl));
            } else {
                $info = curl_getinfo($curl);
                curl_close($curl);
                if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                    echo "Received error: " . $info['http_code'] . "\n";
                    echo "Raw response:" . $response . "\n";
                    die();
                }
            }
            $jsonResponse = json_decode($response, TRUE);
            return $jsonResponse;
        }

        public function payment($booking_id, $customer, $service_name,  $total_price, $tax, $currency, $description, $current_url){
            $setting_db = new Revy_DB_Setting();
            $setting = $setting_db->get_setting();
            $host = isset($setting['paypal_sandbox']) && $setting['paypal_sandbox'] =='live' ? 'https://api.paypal.com' : 'https://api.sandbox.paypal.com';

            $url = $host . '/v1/oauth2/token';
            $postArgs = 'grant_type=client_credentials';
            $access_token = $this->get_paypal_access_token($url, $postArgs);
            if($access_token['code']!=1){
                $message = esc_html__('Cannot get access token. Please check Paypal\'s clientID and secret','revy');
                return array(
                    'result' => $access_token['code'],
                    'message' => $message
                );
            }
            $url = $host . '/v1/payments/payment';
            $cancel_link = add_query_arg(array('source' => 'revy_booking','action' => 'paypal_cancel'), $current_url);
            $return_link = add_query_arg(array('source' => 'revy_booking','action' => 'payment_return'), $current_url);
            $subtotal = $total_price - $tax;

            $payment = array(
                'intent' => 'sale',
                "redirect_urls" => array(
                    "return_url" => $return_link,
                    "cancel_url" => $cancel_link
                ),
                'payer' => array("payment_method" => "paypal"),
            );
            $payment['transactions'][0] = array(
                'amount' => array(
                    'total' => number_format($total_price,2),
                    'currency' => $currency,
                    'details' => array(
                        'subtotal' => number_format($subtotal,2),
                        'tax' =>  $tax,
                        'shipping' => '0.00',
                    )
                ),
                'description' => $description,
                "custom" => $customer,
                "invoice_number" => $booking_id,
            );

            $payment['transactions'][0]['item_list']['items'][] = array(
                'quantity' => 1,
                'name' => $service_name,
                'price' =>  number_format($total_price,2),
                'currency' => $currency,
                'sku' => uniqid(),
            );

            $jsonEncode = json_encode($payment);
            $json_response = $this->execute_paypal_request($url, $jsonEncode, $access_token['access_token']);

            $payment_approval_url = '';
            $payment_execute_url = '';
            foreach ($json_response['links'] as $link) {
                if ($link['rel'] == 'approval_url') {
                    $payment_approval_url = $link['href'];
                }
                if ($link['rel'] == 'execute') {
                    $payment_execute_url = $link['href'];
                }
            }
            global $wpdb;
            $b_gateway_response = 'paypal_approval_url: '.$payment_approval_url. ' ,paypal_result:'.serialize($json_response);
            
            $wpdb->query("UPDATE {$wpdb->prefix}rp_booking SET b_gateway_id = '{$json_response['id']}', b_gateway_response='{$b_gateway_response}', b_gateway_execute_url='{$payment_execute_url}'
                                      WHERE b_id = ({$booking_id})");

            return array(
                'result' => 1,
                'approval_url' => $payment_approval_url
            );
        }

        public function payment_update_status(){

            if(isset($_GET['source']) && $_GET['source'] ==='revy_booking' && isset($_GET['token']) ){
                global $wp;

                $paypal_id = isset($_GET['paymentId']) && $_GET['paymentId'] ? $_GET['paymentId'] : '';
                $payer_ID = isset($_GET['PayerID']) && $_GET['PayerID'] ? $_GET['PayerID'] : '' ;

                // validate payment status
                $setting_db = new Revy_DB_Setting();
                $setting = $setting_db->get_setting();
                $success_url = isset($setting['success_page']) ? $setting['success_page'] : '';
                $error_url =  isset($setting['error_page']) ? $setting['error_page'] : '';

                if(isset($_REQUEST['action']) && $_REQUEST['action']=='paypal_cancel'){
                    if ( wp_redirect( $error_url ) ) {
                        exit;
                    }
                }

                $host = isset($setting['paypal_sandbox']) && $setting['paypal_sandbox'] =='live' ? 'https://api.paypal.com' : 'https://api.sandbox.paypal.com';
                $url = $host . '/v1/oauth2/token';
                $postArgs = 'grant_type=client_credentials';
                $access_token = $this->get_paypal_access_token($url, $postArgs);
                if($access_token['code']!=1){
                    if ( wp_redirect( $error_url ) ) {
                        exit;
                    }
                }

                $url = $host . '/v1/payments/payment/'. $paypal_id;
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $access_token['access_token'],
                    'Content-Type: application/json'
                ));

                global $wpdb;
                $bookings = $wpdb->get_results('SELECT b_id, b_gateway_execute_url  FROM ' . $wpdb->prefix . 'rp_booking WHERE b_gateway_id="'. $paypal_id .'"  ORDER BY b_id ASC');

                if(!isset($bookings[0]->b_id)){
                    if ( wp_redirect( $error_url ) ) {
                        exit;
                    }
                }
                $bookings = $bookings[0];

                $response = curl_exec($curl);
                if (empty($response)) {
                    curl_close($curl);
                } else {
                    $info = curl_getinfo($curl);
                    curl_close($curl);
                    if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                        error_log('Received error:'. $info['http_code']);
                        error_log('Raw response:' . $response);
                        $error_url = $error_url ? $error_url : home_url( $wp->request );
                        $wpdb->query("DELETE FROM {$wpdb->prefix}rp_booking WHERE b_id = ({$bookings->b_id})");

                        if ( wp_redirect( $error_url ) ) {
                            exit;
                        }
                    }
                }

                $jsonResponse = json_decode($response, TRUE);
                if(isset($jsonResponse['state']) && $jsonResponse['state']=='created'){
                    //execute payment
                    $jsonEncode =  json_encode(array(
                        'payer_id' => $payer_ID
                    ));
                    $jsonResponse = $this->execute_paypal_request($bookings->b_gateway_execute_url, $jsonEncode, $access_token['access_token']);
                    if(isset($bookings->b_id)){
                        $pay_now =  $jsonResponse['state']=='approved' ? 1 : 0;
                        $gateway_response = serialize($jsonResponse);
                        $b_process_status = isset($setting['b_process_status']) ? $setting['b_process_status'] : 0;
                        if( $jsonResponse['state']=='approved' ||  $jsonResponse['state']=='created'){
                            $wpdb->query("UPDATE {$wpdb->prefix}rp_booking SET b_process_status = {$b_process_status}, b_gateway_status = '{$jsonResponse['state']}', b_pay_now={$pay_now}, b_gateway_response='{$gateway_response}'
                                      WHERE b_id = ({$bookings->b_id})");

                            do_action('revy_booking_completed', $bookings->b_id);
                        }else{
                            $wpdb->query("DELETE FROM {$wpdb->prefix}rp_booking WHERE b_id = ({$bookings->b_id})");
                        }
                        //send mail
                        try{
                            $booking_db = Revy_DB_Bookings::instance();
                            $booking_db->send_booking_mail($bookings->b_id);
                            $wpdb->query("UPDATE {$wpdb->prefix}rp_booking SET  b_send_notify = 1
                                      WHERE b_id = ({$bookings->b_id})");
                        }catch(Exception $err){}
                    }
                }

                $success_url = $success_url ? get_permalink($success_url) : home_url( $wp->request );
                $success_url = add_query_arg(array('bid' => $bookings->b_id), $success_url);
                if ( wp_redirect( $success_url ) ) {
                    exit;
                }
            }
        }

        public function stripe_payment_create($booking_id){

            require_once(REVY_DIR_PATH . '/inc/payment/stripe-php/init.php');
            $result = array();
            global $wpdb;
            $sql = "SELECT c_first_name, c_last_name, c_email, b_date, b_time, b_total_pay
                    FROM {$wpdb->prefix}rp_booking
                    LEFT JOIN {$wpdb->prefix}rp_customers
                    ON b_customer_id = c_id
                    WHERE b_id = %d AND b_process_status= -1";
            $sql = $wpdb->prepare($sql, $booking_id);
            $booking = $wpdb->get_results($sql);
            error_log('stripe_payment_create sql:'.$sql);
            error_log(' booking count:' .count($booking));

            if(isset($booking[0])){
                $booking = $booking[0];
                try {
                    $setting = Revy_DB_Setting::instance();
                    $setting = $setting->get_setting();
                    $currency = $setting['currency'];
                    $description = $booking->c_first_name.' '. $booking->c_last_name . ', '. $booking->b_date;
                    Stripe::setApiKey($setting['stripe_secret_key']);

                    $amount = $booking->b_total_pay * 100;
                    $amount = round($amount);

                    $paymentIntent = PaymentIntent::create([
                        'amount' => $amount,
                        'currency' =>  $currency,
                        'payment_method_types' => ['card'],
                        'description' => $description,
                        'metadata' => [
                            'First name' => $booking->c_first_name,
                            'Last name' => $booking->c_last_name,
                            'Email' => $booking->c_email,
                            'Booking id' => $booking_id,
                        ]
                    ]);

                    if(isset($paymentIntent->id) && $paymentIntent->status=='requires_source'){
                        $sql = "UPDATE {$wpdb->prefix}rp_booking SET b_gateway_id=%s WHERE b_id = %d";
                        $sql = $wpdb->prepare($sql, $paymentIntent->id, $booking_id);
                        $wpdb->query($sql);
                    }
                    return $paymentIntent;

                } catch (Exception $e) {
                    error_log('stripe_payment_create error:'. $e->getMessage());
                    return array('error' => $e->getMessage());
                }
            }else{
                error_log('stripe_payment_create data is invalid');
                return array('error' => esc_html__('Data invalid','revy'));
            }
        }

        public function stripe_payment_confirm( $booking_id, $payment_response){
            global $wpdb;
            $sql = "SELECT b_date, b_time, b_total_pay
                    FROM {$wpdb->prefix}rp_booking
                    WHERE b_id = %d AND b_gateway_id = %s AND b_process_status=-1";
            $sql = $wpdb->prepare($sql, $booking_id, $payment_response['id']);

            $booking = $wpdb->get_results($sql);
            if(isset($booking[0]) && $payment_response['status'] == 'succeeded'){

                require_once(REVY_DIR_PATH . '/inc/payment/stripe-php/init.php');

                $setting = Revy_DB_Setting::instance();
                $setting = $setting->get_setting();

                $stripe = new StripeClient(
                    $setting['stripe_secret_key']
                );
                $capture = $stripe->paymentIntents->retrieve(
                    $payment_response['id'],
                    []
                );

                error_log('stripe_payment_confirm capture:'.serialize($capture));
                if(isset($capture->status) && $capture->status=='succeeded'){
                    //update booking to publish
                    $default_process_status = isset($setting['b_process_status']) && $setting['b_process_status'] ? $setting['b_process_status'] : 0;
                    $sql = "UPDATE {$wpdb->prefix}rp_booking
                                SET b_gateway_response = %s, b_process_status = %d
                                WHERE b_id = %d";
                    $sql = $wpdb->prepare($sql, $payment_response['amount'], $default_process_status, $booking_id);
                    $wpdb->query($sql);

                    return array(
                        'result' => $booking_id,
                    );
                }else{
                    error_log('stripe_payment_confirm data invalid');
                    return array(
                        'result' => -1,
                        'message' => esc_html__('Data invalid','revy')
                    );
                }

            }else{
                error_log('stripe_payment_confirm: booking not found '.$booking_id);
                return array(
                    'result' => -1,
                    'message' => esc_html__('Data invalid','revy')
                );
            }
        }
    }
}