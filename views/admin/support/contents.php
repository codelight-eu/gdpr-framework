<hr>

<section class="section">
    <h3 class="align-center">
        <?= _x('Need more info?', '(Admin)', 'gdpr'); ?>
    </h3>
    <div class="row">
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/1.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('wordpress-site-owners-guide-to-gdpr/'); ?>" target="_blank">
                <?= _x('Site Owner\'s guide to GDPR', '(Admin)', 'gdpr'); ?>
            </a>
            <p>
                <?= _x('Read the full guide on GDPR compliance.', '(Admin)', 'gdpr'); ?>
            </p>
        </div>
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/2.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('knowledge-base'); ?>" target="_blank">
                <?= _x('Knowledge base', '(Admin)', 'gdpr'); ?>
            </a>
            <p>
                <?= _x('Check out the knowledge base for common questions and answers.', '(Admin)', 'gdpr'); ?>
            </p>
        </div>
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/3.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('developer-docs'); ?>" target="_blank">
                <?= _x('Developer\'s guide to GDPR', '(Admin)', 'gdpr'); ?>
            </a>
            <p>
                <?= _x('We have a thorough guide to help making custom sites compliant.', '(Admin)', 'gdpr'); ?>
            </p>
        </div>
    </div>
</section>

<section class="section">
    <h3 class="align-center">
        <?= _x('Need help?', '(Admin)', 'gdpr'); ?>
    </h3>
    <div class="row">
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/4.png');"></div>
            <a class="button button-primary" href="https://wordpress.org/support/plugin/gdpr-framework" target="_blank">
                <?= _x('Submit a support request', '(Admin)', 'gdpr'); ?>
            </a>
            <p>
                <?= _x('Found a bug or problem with the plugin? Post in the wordpress.org support forum.', '(Admin)', 'gdpr'); ?>
            </p>
        </div>
        <div class="col">
          <div class="col_image" style="background-image:url('<?= gdpr('config')->get('plugin.url'); ?>/assets/5.png');"></div>
            <a class="button button-primary" href="<?= gdpr('helpers')->docs('wordpress-gdpr-consultation'); ?>" target="_blank">
                <?= _x('Request a consultation', '(Admin)', 'gdpr'); ?>
            </a>
            <p>
                <?= _x('Need development or legal assistance in making your site compliant? We can help!', '(Admin)', 'gdpr'); ?>
            </p>
        </div>
    </div>
</section>
