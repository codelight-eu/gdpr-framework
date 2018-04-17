<hr>

<table class="form-table">
    <tr>
        <th>
            <label>
                <?= __('Download your data', 'gdpr-framework') ?>
            </label>
        </th>
        <td>
            <a class="button button-primary" href="<?= esc_url($exportHTMLUrl); ?>">
                <?= __('Download as table', 'gdpr-framework') ?>
            </a>
            <a class="button button-primary" href="<?= esc_url($exportJSONUrl); ?>">
                <?= __('Export as JSON', 'gdpr-framework') ?>
            </a>
            <br />
            <p class="description">
                <?= __('You can download all your data formatted as a table for viewing.', 'gdpr-framework') ?> <br>
                <?= __('Alternatively, you can export it in machine-readable JSON format.', 'gdpr-framework') ?>
            </p>
        </td>
    </tr>
</table>
