<?php

namespace Codelight\GDPR\Components\WordpressUser;

use Codelight\GDPR\DataSubject\DataSubject;

class DataManager
{
    public function getData(DataSubject $dataSubject)
    {
        $user = $dataSubject->getUser();
        if ($user) {
            $meta = get_user_meta($user->ID);
            $data['meta'] = $meta;
        } else {
            $data = [];
        }

        $whitelist = [
            'nickname',
            'first_name',
            'last_name',
            'description',
            'locale',
            'community-events-location',
        ];

        if (isset($data['meta']) && count($data['meta'])) {
            foreach ($data['meta'] as $key => $value) {
                if (!in_array($key, $whitelist)) {
                    unset($data['meta'][$key]);
                }
            }
        }

        // Remove session keys. Just in case.
        if (isset($meta) && isset($meta['session_tokens']) && count($meta['session_tokens'])) {
            foreach ($meta['session_tokens'] as $token) {
                foreach (unserialize($token) as $key => $tokenData) {
                    $data['meta']['session_tokens'][] = $tokenData;
                }
            }
        }

        /*
        $blacklist = [
            'use_ssl',
            'show_admin_bar_front',
            'wp_capabilities',
            'wp_user_level',
            'dismissed_wp_pointers',
            'show_welcome_panel',
            'wp_dashboard_quick_press_last_post_id',
            'wp_user-settings',
            'wp_user-settings-time',
            'closedpostboxes_page',
            'metaboxhidden_page',
            'session_tokens',
            'managenav-menuscolumnshidden',
            'metaboxhidden_nav-menus',
            'nav_menu_recently_edited',
            'acf_user_settings',
        ];

        // Blacklist some data
        if (isset($data['meta']) && count($data['meta'])) {
            foreach ($data['meta'] as $key => $value) {
                if (in_array($key, $blacklist)) {
                    unset($data['meta'][$key]);
                }
            }

            $data['meta'] = array_diff_assoc($data['meta'], $blacklist);
        }
        */

        return apply_filters('gdpr/wordpress-user/export/data', $data);
    }

    public function deleteUser(DataSubject $dataSubject, $reassign = null)
    {
        require_once(ABSPATH . 'wp-admin/includes/user.php');

        $reassignOption = gdpr('options')->get('delete_action_reassign');
        if ('reassign' === $reassignOption) {
            $reassignUserId = gdpr('options')->get('delete_action_reassign_user');
        } else {
            $reassignUserId = false;
        }

        wp_delete_user($dataSubject->getUserId(), $reassignUserId);
    }

    public function anonymizeUser(DataSubject $dataSubject, $anonymizedId)
    {
        if (!$dataSubject->hasUser()) {
            return;
        }

        if (!$anonymizedId) {
            gdpr('helpers')->error();
        }

        // Save a unique identifier to tie anonymized data together for analytics purposes
        update_user_meta($dataSubject->getUserId(), "gdpr_anonymized_id", $anonymizedId);

        // Change username
        global $wpdb;

        $anonymizedUsername = apply_filters('gdpr/wordpress-user/anonymize/username', '[anonymous]');
        $wpdb->update(
            $wpdb->users,
            ['user_login' => $anonymizedUsername],
            ['ID' => $dataSubject->getUserId()]
        );

        // Clear all relevant user fields, reset password
        wp_update_user([
            'ID'                  => $dataSubject->getUserId(),
            'user_email'          => '',
            'user_nicename'       => '',
            'user_url'            => '',
            'user_activation_key' => '',
            'display_name'        => $anonymizedUsername,
            // Set a random password, just in case the functionality that disallows users from logging in should break for any reason
            'user_pass'           => wp_hash_password(wp_generate_password()),
        ]);

        // Clear all relevant usermeta fields
        delete_user_meta($dataSubject->getUserId(), 'first_name');
        delete_user_meta($dataSubject->getUserId(), 'last_name');
        delete_user_meta($dataSubject->getUserId(), 'nickname');
        delete_user_meta($dataSubject->getUserId(), 'description');
        delete_user_meta($dataSubject->getUserId(), 'session_tokens');
        delete_user_meta($dataSubject->getUserId(), 'community-events-location');

        // Remove all capabilities
        $user = $dataSubject->getUser();
        $user->remove_all_caps();

        // Finally, assign the 'anonymous' role to user
        if (apply_filters('gdpr/wordpress-user/anonymize/change_role', true) && get_role('anonymous')) {

            foreach ($user->roles as $role) {
                $user->remove_role($role);
            }

            $user->add_role('anonymous');
        }
    }
}
