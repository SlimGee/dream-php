<?php
namespace Dream\Database;
use PDO;
use PDOException;
use PDOStatement;
 /**
  * Database handler class/ basic abstraction
  * @author Given Ncube
  * @version 0.0.1
  */

 class Database
 {
     /**
      * our handler needs to have multiple connections
      * @access private
      */
     private $connections = [];

     /**
      * we may need to cache the query
      * @access private
      */
     private $query_cache = [];

     /**
      * since it is a database handler we may cache other data with it
      * @accesss private
      */
     private $data_cache = [];

     /**
      * db's last insert id
      * @access private
      */
     private $last_id;

     /**
      * we also need number of fetched rows
      * @access private
      */
     private $rows;

     /**
      * Tells the DB object which connection to use
      * setActiveConnection($id) allows us to change this
      */
     private $active_connection = 0;

     /**
      * Number of queries made during execution process
      */
     private $query_counter = 0;

     /**
      * last query executed
      * @access private
      */
     private $last;

     public function __construct()
     {

     }

     /*
     * create new connection to databse
     * @return connection id
     * @params db host
     * @params db username
     * @params db password
     * @params db name
     */
     public function new_connection($host, $user, $pass, $dbname)
     {
          try{
              $this->connections[] = new PDO("mysql:host={$host};dbname={$dbname}",$user,$pass);
              $this->connections[count($this->connections) - 1]->setAttribute(
                  PDO::ATTR_ERRMODE,
                  PDO::ERRMODE_EXCEPTION
              );
              return count($this->connections) - 1;
            }
            catch(PDOException $e){
                trigger_error('Error connecting: ' . $e->getMessage(), E_USER_ERROR);
            }
     }

     /**
      * get the last pdo statement
      * @return PDOStatement last statement
      */
     public function last()
     {
         return $this->last;
     }

     /**
      * Close the active connection
      * @return void
      */
     public function close_clonnection()
     {
         $this->connections[$this->active_connection]->close();
     }

     /**
      * Change which database connection is actively used for the
      * next operation
      * @param int the new connection id
      * @return void
      */
     public function set_active_connection(int $new)
     {
         $this->active_connection = $new;
     }

     /**
      * Store a query in the query cache for processing later
      * @param string the query string
      * @return int  pointer to the query in the cache
      */
     public function cache_query( $query_str )
     {
         $query = $this->connections[$this->active_connection]->prepare($query_str);
         if( !$query->execute() ){
             trigger_error('Error executing and caching query: ', E_USER_ERROR);
             return -1;
         }
         $this->query_cache[] = $query;
         return count($this->query_cache)-1;
     }

     /**
      * @return int the number of rows
      */
     public function num_rows_from_cache($cache_id)
     {
         return $this->query_cache[$cache_id]->rowCount();
     }

     /**
      * Get the rows from a cached query
      * @param int the query cache pointer
      * @return array the row
      */
     public function results_from_cache($cache_id)
     {
         return $this->query_cache[$cache_id]->fetchAll(PDO::FETCH_ASSOC);
     }

     /**
      * Store some data in a cache for later
      * @param array the data
      * @return int the pointer to the array in the data cache
      */
     public function cache_data($data)
     {
         $this->data_cache[] = $data;
         return count( $this->data_cache )-1;
     }

     /**
     * Get data from the data cache
     * @param int data cache pointed
     * @return array the data
     */
     public function data_from_cache( $cache_id )
     {
         return $this->data_cache[$cache_id];
     }

     /**
      * Delete records from the database
      * @param String the table to remove rows from
      * @param String the condition for which rows are to be removed
      * @param int the number of rows to be removed
      * @return void
      */
      public function delete($table, $condition, $params = [],$limit = '')
      {
         $limit = ( $limit == '' ) ? '' : ' LIMIT ' . $limit;
         $delete = "DELETE FROM {$table} WHERE {$condition} {$limit}";

         if($this->query($delete,$params)){
             return true;
         }
         return false;
     }

     /**
      * Update records in the database
      * @param string the table
      * @param array of changes field => value
      * @param string the condition
      * @return bool
      */
     public function update($table, $changes=[], $condition)
     {
         $fieldString='';
         $values=[];

         foreach($changes as $field=>$value){
             $fieldString.=''.$field.'=?,';
             $values[]=$value;
         }
         $fieldString=rtrim($fieldString,',');
         $fieldString=rtrim($fieldString,',');

         $sql = "UPDATE {$table} SET {$fieldString} WHERE {$condition}";

         if($this->query($sql,$values)){
             return true;
         }
         return false;
     }

     /**
     * Insert records into the database
     * @param string the database table
     * @param array data to insert field => value
     * @return bool
     */
     public function insert($table,$fields=[])
     {
         $fieldString = '';
         $valueString = '';
         $values = [];

         foreach($fields as $field=>$value){
             $fieldString .= '`'.$field.'`,';
             $valueString .= "?,";
             $values[] = $value;
          }

         $fieldString = rtrim($fieldString,',');
         $valueString = rtrim($valueString,',');

         $sql = "INSERT INTO {$table} ({$fieldString}) VALUES ({$valueString})";
         if($this->query($sql,$values)){
             return true;
         }
         return false;
     }


     public function query($sql,$params=[])
     {
         $this->connections[$this->active_connection]->beginTransaction();
         $query = $this->connections[$this->active_connection]->prepare($sql);
         if(count($params)){
             $x=1;
             foreach($params as $param){
                $query->bindValue($x,$param);
                 $x++;
             }
         }
         try{
             $query->execute();
             $this->last = $query;
             $this->last_id = $this->connections[$this->active_connection]->lastInsertId();
             $this->connections[$this->active_connection]->commit();
             return true;
         }
         catch(PDOException $e){
            $this->connections[$this->active_connection]->rollback();
            return false;
         }
     }

     /**
     * Get the rows from the most recently executed query,
     * excluding cached queries
     * @return array
     */
     public function get_rows($mode = false)
     {
       if ($mode){
          return $this->last->fetchAll(PDO::FETCH_ASSOC);
       }
       return $this->last->fetchAll(PDO::FETCH_OBJ);
     }


     /*
     * @return int the number of rows
     */
     public function num_rows()
     {
       if ($this->last == null){
          throw new \BadMethodCallException("Calling num_rows method on null. Please run a query first", 1);
       }
       return $this->last->rowCount();
     }

     /**
     * get the last id of the last query
     * @return last inserted id
     */
     public function last_id()
     {
         return $this->last_id;
     }

     /**
     * select from a table
     * @params string table name
     * @params string conditions of select
     * @params int limit
     * @return array of data
     */
     public function select(string $table,string $conditions = '',array $params = [],$limit = '')
     {
         $conditions = ($conditions !== '') ? "WHERE {$conditions}" : '';
         $limit = ( $limit == '' ) ? '' : ' LIMIT ' . $limit;
         $this->query("SELECT * FROM $table {$conditions} {$limit}",$params);
         return $this->get_rows();
     }

     /**
      * find only one record from db
      * @params string table name
      * @params string conditions of select
      * @return stdClass 1 record from db
      */
     public function find_first(string $table,string $conditions,array $params = [])
     {
         $this->query("SELECT * FROM $table WHERE {$conditions}",$params);
         return $this->last->fetch(PDO::FETCH_OBJ);
     }

 }
