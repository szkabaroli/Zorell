<?php

class DB {

    protected $db_host = '';
    protected $db_user = '';
    protected $db_pass = '';
    protected $db_name = '';
    private $db = NULL;

    function __construct($host, $user, $pass, $name) {

        $this->db_host = $host;
        $this->db_user = $user;
        $this->db_pass = $pass;
        $this->db_name = $name;

    }

    public function Connect() {

        $this->db = new PDO("mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8", $this->db_user, $this->db_pass);
        //$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function Query($query, $params) {

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function QueryAll($query, $params) {

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function QueryAllIndex($query, $params) {

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    public function GetConnection(){
        return $this->db;
    }
}

?>