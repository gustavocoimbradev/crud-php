<?php

namespace App\Controllers;

use PDO;

class Database
{

    /**
     * Esta classe é responsável por realizar a conexão com o banco de dados
     */

    public $host = "localhost";
    public $port = "3306";
    public $dbname = "bd";
    public $user = "root";
    public $password = "";

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        return new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->user, $this->password);
    }

}
