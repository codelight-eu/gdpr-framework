<h2>
    <?= _x('GDPR Data', '(Admin)', 'gdpr'); ?>
</h2>
<?php if ($isAnonymized): ?>
    <p>
        <em><?= _x('This user has been anonymized.', '(Admin)', 'gdpr'); ?></em>
    </p>
<?php endif; ?>
