<h2>
    <?= __('GDPR Data', 'gdpr-admin'); ?>
</h2>
<?php if ($isAnonymized): ?>
    <p>
        <em><?= __('This user has been anonymized.', 'gdpr-admin'); ?></em>
    </p>
<?php endif; ?>
