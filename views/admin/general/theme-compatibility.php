<input
    type="checkbox"
    id="gdpr_enable_theme_compatibility"
    name="gdpr_enable_theme_compatibility"
    value="1"
    <?= checked($enabled, true); ?>
/>
<label for="gdpr_enable_theme_compatibility">
    <?= _x('Automatically add Privacy Policy and Privacy Tools links to your site footer.', '(Admin)', 'gdpr-framework'); ?>
</label>
