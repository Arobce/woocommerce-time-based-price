<?php

class WTBPMain
{

    public function __construct()
    {
        // Hook to add custom fields to the product edit page
        add_action('woocommerce_product_options_pricing', array($this, 'add_custom_pricing_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_custom_pricing_fields'));

        // Hook to apply custom pricing
        add_filter('woocommerce_product_get_price', array($this, 'apply_custom_pricing'), 10, 2);
        add_filter('woocommerce_product_get_sale_price', array($this, 'apply_custom_pricing'), 10, 2);
    }

    public function add_custom_pricing_fields()
    {
        global $post;

        echo '<div class="wtbp-options_group">';
        echo '<h4 class="wtbp-title">' . __('Custom Time-Based Pricing', 'woocommerce') . '</h4>';
        echo '<div class=" wtbp-current_time">';
        echo '<p><b>Current Time:</b> ' . wp_date("d-m-Y H:i:s", null) . '</p>';
        echo '</div>';
        // echo '<div class="notice notice-info wtbp-current_time">';
        // echo '<p>Current Time: ' . wp_date("d-m-Y H:i:s", null) . '</p>';
        // echo '</div>';

        $custom_pricing = get_post_meta($post->ID, '_wtbp_custom_pricing', true);
        $custom_pricing = is_array($custom_pricing) ? $custom_pricing : array();

        foreach ($custom_pricing as $index => $pricing) {
            $this->add_custom_pricing_field($index, $pricing);
        }

        // Add button to add more fields
        echo '<div id="custom_pricing_fields"></div>';
        echo '<div class="wtbp-add_custom_pricing"><button type="button" class="button">' . __('Add Custom Pricing', 'woocommerce') . '</button></div>';

        echo '</div>';

        // Script to handle adding more fields
?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var customPricingIndex = <?php echo count($custom_pricing); ?>;
                $('.wtbp-add_custom_pricing').click(function() {
                    var fieldHTML = `
                    <div class="custom_pricing_field">
                        <p class="form-field">
                            <label><?php _e('Start Date', 'woocommerce'); ?></label>
                            <input type="date" name="custom_pricing[` + customPricingIndex + `][start_date]" class="short">
                        </p>
                        <p class="form-field">
                            <label><?php _e('End Date', 'woocommerce'); ?></label>
                            <input type="date" name="custom_pricing[` + customPricingIndex + `][end_date]" class="short">
                        </p>
                        <p class="form-field">
                            <label><?php _e('Start Time', 'woocommerce'); ?></label>
                            <input type="time" name="custom_pricing[` + customPricingIndex + `][start_time]" class="short">
                        </p>
                        <p class="form-field">
                            <label><?php _e('End Time', 'woocommerce'); ?></label>
                            <input type="time" name="custom_pricing[` + customPricingIndex + `][end_time]" class="short">
                        </p>
                        <p class="form-field">
                            <label><?php _e('Price', 'woocommerce'); ?></label>
                            <input type="text" name="custom_pricing[` + customPricingIndex + `][price]" class="short">
                        </p>
                        <div class="wtbp-remove_custom_pricing">
                            <button type="button" class="button"><?php _e('Remove', 'woocommerce'); ?></button>
                        </div>
                    </div>`;
                    $('#custom_pricing_fields').append(fieldHTML);
                    customPricingIndex++;
                });

                // Remove field
                $(document).on('click', '.wtbp-remove_custom_pricing', function() {
                    $(this).closest('.custom_pricing_field').remove();
                });
            });
        </script>
    <?php
    }

    private function add_custom_pricing_field($index, $pricing)
    {
    ?>
        <div class="custom_pricing_field">
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

    public function save_custom_pricing_fields($post_id)
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

    public function apply_custom_pricing($price, $product)
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
