<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce
 * @subpackage MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce/includes
 * @author     Your Name <email@example.com>
 */
class MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'Paypal Digital Goods Payment Gateway For Woocommerce';
        $this->version = '1.0.1';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Loader. Orchestrates the hooks of the plugin.
     * - MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_i18n. Defines internationalization functionality.
     * - MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin. Defines all hooks for the dashboard.
     * - MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-paypal-digital-goods-payment-gateway-for-woocommerce-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-paypal-digital-goods-payment-gateway-for-woocommerce-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-paypal-digital-goods-payment-gateway-for-woocommerce-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-paypal-digital-goods-payment-gateway-for-woocommerce-public.php';

        $this->loader = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {


        $plugin_admin = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('plugins_loaded', $plugin_admin, 'load_plugin_extend_lib');
        $this->loader->add_filter('woocommerce_payment_gateways', $plugin_admin, 'paypal_digital_goods_payment_gateway_for_woocommerce_add_gateway');
        $this->loader->add_filter('plugin_action_links_' . MBJ_DG_PLUGIN_BASENAME, $plugin_admin, 'paypal_digital_goods_payment_gateway_for_woocommerce_action_links');
        $this->loader->add_action('paypal_digital_goods_payment_gateway_for_woocommerce_check_subscription_status', $plugin_admin, 'paypal_digital_goods_payment_gateway_for_woocommerce_check_subscription_status');
        $this->loader->add_action('paypal_digital_goods_payment_gateway_for_woocommerce_check_subscription_status', $plugin_admin, 'paypal_digital_goods_payment_gateway_for_woocommerce_process_ipn_request', 1);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('get_header', $plugin_public, 'paypal_digital_goods_payment_gateway_for_woocommerce_paypal_return', 11);
        $this->loader->add_action('wp_ajax_paypal_digital_goods_payment_gateway_for_woocommerce_do_express_checkout', $plugin_public, 'paypal_digital_goods_payment_gateway_for_woocommerce_ajax_do_express_checkout');
        $this->loader->add_action('wp_ajax_nopriv_paypal_digital_goods_payment_gateway_for_woocommerce_do_express_checkout', $plugin_public, 'paypal_digital_goods_payment_gateway_for_woocommerce_ajax_do_express_checkout');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
