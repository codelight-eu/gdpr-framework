<input
    type="checkbox"
    id="gdpr_enable"
    name="gdpr_enable"
    value="1"
    <?= checked($enabled, true); ?>
/>
<label for="gdpr_enable">
    <?= __('Enable the view, export and forget functionality for users and visitors', 'gdpr-admin'); ?>
</label>
<p class="description">
    <?= __('Enable the Privacy Tools page on front-end and dashboard. This allows visitors to request viewing and deleting their personal data and withdraw consents.', 'gdpr-admin'); ?>
</p>
