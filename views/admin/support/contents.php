<hr>

<section class="section">
    <h3 class="align-center">
        <?= __('Need more info?', 'gdpr-admin'); ?>
    </h3>
    <div class="row">
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/1.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('wordpress-site-owners-guide-to-gdpr/'); ?>" target="_blank">
                <?= __('Site Owner\'s guide to GDPR', 'gdpr-admin'); ?>
            </a>
            <p>
                <?= __('Read the full guide on GDPR compliance.', 'gdpr-admin'); ?>
            </p>
        </div>
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/2.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('knowledge-base'); ?>" target="_blank">
                <?= __('Knowledge base', 'gdpr-admin'); ?>
            </a>
            <p>
                <?= __('Check out the knowledge base for common questions and answers.', 'gdpr-admin'); ?>
            </p>
        </div>
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/3.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('developer-docs'); ?>" target="_blank">
                <?= __('Developer\'s guide to GDPR', 'gdpr-admin'); ?>
            </a>
            <p>
                <?= __('We have a thorough guide to help making custom sites compliant.', 'gdpr-admin'); ?>
            </p>
        </div>
    </div>
</section>

<section class="section">
    <h3 class="align-center">
        <?= __('Need help?', 'gdpr-admin'); ?>
    </h3>
    <div class="row">
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/4.png');"></div>
            <a class="button button-primary" href="https://wordpress.org/support/plugin/gdpr-framework" target="_blank">
                <?= __('Submit a support request', 'gdpr-admin'); ?>
            </a>
            <p>
                <?= __('Found a bug or problem with the plugin? Post in the wordpress.org support forum.', 'gdpr-admin'); ?>
            </p>
        </div>
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/5.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('wordpress-gdpr-consultation'); ?>" target="_blank">
                <?= __('Request a consultation', 'gdpr-admin'); ?>
            </a>
            <p>
                <?= __('Need development or legal assistance in making your site compliant? We can help!', 'gdpr-admin'); ?>
            </p>
        </div>
    </div>
</section>
