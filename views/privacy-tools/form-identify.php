<?php do_action('gdpr/privacy-tools-page/identify/before'); ?>

<?php if (isset($_REQUEST['gdpr_notice']) && in_array($_REQUEST['gdpr_notice'], ['data_deleted', 'request_sent'])): ?>
    <p>
        <br>

        <a href="<?= gdpr('helpers')->getPrivacyToolsPageUrl(); ?>">
            <?= __('Back to Privacy Tools', 'gdpr'); ?>
        </a>
    </p>
<?php else: ?>

    <h3>
        <?= __('Identify yourself!', 'gdpr'); ?>
    </h3>
    <form>
        <label for="gdpr_email"><?= __('Enter your email address', 'gdpr') ?></label>
        <input type="hidden" name="gdpr_action" value="identify" />
        <input type="hidden" name="gdpr_nonce" value="<?= $nonce ?>" />
        <input type="email" id="gdpr_email" name="email" placeholder="<?= __('Enter your email address', 'gdpr') ?>" />
        <?php do_action('gdpr/privacy-tools-page/identify'); ?>

        <input type="submit" value="<?= __('Send email', 'gdpr') ?>" />
    </form>

<?php endif; ?>

<?php do_action('gdpr/privacy-tools-page/identify/after'); ?>
