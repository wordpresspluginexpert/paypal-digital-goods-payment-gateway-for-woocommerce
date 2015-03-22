<?php

/**
 *
 * @wordpress-plugin
 * Plugin Name:       Paypal Digital Goods Payment Gateway For Woocommerce
 * Plugin URI:        http://www.mbjtechnolabs.com
 * Description:       The Paypal Digital Goods Payment Gateway For Woocommerce makes it possible to sell products with WooCommerce via PayPal Express Checkout for Digital Goods.
 * Version:           1.0.1
 * Author:            phpwebcreators
 * Author URI:        http://www.mbjtechnolabs.com
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       product-tab-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!defined('MBJ_DG_PLUGIN_BASENAME')) {
    define('MBJ_DG_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-paypal-digital-goods-payment-gateway-for-woocommerce-activator.php
 */
function activate_paypal_digital_goods_payment_gateway_for_woocommerce() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-digital-goods-payment-gateway-for-woocommerce-activator.php';
    MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-paypal-digital-goods-payment-gateway-for-woocommerce-deactivator.php
 */
function deactivate_paypal_digital_goods_payment_gateway_for_woocommerce() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-digital-goods-payment-gateway-for-woocommerce-deactivator.php';
    MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_paypal_digital_goods_payment_gateway_for_woocommerce');
register_deactivation_hook(__FILE__, 'deactivate_paypal_digital_goods_payment_gateway_for_woocommerce');

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-paypal-digital-goods-payment-gateway-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_paypal_digital_goods_payment_gateway_for_woocommerce() {

    $plugin = new MBJ_Paypal_Digital_Goods_Payment_Gateway_For_WooCommerce();
    $plugin->run();
}

run_paypal_digital_goods_payment_gateway_for_woocommerce();
