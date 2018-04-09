<p>
    The GDPR Framework has not been set up yet. Would you like to do that? <br>
    Our setup wizard will guide you through the process.
</p>

<a class="button button-primary" href="<?= $installerUrl; ?>">
    <?= __('Run the setup wizard', 'gdpr-admin'); ?>
</a>

<a class="button button-secondary" href="<?= $autoInstallUrl; ?>">
    <?= __('Auto-install pages', 'gdpr-admin'); ?>
</a>

<a class="button button-secondary" href="<?= $skipUrl; ?>">
    <?= __('Skip and install manually', 'gdpr-admin'); ?>
</a>