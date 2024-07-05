<?php

namespace App\Models;

use App\Controllers\Database;

use PDO;

class CRUD
{

    /**
     * Esta classe é responsável por criar, ler, atualizar e deletar registros no banco de dados
     */

    public $connection;

    public function __construct()
    {
        $database = new Database;
        $this->connection = $database->connect();
    }

    public function Create($table, $values)
    {
        $stmt = $this->connection->prepare("INSERT INTO $table (" . implode(', ', array_keys($values)) . ") VALUES (" . implode(', ', array_map(function ($value) {
            return "'" . addslashes($value) . "'";
        }, array_values($values))) . ")");
        if ($stmt->execute()) {
            return $this->connection->lastInsertId();
        } else {
            return 0;
        }
    }

    public function Read($table, $conditions, $fields = null)
    {
        $conditionsString = "";
        if (!is_null($conditions)) {
            $conditionsString = "WHERE ";
            $i = 0;
            foreach ($conditions as $key => &$condition) {
                if($i > 0) { $conditionsString .= "AND "; }
                $conditionsString .= "$key = '$condition' ";
                $i++;
            }
        }

        $stmt = $this->connection->prepare("SELECT " . (is_null($fields) ? "*" : implode(', ', $fields)) . " FROM $table $conditionsString");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $result;
    }

    public function Update($table, $values, $conditions)
    {
        $valuesString = "";
        if (!is_null($conditions)) {
            foreach ($values as $key => &$value) {
                $valuesString .= "$key = '$value' ";
            }
        }

        $conditionsString = "";
        if (!is_null($conditions)) {
            $conditionsString = "WHERE ";
            $i = 0;
            foreach ($conditions as $key => &$condition) {
                if($i > 0) { $conditionsString .= "AND "; }
                $conditionsString .= "$key = '$condition' ";
                $i++;
            }
        }
        $stmt = $this->connection->prepare("UPDATE $table SET $valuesString $conditionsString");
        $stmt->execute();
    }

    public function Delete($table, $conditions)
    {

        if (!is_null($conditions)) {
            $conditionsString = "WHERE ";
            $i = 0;
            foreach ($conditions as $key => &$condition) {
                if($i > 0) { $conditionsString .= "AND "; }
                $conditionsString .= "$key = '$condition' ";
                $i++;
            }
            $stmt = $this->connection->prepare("DELETE FROM $table $conditionsString");
            return $stmt->execute();
        }
    }
}
