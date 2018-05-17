<p class="gdpr-terms-container" style="margin-bottom: 10px">
    <label>
        <input type="checkbox" required name="gdpr_terms" id="gdpr_terms" value="1" />

        <?php if ($termsUrl): ?>
            <?= sprintf(
                __('I accept the %sTerms and Conditions%s and the %sPrivacy Policy%s', 'gdpr-framework'),
                "<a href='{$termsUrl}' target='_blank'>",
                '</a>',
                "<a href='{$privacyPolicyUrl}' target='_blank'>",
                '</a>'
            ); ?>
        <?php else: ?>
            <?= sprintf(
                __('I accept the %sPrivacy Policy%s', 'gdpr-framework'),
                "<a href='{$privacyPolicyUrl}' target='_blank'>",
                '</a>'
            ); ?>
        <?php endif; ?>*
    </label>
</p>
