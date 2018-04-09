<option value="anonymize" <?= selected($deleteAction, 'anonymize'); ?>>
    <?= __('Automatically anonymize data', 'gdpr-admin') ?>
</option>
<option value="delete" <?= selected($deleteAction, 'delete'); ?> data-show=".js-gdpr-delete-action-reassign">
    <?= __('Automatically delete data', 'gdpr-admin') ?>
</option>
<option value="anonymize_and_notify" <?= selected($deleteAction, 'anonymize_and_notify'); ?>
        data-show=".js-gdpr-delete-action-email">
    <?= __('Automatically anonymize data and notify me via email', 'gdpr-admin') ?>
</option>
<option value="delete_and_notify" <?= selected($deleteAction, 'delete_and_notify'); ?>
        data-show=".js-gdpr-delete-action-email, .js-gdpr-delete-action-reassign">
    <?= __('Automatically delete data and notify me via email', 'gdpr-admin') ?>
</option>
<option value="notify" <?= selected($deleteAction, 'notify'); ?> data-show=".js-gdpr-delete-action-email">
    <?= __('Only notify me via email', 'gdpr-admin') ?>
</option>



