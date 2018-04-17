<?php

namespace Codelight\GDPR\Modules\ContactForm7;

use Codelight\GDPR\Components\Consent\ConsentManager;
use Codelight\GDPR\DataSubject\DataSubjectManager;

class ContactForm7
{
    public function __construct(DataSubjectManager $dataSubjectManager, ConsentManager $consentManager)
    {
        $this->dataSubjectManager = $dataSubjectManager;
        $this->consentManager = $consentManager;

        //add_action('wpcf7_init', [$this, 'addFormTags'], 10, 3);
        //add_action('wpcf7_admin_init', [$this, 'addFormTagGenerators'], 9999, 3);

        add_action('wpcf7_before_send_mail', [$this, 'processFormSubmission'], 10, 3);
    }

    public function addFormTags()
    {
        wpcf7_add_form_tag(
            ['gdpr_consent_text'],
            [$this, 'renderPrivacyTag']
        );
    }

    public function addFormTagGenerators()
    {
        $generator = \WPCF7_TagGenerator::get_instance();

        $generator->add(
            'gdpr_privacy',
            _x('gdpr terms txt', '(Admin)', 'gdpr-framework'),
            [$this, 'generatePrivacyTag']
        );
    }

    public function generatePrivacyTag($contactForm, $args)
    {
        //$args = wp_parse_args( $args, array() );
        //$description = _x( "Generate the default text for your Privacy Policy acceptance checkbox. For more details, see %s.", '(Admin)', 'gdpr-framework' );
        //$descLink = wpcf7_link( _x( 'https://TODO', '(Admin)', 'gdpr-framework' ), _x( 'GDPR Terms', '(Admin)', 'gdpr-framework' ) );
        //$content = $this->renderPrivacyTag();

        echo gdpr('view')->render(
            'modules/contact-form-7/generator-privacy',
            compact('description', 'descLink', 'args', 'content')
        );
    }

    public function renderPrivacyTag()
    {
        $privacyPolicyUrl = get_permalink(gdpr('options')->get('policy_page'));

        return gdpr('view')->render(
            'modules/contact-form-7/content-privacy',
            compact('privacyPolicyUrl')
        );
    }

    public function processFormSubmission(\WPCF7_ContactForm $form, $abort, \WPCF7_Submission $submission)
    {
        $consents = $this->findConsents($form, $submission);

        if (!count($consents)) {
            return;
        }

        $email = $this->findEmail($submission);

        if (!$email) {
            return;
        }

        $dataSubject = $this->dataSubjectManager->getByEmail($email);

        foreach ($consents as $consent) {
            $dataSubject->giveConsent($consent);
        }
    }

    public function findConsents(\WPCF7_ContactForm $form, \WPCF7_Submission $submission)
    {
        $consents = [];

        foreach ($form->scan_form_tags() as $tag) {
            /* @var $tag \WPCF7_FormTag */
            if ('acceptance' === $tag->type && $submission->get_posted_data()[$tag->name] && $this->consentManager->isRegisteredConsent($tag->name)) {
                $consents[] = $tag->name;
            }
        }

        return $consents;
    }

    public function findEmail(\WPCF7_Submission $submission)
    {
        if (isset($submission->get_posted_data()['your-email'])) {
            return $submission->get_posted_data()['your-email'];
        }

        if (isset($submission->get_posted_data()['email'])) {
            return $submission->get_posted_data()['email'];
        }
    }
}
