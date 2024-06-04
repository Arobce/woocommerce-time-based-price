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

// Include the main plugin class
include_once 'classes/WTBPMain.php';

class WoocommerceTimeBasedPrice
{

    protected $main_class;

    public function __construct()
    {
        $this->main_class = new WTBPMain();

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style('wtbp-style', plugins_url('style.css', __FILE__));
    }
}

new WoocommerceTimeBasedPrice();
