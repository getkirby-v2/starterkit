<?php

/**
 * SQL
 *
 * SQL Query builder
 *
 * @package   Kirby Toolkit
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Sql {

  // list of literals which should not be escaped in queries
  protected $literals = array('NOW()', null);

  // the parent db connection
  protected $db;

  /**
   * Constructor
   *
   * @param object $db
   */
  public function __construct($db) {
    $this->db = $db;
  }

  /**
   * Builds a select clause
   *
   * @param array $params List of parameters for the select clause. Check out the defaults for more info.
   * @return string
   */
  public function select($params = array()) {

    $defaults = array(
      'table'    => '',
      'columns'  => '*',
      'join'     => false,
      'distinct' => false,
      'where'    => false,
      'group'    => false,
      'having'   => false,
      'order'    => false,
      'offset'   => 0,
      'limit'    => false,
    );

    $options = array_merge($defaults, $params);
    $query   = array();

    $query[] = 'SELECT';

    // select distinct values
    if($options['distinct']) $query[] = 'DISTINCT';

    $query[] = empty($options['columns']) ? '*' : implode(', ', (array)$options['columns']);
    $query[] = 'FROM ' . $options['table'];

    if(!empty($options['join'])) {
      foreach($options['join'] as $join) {
        $query[] = ltrim(strtoupper(a::get($join, 'type', '')) . ' JOIN ') . $join['table'] . ' ON ' . $join['on'];
      }
    }

    if(!empty($options['where'])) {
      $query[] = 'WHERE ' . $options['where'];
    }

    if(!empty($options['group'])) {
      $query[] = 'GROUP BY ' . $options['group'];
    }

    if(!empty($options['having'])) {
      $query[] = 'HAVING ' . $options['having'];
    }

    if(!empty($options['order'])) {
      $query[] = 'ORDER BY ' . $options['order'];
    }

    if($options['offset'] > 0 || $options['limit']) {
      if(!$options['limit']) $options['limit'] = '18446744073709551615';
      $query[] = 'LIMIT ' . $options['offset'] . ', ' . $options['limit'];
    }

    return implode(' ', $query);

  }

  /**
   * Builds an insert clause
   *
   * @param array $params List of parameters for the insert clause. See defaults for more info
   * @return string
   */
  public function insert($params = array()) {

    $defaults = array(
      'table'  => '',
      'values' => false,
    );

    $options = array_merge($defaults, $params);
    $query   = array();

    $query[] = 'INSERT INTO ' . $options['table'];
    $query[] = $this->values($options['values'], ', ', false);

    return implode(' ', $query);

  }

  /**
   * Builds an update clause
   *
   * @param array $params List of parameters for the update clause. See defaults for more info
   * @return string
   */
  public function update($params = array()) {

    $defaults = array(
      'table'  => '',
      'values' => false,
      'where'  => false,
    );

    $options = array_merge($defaults, $params);
    $query   = array();

    $query[] = 'UPDATE ' . $options['table'] . ' SET';
    $query[] = $this->values($options['values']);

    if(!empty($options['where'])) {
      $query[] = 'WHERE ' . $options['where'];
    }

    return implode(' ', $query);

  }

  /**
   * Builds a delete clause
   *
   * @param array $params List of parameters for the delete clause. See defaults for more info
   * @return string
   */
  public function delete($params = array()) {

    $defaults = array(
      'table'  => '',
      'where'  => false,
    );

    $options = array_merge($defaults, $params);
    $query   = array();

    $query[] = 'DELETE FROM ' . $options['table'];

    if(!empty($options['where'])) {
      $query[] = 'WHERE ' . $options['where'];
    }

    return implode(' ', $query);

  }

  /**
   * Builds a safe list of values for insert, select or update queries
   *
   * @param mixed $values A value string or array of values
   * @param string $separator A separator which should be used to join values
   * @param boolean $set If true builds a set list of values for update clauses
   * @return string
   */
  public function values($values, $separator = ', ', $set = true) {

    if(!is_array($values)) return $values;

    if($set) {

      $output = array();

      foreach($values AS $key => $value) {
        if(in_array($value, $this->literals, true)) {
          $output[] = $key . ' = ' . (($value === null)? 'null' : $value);
        } elseif(is_array($value)) {
          $output[] = $key . " = '" . json_encode($value) . "'";
        } else {
          $output[] = $key . " = '" . $this->db->escape($value) . "'";
        }
      }

      return implode($separator, $output);

    } else {

      $fields = array();
      $output = array();

      foreach($values AS $key => $value) {
        $fields[] = $key;
        if(in_array($value, $this->literals, true)) {
          $output[] = ($value === null)? 'null' : $value;
        } elseif(is_array($value)) {
          $output[] = "'" . $this->db->escape(json_encode($value)) . "'";
        } else {
          $output[] = "'" . $this->db->escape($value) . "'";
        }
      }

      return '(' . implode($separator, $fields) . ') VALUES (' . implode($separator, $output) . ')';

    }

  }

  /**
   * Creates the sql for dropping a single table
   *
   * @param string $table
   * @return string
   */
  public function dropTable($table) {
    return 'DROP TABLE `' . $table . '`';
  }

  /**
   * Creates a table with a simple scheme array for columns
   *
   * @todo  add more options per column
   * @param string $table The table name
   * @param array $columns
   * @return string
   */
  public function createTable($table, $columns = array()) {

    $type   = strtolower($this->db->type());
    $output = array();
    $keys   = array();

    if(!in_array($type, array('mysql', 'sqlite'))) throw new Exception('Unsupported database type: ' . $type);

    foreach($columns as $name => $column) {

      $template = array();

      switch($column['type']) {
        case 'id':
          $template['mysql']  = '"{column.name}" INT(11) UNSIGNED NOT NULL AUTO_INCREMENT';
          $template['sqlite'] = '"{column.name}" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE';
          $keys[$name] = 'PRIMARY';
          break;
        case 'varchar':
          $template['mysql']  = '"{column.name}" varchar(255) {column.null} {column.default}';
          $template['sqlite'] = '"{column.name}" TEXT {column.null} {column.key} {column.default}';
          break;
        case 'text':
          $template['mysql']  = '"{column.name}" TEXT';
          $template['sqlite'] = '"{column.name}" TEXT {column.null} {column.key} {column.default}';
          break;
        case 'int':
          $template['mysql']  = '"{column.name}" INT(11) UNSIGNED {column.null} {column.default}';
          $template['sqlite'] = '"{column.name}" INTEGER {column.null} {column.key} {column.default}';
          break;
        case 'timestamp':
          $template['mysql']  = '"{column.name}" INT(11) UNSIGNED {column.null} {column.default}';
          $template['sqlite'] = '"{column.name}" INTEGER {column.null} {column.key} {column.default}';
          break;
        default:
          throw new Exception('Unsupported column type: ' . $column['type']);
      }

      $key = false;
      if(isset($column['key'])) {
        $key = strtoupper($column['key']);
        $keys[$name] = $key;
      }

      $defaultValue = null;
      if(isset($column['default'])) {
        $defaultValue = is_integer($column['default']) ? $column['default'] : "'" . $column['default'] . "'";
      }

      $output[] = trim(str::template($template[$type], array(
        'column.name'    => $name,
        'column.null'    => r(a::get($column, 'null') === false, 'NOT NULL', 'NULL'),
        'column.key'     => r($key && $key != 'INDEX', $key, false),
        'column.default' => r(!is_null($defaultValue), 'DEFAULT ' . $defaultValue),
      )));

    }

    // all columns
    $inner = implode(', ' . PHP_EOL, $output);

    // add keys for mysql
    if($type ==  'mysql') {
      foreach($keys as $name => $key) {
        $inner .= ', ' . PHP_EOL . trim(r($key != 'INDEX', $key) . ' KEY (`' . $name . '`)');
      }
    }

    // make it a string
    $output = 'CREATE TABLE "' . $table . '" (' . PHP_EOL . $inner . PHP_EOL . ');';

    if($type == 'mysql') {
      $output = str_replace('"', '`', $output);
    }

    // add index keys for sqlite
    if($type == 'sqlite') {
      foreach($keys as $name => $key) {
        if($key != 'INDEX') continue;
        $output .= PHP_EOL . 'CREATE INDEX "' . $name . '" ON "' . $table . '" ("' . $name . '");';
      }
    }

    return $output;

  }

}
