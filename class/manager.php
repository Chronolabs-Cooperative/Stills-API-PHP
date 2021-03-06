<?php
/**
 * Chronolabs REST GeoSpatial Places API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         places
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		REST GeoSpatial Places API
 */


/**
 * Database Manager Class Factory
 *
 * @abstract
 * @author     Simon Roberts <meshy@labs.coop>
 * @package    places
 * @subpackage database
 */
class StillsDatabaseManager
{
    /**
     *  Tables Buffer
     *  
     * @var array
     */
    private $s_tables = array();

    /**
     *  Functional Table Buffer
     * @var array
     */
    private $f_tables = array();

    /**
     *  Database Object
     * 
     * @var StillsDatabase
     */
    public $db;

    /**
     * Success Strings
     * 
     * @var array
     */
    public $successStrings = array(
        'create' => TABLE_CREATED, 'insert' => ROWS_INSERTED, 'alter' => TABLE_ALTERED, 'drop' => TABLE_DROPPED,
    );

    /**
     * Failure Strings
     * 
     * @var array
     */
    public $failureStrings = array(
        'create' => TABLE_NOT_CREATED, 'insert' => ROWS_FAILED, 'alter' => TABLE_NOT_ALTERED,
        'drop' => TABLE_NOT_DROPPED,
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = StillsDatabaseFactory::getDatabase();
        $this->db->setPrefix(STILLS_DB_PREFIX);
    }

    /**
     * Checks if a table is connected
     * @return bool
     */
    public function isConnectable()
    {
        return ($this->db->connect(false) != false) ? true : false;
    }

    /**
     * Checks if a Database Exists
     * 
     * @return bool
     */
    public function dbExists()
    {
        return ($this->db->connect() != false) ? true : false;
    }

    /**
     * Creates a Database
     * 
     * @return bool
     */
    public function createDB()
    {
        $this->db->connect(false);

        $result = $this->db->query("CREATE DATABASE " . STILLS_DB_NAME);

        return ($result != false) ? true : false;
    }

    /**
     * Queries executes from SQL File
     * 
     * @param string $sql_file_path
     * @return bool
     */
    public function queryFromFile($sql_file_path)
    {
        if (!file_exists($sql_file_path)) {
            return false;
        }
        $sql_query = trim(fread(fopen($sql_file_path, 'r'), filesize($sql_file_path)));
        SqlUtility::splitMySqlFile($pieces, $sql_query);
        $this->db->connect();
        foreach ($pieces as $piece) {
            $piece = trim($piece);
            // [0] contains the prefixed query
            // [4] contains unprefixed table name
            $prefixed_query = SqlUtility::prefixQuery($piece, $this->db->prefix());
            if ($prefixed_query != false) {
                $table = $this->db->prefix($prefixed_query[4]);
                if ($prefixed_query[1] == 'CREATE TABLE') {
                    if ($this->db->query($prefixed_query[0]) != false) {
                        if (!isset($this->s_tables['create'][$table])) {
                            $this->s_tables['create'][$table] = 1;
                        }
                    } else {
                        if (!isset($this->f_tables['create'][$table])) {
                            $this->f_tables['create'][$table] = 1;
                        }
                    }
                } else {
                    if ($prefixed_query[1] == 'INSERT INTO') {
                        if ($this->db->query($prefixed_query[0]) != false) {
                            if (!isset($this->s_tables['insert'][$table])) {
                                $this->s_tables['insert'][$table] = 1;
                            } else {
                                $this->s_tables['insert'][$table]++;
                            }
                        } else {
                            if (!isset($this->f_tables['insert'][$table])) {
                                $this->f_tables['insert'][$table] = 1;
                            } else {
                                $this->f_tables['insert'][$table]++;
                            }
                        }
                    } else {
                        if ($prefixed_query[1] == 'ALTER TABLE') {
                            if ($this->db->query($prefixed_query[0]) != false) {
                                if (!isset($this->s_tables['alter'][$table])) {
                                    $this->s_tables['alter'][$table] = 1;
                                }
                            } else {
                                if (!isset($this->s_tables['alter'][$table])) {
                                    $this->f_tables['alter'][$table] = 1;
                                }
                            }
                        } else {
                            if ($prefixed_query[1] == 'DROP TABLE') {
                                if ($this->db->query('DROP TABLE ' . $table) != false) {
                                    if (!isset($this->s_tables['drop'][$table])) {
                                        $this->s_tables['drop'][$table] = 1;
                                    }
                                } else {
                                    if (!isset($this->s_tables['drop'][$table])) {
                                        $this->f_tables['drop'][$table] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * SQL report
     * 
     * @return string
     */
    public function report()
    {
        $commands = array('create', 'insert', 'alter', 'drop');
        $content = '<ul class="log">';
        foreach ($commands as $cmd) {
            if (!@empty($this->s_tables[$cmd])) {
                foreach ($this->s_tables[$cmd] as $key => $val) {
                    $content .= '<li class="success">';
                    $content .= ($cmd != 'insert') ? sprintf($this->successStrings[$cmd], $key)
                            : sprintf($this->successStrings[$cmd], $val, $key);
                    $content .= "</li>\n";
                }
            }
        }
        foreach ($commands as $cmd) {
            if (!@empty($this->f_tables[$cmd])) {
                foreach ($this->f_tables[$cmd] as $key => $val) {
                    $content .= '<li class="failure">';
                    $content .= ($cmd != 'insert') ? sprintf($this->failureStrings[$cmd], $key)
                            : sprintf($this->failureStrings[$cmd], $val, $key);
                    $content .= "</li>\n";
                }
            }
        }
        $content .= '</ul>';
        return $content;
    }

    /**
     * Executes a Query
     * 
     * @param $sql
     * @return bool|resource
     */
    public function query($sql)
    {
        $this->db->connect();
        return $this->db->query($sql);
    }

    /**
     * Adds Database Prefix
     * 
     * @param $table
     * @return string
     */
    public function prefix($table)
    {
        $this->db->connect();
        return $this->db->prefix($table);
    }

    /**
     * Fetches Array
     * 
     * @param $ret
     * @return array
     */
    public function fetchArray($ret)
    {
        $this->db->connect();
        return $this->db->fetchArray($ret);
    }

    /**
     * Executes Database Table Insert
     * 
     * @param $table
     * @param $query
     * @return bool|void
     */
    public function insert($table, $query)
    {
        $this->db->connect();
        $table = $this->db->prefix($table);
        $query = 'INSERT INTO ' . $table . ' ' . $query;
        if (!$this->db->queryF($query)) {
            if (!isset($this->f_tables['insert'][$table])) {
                $this->f_tables['insert'][$table] = 1;
            } else {
                $this->f_tables['insert'][$table]++;
            }
            return false;
        } else {
            if (!isset($this->s_tables['insert'][$table])) {
                $this->s_tables['insert'][$table] = 1;
            } else {
                $this->s_tables['insert'][$table]++;
            }
            return $this->db->getInsertId();
        }
    }

    /**
     * Reports if an Error Exists
     * 
     * @return bool
     */
    public function isError()
    {
        return (isset($this->f_tables)) ? true : false;
    }

    /**
     * Deletes Tables
     * 
     * @param $tables
     * @return array
     */
    public function deleteTables($tables)
    {
        $deleted = array();
        $this->db->connect();
        foreach ($tables as $key => $val) {
            if (!$this->db->query("DROP TABLE " . $this->db->prefix($key))) {
                $deleted[] = $val;
            }
        }
        return $deleted;
    }

    /**
     * Checks if a table exists
     * 
     * @param $table
     * @return bool
     */
    public function tableExists($table)
    {
        $table = trim($table);
        $ret = false;
        if ($table != '') {
            $this->db->connect();
            $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix($table);
            $ret = (false != $this->db->query($sql)) ? true : false;
        }
        return $ret;
    }
}