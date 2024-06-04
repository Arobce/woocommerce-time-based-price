jQuery(document).ready(function($) {
    var customPricingIndex = $('div.wtbp-custom_pricing_field').length;
    $('.wtbp-add_custom_pricing').click(function() {
        var fieldHTML = `
        <div class="wtbp-custom_pricing_field">
            <p class="form-field">
                <label>Start Date</label>
                <input type="date" name="custom_pricing[` + customPricingIndex + `][start_date]" class="short">
            </p>
            <p class="form-field">
                <label>End Date</label>
                <input type="date" name="custom_pricing[` + customPricingIndex + `][end_date]" class="short">
            </p>
            <p class="form-field">
                <label>Start Time</label>
                <input type="time" name="custom_pricing[` + customPricingIndex + `][start_time]" class="short">
            </p>
            <p class="form-field">
                <label>End Time</label>
                <input type="time" name="custom_pricing[` + customPricingIndex + `][end_time]" class="short">
            </p>
            <p class="form-field">
                <label>Price</label>
                <input type="text" name="custom_pricing[` + customPricingIndex + `][price]" class="short">
            </p>
            <div class="wtbp-remove_custom_pricing">
                <button type="button" class="button">Remove</button>
            </div>
        </div>`;
        $('#wtbp-custom_pricing_fields').append(fieldHTML);
        customPricingIndex++;
    });

    // Remove field
    $(document).on('click', '.wtbp-remove_custom_pricing', function() {
        console.log("Remove button clicked");
        $(this).closest('.wtbp-custom_pricing_field').remove();
    });
});
