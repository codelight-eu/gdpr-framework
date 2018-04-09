<hr>

<table class="form-table">
    <tr>
        <th>
            <label>
                <?= __('Download your data', 'gdpr') ?>
            </label>
        </th>
        <td>
            <a class="button button-primary" href="<?= esc_url($exportHTMLUrl); ?>">
                <?= __('Download as table', 'gdpr') ?>
            </a>
            <a class="button button-primary" href="<?= esc_url($exportJSONUrl); ?>">
                <?= __('Export as JSON', 'gdpr') ?>
            </a>
            <br />
            <p class="description">
                <?= __('You can download all your data formatted as a table for viewing.', 'gdpr') ?> <br>
                <?= __('Alternatively, you can export it in machine-readable JSON format.', 'gdpr') ?>
            </p>
        </td>
    </tr>
</table>
