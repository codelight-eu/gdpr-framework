<h1>
    Configuration (1/2)
</h1>

<h2>Plugin settings</h2>
<p>In WordPress admin, there is now a new page under the Tools menu item called "Privacy." Once you've finished the wizard, you can modify the plugin settings there.</p>
<div>
    <img src="<?= gdpr('config')->get('plugin.url') . 'assets/menu.jpg'; ?>" />
</div>


<h2>Privacy Tools page</h2>
<p>
    The first major requirement of GDPR is that your customers need to be in control of their data. They have the
    right to view, edit and request deleting their <a href="<?= gdpr('helpers')->docs('guide/wordpress-gdpr-definitions-you-need-to-know/#personal-data'); ?>" target="_blank">personal data</a>. Note that this also
    applies to visitors who do not have accounts on your website.
</p>
<p>
    For this, we will designate a page where customers will be able to authenticate via login or email and automatically do all of the above.
    <a href="<?= gdpr('helpers')->docs('guide/privacy-tools-page-accessing-exporting-and-deleting-personal-data/'); ?>" target="_blank">Read more about the Privacy Tools page</a>
</p>
<hr>

<h4>Set up the Privacy Tools page</h4>
<fieldset>
    <label>
        <input type="radio" name="gdpr_create_tools_page" value="yes" class="js-gdpr-conditional" <?= !$privacyToolsPage ? 'checked': ''; ?>>
        Automatically create a new page for Privacy Tools
    </label>

    <label>
        <input type="radio" name="gdpr_create_tools_page" value="no" class="js-gdpr-conditional" data-show=".gdpr-select-privacy-tools-page" <?= $privacyToolsPage ? 'checked': ''; ?>> Select an existing page
    </label>
</fieldset>

<p class="gdpr-select-privacy-tools-page hidden">
    <label for="gdpr_tools_page">Select the page for Privacy Tools</label>
    <?= $privacyToolsPageSelector; ?>
    <strong>Important:</strong> Make sure that the page contains the <strong>[gdpr_privacy_tools]</strong> shortcode.
</p>

<hr>
<br>
<input type="submit" class="button button-gdpr button-right" value="Save &raquo;"/>
