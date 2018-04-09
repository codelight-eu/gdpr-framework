<option value="download" <?= selected($exportAction, 'download'); ?>>
    <?= __('Automatically download data', 'gdpr-admin') ?>
</option>
<option value="download_and_notify" <?= selected($exportAction, 'download_and_notify'); ?>
        data-show=".js-gdpr-export-action-email">
    <?= __('Automatically download data and notify me via email', 'gdpr-admin') ?>
</option>
<option value="notify" <?= selected($exportAction, 'notify'); ?>
        data-show=".js-gdpr-export-action-email">
    <?= __('Only notify me via email', 'gdpr-admin') ?>
</option>



