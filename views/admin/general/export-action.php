<select class="gdpr-select js-gdpr-conditional" name="gdpr_export_action">
    <?= gdpr('view')->render('global/export-action', compact('exportAction')); ?>
</select>