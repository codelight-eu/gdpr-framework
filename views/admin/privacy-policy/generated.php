<h3><?= _x('Privacy Policy', '(Admin)', 'gdpr-framework'); ?></h3>
<p>
    <?= _x('Your Privacy Policy has been generated.', '(Admin)', 'gdpr-framework'); ?>
    <?php if ($policyUrl): ?>
        <?= __(
            sprintf(
                'You can copy and paste it to your %sPrivacy Policy page%s.',
                "<a href='{$policyUrl}' target='_blank'>",
                "</a>"
            ),
            '(Admin)',
            'gdpr-framework'
        ); ?>
    <?php endif; ?>
</p>

<?= $editor; ?>

<br>
<a href="<?= $backUrl; ?>" class="button button-secondary"><?= _x('&laquo; Back', '(Admin)', 'gdpr-framework'); ?></a>
<br><br>
