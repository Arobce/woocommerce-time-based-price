<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              
 * @since             1.0.0
 * @package           Time-Based Product Pricing
 *
 * @wordpress-plugin
 * Plugin Name:       Time-Based Product Pricing
 * Plugin URI:        
 * Description:       Set different prices for WooCommerce products based on time periods
 * Version:           1.0.0
 * Author:            Roshan Chapagain
 * Author URI:        https://roshanchapagain.com/
 * Requires PHP:      5.6
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

include_once('includes/WTBPCustomPricingFields.php');
include_once('includes/WTBPPricingLogic.php');

class TimeBasedProductPricing
{
    protected $custom_pricing_fields;
    protected $pricing_logic;


    /**
     * WoocommerceTimeBasedPrice constructor.
     * Initializes dependencies and sets up WordPress hooks.
     */
    public function __construct()
    {
        $this->init_dependencies();

        // Hook to add custom fields to the product edit page
        add_action('woocommerce_product_options_pricing', array($this->custom_pricing_fields, 'add_custom_pricing_fields'));
        add_action('woocommerce_process_product_meta', array($this->custom_pricing_fields, 'save_custom_pricing_fields'));

        // Hook to apply custom pricing
        add_filter('woocommerce_product_get_price', array($this->pricing_logic, 'apply_custom_pricing'), 10, 2);
        add_filter('woocommerce_product_get_sale_price', array($this->pricing_logic, 'apply_custom_pricing'), 10, 2);

        // Enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Initializes the dependencies by creating instances of required classes.
     *
     * @return void
     */
    private function init_dependencies()
    {
        $this->custom_pricing_fields = new WTBPCustomPricingFields();
        $this->pricing_logic = new WTBPPricingLogic();
    }

    /**
     * Enqueues the necessary scripts and styles for the admin area.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style('wtbp-custom-pricing-style', plugins_url('style.css', __FILE__));

        wp_enqueue_script('wtbp-custom-pricing-fields-handler', plugins_url('/assets/js/custom-pricing-fields-handler.js', __FILE__), array('jquery'), null, true);
    }
}

new TimeBasedProductPricing();
