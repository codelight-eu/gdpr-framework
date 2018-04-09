<p>
    <?= __('On this page, you can find which data subjects personal data you are storing and download, export or delete it.', 'gdpr-admin'); ?>
</p>

<hr>

<?= $results; ?>

<label>
    <h3><?= __('Find data subject by email', 'gdpr-admin'); ?></h3>
    <input type="email" name="gdpr_email" placeholder="<?= __('Email address', 'gdpr-admin'); ?>" />
</label>

<input type="hidden" name="gdpr_nonce" value="<?= $nonce; ?>" />
<input type="hidden" name="gdpr_action" value="search" />
<input class="button button-primary" type="submit" value="<?= __('Search', 'gdpr-admin'); ?>" />

<br><br>