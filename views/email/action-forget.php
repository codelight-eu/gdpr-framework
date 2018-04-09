<p>
    <?= __(
        sprintf('A data subject (%s) has just removed all their data from your website.', esc_html($email)),
        'gdpr-admin'
    ); ?> <br><br>

    <?php if ($userId): ?>
        <?= __('The data subject had a user account on your website.'); ?> (ID: <?= $userId; ?>).
    <?php endif; ?>
</p>
<p>
    <?= __('This email is just for your information. You don\'t need to take any action', 'gdpr-admin'); ?>
</p>
