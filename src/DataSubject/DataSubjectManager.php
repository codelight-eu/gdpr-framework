<?php

namespace Codelight\GDPR\DataSubject;

use Codelight\GDPR\Components\Consent\ConsentManager;

/**
 * Handles finding data subjects by ID or email
 *
 * Class DataSubjectManager
 *
 * @package Codelight\GDPR\DataSubject
 */
class DataSubjectManager
{
    /* @var ConsentManager */
    protected $consentManager;

    /**
     * DataSubjectManager constructor.
     *
     * @param ConsentManager $consentManager
     */
    public function __construct(ConsentManager $consentManager)
    {
        $this->consentManager = $consentManager;
    }

    /**
     * @param $email
     * @return DataSubject
     */
    public function getByEmail($email)
    {
        $user = get_user_by('email', $email);

        return gdpr()->makeWith(
            DataSubject::class,
            [
                'email'          => $email,
                'user'           => $user ? $user : null,
                'consentManager' => $this->consentManager,
            ]
        );
    }

    /**
     * @param $id
     * @return DataSubject|false
     */
    public function getById($id)
    {
        $user = get_user_by('id', $id);

        if (!$user) {
            return false;
        }

        return gdpr()->makeWith(
            DataSubject::class,
            [
                'email'          => $user->user_email,
                'user'           => $user,
                'consentManager' => $this->consentManager,
            ]
        );
    }

    /**
     * @return bool|DataSubject
     */
    public function getByLoggedInUser()
    {
        if (!is_user_logged_in()) {
            return false;
        }

        return $this->getById(get_current_user_id());
    }
}