<?php

namespace Codelight\GDPR\Updater;

class Updater
{
    public function __construct()
    {
        $currentVersion = $this->getVersion();

        if ($currentVersion === GDPR_FRAMEWORK_VERSION) {
            return;
        }

        if (version_compare($currentVersion, '1.0.5', '<')) {
            $this->update_1_0_5();
        }
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return get_option('gdpr_plugin_version', '1.0.0');
    }

    /**
     * Retroactively fix bug in v1.0.3 where the activation code didn't run properly
     */
    public function update_1_0_5()
    {
        $model = new \Codelight\GDPR\Components\Consent\UserConsentModel();
        $model->createTable();

        if (apply_filters('gdpr/data-subject/anonymize/change_role', true) && ! get_role('anonymous')) {

            add_role(
                'anonymous',
                _x('Anonymous', '(Admin)', 'gdpr-framework'),
                []
            );
        }

        update_option('gdpr_plugin_version', '1.0.5');
    }
}