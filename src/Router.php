<?php

namespace Codelight\GDPR;

use Codelight\GDPR\DataSubject\DataSubjectAuthenticator;

/**
 * Handles automatically identifying context and triggering actions based on $_REQUEST['gdpr_action']
 *
 * Class Router
 *
 * @package Codelight\GDPR
 */
class Router
{
    /* @var DataSubjectAuthenticator $authenticator */
    protected $authenticator;

    /**
     * Router constructor.
     *
     * @param DataSubjectAuthenticator $authenticator
     */
    public function __construct(DataSubjectAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;

        // Routing happens at priority 20 to allow other 'init' actions to complete before
        add_action('init', [$this, 'routeFrontendRequest'], 20);
        add_action('admin_init', [$this, 'routeAdminRequest'], 20);
    }

    /**
     * Get and sanitize the action parameter
     *
     * @return bool|mixed
     */
    protected function getAction()
    {
        if (!isset($_REQUEST['gdpr_action'])) {
            return false;
        }

        // Simple sanitization: allowed chars are alphanumeric, dash, underscore and forward slash.
        return preg_replace("/[^a-zA-Z0-9_\-\/]/", "", $_REQUEST['gdpr_action']);
    }

    /**
     * Detect and trigger proper action in front-end
     *
     * @param $action
     */
    public function routeFrontendRequest()
    {
        // Since the 'init' hooks runs in both admin and non-admin requests, double-check where we are
        if (is_admin()) {
            return;
        }

        // Handle identification by email
        $this->authenticator->identify();

        $action = $this->getAction();
        $nonce = isset($_REQUEST['gdpr_nonce']) ? $_REQUEST['gdpr_nonce'] : null;

        if (!$action) {
            return;
        }

        $dataSubject = $this->authenticator->authenticate();

        if ($dataSubject) {
            $tag = "gdpr/frontend/privacy-tools-page/action/{$action}";
            if (wp_verify_nonce($nonce, $tag)) {
                $key = isset($_REQUEST['gdpr_key']) ? $_REQUEST['gdpr_key'] : null;
                do_action($tag, $dataSubject, $key);
            } else {
                wp_die(
                    sprintf(
                        __('Nonce error for action "%s". Please go back and try again!', 'gdpr-framework'),
                        esc_html($action)
                    )
                );
            }
        } else {
            $tag = "gdpr/frontend/action/{$action}";
            if (wp_verify_nonce($nonce, $tag)) {
                do_action($tag);
            } else {
                wp_die(
                    sprintf(
                        __('Nonce error for action "%s". Please go back and try again!', 'gdpr-framework'),
                        esc_html($action)
                    )
                );
            }
        }
    }

    /**
     * Detect and trigger proper action in admin
     *
     * @param $action
     */
    public function routeAdminRequest()
    {
        $action = $this->getAction();
        $nonce = isset($_REQUEST['gdpr_nonce']) ? $_REQUEST['gdpr_nonce'] : null;

        if (!$action) {
            return;
        }

        if (isset($_GET['page']) && 'gdpr-profile' === $_GET['page']) {

            $dataSubject = $this->authenticator->authenticate();
            if ($dataSubject) {
                $tag = "gdpr/dashboard/privacy-tools/action/{$action}";

                if (wp_verify_nonce($nonce, $tag)) {
                    do_action($tag, $dataSubject);
                } else {
                    wp_die(
                        sprintf(
                            __('Nonce error for action "%s". Please go back and try again!', 'gdpr-framework'),
                            esc_html($action)
                        )
                    );
                }
            }
        } else {
            if ($this->checkAdminPermissions()) {

                $tag = "gdpr/admin/action/{$action}";

                if (wp_verify_nonce($nonce, $tag)) {
                    do_action($tag);
                } else {
                    wp_die(
                        sprintf(
                            __('Nonce error for action "%s". Please go back and try again!', 'gdpr-framework'),
                            esc_html($action)
                        )
                    );
                }
            } else {
                wp_die(
                    sprintf(
                        _x('You do not have the required permissions to perform this action!', '(Admin)', 'gdpr-framework'),
                        esc_html($action)
                    )
                );
            }
        }
    }

    /**
     * Check if the current user has the correct capability to perform an admin action
     *
     * @return bool
     */
    protected function checkAdminPermissions()
    {
        return current_user_can(apply_filters('gdpr/capability', 'manage_options'));
    }
}
