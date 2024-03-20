<?php

class DB
{
    private $pdo;
    private static $instance = null;

    private function __construct()
    {
        $dsn = 'mysql:dbname=phptest;host=127.0.0.1';
        $user = 'root';
        $password = 'pass';

        try {
            $this->pdo = new \PDO($dsn, $user, $password);
            // Set PDO to throw exceptions on error
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // Handle PDO connection error
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        try {
            $sth = $this->pdo->prepare($sql);
            $sth->execute($params);
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            // Handle PDO query error
            throw new \Exception("Query failed: " . $e->getMessage());
        }
    }

    public function execute($sql, $params = [])
    {
        try {
            $sth = $this->pdo->prepare($sql);
            return $sth->execute($params);
        } catch (\PDOException $e) {
            // Handle PDO execute error
            throw new \Exception("Execution failed: " . $e->getMessage());
        }
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}

/**
 *Comments 
 *Renamed select() method to query() to better reflect its purpose and avoid confusion.
 *Added parameter $params to query() and execute() methods to support prepared statements, enhancing security and performance.
 *Modified exec() method to execute() to follow a more consistent naming convention.
 *Updated the __construct() method to set PDO to throw exceptions on error for better error handling.
 *Improved error handling within methods to catch and throw exceptions for better debugging and error reporting.
*/