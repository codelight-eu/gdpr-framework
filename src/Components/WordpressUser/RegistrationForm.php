<?php

namespace Codelight\GDPR\Components\WordpressUser;

use Codelight\GDPR\DataSubject\DataSubjectManager;

class RegistrationForm
{
    /* @var DataSubjectManager */
    protected $dataSubjectManager;

    public function __construct(DataSubjectManager $dataSubjectManager)
    {
        $this->dataSubjectManager = $dataSubjectManager;

        if (gdpr('options')->get('policy_page')) {
            add_action('register_form', [$this, 'addRegisterFormCheckbox']);
            add_filter('registration_errors', [$this, 'validate'], PHP_INT_MAX);
        }
    }

    public function addRegisterFormCheckbox()
    {
        $privacyPolicyUrl = get_permalink(gdpr('options')->get('policy_page'));
        $termsPage = gdpr('options')->get('terms_page');

        if ($termsPage) {
            $termsUrl = get_permalink($termsPage);
        } else {
            $termsUrl = false;
        }

        echo gdpr('view')->render(
            'modules/wordpress-user/registration-terms-checkbox',
            compact('privacyPolicyUrl', 'termsUrl')
        );
    }

    public function validate(\WP_Error $errors)
    {
        if (empty($_POST['gdpr_terms']) || !$_POST['gdpr_terms']) {
            $errors->add('gdpr_error', __('<strong>ERROR</strong>: You must accept the terms and conditions.', 'gdpr-framework'));
        } else {
            $dataSubject = $this->dataSubjectManager->getByEmail($_POST['user_email']);
            $dataSubject->giveConsent('privacy-policy');
        }

        return $errors;
    }
}
