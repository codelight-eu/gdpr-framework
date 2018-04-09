<?php

namespace Codelight\GDPR\Database;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * DB base class
 * https://pippinsplugins.com/custom-database-api-the-basic-api-class/
 */
abstract class WordpressDatabase
{

    /**
     * The name of our database table
     *
     * @access  public
     * @since   2.1
     */
    public $tableName;

    /**
     * The version of our database table
     *
     * @access  public
     * @since   2.1
     */
    public $version;

    /**
     * The name of the primary column
     *
     * @access  public
     * @since   2.1
     */
    public $primaryKey;

    /**
     * Get things started
     *
     * @access  public
     * @since   2.1
     */
    public function __construct()
    {
    }

    /**
     * Whitelist of columns
     *
     * @access  public
     * @since   2.1
     * @return  array
     */
    public function getColumns()
    {
        return [];
    }

    /**
     * Default column values
     *
     * @access  public
     * @since   2.1
     * @return  array
     */
    public function getColumnDefaults()
    {
        return [];
    }

    /**
     * Retrieve a row by the primary key
     *
     * @access  public
     * @since   2.1
     * @return  object
     */
    public function get($row_id)
    {
        global $wpdb;

        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $this->tableName WHERE $this->primary_key = %s LIMIT 1;", $row_id
        ));
    }

    /**
     * Retrieve a row by a specific column / value
     *
     * @access  public
     * @since   2.1
     * @return  object
     */
    public function getBy($column, $row_id)
    {
        global $wpdb;
        $column = esc_sql($column);

        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $this->tableName WHERE $column = %s LIMIT 1;", $row_id
        ));
    }

    /**
     * Retrieve a specific column's value by the primary key
     *
     * @access  public
     * @since   2.1
     * @return  string
     */
    public function getColumn($column, $row_id)
    {
        global $wpdb;
        $column = esc_sql($column);

        return $wpdb->get_var($wpdb->prepare(
            "SELECT $column FROM $this->tableName WHERE $this->primary_key = %s LIMIT 1;", $row_id
        ));
    }

    /**
     * Retrieve a specific column's value by the the specified column / value
     *
     * @access  public
     * @since   2.1
     * @return  string
     */
    public function getColumnBy($column, $column_where, $column_value)
    {
        global $wpdb;
        $column_where = esc_sql($column_where);
        $column = esc_sql($column);

        return $wpdb->get_var($wpdb->prepare(
            "SELECT $column FROM $this->tableName WHERE $column_where = %s LIMIT 1;", $column_value
        ));
    }

    /**
     * Insert a new row
     *
     * @access  public
     * @since   2.1
     * @return  int
     */
    public function insert($data, $type = '')
    {
        global $wpdb;

        // Set default values
        $data = wp_parse_args($data, $this->getColumnDefaults());

        do_action('bs_db_pre_insert_' . $type, $data);

        // Initialise column format array
        $columnFormats = $this->getColumns();

        // Force fields to lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $columnFormats);

        // Reorder $columnFormats to match the order of columns given in $data
        $data_keys = array_keys($data);
        $columnFormats = array_merge(array_flip($data_keys), $columnFormats);

        $wpdb->insert($this->tableName, $data, $columnFormats);

        do_action('bs_db_post_insert_' . $type, $wpdb->insert_id, $data);

        return $wpdb->insert_id;
    }

    /**
     * Update a row
     *
     * @access  public
     * @since   2.1
     * @return  bool
     */
    public function update($row_id, $data = [], $where = '')
    {
        global $wpdb;

        // Row ID must be positive integer
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (empty($where)) {
            $where = $this->primaryKey;
        }

        // Initialise column format array
        $columnFormats = $this->getColumns();

        // Force fields to lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $columnFormats);

        // Reorder $columnFormats to match the order of columns given in $data
        $data_keys = array_keys($data);
        $columnFormats = array_merge(array_flip($data_keys), $columnFormats);

        if (false === $wpdb->update($this->tableName, $data, [$where => $row_id], $columnFormats)) {
            return false;
        }

        return true;
    }


    /**
     * Delete a row identified by the primary key
     *
     * @access  public
     * @since   2.1
     * @return  bool
     */
    public function delete($row_id = 0)
    {
        global $wpdb;

        // Row ID must be positive integer
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (false === $wpdb->query($wpdb->prepare(
                "DELETE FROM $this->tableName WHERE $this->primary_key = %d", $row_id
            ))) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given table exists
     *
     * @since  2.4
     * @param  string $table The table name
     * @return bool          If the table name exists
     */
    public function tableExists($table)
    {
        global $wpdb;
        $table = sanitize_text_field($table);

        return $wpdb->get_var($wpdb->prepare(
                "SHOW TABLES LIKE '%s'", $table
            )) === $table;
    }


}