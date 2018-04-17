<option value="anonymize" <?= selected($deleteAction, 'anonymize'); ?>>
    <?= _x('Automatically anonymize data', '(Admin)', 'gdpr-framework') ?>
</option>
<option value="delete" <?= selected($deleteAction, 'delete'); ?> data-show=".js-gdpr-delete-action-reassign">
    <?= _x('Automatically delete data', '(Admin)', 'gdpr-framework') ?>
</option>
<option value="anonymize_and_notify" <?= selected($deleteAction, 'anonymize_and_notify'); ?>
        data-show=".js-gdpr-delete-action-email">
    <?= _x('Automatically anonymize data and notify me via email', '(Admin)', 'gdpr-framework') ?>
</option>
<option value="delete_and_notify" <?= selected($deleteAction, 'delete_and_notify'); ?>
        data-show=".js-gdpr-delete-action-email, .js-gdpr-delete-action-reassign">
    <?= _x('Automatically delete data and notify me via email', '(Admin)', 'gdpr-framework') ?>
</option>
<option value="notify" <?= selected($deleteAction, 'notify'); ?> data-show=".js-gdpr-delete-action-email">
    <?= _x('Only notify me via email', '(Admin)', 'gdpr-framework') ?>
</option>



