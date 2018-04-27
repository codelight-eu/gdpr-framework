<?php do_action('gdpr/privacy-tools-page/identify/before'); ?>

<?php if (isset($_REQUEST['gdpr_notice']) && in_array($_REQUEST['gdpr_notice'], ['data_deleted', 'request_sent'])): ?>
    <p>
        <br>

        <a href="<?= get_home_url() ?>">
            <?= __('Back to front page', 'gdpr-framework'); ?>
        </a>
    </p>
<?php else: ?>

    <h3>
        <?=
            __('Identify yourself via e-mail', 'gdpr-framework');
        ?>
    </h3>
    <form>
        <label for="gdpr_email"><?= __('Enter your email address', 'gdpr-framework') ?></label>
        <input type="hidden" name="gdpr_action" value="identify" />
        <input type="hidden" name="gdpr_nonce" value="<?= $nonce ?>" />
        <input type="email" id="gdpr_email" name="email" placeholder="<?= __('Enter your email address', 'gdpr-framework') ?>" />
        <?php do_action('gdpr/privacy-tools-page/identify'); ?>

        <input type="submit" value="<?= __('Send email', 'gdpr-framework') ?>" />
    </form>

<?php endif; ?>

<?php do_action('gdpr/privacy-tools-page/identify/after'); ?>
