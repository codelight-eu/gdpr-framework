<h1>
    Privacy Policy (1/2)
</h1>

<h2>Privacy Policy page</h2>
<p>
    The second major requirement of GDPR is a thorough Privacy Policy that explains all of the rights your customers
    have and describes how exactly their data is used. We've put together a GDPR-compliant privacy policy template for you.
    Fill in the fields below and a privacy policy will be generated automatically. Note that you will need to modify it later to suit your website and business. <br>
    <a href="<?= gdpr('helpers')->docs('guide/setting-up-the-privacy-policy/'); ?>" target="_blank">Read more about the Privacy Policy</a><br><br>
    If you already have a GDPR-compliant Privacy Policy, simply select the page where it is displayed and skip the rest.
    <br>
</p>

<fieldset>
    <label>
        <input type="radio" name="gdpr_create_policy_page" value="yes" class="js-gdpr-conditional" <?php echo !$policyPage ? 'checked': ''; ?>>
        Automatically create a new page for Privacy Policy
    </label>

    <label>
        <input type="radio" name="gdpr_create_policy_page" value="no" class="js-gdpr-conditional" data-show=".gdpr-select-policy-page" <?php echo $policyPage ? 'checked': ''; ?>> I want to use an existing page
    </label>
</fieldset>

<p class="gdpr-select-policy-page hidden">
    <label for="gdpr_policy_page">Select the page where your Privacy Policy will be displayed</label>
    <?= $policyPageSelector; ?>
</p>

<p>
    We can generate a somewhat personalized Privacy Policy template for you based on some information you can fill in below.
    Note that if you're using an existing page, this will overwrite the page contents.

    <label for="gdpr_generate_policy">
        <input
                type="checkbox"
                name="gdpr_generate_policy"
                id="gdpr_generate_policy"
                class="js-gdpr-conditional"
                data-show=".gdpr-generator-fields"
                value="yes"
        >
        Generate Privacy Policy
    </label>
    <em>Heads up - this will take some time to configure!</em>
</p>

<hr>

<div class="gdpr-generator-fields hidden">

    <h2>Company information</h2>
    <label for="gdpr_company_name">Company legal name</label>
    <input type="text" id="gdpr_company_name" name="gdpr_company_name" value="<?= esc_attr($companyName); ?>"/>
    <em>Enter the name of your company. If you are not registered as a company, enter your full name.</em>

    <label for="gdpr_contact_email">Contact email</label>
    <input type="email" id="gdpr_contact_email" name="gdpr_contact_email" value="<?= esc_attr($contactEmail); ?>"/>
    <em>
        Enter the contact email which should be used for GDPR and personal data related inquiries.<br>
        This email will be displayed in the Privacy Policy.
    </em>

    <hr>

    <h2>Company location</h2>
    <label for="gdpr_company_location">Company location</label>
    <select id="gdpr_company_location" name="gdpr_company_location" class="js-gdpr-select2 gdpr-select js-gdpr-conditional js-gdpr-country-selector">
        <?= $countryOptions; ?>
    </select>
    <em>
        Select the country where your company is registered. <br>
        If you are not registered as a company, enter your country of residence.
    </em>
    <div class="gdpr-representative hidden">
        <p>

            If your company is located outside of the EU and the EFTA zone and you do not have a branch inside the EU,
            GDPR requires you to appoint a representative contact which has to be a person or company located in the EU.
            <a href="<?= gdpr('helpers')->docs('knowledge-base/do-i-need-to-appoint-an-eu-based-representative/'); ?>" target="_blank">Read more about appointing a representative and your options here</a>.
        </p>
        <p>
            If you have a representative contact, enter the contact details below.

            <label for="gdpr_representative_contact_name">Representative contact name</label>
            <input type="text" value="<?= esc_attr($representativeContactName); ?>" id="gdpr_representative_contact_name" name="gdpr_representative_contact_name" data />

            <label for="gdpr_representative_contact_email">Representative contact email</label>
            <input type="email" value="<?= esc_attr($representativeContactEmail); ?>" id="gdpr_representative_contact_email" name="gdpr_representative_contact_email" />

            <label for="gdpr_representative_contact_phone">Representative contact phone</label>
            <input type="text" value="<?= esc_attr($representativeContactPhone); ?>" id="gdpr_representative_contact_phone" name="gdpr_representative_contact_phone" />

        </p>
    </div>
    <br>
    <hr>

    <h2>Data Protection Authority</h2>
    <p>
        You are also obliged to provide the contact of your local supervisory authority.

        <span class="gdpr-representative hidden">
            <br><br>
            You are located outside of the EU. This means that you must designate an EU country's data protection authority to be your supervisory authority.
            Choose the Data Protection Authority of the country where most of your target audience resides. See the <a href="http://ec.europa.eu/justice/data-protection/article-29/structure/data-protection-authorities/index_en.htm" target="_blank">list of contacts here</a>.
        </span>

        <label for="gdpr_dpa_website">Data Protection Authority website</label>
        <input type="text" value="<?= esc_attr($dpaWebsite); ?>" id="gdpr_dpa_website" name="gdpr_dpa_website" data-set="<?= esc_attr($dpaWebsite); ?>" />

        <label for="gdpr_dpa_email">Data Protection Authority email</label>
        <input type="email" value="<?= esc_attr($dpaEmail); ?>" id="gdpr_dpa_email" name="gdpr_dpa_email" data-set="<?= esc_attr($dpaEmail); ?>" />

        <label for="gdpr_dpa_phone">Data Protection Authority phone</label>
        <input type="text" value="<?= esc_attr($dpaPhone); ?>" id="gdpr_dpa_phone" name="gdpr_dpa_phone" data-set="<?= esc_attr($dpaPhone); ?>" />
    </p>
    <script>
        window.gdprDpaData = <?= $dpaData; ?>;
    </script>

    <h2>Data Protection Officer</h2>
    <p>
        Under some circumstances, GDPR requires you to appoint a Data Protection Officer and display the contacts
        in your Privacy Policy. If you don't know whether or not you need a DPO, read this article: <a href="<?= gdpr('helpers')->docs('knowledge-base/do-i-need-to-appoint-data-protection-officer-dpo/'); ?>" target="_blank">Do I need a Data Protection Officer?</a>

        <label for="gdpr_has_dpo">
            <input
                type="checkbox"
                name="gdpr_has_dpo"
                id="gdpr_has_dpo"
                class="js-gdpr-conditional"
                data-show=".gdpr-dpo"
                value="yes"
                <?= checked($hasDPO, 'yes'); ?>
            >
            I have appointed a Data Protection Officer (DPO)
        </label>
    </p>
    <p class="gdpr-dpo hidden">
        <label for="gdpr_dpo_name">Data Protection Officer name</label>
        <input type="text" id="gdpr_dpo_name" name="gdpr_dpo_name" value="<?= esc_attr($dpoName); ?>"/>
        <em>This will be displayed in the Privacy Policy.</em>

        <label for="gdpr_dpo_email">Data Protection Officer email</label>
        <input type="email" id="gdpr_dpo_email" name="gdpr_dpo_email" value="<?= esc_attr($dpoEmail); ?>"/>
        <em>This will be displayed in the Privacy Policy.</em>
    </p>
    <hr>
</div>

<h2>Terms & Conditions page</h2>
<p>
    If you have a Terms & Conditions page, we will need to know where it is located. If you don't have a Terms & Conditions page, you can safely skip this step.<br>
    If you are unsure if you need a T&C page, read this article:
    <a href="<?= gdpr('helpers')->supportUrl('knowledge-base/do-i-need-a-terms-conditions-page/') ?>" target="_blank">Do I need a Terms & Conditions page?</a>

    <label for="gdpr_has_terms_page">
        <input
                type="checkbox"
                name="gdpr_has_terms_page"
                id="gdpr_has_terms_page"
                class="js-gdpr-conditional"
                data-show=".gdpr-terms-page"
                value="yes"
            <?= checked($hasTermsPage, 'yes'); ?>
        >
        I have a Terms & Conditions page
    </label>
</p>
<p>
    <span class="gdpr-terms-page hidden">
    <label for="gdpr_terms_page">Select the page where your Terms & Conditions are displayed</label>
        <?php if ($termsPageNote): ?>
            <em><?= esc_html($termsPageNote); ?></em>
        <?php endif; ?>
        <?= $termsPageSelector; ?>
        <br>
    </span>
</p>

<hr>
<br>
<input type="submit" class="button button-gdpr button-right" value="Save &raquo;" />
