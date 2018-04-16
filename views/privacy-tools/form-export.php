<h2><?= __('Download your data', 'gdpr') ?></h2>

<p class="description">
    <?= __('You can download all your data formatted as a table for viewing.', 'gdpr') ?> <br>
    <?= __('Alternatively, you can export it in machine-readable JSON format.', 'gdpr') ?>
</p>

<div class="gdpr-download-button">
    <form method="POST">
        <input type="hidden" name="gdpr_nonce" value="<?= $nonce; ?>" />
        <input type="hidden" name="gdpr_action" value="export" />
        <input type="hidden" name="gdpr_format" value="html" />
        <input type="submit" class="button button-primary" value="<?= __('Download as table', 'gdpr') ?>" />
    </form>
</div>

<div class="gdpr-export-button">
    <form method="POST">
        <input type="hidden" name="gdpr_nonce" value="<?= $nonce; ?>" />
        <input type="hidden" name="gdpr_action" value="export" />
        <input type="hidden" name="gdpr_format" value="json" />
        <input type="submit" class="button button-primary" value="<?= __('Export as JSON', 'gdpr') ?>" />
    </form>
</div>

<hr>
