<p>
    <?= __(
        sprintf('A data subject (%s) has just downloaded their data in %s format.', esc_html($email), esc_html($format)),
        'gdpr-admin'
    ); ?>
</p>
<p>
    <?= __('This email is just for your information. You don\'t need to take any action', 'gdpr-admin'); ?>
</p>