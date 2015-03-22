<?php

class MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $plugin_name       The name of this plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function load_plugin_extend_lib() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        if (!class_exists('WC_Payment_Gateway'))
            return;

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-digital-goods-payment-gateway-for-woocommerce-admin-display.php';
        require_once( 'partials/lib/paypal-digital-goods/paypal-purchase.class.php' );
        require_once( 'partials/lib/paypal-digital-goods/paypal-subscription.class.php' );
    }

    public function paypal_digital_goods_payment_gateway_for_woocommerce_add_gateway($methods) {

        $methods[] = 'MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin_Display';
        return $methods;
    }

    public function paypal_digital_goods_payment_gateway_for_woocommerce_action_links($links) {
        $plugin_links = array(
            '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=mbj_authorize_advanced_payment_gateway_for_woocommerce_lib') . '">' . __('Settings', 'authorize-net-advanced-payment-gateway-for-woocommerce') . '</a>',
        );

          return array_merge($plugin_links, $links);
    }

    /**
     * Gets the details of a given recurring payments profile with PayPal then calls process_subscription_sign_up.
     *
     * Hooked to @see 'paypal_digital_goods_payment_gateway_for_woocommerce_check_subscription_status' which is fired every every 12 hours to make sure subscriptions
     * cancelled with PayPal are also cancelled on the site. The hook is also fired every 45 seconds after a
     * subscription is ordered but pending.
     *
     * @since 1.0.0
     */
    function paypal_digital_goods_payment_gateway_for_woocommerce_check_subscription_status($order_id, $profile_id) {
        $paypal_digital_goods_payment_gateway_for_woocommerce_gateway = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin_Display();

        $paypal_object = $paypal_digital_goods_payment_gateway_for_woocommerce_gateway->get_paypal_object($order_id);

        $transaction_details = $paypal_object->get_details($profile_id);

        $paypal_digital_goods_payment_gateway_for_woocommerce_gateway->process_subscription_sign_up($transaction_details);
    }

    /**
     * @since 1.0.0
     */
    function paypal_digital_goods_payment_gateway_for_woocommerce_process_ipn_request($transaction_details) {
        $paypal_digital_goods_payment_gateway_for_woocommerce_gateway = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin_Display();

        $transaction_details = stripslashes_deep($transaction_details);

        $paypal_digital_goods_payment_gateway_for_woocommerce_gateway->process_ipn_request($transaction_details);
    }

}
