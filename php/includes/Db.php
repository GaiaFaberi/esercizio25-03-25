<?php
    class Db extends MySQLi{
        static protected $instance = null;

        public function __constructor($host, $user, $password, $schema){
            parent::__constructor($host, $user, $password, $schema);
        }
    
        static function getInstance(){
            if(self::$instance == null){
                self::$instance = new Db('my_mariadb', 'root', 'ciccio', 'scuola');
            }
            return self::$instance;
        }

        public function select($table, $where = 1){
            $query = "SELECT * FROM $table WHERE $where";
            if($results = $this->query($query)){
                return $results->fetch_all(MYSQLI_ASSOC);
            }
            return [];
        }
    }
?>