<?php

namespace Melonly\Database;

use PDO;
use Exception;

class DBConnection implements DBConnectionInterface {
    protected readonly string $system;

    protected array $credentials = [];

    protected null | PDO $pdo = null;

    public function __construct() {
        $this->system = env('DB_SYSTEM');

        $this->credentials['host'] = env('DB_HOST');
        $this->credentials['user'] = env('DB_USERNAME');
        $this->credentials['password'] = env('DB_PASSWORD');
        $this->credentials['database'] = env('DB_DATABASE');

        switch ($this->system) {
            case 'mysql':
                $dsn = "mysql:host={$this->credentials['host']};dbname={$this->credentials['database']};charset=utf8";

                break;
            case 'sqlite':
                $dbFile = $this->credentials['database'];

                $dsn = "sqlite:$dbFile";

                break;
            default:
                throw new Exception("Unsupported database driver '$this->system'");
        }

        $this->pdo = new PDO(
            $dsn, $this->credentials['user'], $this->credentials['password'], [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    public function __destruct() {
        $this->pdo = null;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
