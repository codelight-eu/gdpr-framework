<?php do_action('gdpr/frontend/privacy-tools-page/content/before', $dataSubject); ?>

<p>
    <?= __('You are identified as', 'gdpr'); ?> <strong><?= esc_html($email); ?></strong>
</p>

<hr>

<?php do_action('gdpr/frontend/privacy-tools-page/content', $dataSubject); ?>

<?php do_action('gdpr/frontend/privacy-tools-page/content/after', $dataSubject); ?>

<style>
    .gdpr-notice {
        padding: 10px 20px;
        border: 1px solid #666;
    }

    .gdpr-consent td {
        padding: 0.6em;
    }

    .gdpr-consent td:first-child {
        padding-left: 0;
    }

    .gdpr-consent td:last-child {
        padding-right: 0;
    }
</style>