<?php
/*
Plugin Name: Revy
Plugin URI:  https://plugins.roninwp.com/revy/
Description: Revy - Online bookings system for car, mobile, computer, washing machine, plumb
Version:     1.9
Author:      Roninwp
Author URI:  https://codecanyon.net/user/roninwp/portfolio?ref=roninwp
Domain Path: /languages
Text Domain: revy
*/

if (!defined('ABSPATH')) die('-1');
if (!class_exists('Revy')) {
    class Revy
    {
        private static $instance = NULL;
        private static $version = '1.9';

        function __construct()
        {
            $this->init();
        }

        function init()
        {
            do_action('revy_before_init');

            spl_autoload_extensions(".php");
            spl_autoload_register(array($this, 'autoload'));

            $this->define_constants();
            $this->hook();
            $this->register_shortcode();
            $this->includes();

            do_action('revy_after_init');
        }

        private function autoload($class_name)
        {
            $class_name = strtolower($class_name);
            $class_name = 'class-' . str_replace('_', '-', $class_name) . '.php';
            $class_path = '';
            if (strrpos($class_name, 'revy-db') !== FALSE) {
                $class_path = REVY_DIR_PATH . "inc/db/{$class_name}";
            }
            if (strrpos($class_name, 'revy-menu') !== FALSE) {
                $class_path = REVY_DIR_PATH . "inc/menu/{$class_name}";
            }
            if (strrpos($class_name, 'revy-ajax') !== FALSE) {
                $class_path = REVY_DIR_PATH . "inc/ajax/{$class_name}";
            }
            if (strrpos($class_name, 'revy-utils') !== FALSE || strrpos($class_name, 'revy-import-export') !== FALSE) {
                $class_path = REVY_DIR_PATH . "inc/utils/{$class_name}";
            }

            if (strrpos($class_name, 'revy-shortcodes') !== FALSE) {
                $class_path = REVY_DIR_PATH . "shortcodes/{$class_name}";
            }
            if (strrpos($class_name, 'revy-payment') !== FALSE) {
                $class_path = REVY_DIR_PATH . "inc/payment/{$class_name}";
            }

            if (strrpos($class_name, 'ics') !== FALSE) {
                $class_path = REVY_DIR_PATH . "libs/{$class_name}";
            }
            if (is_readable($class_path)) {
                return require_once($class_path);
            }
            return false;
        }

        function define_constants()
        {
            defined('REVY_DIR_PATH') or define('REVY_DIR_PATH', plugin_dir_path(__FILE__));
            defined('REVY_SC_TEMPLATE_PATH') or define('REVY_SC_TEMPLATE_PATH', plugin_dir_path(__FILE__).'/templates/shortcodes/');
            defined('REVY_PLUGIN_URL') or define('REVY_PLUGIN_URL', plugins_url('', __FILE__));
            defined('REVY_ASSET_URL') or define('REVY_ASSET_URL', plugins_url() . '/revy/assets/');
            defined('REVY_PLUGIN_VERSION') or define('REVY_PLUGIN_VERSION', Revy::$version);
        }

        private function hook()
        {
            register_activation_hook(__FILE__, array($this, 'plugin_activate'));
            $ajax_handlers = new Revy_Ajax_Handlers();
            if (is_admin()) {
                $menu_admin = new Revy_Menu_Admin();
                add_action('admin_enqueue_scripts', array($menu_admin, 'admin_enqueue_script'));
                add_action('admin_init', array($ajax_handlers, 'setup_ajax_handler'));
                add_action('admin_menu', array($menu_admin, 'init_menu'));
                add_action('admin_init', array($this, 'init_import'));

            }
            add_action('send_headers', array($this, 'payment_update_status'));
            add_action('init', array($ajax_handlers, 'setup_fe_ajax_handler'));
            add_action('init', array($this, 'load_text_domain'));
        }

        private function register_shortcode()
        {
            $shortcode = Revy_Shortcodes::instance();
            add_shortcode('rp', array($shortcode, 'revy_shortcode'));
            add_shortcode('rp_history', array($shortcode, 'history_shortcode'));

        }

        public function plugin_activate()
        {
            $table = Revy_DB_Table::instance();
            $table->create_tables();
        }

        function load_text_domain()
        {
            $domain = dirname(plugin_basename(__FILE__));
            $locale = apply_filters('plugin_locale', get_locale(), $domain);
            load_textdomain('revy', trailingslashit(WP_LANG_DIR) . 'plugins' . '/' . $domain . '-' . $locale . '.mo');
            load_plugin_textdomain('revy', false, basename(dirname(__FILE__)) . '/languages/');
        }

        public function require_file($path)
        {
            if (is_readable($path)) {
                require_once($path);
                return true;
            } else {
                return false;
            }
        }

        public function includes(){
            $setting = Revy_DB_Setting::instance();
            $setting = $setting->get_setting();
            if(isset($setting['wc_enable']) && $setting['wc_enable']=='1'){
                $this->require_file(REVY_DIR_PATH . "inc/payment/class-revy-wc.php");
            }
        }

        public function payment_update_status()
        {
            if (isset($_GET['source']) && $_GET['source'] === 'revy_booking' && isset($_GET['token'])) {
                $payment = new Revy_Payment();
                $payment->payment_update_status();
            }
        }

        public static function getInstance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function init_import(){

            if(isset($_POST['revy_action']) && $_POST['revy_action']=='import'){

                $import = Revy_Import_Export::instance();
                $import->import();
            }
        }

    }

    Revy::getInstance();
}
