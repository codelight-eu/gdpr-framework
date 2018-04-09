<?php

namespace Codelight\GDPR\Components\WordpressComments;

use Codelight\GDPR\DataSubject\DataSubject;
use Codelight\GDPR\DataSubject\DataSubjectManager;

class WordpressComments
{
    /* @var DataSubjectManager */
    protected $dataSubjectManager;

    public function __construct(DataSubjectManager $dataSubjectManager)
    {
        $this->dataSubjectManager = $dataSubjectManager;

        $this->setup();
    }

    public function setup()
    {
        if (gdpr('options')->get('policy_page')) {
            add_action('comment_form_after_fields', [$this, 'maybeAddCommentFormCheckbox']);
            add_action('comment_form_logged_in_after', [$this, 'maybeAddCommentFormCheckbox']);

            add_filter('preprocess_comment', [$this, 'validate']);
        }

        add_filter('gdpr/data-subject/data', [$this, 'getExportData'], 1, 2);
        add_action('gdpr/data-subject/delete', [$this, 'deleteComments']);
        add_action('gdpr/data-subject/anonymize', [$this, 'deleteComments']);
    }

    /**
     * Check if consent is needed
     *
     * @return bool
     */
    public function needsConsent($email = null)
    {
        if ($email) {
            $dataSubject = $this->dataSubjectManager->getByEmail($email);
        } else {
            $dataSubject = $this->dataSubjectManager->getByLoggedInUser();
        }
        return !($dataSubject && $dataSubject->hasConsented('privacy-policy'));
    }

    /**
     * If consent is needed, render the checkbox
     *
     * @param $fields
     */
    public function maybeAddCommentFormCheckbox()
    {
        $email = isset($_POST['email']) ? $_POST['email'] : null;

        if (!$this->needsConsent($email)) {
            return;
        }

        $privacyPolicyUrl = get_permalink(gdpr('options')->get('policy_page'));
        $termsPage = gdpr('options')->get('terms_page');
        if ($termsPage) {
            $termsUrl = get_permalink($termsPage);
        } else {
            $termsUrl = false;
        }

        echo gdpr('view')->render(
            'modules/wordpress-comments/terms-checkbox',
            compact('termsUrl', 'privacyPolicyUrl')
        );
    }

    /**
     * If consent is needed, validate it
     */
    public function validate($commentData)
    {
        $email = isset($_POST['email']) ? $_POST['email'] : null;

        if (!$this->needsConsent($email)) {
            return $commentData;
        }

        if (!isset($_POST['gdpr_terms']) || !$_POST['gdpr_terms']) {
            wp_die(
                sprintf(
                    __('%sERROR:%s You need to accept the terms and conditions to post a comment.'),
                    '<strong>',
                    '</strong>'
                )
            );
        } else {
            if (is_user_logged_in()) {
                $dataSubject = $this->dataSubjectManager->getByLoggedInUser();
            } else {
                $dataSubject = $this->dataSubjectManager->getByEmail($email);
            }
            $dataSubject->giveConsent('privacy-policy');
        }

        return $commentData;
    }

    /**
     * Add comments as well as comment meta to export data
     *
     * @param $data
     * @param $email
     * @param $dataSubject
     * @return mixed
     */
    public function getExportData($data, $email)
    {
        $comments = $this->getCommentsByEmail($email);

        if (count($comments)) {

            foreach ($comments as $comment) {
                /* @var $comment \WP_Comment */

                $commentData = [
                    'comment_author' => $comment->comment_author,
                    'comment_author_email' => $comment->comment_author_email,
                    'comment_author_url' => $comment->comment_author_url,
                    'comment_author_IP' => $comment->comment_author_IP,
                    'comment_date' => $comment->comment_date,
                    'comment_date_gmt' => $comment->comment_date_gmt,
                    'comment_content' => $comment->comment_content,
                    'comment_approved' => $comment->comment_approved,
                    'comment_agent' => $comment->comment_agent,
                ];

                $commentMeta = get_comment_meta($comment->comment_ID);
                if (!empty($commentMeta)) {
                    $commentData['comment_meta'] = $commentMeta;
                }

                $data['comments'][] = $commentData;
            }
        }

        return $data;
    }

    public function deleteComments($email)
    {
        $comments = $this->getCommentsByEmail($email);

        if (!count($comments)) {
            return;
        }

        foreach ($comments as $comment) {
            /* @var $comment \WP_Comment */
            wp_delete_comment($comment->comment_ID, true);
        }
    }

    public function getCommentsByEmail($email)
    {
        if (!$email || !is_email($email)) {
            return [];
        }

        $query = new \WP_Comment_Query;
        return $query->query([
            'author_email' => $email,
        ]);
    }
}