<h2><?php echo esc_html(__('Privacy', 'gdpr-framework')); ?></h2>
<fieldset>
    <legend>
        <?php //_ex('Privacy configuration', '(Admin)', 'gdpr-framework'); ?>
    </legend>

    <p class="description">
        <label for="gdpr_cf7_enabled">
            <input type="checkbox" id="gdpr_cf7_enabled" name="gdpr_cf7_enabled" value="1" <?= checked($enabled, true); ?>>
            <?php _ex("Include the entries of this form when downloading or deleting a data subject's data.", '(Admin)', 'gdpr-framework'); ?>
        </label>
    </p>

    <br>

    <p class="description">
        <label for="gdpr_cf7_email_field">
            <?php _ex("Enter the mail-tag of the sender's email field (for example, your-email).", '(Admin)', 'gdpr-framework'); ?>
            <br>
            <input
                    type="text"
                    id="gdpr_cf7_email_field"
                    name="gdpr_cf7_email_field"
                    class="large-text"
                    size="70"
                    value="<?php echo $emailField ? $emailField : 'your-email'; ?>"
                    placeholder="your-email"
            >
        </label>
    </p>
</fieldset>