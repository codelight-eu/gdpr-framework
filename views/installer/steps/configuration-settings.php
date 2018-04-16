<h1>
    Configuration (2/2)
</h1>
<h2>&#10004; Privacy Tools page configured!</h2>
<p>
    You can take a look at the Privacy Tools page <a href="<?= $privacyToolsPageUrl; ?>" target="_blank">here</a>. <br>
    <br>
    <a href="<?= gdpr('helpers')->docs('guide/privacy-tools-page-accessing-exporting-and-deleting-personal-data/'); ?>" target="_blank">Read more about the Privacy Tools page</a>
</p>
<hr>

<h2>Right to view & export data</h2>
<p>
    Your customers have the right to review and export their personal data.

    <label for="gdpr_export_action">Select what happens if a customer wishes to view or export their personal data</label>

    <select class="gdpr-select js-gdpr-conditional" name="gdpr_export_action">
        <?= gdpr('view')->render('global/export-action', compact('exportAction')); ?>
    </select>
    <span class="hidden js-gdpr-export-action-email">
        <label for="export_action_email">
            <?= _x('Enter the email address to notify', '(Admin)', 'gdpr'); ?>
        </label>
        <input
                type="email"
                id="gdpr_export_action_email"
                name="gdpr_export_action_email"
                placeholder="<?= __('Email address', 'gdpr'); ?>"
                value="<?= esc_attr($exportActionEmail); ?>"
        />
    </span>
</p>
<hr>

<h2>Right to be forgotten</h2>
<p>
    Your customers have the right to request deleting their personal data.

    <label for="gdpr_delete_action">Select what happens if a customer wishes to delete their personal data</label>

    <select class="gdpr-select js-gdpr-conditional" name="gdpr_delete_action">
        <?= gdpr('view')->render('global/delete-action', compact('deleteAction')); ?>
    </select>

    <span class="hidden js-gdpr-delete-action-reassign">
        <label for="gdpr_delete_action_reassign">If the user has created any content (posts or pages), should it be deleted or reassigned?</label>
        <select id="gdpr_delete_action_reassign" name="gdpr_delete_action_reassign" class="gdpr-select js-gdpr-conditional">
            <option value="delete" <?= selected($reassign, 'delete'); ?>>
                <?= _x('Delete content', '(Admin)', 'gdpr'); ?>
            </option>
            <option value="reassign" <?= selected($reassign, 'reassign'); ?> data-show=".js-gdpr-delete-action-reassign-user">
                <?= _x('Reassign content to a user', '(Admin)', 'gdpr'); ?>
            </option>
        </select>
    </span>

    <span class="hidden js-gdpr-delete-action-reassign-user">
        <label for="gdpr_delete_action_reassign_user">Select the user to reassign content to</label>
        <?php wp_dropdown_users([
            'name'              => 'gdpr_delete_action_reassign_user',
            'show_option_none'  => _x('&mdash; Select &mdash;', '(Admin)', 'gdpr'),
            'option_none_value' => '0',
            'selected'          => $reassignUser,
            'class'             => 'js-gdpr-select2 gdpr-select',
            'id'                => 'gdpr_delete_action_reassign_user',
            'role__in'          => apply_filters('gdpr/options/reassign/roles', ['administrator', 'editor']),
        ]); ?>
    </span>

    <span class="hidden js-gdpr-delete-action-email">
        <label for="delete_action_email">
            <?= _x('Enter the email address to notify', '(Admin)', 'gdpr'); ?>
        </label>
        <input
            type="email"
            id="gdpr_delete_action_email"
            name="gdpr_delete_action_email"
            placeholder="<?= __('Email address', 'gdpr'); ?>"
            value="<?= esc_attr($deleteActionEmail); ?>"
        />
    </span>
</p>

<hr>
<br>
<input type="submit" class="button button-gdpr button-right" value="Save &raquo;"/>
