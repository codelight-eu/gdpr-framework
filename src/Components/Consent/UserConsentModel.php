<?php

namespace Codelight\GDPR\Components\Consent;

/**
 * Class UserConsentModel
 *
 * @package Codelight\GDPR\Components\Consent
 */
class UserConsentModel
{
    /* @var string */
    public $tableName;

    /* @var string */
    public $version = '1.0';

    /* @var string */
    public $primaryKey = 'id';

    /**
     * UserConsentModel constructor.
     */
    public function __construct()
    {
        $this->setTableName();

        // todo: cleanup
        // global $wpdb;
        //$wpdb->query('TRUNCATE TABLE wp_gdpr_consent');
    }

    /**
     * Set the table name with wpdb-s prefix
     */
    protected function setTableName()
    {
        global $wpdb;
        $this->tableName = $wpdb->prefix . 'gdpr_consent';
    }

    /**
     * Check if a user has given a consent
     *
     * @param $email
     * @param $consent
     * @return int
     */
    public function given($email, $consent)
    {
        global $wpdb;

        return count($wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->tableName} WHERE email = %s AND consent = %s AND status = 1;",
            $email,
            $consent
        )));
    }

    /**
     * Check if a user has withdrawn a consent
     *
     * @param $email
     * @param $consent
     * @return int
     */
    public function withdrawn($email, $consent)
    {
        global $wpdb;

        return count($wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->tableName} WHERE email = %s AND consent = %s AND status = 0;",
            $email,
            $consent
        )));
    }

    /**
     * Check if a consent exists in the database
     *
     * @param $email
     * @param $consent
     * @return array|null|object
     */
    public function exists($email, $consent)
    {
        global $wpdb;

        return count($wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->tableName} WHERE email = %s AND consent = %s;",
            $email,
            $consent
        )));
    }

    /**
     * Set a consent to 'given'
     *
     * @param $email
     * @param $consent
     * @param $status
     * @return false|int
     */
    public function give($email, $consent)
    {
        $this->set($email, $consent, 1);
    }

    /**
     * Set a consent to 'withdrawn'
     *
     * @param $email
     * @param $consent
     * @param $status
     * @return false|int
     */
    public function withdraw($email, $consent)
    {
        $this->set($email, $consent, 0);
    }

    /**
     * Set a consent to 'given' or 'withdrawn'
     *
     * @param $email
     * @param $consent
     * @param $status
     * @return false|int
     */
    protected function set($email, $consent, $status, $version = 1)
    {
        global $wpdb;

        if ($this->exists($email, $consent)) {
            return $wpdb->update(
                $this->tableName,
                [
                    'version'    => $version,
                    'consent'    => $consent,
                    'status'     => $status,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'ip'         => $_SERVER['REMOTE_ADDR'],
                ],
                [
                    'email'   => $email,
                    'consent' => $consent,
                ]
            );
        } else {
            return $wpdb->insert(
                $this->tableName,
                [
                    'email'      => $email,
                    'version'    => $version,
                    'consent'    => $consent,
                    'status'     => $status,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'ip'         => $_SERVER['REMOTE_ADDR'],
                ]
            );
        }
    }

    /**
     * Get all consent given by data subject
     *
     * @param $email
     */
    public function getAll($email)
    {
        global $wpdb;

        /**
         * Workaround to an issue with array_column in PHP5.6 - thanks @paulnewson
         */
        if (version_compare(PHP_VERSION, '7') >= 0) {
            return array_column($wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->tableName} WHERE email = %s and status = 1;",
                $email
            )), 'consent');
        } else {
            return array_column(json_decode(json_encode($wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->tableName} WHERE email = %s and status = 1;",
                $email
            ))), true), 'consent');
        }
    }

    /**
     * Remove a consent row from the database
     *
     * @param $email
     * @param $consent
     * @return false|int
     */
    public function delete($email, $consent)
    {
        global $wpdb;

        return $wpdb->delete(
            $this->tableName,
            [
                'email'   => $email,
                'consent' => $consent,
            ]
        );
    }

    /**
     * Withdraw consent and anonymize data subject's email
     *
     * @param $email
     * @param $consent
     * @param $anonymizedId
     * @return false|int
     */
    public function anonymize($email, $consent, $anonymizedId)
    {
        global $wpdb;

        if ($this->exists($email, $consent)) {
            return $wpdb->update(
                $this->tableName,
                [
                    'email'      => $anonymizedId,
                    'consent'    => $consent,
                    'status'     => 0,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'ip'         => $_SERVER['REMOTE_ADDR'],
                ],
                [
                    'email'   => $email,
                    'consent' => $consent,
                ]
            );
        }
    }

    /**
     * Get columns and formats
     */
    public function getColumns()
    {
        return [
            'id'         => '%d',
            'version'    => '%d',
            'email'      => '%s',
            'consent'    => '%s',
            'status'     => '%d',
            'updated_at' => '%s',
            'ip'         => '%s'
            //'valid_until'   => '%s',
        ];
    }

    /**
     * Get default column values
     */
    public function getColumnDefaults()
    {
        return [
            'id'         => '',
            'version'    => 1,
            'email'      => '',
            'consent'    => '',
            'status'     => '',
            'updated_at' => '',
            'ip'         => '',
            //'valid_until'   => '',
        ];
    }

    /**
     * Create the table
     */
    public function createTable()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "CREATE TABLE " . $this->tableName . " (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            version int NOT NULL,
            email varchar(64) NOT NULL,
            consent varchar(128) NOT NULL,
            status tinyint NOT NULL,
            updated_at TIMESTAMP NULL,
            ip varchar(64) NOT NULL,
            PRIMARY KEY  (id)
            ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
        dbDelta($sql);
        update_option($this->tableName . '_db_version', $this->version);
    }
}