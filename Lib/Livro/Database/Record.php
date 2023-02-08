<?php
namespace Livro\Database;

abstract class Record
{
    protected $data;

    public function __construct($id = null)
    {
        if ($id)
        {
            $object = $this->load($id);
            if($object)
            {
                $this->fromArray( $object->toArray() );
            }
        }
    }

    public function __set($prop, $value)
    {
        if ($value === NULL)
        {
            unset($this->data[$prop]);
        }
        else
        {
            $this->data[$prop] = $value;
        }
    }

    public function __get($prop)
    {
        if (isset($this->data[$prop]))
        {
            return $this->data[$prop];
        }
    }

    public function __isset($prop)
    {
        return isset($this->data[$prop]);
    }

    public function __clone()
    {
       unset($this->data['id']);
    }

    public function fromArray($data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function getEntity()
    {
        $class = get_class($this);
        return constant("{$class}::TABLENAME");
    }

    public function load($id)
    {
        $sql = "SELECT * FROM {$this->getEntity()} WHERE id=" . (int) $id;

        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            $result = $conn->query($sql);
            if ($result)
            {
               return  $result->fetchObject( get_class($this) );
            }
        }
        else
        {
            throw new Exception('Não há transação ativa');
        }
    }

    public function store()
    {
        if (empty($this->data['id']) OR (!$this->load($this->data['id'])))
        {

            $prepared = $this->prepare($this->data);

            if (empty($this->data['id']))
            {
                $this->data['id'] = $this->getLast() + 1;
                $prepared['id'] = $this->data['id'];
            }
            $sql = "INSERT INTO {$this->getEntity()}" .
                '('. implode(',', array_keys($prepared)) .')'.
                'VALUES'.
                '('. implode(',', array_values($prepared)) . ')';
        }
        else
        {
            $prepared = $this->prepare($this->data);

            $set = [];
            foreach ($prepared as $column => $value)
            {
                $set[] = "$column = $value";
            }

            $sql  = "UPDATE {$this->getEntity()}";
            $sql .= " SET " . implode(',', $set);
            $sql .= " WHERE id= " . (int) $this->data['id'];
        }

        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            return $conn->exec($sql);

        }
        else
        {
            throw new Exception('Não há transação ativa');
        }
    }

    public function delete($id = null)
    {
        $id = $id ? $id : $this->data['id'];
        $sql = "DELETE FROM {$this->getEntity()} WHERE id=" . (int) $id;
        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            return $conn->exec($sql);

        }
        else
        {
            throw new Exception('Não há transação ativa');
        }
    }

    public function getLast()
    {
        if ($conn = Transaction::get())
        {
            $sql = "SELECT max(id) FROM {$this->getEntity()}";

            Transaction::log($sql);
            $result = $conn->query($sql);
            $row = $result->fetch();
            return $row[0];
        }
        else
        {
            throw new Exception('Não há transação ativa');
        }
    }

    public static function find($id)
    {
        $classname = get_called_class();
        $ar = new $classname;
        return $ar->load($id);
    }

    public function prepare($data)
    {
        $prepared = array();
        foreach ($data as $key => $value){
            if (is_scalar($value)){
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    public function escape($value)
    {
        if (is_string($value) and (!empty($value))){
            // adiciona \ em aspas
            $value = addslashes($value);
            return"'$value'";
        }
        else if (is_bool($value)){
            return $value ? 'TRUE' : 'FALSE';
        }
        else if ($value !== '') {
            return $value;
        }
        else{
            return "NULL";
        }
    }
}