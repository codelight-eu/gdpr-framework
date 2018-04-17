<option value="download" <?= selected($exportAction, 'download'); ?>>
    <?= _x('Automatically download data', '(Admin)', 'gdpr-framework') ?>
</option>
<option value="download_and_notify" <?= selected($exportAction, 'download_and_notify'); ?>
        data-show=".js-gdpr-export-action-email">
    <?= _x('Automatically download data and notify me via email', '(Admin)', 'gdpr-framework') ?>
</option>
<option value="notify" <?= selected($exportAction, 'notify'); ?>
        data-show=".js-gdpr-export-action-email">
    <?= _x('Only notify me via email', '(Admin)', 'gdpr-framework') ?>
</option>



