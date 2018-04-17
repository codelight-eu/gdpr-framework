<input
    type="checkbox"
    id="gdpr_enable_stylesheet"
    name="gdpr_enable_stylesheet"
    value="1"
    <?php echo checked($enabled, true); ?>
/>
<label for="gdpr_enable_stylesheet">
    <?php _ex('Enable basic styling for Privacy Tools page.', '(Admin)', 'gdpr-framework'); ?>
</label>
