<?php

/**
 * Class WTBPCustomPricingFields
 * Handles the custom pricing fields for WooCommerce products.
 *
 * @package WTBP
 */
class WTBPCustomPricingFields
{

    /**
     * Adds custom pricing fields to the WooCommerce product edit page.
     *
     * @return void
     */
    public static function add_custom_pricing_fields()
    {
        global $post;

        echo '<div class="wtbp-options_group">';
        echo '<h4 class="wtbp-title">' . __('Custom Time-Based Pricing', 'woocommerce') . '</h4>';
        echo '<div class="wtbp-current_time">';
        echo '<p><b>Current Server Time:</b> ' . wp_date("d-m-Y H:i:s", null) . '</p>';
        echo '</div>';

        $custom_pricing = get_post_meta($post->ID, '_wtbp_custom_pricing', true);
        $custom_pricing = is_array($custom_pricing) ? $custom_pricing : array();

        foreach ($custom_pricing as $index => $pricing) {
            self::add_custom_pricing_field($index, $pricing);
        }

        echo '<div id="wtbp-custom_pricing_fields"></div>';
        echo '<div class="wtbp-add_custom_pricing"><button type="button" class="button">' . __('Add Custom Pricing', 'woocommerce') . '</button></div>';

        echo '</div>';
    }

    /**
     * Renders an individual custom pricing field.
     *
     * @param int $index The index of the custom pricing field.
     * @param array $pricing The pricing data for the field.
     *
     * @return void
     */
    private static function add_custom_pricing_field($index, $pricing)
    {
?>
        <div class="wtbp-custom_pricing_field">
            <p class="form-field">
                <label><?php _e('Start Date', 'woocommerce'); ?></label>
                <input type="date" name="custom_pricing[<?php echo $index; ?>][start_date]" value="<?php echo esc_attr($pricing['start_date'] ?? ''); ?>" class="short">
            </p>
            <p class="form-field">
                <label><?php _e('End Date', 'woocommerce'); ?></label>
                <input type="date" name="custom_pricing[<?php echo $index; ?>][end_date]" value="<?php echo esc_attr($pricing['end_date'] ?? ''); ?>" class="short">
            </p>
            <p class="form-field">
                <label><?php _e('Start Time', 'woocommerce'); ?></label>
                <input type="time" name="custom_pricing[<?php echo $index; ?>][start_time]" value="<?php echo esc_attr($pricing['start_time'] ?? ''); ?>" class="short">
            </p>
            <p class="form-field">
                <label><?php _e('End Time', 'woocommerce'); ?></label>
                <input type="time" name="custom_pricing[<?php echo $index; ?>][end_time]" value="<?php echo esc_attr($pricing['end_time'] ?? ''); ?>" class="short">
            </p>
            <p class="form-field">
                <label><?php _e('Price', 'woocommerce'); ?></label>
                <input type="text" name="custom_pricing[<?php echo $index; ?>][price]" value="<?php echo esc_attr($pricing['price'] ?? ''); ?>" class="short">
            </p>
            <div class="wtbp-remove_custom_pricing">
                <button type="button" class="button"><?php _e('Remove', 'woocommerce'); ?></button>
            </div>
        </div>
<?php
    }

    /**
     * Saves the custom pricing fields data when the product is saved.
     *
     * @param int $post_id The ID of the post (product) being saved.
     *
     * @return void
     */
    public static function save_custom_pricing_fields($post_id)
    {
        // Save custom fields data
        if (isset($_POST['custom_pricing'])) {
            $custom_pricing = array_map(function ($pricing) {
                return array_map('sanitize_text_field', $pricing);
            }, $_POST['custom_pricing']);
            update_post_meta($post_id, '_wtbp_custom_pricing', $custom_pricing);
        } else {
            delete_post_meta($post_id, '_wtbp_custom_pricing');
        }
    }
}
