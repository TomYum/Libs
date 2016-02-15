<?php

namespace \TY\Db;

class SimpleDbInsert {
    
    private $connection;
    
    public function __construct(\mysqli $connection = null)
    {
        if ($connection) $this->connection = $connection;
    }
    
    /**
     * Get a MySQLi connected object
     * @return \mysqli
     */
    private function getConnection()
    {
        return ($this->connection) ?: Db::getInstance();
    }
    
    /**
     * Insert data from array
     */
    public function insert($where,array $what){
        $where = (string) $vhere; // !must be prepered
        
        $type_string = $this->prepareStmtValues($what);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s);", 
                            $where,
                            join('`, `', array_keys($what)), 
                            join(',',array_fill(0,count($what),'?')) 
                        );
        $stmt = $this->getConnection()->prepare($sql);
        
        $reflection = new ReflectionClass('mysqli_stmt');
        $method = $reflection->getMethod("bind_param");
        $dataArray = array_merge([$type_string], $what);
        $method->invokeArgs($stmt,$dataArray);
        $stmt->execute();
        return (boolean) $this->getConnection()->error;
    }
    
    /**
     * Проверяет фходной массив и преобразует массивы в json
     * @param array $values
     * @return string - Возвращает строку типов данных
     */
    protected function prepareStmtValues(array &$values)
    {
        $param_types = [];
        array_walk($values, function (&$item) use (&$param_types) {
            switch (gettype($item)) {
                case 'integer' :
                    $param_types[] = 'i';
                    break;
                case 'double' :
                    $param_types[] = 'd';
                    break;
                case 'array':
                    $item = json_encode($item);
                case 'string':
                default:
                    $param_types[] = 's';
                    break;
            }
        });
        return join('', $param_types);
    }
    
}