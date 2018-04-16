<div class="wrap gdpr-framework-wrap">
    <h2>
        <?= _x('The GDPR Framework', '(Admin)', 'gdpr'); ?>
    </h2>

    <?php if (!empty($_GET['updated'])) : ?>
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
            <p><strong><?php _ex('GDPR settings saved!', '(Admin)', 'gdpr') ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (count($tabs)): ?>
        <nav class="nav-tab-wrapper">
            <?php foreach ($tabs as $slug => $tab): ?>
                <a href="<?= $tab['url']; ?>" class="nav-tab <?= $tab['active'] ? 'nav-tab-active' : ''; ?>">
                    <?= $tab['title'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>

    <form action="options.php" method="POST">
      <?= $currentTabContents; ?>
    </form>

    <?php if ($signature): ?>
        <hr>
        <p>
            <em>
                <?= sprintf(
                    _x('The GDPR Framework. Built with &#9829; by %sCodelight%s.', '(Admin)', 'gdpr'),
                    '<a href="https://codelight.eu/" target="_blank">',
                        '</a>'
                ); ?>
            </em>
        </p>
    <?php endif; ?>
</div>
