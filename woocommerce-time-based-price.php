<?php
/*
Plugin Name: WooCommerce Time-Based Pricing
Description: Set different prices for WooCommerce products based on time periods.
Version: 1.0
Author: Your Name
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
