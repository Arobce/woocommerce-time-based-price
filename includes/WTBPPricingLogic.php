<?php
class WTBPPricingLogic
{
    /**
     * Applies custom pricing to the product based on the current time.
     *
     * @param float $price The original price of the product.
     * @param WC_Product $product The WooCommerce product object.
     *
     * @return float The modified price based on custom time-based pricing rules.
     */
    public static function apply_custom_pricing($price, $product)
    {
        // Get custom pricing fields
        $custom_pricing = get_post_meta($product->get_id(), '_wtbp_custom_pricing', true);
        $current_time = current_time('timestamp');

        if ($custom_pricing && is_array($custom_pricing)) {
            foreach ($custom_pricing as $pricing) {
                $start_datetime = strtotime($pricing['start_date'] . ' ' . $pricing['start_time']);
                $end_datetime = strtotime($pricing['end_date'] . ' ' . $pricing['end_time']);

                if ($start_datetime <= $current_time && $end_datetime >= $current_time) {
                    return $pricing['price'];
                }
            }
        }

        return $price;
    }
}
