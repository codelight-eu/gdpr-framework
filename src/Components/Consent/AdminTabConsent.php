<?php

namespace Codelight\GDPR\Components\Consent;

use Codelight\GDPR\Admin\AdminTab;

/**
 * Handle rendering and saving the Consent tab on GDPR Options page
 *
 * Class AdminTabConsent
 * @package Codelight\GDPR\Components\Consent
 */
class AdminTabConsent extends AdminTab
{
    /* @var string */
    protected $slug = 'consent';

    /* @var ConsentManager */
    protected $consentManager;

    /**
     * AdminTabConsent constructor.
     *
     * @param ConsentManager $consentManager
     */
    public function __construct(ConsentManager $consentManager)
    {
        $this->consentManager = $consentManager;

        $this->title = _x('Consent', '(Admin)', 'gdpr-framework');

        // If we don't register the settings, WP will not allow this page to be submitted
        $this->registerSetting('consent_types');
        $this->registerSetting('consent_info');

        $this->renderErrors();

        // Register handler for this action
        add_action('gdpr/admin/action/update_consent_data', [$this, 'updateConsentData']);
    }

    /**
     * Initialize tab contents and register hooks
     */
    public function init()
    {
        $this->registerSettingSection(
            'gdpr_section_consent',
            _x('Consent', '(Admin)', 'gdpr-framework'),
            [$this, 'renderConsentForm']
        );
    }

    /**
     * Render the contents of the registered section
     */
    public function renderConsentForm()
    {
        $consentInfo = gdpr('options')->get('consent_info');

        if (is_null($consentInfo)) {
            $consentInfo = $this->getDefaultConsentInfo();
        } elseif (!$consentInfo) {
            $consentInfo = '';
        }

        $nonce = wp_create_nonce("gdpr/admin/action/update_consent_data");
        $defaultConsentTypes = $this->consentManager->getDefaultConsentTypes();
        $customConsentTypes = $this->consentManager->getCustomConsentTypes();

        // todo: move to a filter
        if (defined('ICL_LANGUAGE_CODE')) {
            $prefix = ICL_LANGUAGE_CODE . '_';
        } else {
            $prefix = '';
        }

        echo gdpr('view')->render('admin/consent', compact('nonce', 'customConsentTypes', 'defaultConsentTypes', 'consentInfo', 'prefix'));
    }

    /**
     * Save the submitted consent types
     */
    public function updateConsentData()
    {
        // Update additional information
        if (isset($_POST['gdpr_consent_info'])) {
            gdpr('options')->set('consent_info', wp_unslash($_POST['gdpr_consent_info']));
        }

        // Update consent types
        if (isset($_POST['gdpr_consent_types']) && is_array($_POST['gdpr_consent_types'])) {
            $consentTypes = $_POST['gdpr_consent_types'];
        } else {
            $consentTypes = [];
        }

        // Strip slashes which WP adds automatically
        if (count($consentTypes)) {
            foreach ($consentTypes as &$type) {
                foreach ($type as $key => $item) {
                    if (is_array($item)) {
                        $type[$key] = array_map('wp_unslash', $item);
                    } else {
                        $type[$key] = wp_unslash($item);
                    }

                    if ('visible' === $key) {
                        $type[$key] = 1;
                    }
                }
            }
        }

        $errors = [];

        if (!empty($consentTypes)) {
            $errors = $this->validate($consentTypes);
        }

        if (!count($errors)) {
            $this->consentManager->saveCustomConsentTypes($consentTypes);
        } else {
            $errorQuery = http_build_query($errors);
            wp_safe_redirect(gdpr('helpers')->getAdminUrl('&gdpr-tab=consent&') . $errorQuery);
            exit;
        }
    }

    protected function validate($consentTypes)
    {
        $errors = [];

        foreach ($consentTypes as $consentType) {
            if (empty($consentType['slug'])) {
                $errors['errors[]'] = 'slug-empty';
            }

            if (!preg_match('/^[A-Za-z0-9_-]+$/', $consentType['slug'])) {
                $errors['errors[]'] = 'slug-invalid';
            }

            if (empty($consentType['title'])) {
                $errors['errors[]'] = 'title-empty';
            }
        }

        return $errors;
    }

    public function renderErrors()
    {
        if (isset($_GET['errors']) && count($_GET['errors'])) {

            foreach ($_GET['errors'] as $error) {
                if ('slug-empty' === $error) {
                    $message = _x("Consent slug is a required field!", '(Admin)', 'gdpr-framework');
                    gdpr('admin-error')->add('admin/notices/error', compact('message'));
                }

                if ('slug-invalid' === $error) {
                    $message = _x("You may only use alphanumeric characters, dash and underscore in the consent slug field.", '(Admin)', 'gdpr-framework');
                    gdpr('admin-error')->add('admin/notices/error', compact('message'));
                }

                if ('title-empty' === $error) {
                    $message = _x("Consent title is a required field!", '(Admin)', 'gdpr-framework');
                    gdpr('admin-error')->add('admin/notices/error', compact('message'));
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getDefaultConsentInfo()
    {
        return __('To use this website, you accepted our Privacy Policy. If you wish to withdraw your acceptance, please use the "Delete my data" button below.', 'gdpr-framework');
    }
}
