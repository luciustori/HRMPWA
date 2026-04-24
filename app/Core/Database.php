<?php
// File: app/Core/Database.php

class Database {
    private $dbh;
    private $stmt;

    public function __construct() {
        // Menggunakan Konstanta dari Config.php
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->dbh = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value): $type = PDO::PARAM_INT; break;
                case is_bool($value): $type = PDO::PARAM_BOOL; break;
                case is_null($value): $type = PDO::PARAM_NULL; break;
                default: $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() { return $this->stmt->execute(); }
    public function resultSet() { $this->execute(); return $this->stmt->fetchAll(); }
    public function single() { $this->execute(); return $this->stmt->fetch(); }
    public function rowCount() { return $this->stmt->rowCount(); }
    public function lastInsertId() { return $this->dbh->lastInsertId(); }

    // --- RESTORE FITUR TRANSAKSI (PENTING!) ---
    public function beginTransaction() { return $this->dbh->beginTransaction(); }
    public function commit() { return $this->dbh->commit(); }
    public function rollBack() { return $this->dbh->rollBack(); }

    // --- Helper Kompatibilitas ---
    public function fetchOne($query, $params = []) {
        $this->query($query);
        foreach($params as $key => $val) {
            $this->bind($key, $val); // Fix bind logic
        }
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($query, $params = []) {
        $this->query($query);
        foreach($params as $key => $val) {
            $this->bind($key, $val);
        }
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data) {
        $keys = array_keys($data);
        $fields = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);
        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $this->query($query);
        foreach($data as $key => $value) {
            $this->bind(":$key", $value);
        }
        $this->execute();
        return $this->dbh->lastInsertId();
    }
}