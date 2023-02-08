<?php
namespace Livro\Database;

use Exception;

class Repository
{
    private $activeRecord;

    public function __construct($class)
    {
        $this->activeRecord = $class;
    }

    public function load(Criteria $criteria)
    {
        $sql = "SELECT * FROM " . constant($this->activeRecord . '::TABLENAME');

        if ($criteria)
        {
            $expression = $criteria->dump();
            if ($expression)
            {
                $sql .= ' WHERE ' . $expression;
            }

            $order = $criteria->getProperty('order');
            $limit = $criteria->getProperty('limit');
            $offset = $criteria->getProperty('offset');

            if ($order)
            {
                $sql .= ' ORDER BY ' . $order;
            }

            if ($limit)
            {
                $sql .= ' LIMIT ' . $limit;
            }

            if ($offset)
            {
                $sql .= ' OFFSET ' . $offset;
            }
        }

        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            $result = $conn->query($sql);

             if ($result)
             {
                 $results = [];
                 while ($row = $result->fetchObject( $this->activeRecord ))
                 {
                     $results[] = $row;
                 }

                 return $results;
             }
        }
        else
        {
            throw new Exception('Não há transação ativa');
        }
    }

    public function delete(Criteria $criteria)
    {
        $sql = "DELETE FROM " . constant($this->activeRecord . '::TABLENAME');

        if ($criteria) {
            $expression = $criteria->dump();
            if ($expression) {
                $sql .= ' WHERE ' . $expression;
            }
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

    public function count(Criteria $criteria)
    {
        $sql = "SELECT COUNT(*) FROM " . constant($this->activeRecord . '::TABLENAME');

        if ($criteria) {
            $expression = $criteria->dump();
            if ($expression) {
                $sql .= ' WHERE ' . $expression;
            }
        }
        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            $result =  $conn->query($sql);
            if ($result)
            {
                $row = $result->fetch();
                return $row[0];
            }
        }
        else
        {
            throw new Exception('Não há transação ativa');
        }
    }
}
