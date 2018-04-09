<h3><?= __('Privacy Policy', 'gdpr-admin'); ?></h3>
<p>
    <?= __('Your Privacy Policy has been generated.', 'gdpr-admin'); ?>
    <?php if ($policyUrl): ?>
        <?= __(
            sprintf(
                'You can copy and paste it to your %sPrivacy Policy page%s.',
                "<a href='{$policyUrl}' target='_blank'>",
                "</a>"
            ),
            'gdpr-admin'
        ); ?>
    <?php endif; ?>
</p>

<?= $editor; ?>

<br>
<a href="<?= $backUrl; ?>" class="button button-secondary"><?= __('&laquo; Back', 'gdpr-admin'); ?></a>
<br><br>