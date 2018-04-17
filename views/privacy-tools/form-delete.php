<h2><?= __('Delete my user and data', 'gdpr-framework') ?></h2>

<div class="gdpr-delete-button">
    <form method="GET">
        <input type="hidden" name="gdpr_nonce" value="<?= $nonce; ?>"/>
        <input type="hidden" name="gdpr_action" value="<?= $action; ?>"/>
        <input type="submit" class="button button-primary" value="<?= __('Delete my data', 'gdpr-framework') ?>"/>
    </form>
</div>

<br/>
<p class="description">
    <?= __('Delete all data we have gathered about you.', 'gdpr-framework') ?> <br/>
    <?= __('If you have a user account on our site, it will also be deleted.', 'gdpr-framework') ?> <br/>
    <?= __('Be careful - this action is permanent and CANNOT be undone.', 'gdpr-framework') ?>
</p>

<hr>
