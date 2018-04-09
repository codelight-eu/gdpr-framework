<?php

namespace Codelight\GDPR\Admin;

/**
 * Handle registering and rendering the GDPR admin page contents
 *
 * Class WordpressAdminPage
 *
 * @package Codelight\GDPR\Admin
 */
class WordpressAdminPage
{
    protected $slug = 'gdpr';

    protected $tabs = [];

    public function __construct()
    {
        $this->setup();
    }

    protected function setup()
    {
        // Register the tabs
        add_action('admin_init', [$this, 'registerTabs']);

        // todo
        //if (gdpr('options')->get('plugin_disclaimer_accepted')) {
            // Initialize the active tab
            add_action('admin_init', [$this, 'initActiveTab']);
        //}

        // todo
        // gdpr('admin-modal')->add('gdpr-test', 'admin/modals/test', ['title' => 'Test modal']);
    }

    /**
     * Render the main GDPR options page
     */
    public function renderPage()
    {
        $tabs = $this->getNavigationData();
        $currentTabContents = $this->getActiveTab()->renderContents();
        $signature = apply_filters('gdpr/admin/show_signature', true);
        echo gdpr('view')->render('admin/settings-page', compact('tabs', 'currentTabContents', 'signature'));
    }

    /**
     * Allow modules to add tabs
     */
    public function registerTabs()
    {
        $this->tabs = apply_filters('gdpr/admin/tabs', []);
    }

    /**
     * Get the active tab or the first tab if none are active
     *
     * @return AdminTabInterface
     */
    public function getActiveTab()
    {
        foreach ($this->tabs as $tab) {
            if (isset($_GET['gdpr-tab']) && $_GET['gdpr-tab'] === $tab->getSlug()) {
                return $tab;
            }
        }

        return reset($this->tabs);
    }

    /**
     * Check if the given tab is active
     *
     * @param $slug
     * @return bool
     */
    public function isTabActive($slug)
    {
        $activeTab = $this->getActiveTab();
        if ($activeTab->getSlug() === $slug) {
            return true;
        }

        // Hacky: if no tab set, the first tab is active
        if (!isset($_GET['gdpr-tab'])) {
            $firstTab = reset($this->tabs);
            if ($firstTab->getSlug() === $slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * Initialize the active tab
     */
    public function initActiveTab()
    {
        $activeTab = $this->getActiveTab();
        $activeTab->setup();
    }

    /**
     * Get the tabbed navigation for GDPR options page
     *
     * @return array
     */
    public function getNavigationData()
    {
        if (!count($this->tabs)) {
            return [];
        }

        $navigation = [];

        foreach ($this->tabs as $tab) {
            /* @var $tab AdminTabInterface */
            $navigation[$tab->getSlug()] = [
                'slug'   => $tab->getSlug(),
                'url'    => $this->getTabUrl($tab->getSlug()),
                'title'  => $tab->getTitle(),
                'active' => false,
            ];

            if ($this->isTabActive($tab->getSlug())) {
                $navigation[$tab->getSlug()]['active'] = true;
            }
        }

        return $navigation;
    }


    /**
     * todo: move to helper?
     *
     * @param $slug
     * @return string
     */
    public function getTabUrl($slug)
    {
        return admin_url('tools.php?page=privacy&gdpr-tab=' . $slug);
    }
}
