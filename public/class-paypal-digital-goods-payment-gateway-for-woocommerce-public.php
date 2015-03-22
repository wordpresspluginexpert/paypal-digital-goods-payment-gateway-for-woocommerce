<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce
 * @subpackage MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce/public
 * @author     Your Name <email@example.com>
 */
class MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Public {

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
     * @var      string    $plugin_name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    
    /**
     * When a user returns from the PayPal in context payment flow, they remain in the iframe.
     *
     * This function checks if they are on an immediate return page, and if they are, closes
     * the frame and redirects them back to the main site.
     *
     * @since 1.0.0
     * */
    function paypal_digital_goods_payment_gateway_for_woocommerce_paypal_return() {
        global $woocommerce, $wp;

        if (!isset($_GET['paypal_digital_goods_payment_gateway_for_woocommerce'])) {
            return;
        }

        $paypal_digital_goods_payment_gateway_for_woocommerce_gateway = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin_Display();

        $is_paying = ( 'paid' == $_GET['paypal_digital_goods_payment_gateway_for_woocommerce'] ) ? true : false;

        unset($_GET['paypal_digital_goods_payment_gateway_for_woocommerce']);

        if (isset($wp->query_vars['order-received'])) { // WC 2.1, order received
            $order_id = $_GET['paypal_digital_goods_payment_gateway_for_woocommerce_order'] = $wp->query_vars['order-received'];
        } elseif (isset($_GET['order_id'])) { // WC 2.1, order cancelled
            $order_id = $_GET['paypal_digital_goods_payment_gateway_for_woocommerce_order'] = $_GET['order_id'];
        } else { // WC 2.0
            $order_id = $_GET['paypal_digital_goods_payment_gateway_for_woocommerce_order'] = $_GET['order'];
        }

        $order = new WC_Order($order_id);

        $paypal_object = $paypal_digital_goods_payment_gateway_for_woocommerce_gateway->get_paypal_object($order->id);
        wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-digital-goods-payment-gateway-for-woocommerce-public.css', array(), $this->version, 'all');
        wp_register_script('paypal-digital-goods-payment-gateway-for-woocommerce-return', plugin_dir_url(__FILE__) . 'js/paypal-digital-goods-payment-gateway-for-woocommerce-public.js', array('jquery'), $this->version, false);

        $paypal_digital_goods_payment_gateway_for_woocommerce_params = array(
            'ajaxUrl' => (!is_ssl() ) ? str_replace('https', 'http', admin_url('admin-ajax.php')) : admin_url('admin-ajax.php'),
            'queryString' => http_build_query($_GET),
            'msgWaiting' => __("This won't take a minute", 'paypal_digital_goods_payment_gateway_for_woocommerce'),
            'msgComplete' => __('Payment Processed', 'paypal_digital_goods_payment_gateway_for_woocommerce'),
        );

        wp_localize_script('paypal-digital-goods-payment-gateway-for-woocommerce-return', 'paypal_digital_goods_payment_gateway_for_woocommerce', $paypal_digital_goods_payment_gateway_for_woocommerce_params);


        ?>
        <html>
            <head>
                <title><?php __('Processing...', 'paypal_digital_goods_payment_gateway_for_woocommerce'); ?></title>
                <?php wp_print_styles('paypal_digital_goods_payment_gateway_for_woocommerce-iframe'); ?>
                <?php if ($is_paying) :   ?>
                    <?php wp_print_scripts('jquery'); ?>
                    <?php wp_print_scripts('paypal-digital-goods-payment-gateway-for-woocommerce-return'); ?>
                <?php endif; ?>
                <meta name="viewport" content="width=device-width">
            </head>
            <body>
                <div id="left_frame">
                    <div id="right_frame">
                        <p id="message">
                            <?php if ($is_paying) {   ?>
                                <?php _e('Processing payment', 'paypal_digital_goods_payment_gateway_for_woocommerce'); ?>
                                <?php $location = remove_query_arg(array('paypal_digital_goods_payment_gateway_for_woocommerce', 'token', 'PayerID')); ?>
                            <?php } else {   ?>
                                <?php _e('Cancelling Order', 'paypal_digital_goods_payment_gateway_for_woocommerce'); ?>
                                <?php $location = html_entity_decode($order->get_cancel_order_url());  // We need it as an raw string not a HTML encoded string ?>
                            <?php } ?>
                        </p>
                        <img src="https://www.paypal.com/en_US/i/icon/icon_animated_prog_42wx42h.gif" alt="Processing..." />
                        <div id="right_bottom">
                            <div id="left_bottom">
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!$is_paying) :     ?>
                    <script type="text/javascript">
                        setTimeout('if (window!=top) {top.location.replace("<?php echo $location; ?>");}else{location.replace("<?php echo $location; ?>");}', 1500);
                    </script>
                <?php endif; ?>
            </body>
        </html>
        <?php
        exit();
    }

    /**
     * Handles ajax requests to process express checkout payments
     *
     * @since 1.0.0
     */
    function paypal_digital_goods_payment_gateway_for_woocommerce_ajax_do_express_checkout() {
        $paypal_digital_goods_payment_gateway_for_woocommerce_gateway = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Admin_Display();
        $paypal_digital_goods_payment_gateway_for_woocommerce_gateway->ajax_do_express_checkout();
    }

}
