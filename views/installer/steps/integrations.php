<h1>
    Integrations
</h1>

<h2>Theme compatibility</h2>
<p>
    The links to Privacy Policy and Privacy Tools should be visible somewhere on your site.
    A good place would be your site's footer.
</p>
<?php if ($isThemeSupported): ?>
    <p>
        We have detected that you are running <strong><?= esc_html(ucfirst($currentTheme)); ?> theme</strong>. We can automatically add the links to your site's footer if you'd like.
        <label for="gdpr_enable_theme_compatibility">
            <input
                type="checkbox"
                id="gdpr_enable_theme_compatibility"
                name="gdpr_enable_theme_compatibility"
                value="yes"
                <?= checked($enableThemeCompatibility, true); ?>
            />
            <?= _x('Automatically add Privacy Policy and Privacy Tools links to your site footer.', '(Admin)', 'gdpr-framework'); ?>
        </label>
    </p>
<?php endif; ?>
<hr>

<?php if ($hasWooCommerce): ?>
    <h2>WooCommerce compatibility</h2>
    <p>
        TBD
    </p>
    <hr>
<?php endif; ?>

<?php if ($hasEDD): ?>
    <h2>Easy Digital Downloads compatibility</h2>
    <p>
        TBD
    </p>
    <hr>
<?php endif; ?>

<h2>Custom development</h2>
<p>
    If you've had a developer build any custom features for your site, you should also make sure that everything is properly GDPR-compliant.
    <br>
    <a href="<?= gdpr('helpers')->docs('developer-docs/'); ?>" target="_blank">Read about making custom-built sites and features GDPR-compliant.</a>
</p>

<hr>
<br>
<input type="submit" class="button button-gdpr button-right" value="Continue &raquo;" />
