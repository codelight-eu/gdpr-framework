<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        <?php esc_html_e( 'WordPress GDPR &rsaquo; Setup Wizard', 'gdpr-admin' ); ?>
    </title>
    <?php wp_print_scripts(['jquery']); ?>
    <?php do_action('admin_print_styles'); ?>
    <?php do_action('admin_head'); ?>
</head>

<body class="gdpr-installer wp-core-ui">

    <div class="container gdpr-installer-container">
        <div class="gdpr-header">
          <div class="gdpr-header_left">
            <img class="gdpr-logo" src="<?= gdpr('config')->get('plugin.url'); ?>/assets/gdpr-rhino.svg" />
          </div>
          <div class="gdpr-header_right">
            <h1>
              <?= __('The GDPR Framework', 'gdpr-admin'); ?>
            </h1>
            <a href="<?= gdpr('helpers')->docs('wordpress-gdpr-consultation'); ?>" class="button button-secondary button-side" target="_blank">
              <?= __('I need help', 'gdpr-admin'); ?>
            </a>
            <a href="<?= gdpr('helpers')->docs('developer-docs'); ?>" class="button button-secondary button-side" target="_blank">
              <?= __('Developer Docs', 'gdpr-admin'); ?>
            </a>
          </div>
        </div>
        <div class="gdpr-breadcrumbs">
          <div class="gdpr-breadcrumbs_unit <?= $activeSteps > 0 ? 'active' : ''; ?>">
            <div class="gdpr-breadcrumbs_item">
              <?= __('Configuration', 'gdpr-admin'); ?>
            </div>
          </div>
          <div class="gdpr-breadcrumbs_unit <?= $activeSteps > 1 ? 'active' : ''; ?>">
            <div class="gdpr-breadcrumbs_item">
              <?= __('Privacy Policy', 'gdpr-admin'); ?>
            </div>
          </div>
          <div class="gdpr-breadcrumbs_unit <?= $activeSteps > 2 ? 'active' : ''; ?>">
            <div class="gdpr-breadcrumbs_item">
              <?= __('Forms & Consent', 'gdpr-admin'); ?>
            </div>
          </div>
          <div class="gdpr-breadcrumbs_unit <?= $activeSteps > 3 ? 'active' : ''; ?>">
            <div class="gdpr-breadcrumbs_item">
              <?= __('Integrations', 'gdpr-admin'); ?>
            </div>
          </div>
        </div>

        <div class="gdpr-content">

            <?php if (isset($_GET['gdpr-error'])): ?>
                <p class="error">Failed to validate nonce! Please reload page and try again.</p>
            <?php endif; ?>

            <!-- Open the installer form -->
            <form method="POST">
                <input type="hidden" name="gdpr-installer" value="next" />
