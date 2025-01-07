<?php

require_once 'Database.php';

class CrudOperations
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute($data);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            die("Create operation failed: " . $e->getMessage());
        }
    }

    public function read($table, $conditions = [], $columns = '*')
    {
        $whereClause = '';
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $key => $value) {
                $clauses[] = "$key = :$key";
            }
            $whereClause = "WHERE " . implode(" AND ", $clauses);
        }
        $sql = "SELECT $columns FROM $table $whereClause";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute($conditions);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Read operation failed: " . $e->getMessage());
        }
    }

    public function update($table, $data, $conditions)
    {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));

        $sql = "UPDATE $table SET $setClause WHERE $whereClause";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute(array_merge($data, $conditions));
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Update operation failed: " . $e->getMessage());
        }
    }

    public function delete($table, $conditions)
    {
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $sql = "DELETE FROM $table WHERE $whereClause";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute($conditions);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Delete operation failed: " . $e->getMessage());
        }
    }
}
