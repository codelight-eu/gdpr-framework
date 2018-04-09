<hr>

<h3><?= __('Default consent types', 'gdpr-admin'); ?></h3>
<p><?= __('These are the consent types that have been automatically registered by the framework or a plugin.', 'gdpr-admin'); ?></p>
<?php if (count($defaultConsentTypes)): ?>
    <table class="gdpr-consent">
        <th><?= __('Slug', 'gdpr-admin'); ?></th>
        <th><?= __('Title', 'gdpr-admin'); ?></th>
        <th><?= __('Description', 'gdpr-admin'); ?></th>
        <th><?= __('Visibility', 'gdpr-admin'); ?></th>
    <?php foreach ($defaultConsentTypes as $consentType): ?>
        <tr>
            <td class="gdpr-consent-table-input"><?= $consentType['slug']; ?></td>
            <td class="gdpr-consent-table-input"><?= $consentType['title']; ?></td>
            <td class="gdpr-consent-table-desc"><?= $consentType['description']; ?></td>
            <td>
                <?php if ($consentType['visible']): ?>
                    <?= __('Visible', 'gdpr-admin'); ?>
                <?php else: ?>
                    <?= __('Hidden', 'gdpr-admin'); ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
<br>
<hr>
<h3><?= __('Custom consent types', 'gdpr-admin'); ?></h3>
<p><?= __('Here you can add custom consent types to track. They will not be used anywhere by default - you will need to build an integration for each of them.', 'gdpr-admin'); ?></p>
<div class="js-gdpr-repeater" data-name="gdpr_consent_types">
    <table class="gdpr-consent-admin" data-repeater-list="gdpr_consent_types">
        <thead>
            <th>
                <?= __('Machine-readable slug', 'gdpr-admin'); ?>*
            </th>
            <th>
                <?= __('Title', 'gdpr-admin'); ?>*
            </th>
            <th>
                <?= __('Description', 'gdpr-admin'); ?>
            </th>
            <th>
                <?= __('Visible?', 'gdpr-admin'); ?>
            </th>
        </thead>
        <tr data-repeater-item>
            <td class="gdpr-consent-table-input">
                <input
                        type="text"
                        name="slug"
                        placeholder="<?= __('Slug', 'gdpr-admin'); ?>"
                        pattern="^[A-Za-z0-9_-]+$"
                        oninvalid="setCustomValidity('Please fill in this field using alphanumeric characters, dashes and underscores.')"
                        oninput="setCustomValidity('')"
                        required
                />
            </td>
            <td class="gdpr-consent-table-input">
                <input type="text" name="title" placeholder="<?= __('Title', 'gdpr-admin'); ?>" required />
            </td>
            <td class="gdpr-consent-table-desc">
                <textarea type="text" name="description" placeholder="<?= __('Description', 'gdpr-admin'); ?>"></textarea>
            </td>
            <td>
                <label>
                    <input type="checkbox" name="visible" value="1"/>
                    <?= __('Visible?', 'gdpr-admin'); ?>
                </label>
            </td>
            <td>
              <input data-repeater-delete class="button button-primary" type="button" value="<?= __('Remove', 'gdpr-admin'); ?>"/>
            </td>
        </tr>

    </table>
    <div class="gdpr-consent-add-button">
      <input data-repeater-create class="button button-primary" type="button" value="Add consent type"/>
    </div>
    <input type="hidden" name="gdpr_nonce" value="<?= $nonce; ?>" />
    <input type="hidden" name="gdpr_action" value="update_consent_data" />
</div>

<?php if (count($customConsentTypes)): ?>
    <script>
        window.repeaterData = [];
        window.repeaterData['gdpr_consent_types'] = <?= json_encode($customConsentTypes); ?>;
    </script>
<?php endif; ?>

<br>
<hr>
<h3><?= __('Additional info', 'gdpr-admin'); ?></h3>
<p>
    <?= __('This text will be displayed to your data subjects on the Privacy Tools page.', 'gdpr-admin'); ?>
</p>
<?php wp_editor(
    wp_kses_post($consentInfo),
    'gdpr_consent_info',
    [
        'textarea_rows' => 4,
    ]
  );
?>

