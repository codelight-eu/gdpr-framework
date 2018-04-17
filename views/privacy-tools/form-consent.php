<?php if (count($consentData) or $consentInfo): ?>
    <h2><?= __('Consent', 'gdpr-framework'); ?></h2>

    <?php if (count($consentData)): ?>
        <p>
            <?= __('Here you can withdraw any consents you have given.', 'gdpr-framework'); ?>
        </p>
        <table class="gdpr-consent">
            <th colspan="4"><?= __('Consent types', 'gdpr-framework'); ?></th>
            <?php foreach ($consentData as $item): ?>
                <tr>
                    <td>
                        &#10004;
                    </td>
                    <td>
                        <?= $item['title']; ?>
                    </td>
                    <td>
                        <em><?= $item['description']; ?></em>
                    </td>
                    <td>
                        <?php if ('privacy-policy' !== $item['slug']): ?>
                            <a href="<?= esc_url($item['withdraw_url']); ?>" class="button button-primary">
                                <?= __('Withdraw', 'gdpr-framework'); ?>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if ($consentInfo): ?>
        <div class="gdpr-consent-disclaimer">
            <?= $consentInfo; ?>
        </div>
    <?php endif; ?>
    <hr>
<?php endif; ?>
