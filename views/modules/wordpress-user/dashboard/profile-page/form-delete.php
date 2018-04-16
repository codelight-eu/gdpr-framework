<table class="form-table">
    <tr>
        <th>
            <label>
                <?= _x('Delete this user and all data', '(Admin)', 'gdpr') ?>
            </label>
        </th>
        <td>
            <?php if ($showDelete): ?>
                <a class="button" href="<?= esc_url($deleteUrl); ?>">
                    <?= _x('Delete user and all data', '(Admin)', 'gdpr') ?>
                </a>
                <a class="button" href="<?= esc_url($anonymizeUrl); ?>">
                    <?= _x('Anonymize user and all data', '(Admin)', 'gdpr') ?>
                </a>
                <br/>
                <p class="description">
                    <?= _z('Be careful - this action is permanent and CANNOT be undone.', 'gdpr') ?>
                </p>
            <?php else: ?>
                <p>
                    <em>
                        <?= _x('You seem to have an administrator or equivalent role, so deleting/anonymizing via this page is disabled.', '(Admin)', 'gdpr'); ?>
                    </em>
                </p>
            <?php endif; ?>
        </td>
    </tr>
</table>
