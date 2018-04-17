<div>
    <h3>Results for: <?= esc_html($email); ?></h3>
    <?php if ($hasData): ?>

        <?php if (isset($links['profile'])): ?>
            <p>
                <strong><?= _x('Username', '(Admin)', 'gdpr-framework'); ?>:</strong>
                <a href="<?= $links['profile']; ?>"><?= esc_html($userName); ?></a>
            </p>
        <?php else: ?>
            <p>
                <em><?= esc_html($email); ?> <?= _x('is not a registered user.', '(Admin)', 'gdpr-framework'); ?></em>
            </p>
        <?php endif; ?>

        <a class="button button-primary" href="<?= esc_url($links['view']); ?>"><?= _x('Download data (html)', '(Admin)', 'gdpr-framework'); ?></a>
        <a class="button button-primary" href="<?= esc_url($links['export']); ?>"><?= _x('Export data (json)', '(Admin)', 'gdpr-framework'); ?></a>

        <?php if ($adminCap): ?>
            <p>
                <strong><?= _x('This user has admin capabilities. Deleting data via this interface is disabled.', '(Admin)', 'gdpr-framework'); ?></strong>
            </p>
        <?php else: ?>
            <a class="button button-primary" href="<?= esc_url($links['anonymize']); ?>"><?= _x('Anonymize data', '(Admin)', 'gdpr-framework'); ?></a>
            <a class="button button-primary" href="<?= esc_url($links['delete']); ?>"><?= _x('Delete data', '(Admin)', 'gdpr-framework'); ?></a>
        <?php endif; ?>

    <?php else: ?>
        <p><?= _x('No data found!', '(Admin)', 'gdpr-framework'); ?></p>
    <?php endif; ?>
</div>
<br>
<hr>
