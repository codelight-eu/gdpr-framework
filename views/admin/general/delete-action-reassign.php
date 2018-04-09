<select id="gdpr_delete_action_reassign" name="gdpr_delete_action_reassign" class="gdpr-select js-gdpr-conditional">
    <option value="delete" <?= selected($reassign, 'delete'); ?>>
        <?= __('Delete content', 'gdpr-admin'); ?>
    </option>
    <option value="reassign" <?= selected($reassign, 'reassign'); ?> data-show=".js-gdpr-delete-action-reassign-user">
        <?= __('Reassign content to a user', 'gdpr-admin'); ?>
    </option>
</select>
<p class="description">
    <?= __('If the user has submitted any content on your site, should it be deleted or reassigned to another user?', 'gdpr-admin'); ?>
</p>