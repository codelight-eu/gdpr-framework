<?php

namespace Codelight\GDPR\Components\PrivacyPolicy;

class PolicyGenerator
{
    public function generate()
    {
        return gdpr('view')->render(
            'policy/policy',
            $this->getData()
        );
    }

    public function getData()
    {
        $location = gdpr('options')->get('company_location');
        $date = date(get_option('date_format'));

        return [
            'companyName'     => gdpr('options')->get('company_name'),
            'companyLocation' => $location,
            'contactEmail'    => gdpr('options')->get('contact_email') ?
                gdpr('options')->get('contact_email') :
                get_option('admin_email'),

            'hasRepresentative'          => gdpr('helpers')->countryNeedsRepresentative($location),
            'representativeContactName'  => gdpr('options')->get('representative_contact_name'),
            'representativeContactEmail' => gdpr('options')->get('representative_contact_email'),
            'representativeContactPhone' => gdpr('options')->get('representative_contact_phone'),

            'dpaWebsite' => gdpr('options')->get('dpa_name'),
            'dpaEmail'   => gdpr('options')->get('dpa_email'),
            'dpaPhone'   => gdpr('options')->get('dpa_phone'),

            'hasDpo'   => gdpr('options')->get('has_dpo'),
            'dpoName'  => gdpr('options')->get('dpo_name'),
            'dpoEmail' => gdpr('options')->get('dpo_email'),

            'hasTerms' => gdpr('options')->get('terms_page'),

            'date' => $date,
        ];
    }
}