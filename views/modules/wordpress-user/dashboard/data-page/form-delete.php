<table class="form-table">
    <tr>
        <th>
            <label>
                <?= _x('Delete this user and all data', '(Admin)', 'gdpr-framework') ?>
            </label>
        </th>
        <td>
            <?php if ($showDelete): ?>
                <a class="button" href="<?= esc_url($url); ?>">
                    <?= _x('Delete my data', '(Admin)', 'gdpr-framework') ?>
                </a>
                <br/>
                <p class="description">
                    <?= __('Delete all data we have gathered about you.', 'gdpr-framework') ?> <br/>
                    <?= __('If you have a user account on our site, it will also be deleted.', 'gdpr-framework') ?> <br/>
                    <?= __('Be careful - this action is permanent and CANNOT be undone.', 'gdpr-framework') ?>
                </p>
            <?php else: ?>
                <p>
                    <em>
                        <?= _x('You seem to have an administrator or equivalent role, so deleting/anonymizing via this page is disabled.', '(Admin)', 'gdpr-framework'); ?>
                    </em>
                </p>
            <?php endif; ?>
        </td>
    </tr>
</table>


