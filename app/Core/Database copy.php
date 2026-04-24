<?php
// File: app/Core/Database.php

class Database {
    private $dbh; // Database Handler
    private $stmt; // Statement
    private $error;

    public function __construct() {
        // Load config
        $config = require __DIR__ . '/../../config/database.php';

        // DSN (Data Source Name)
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];

        try {
            $this->dbh = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            // Di Production, jangan echo error mentah ke user!
            die("Database Connection Error. Check logs."); 
        }
    }

    // Prepare Query
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind Value (Mencegah SQL Injection)
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Eksekusi Query
    public function execute() {
        return $this->stmt->execute();
    }

    // Ambil Banyak Data (Array of Assocs)
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Ambil Satu Baris Data
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Hitung Jumlah Baris
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    // Ambil ID terakhir yang di-insert
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    // --- TRANSACTION METHODS (Wajib untuk Payroll/KPI) ---
    
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction() {
        return $this->dbh->commit();
    }

    public function cancelTransaction() {
        return $this->dbh->rollBack();
    }

    // --- TAMBAHAN BARU (Alias Standar PDO) ---
    // Biar RoleModel.php bisa panggil commit() & rollBack() tanpa error
    
    public function commit() {
        return $this->dbh->commit();
    }

    public function rollBack() {
        return $this->dbh->rollBack();
    }

    // ... sisa kode di bawahnya ...    

    // --- ADAPTER UNTUK FILE RESTORE ---

    public function fetchAll($query, $params = []) {
        $this->query($query);
        if (!empty($params)) {
            $this->stmt->execute($params);
        } else {
            $this->execute();
        }
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne($query, $params = []) {
        $this->query($query);
        if (!empty($params)) {
            $this->stmt->execute($params);
        } else {
            $this->execute();
        }
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
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
        return $this->dbh->lastInsertId(); // Return ID langsung dari sini
    }
    

} // End of Class