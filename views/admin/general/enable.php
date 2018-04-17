<input
    type="checkbox"
    id="gdpr_enable"
    name="gdpr_enable"
    value="1"
    <?= checked($enabled, true); ?>
/>
<label for="gdpr_enable">
    <?= _x('Enable the view, export and forget functionality for users and visitors', '(Admin)', 'gdpr-framework'); ?>
</label>
<p class="description">
    <?= _x('Enable the Privacy Tools page on front-end and dashboard. This allows visitors to request viewing and deleting their personal data and withdraw consents.', '(Admin)', 'gdpr-framework'); ?>
</p>
