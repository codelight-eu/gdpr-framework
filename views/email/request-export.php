<p>
    <?= __(
        sprintf('A data subject (%s) has requested to download their data in %s format.', esc_html($email), esc_html($format)),
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