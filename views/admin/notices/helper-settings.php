<p>
    <?= _x('Heads up! The GDPR Framework is not properly configured, so it will not work just yet.', '(Admin)', 'gdpr-framework'); ?> <br>
    <?= sprintf(
        _x('Go to %sTools > Privacy%s and make sure all fields are filled in.', '(Admin)', 'gdpr-framework'),
        "<a href='" . gdpr('helpers')->getAdminUrl() . "'>",
        '</a>'
    ); ?>
</p>
