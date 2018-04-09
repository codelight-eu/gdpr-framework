<div>
    <h3>Results for: <?= esc_html($email); ?></h3>
    <?php if ($hasData): ?>

        <?php if (isset($links['profile'])): ?>
            <p>
                <strong><?= __('Username', 'gdpr-admin'); ?>:</strong>
                <a href="<?= $links['profile']; ?>"><?= esc_html($userName); ?></a>
            </p>
        <?php else: ?>
            <p>
                <em><?= esc_html($email); ?> <?= __('is not a registered user.', 'gdpr-admin'); ?></em>
            </p>
        <?php endif; ?>

        <a class="button button-primary" href="<?= esc_url($links['view']); ?>"><?= __('Download data (html)', 'gdpr-admin'); ?></a>
        <a class="button button-primary" href="<?= esc_url($links['export']); ?>"><?= __('Export data (json)', 'gdpr-admin'); ?></a>

        <?php if ($adminCap): ?>
            <p>
                <strong><?= __('This user has admin capabilities. Deleting data via this interface is disabled.', 'gdpr-admin'); ?></strong>
            </p>
        <?php else: ?>
            <a class="button button-primary" href="<?= esc_url($links['anonymize']); ?>"><?= __('Anonymize data', 'gdpr-admin'); ?></a>
            <a class="button button-primary" href="<?= esc_url($links['delete']); ?>"><?= __('Delete data', 'gdpr-admin'); ?></a>
        <?php endif; ?>

    <?php else: ?>
        <p><?= __('No data found!', 'gdpr-admin'); ?></p>
    <?php endif; ?>
</div>
<br>
<hr>
