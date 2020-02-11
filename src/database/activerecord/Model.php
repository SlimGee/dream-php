<?php

namespace Dream\Database\ActiveRecord;

use Dream\Database\ActiveRecord\Concerns\Relationship;
use PDO;
use PDOStatement;
use PDOException;

/**
 * Model class
 */
abstract class Model
{
    /**
     * database table
     */
    public $table;

    /**
     * db columns
     */
    private $columns;

    const SELECT = "SELECT %s FROM %s ";

    use Relationship;

    /**
     * class constructor
     * @param array key val pairs of data
     */
    public function __construct($data = [])
    {
        $this->table = self::table();
        $this->columns = $this->columns();
        if (sizeof($data) > 0) {
            $this->populate($data);
        }
    }

    /**
     * Get all the colums associated with the model
     * @return array columns;
     */
    public function columns()
    {
        self::db()->query("SHOW columns FROM " . $this->table . " WHERE Field != 'created_at' AND Field != 'updated_at'");
        $fields = self::db()->get_rows();
        $return_array = [];
        foreach ($fields as $value) {
            $return_array[] = $value->Field;
        }
        return $return_array;
    }

    /**
     * getter for database connection
     * @return object Database instance
     */
    public static function db()
    {
        return app()->registry()->get('db');
    }

    /**
     * get the database table asscoiated with the model
     * @return string table name
     */
    public static function table($model = null)
    {
        return pluralize(
          strtolower((new \ReflectionClass(get_called_class()))->getShortName()));
    }

    /**
     * create a model from an array of data
     * @param array data
     * @return Model instance
     */
    public static function create(array $data)
    {
        self::db()->insert(self::table(), $data);
        $data['id'] = (int)self::db()->last_id();
        return new static($data);
    }

    /**
     * gets all models
     * @return array Model;
     */
    public static function all()
    {
        self::db()->query(sprintf(self::SELECT, '*', self::table()));
        return new RowSet(
           self::db()->last()->fetchAll(PDO::FETCH_CLASS, get_called_class(), [])
        );
    }

    /**
     * update the model's data
     * @param array updates
     */
    public function update(array $updates)
    {
        foreach ($updates as $key => $value){
            if (!in_array($key, $this->columns)){
                throw new \Exception("Cannot update $key because it is not a property", 1);
            }
            $this->$key = $value;
        }
        return $this->save();
    }

    /**
     * save changes made to object at runtime
     * @return boolean
     */
    public function save()
    {
        if (!isset($this->id)) {
            return self::create($this->morph());
        }
        return self::db()->update($this->table,$this->morph(),'id = ' . $this->id);
    }

    /**
     * delete the model
     * @return boolean
     */
    public function delete()
    {
        return self::db()->delete($this->table,'id = ?',[$this->id]);
    }

    /**
     * get the properties as key => pairs
     * @return array 'key' => val
     */
    public function morph()
    {
        $return_array = [];
        foreach ($this->columns as $property) {
            $return_array[$value] = $this->$property;
        }
        return $return_array;
    }

    /**
     * find a specific model by its id
     * @example $user = User::find(5);
     * @example $user = User::find($this->params['id']);
     * @param int id
     * @return object model
     */
    public static function find($id)
    {
        return self::find_by('id',(int) $id);
    }

    /**
     * find model by specific attribute
     * @example $user = User::find_by('email','example@domain.com');
     * @param string attribute key
     * @param string the value
     */
    public static function find_by($key,$value)
    {
        $query_str = sprintf(self::SELECT, '*', self::table()) . ' WHERE ' . $key . ' = ? LIMIT 1';
        self::db()->query($query_str,[$value]);
        return self::db()->last()->fetchObject(get_called_class(),[]);
    }

    /**
     * The where sql cluase
     * @param string the condition
     * @return object the model
     */
    public static function where(string $condtion,$array = false)
    {
        $sql = sprintf(self::SELECT, '*', self::table()) . ' WHERE ' . $condtion;
        self::db()->query($sql);
        if ($array) {
            return self::db()->last()->fetchAll(PDO::FETCH_CLASS, get_called_class(),[]);
        }
        return new RowSet(self::db()->last()->fetchAll(PDO::FETCH_CLASS, get_called_class(),[]));
    }

    public static function first()
    {
        $query_str = sprintf(self::SELECT, '*', self::table()). ' ORDER BY id ASC LIMIT 1';
        self::db()->query($query_str,[]);
        return self::db()->last()->fetchObject(get_called_class(),[]);
    }

    /**
     * populate the instance with data
     * @param array data
     * @return void
     */
    public function populate(array $data)
    {
        foreach ($data as $key => $value){
            $this->$key = $value;
        }
    }
}
