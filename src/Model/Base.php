<?php

namespace Model;

use PicoDb\Database;

/**
 * Base model class
 *
 * @package  model
 */
abstract class Base extends \Core\Base
{
    /**
     * Save a record in the database
     *
     * @access public
     * @param  string            $table      Table name
     * @param  array             $values     Form values
     * @return boolean|integer
     */
    public function persist($table, array $values)
    {
        return $this->db->transaction(function (Database $db) use ($table, $values) {

            if (! $db->table($table)->save($values)) {
                return false;
            }

            return (int) $db->getLastId();
        });
    }

    /**
     * Remove keys from an array
     *
     * @access public
     * @param  array     $values    Input array
     * @param  string[]  $keys      List of keys to remove
     */
    public function removeFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $values)) {
                unset($values[$key]);
            }
        }
    }

    /**
     * Remove keys from an array if empty
     *
     * @access public
     * @param  array     $values    Input array
     * @param  string[]  $keys      List of keys to remove
     */
    public function removeEmptyFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $values) && empty($values[$key])) {
                unset($values[$key]);
            }
        }
    }

    /**
     * Force fields to be at 0 if empty
     *
     * @access public
     * @param  array        $values    Input array
     * @param  string[]     $keys      List of keys
     */
    public function resetFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($values[$key]) && empty($values[$key])) {
                $values[$key] = 0;
            }
        }
    }

    /**
     * Force some fields to be integer
     *
     * @access public
     * @param  array        $values    Input array
     * @param  string[]     $keys      List of keys
     */
    public function convertIntegerFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($values[$key])) {
                $values[$key] = (int) $values[$key];
            }
        }
    }

    /**
     * Force some fields to be null if empty
     *
     * @access public
     * @param  array        $values    Input array
     * @param  string[]     $keys      List of keys
     */
    public function convertNullFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $values) && empty($values[$key])) {
                $values[$key] = null;
            }
        }
    }

    /**
     * Group a collection of records by a column
     *
     * @access public
     * @param  array   $collection
     * @param  string  $column
     * @return array
     */
    public function groupByColumn(array $collection, $column)
    {
        $result = array();

        foreach ($collection as $item) {
            $result[$item[$column]][] = $item;
        }

        return $result;
    }
}
