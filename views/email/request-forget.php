<p>
    <?= __(
        sprintf('A data subject (%s) has requested to remove their data.', esc_html($email)),
        'gdpr-admin'
    ); ?>
    <br>
    <?= __(
        sprintf('To access the data subject\'s data, %sclick here%s', "<a href='{$adminTabLink}'>", '</a>'),
        'gdpr-admin'
    ); ?>
</p>
<p>
    <?= __('As a reminder: according to GDPR, you have 30 days to comply.', 'gdpr-admin'); ?>
</p>