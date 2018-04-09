<option disabled>-- Choose --</option>

<optgroup label="Outside EU">
    <?php foreach ($outside as $code => $name): ?>
        <option
            value="<?= esc_attr($code); ?>"
            <?= selected($code, $current); ?>
            <?php if (in_array($code, ['UK', 'US', 'other'])): ?>
                data-show=".gdpr-representative"
            <?php endif; ?>
        >
            <?= esc_html($name); ?>
        </option>
    <?php endforeach; ?>
</optgroup>

<optgroup label="European Union">
    <?php foreach ($eu as $code => $name): ?>
        <option value="<?= esc_attr($code); ?>" <?= selected($code, $current); ?>>
            <?= esc_html($name); ?>
        </option>
    <?php endforeach; ?>
</optgroup>
