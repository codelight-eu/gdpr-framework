<select class="gdpr-select js-gdpr-conditional" name="gdpr_delete_action">
    <?= gdpr('view')->render('global/delete-action', compact('deleteAction')); ?>
</select>