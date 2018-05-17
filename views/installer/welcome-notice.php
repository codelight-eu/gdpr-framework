<p>
    The GDPR Framework has not been set up yet. Would you like to do that? <br>
    Our setup wizard will guide you through the process. <br>
    You can also configure the plugin manually by going to <a href="<?= gdpr('helpers')->getAdminUrl(); ?>">Tools > Privacy</a>.
</p>

<a class="button button-primary" href="<?= $installerUrl; ?>">
    <?= _x('Run the setup wizard', '(Admin)', 'gdpr-framework'); ?>
</a>

<a class="button button-secondary" href="<?= $autoInstallUrl; ?>">
    <?= _x('Auto-install pages', '(Admin)', 'gdpr-framework'); ?>
</a>

<a class="button button-secondary" href="<?= $skipUrl; ?>">
    <?= _x('Skip and install manually', '(Admin)', 'gdpr-framework'); ?>
</a>
