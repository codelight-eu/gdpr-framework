<hr>
<?php if (count($consentData)): ?>
    <table class="gdpr-consent">
        <th colspan="2"><?= __('Consents given', 'gdpr-admin'); ?></th>
        <?php foreach ($consentData as $item): ?>
            <tr>
                <td>
                    &#10004;
                </td>
                <td>
                    <?= $item['title']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p><?= __('No consents given', 'gdpr-admin'); ?>.</p>
<?php endif; ?>
