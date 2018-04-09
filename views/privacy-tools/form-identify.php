<?php do_action('gdpr/privacy-tools-page/identify/before'); ?>

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

<?php do_action('gdpr/privacy-tools-page/identify/after'); ?>
