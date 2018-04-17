<p>
    <?= __('Someone has requested access to your data on', 'gdpr-framework'); ?> <?= esc_html($siteName); ?> <br/>
    <?= __('If this was a mistake, just ignore this email and nothing will happen.', 'gdpr-framework'); ?> <br/>
    <?= __('To manage your data, visit the following address:', 'gdpr-framework'); ?> <br/>
    <a href="<?= esc_url($identificationUrl); ?>">
        <?= esc_url($identificationUrl); ?>
    </a>
</p>
<p>
    <?= __('This link is valid for 15 minutes.', 'gdpr-framework'); ?>
</p>
