<h2>
    <?= _x('GDPR Data', '(Admin)', 'gdpr-framework'); ?>
</h2>
<?php if ($isAnonymized): ?>
    <p>
        <em><?= _x('This user has been anonymized.', '(Admin)', 'gdpr-framework'); ?></em>
    </p>
<?php endif; ?>
