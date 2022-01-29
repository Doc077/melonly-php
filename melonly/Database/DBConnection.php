<?php

namespace Melonly\Database;

use Melonly\Exceptions\Handler;
use Melonly\Support\Containers\Vector;
use PDO;
use PDOException;

class DBConnection implements DBConnectionInterface {
    protected readonly string $system;

    protected array $credentials = [];

    protected ?PDO $pdo = null;

    public function __construct() {
        $this->system = env('DB_SYSTEM');

        $this->credentials['host'] = env('DB_HOST');
        $this->credentials['user'] = env('DB_USERNAME');
        $this->credentials['password'] = env('DB_PASSWORD');
        $this->credentials['database'] = env('DB_DATABASE');

        if (php_sapi_name() !== 'cli') {
            switch ($this->system) {
                case 'mysql':
                    $dsn = "mysql:host={$this->credentials['host']};dbname={$this->credentials['database']};charset=utf8";

                    break;
                case 'sqlite':
                    $dbFile = $this->credentials['database'];

                    $dsn = "sqlite:$dbFile";

                    break;
                default:
                    throw new UnsupportedDBDriverException("Unsupported database driver '$this->system'");
            }

            $this->pdo = new PDO(
                $dsn, $this->credentials['user'], $this->credentials['password'], [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        }
    }

    public function query(string $sql, string $modelClass = Record::class, array $boundParams = []): object|array {
        try {
            $this->pdo->query('SET NAMES UTF8');
            $this->pdo->query('SET CHARACTER SET UTF8');

            $query = $this->pdo->prepare($sql);

            /**
             * Bind params for prepared statement.
             */
            foreach ($boundParams as $key => $value) {
                $query->bindParam($key, $value);
            }

            $query->execute();

            $result = $query->fetchAll();

            /**
             * Return element if SELECT query fetched only one element.
             */
            if (is_array($result[0] && count($result[0]) === 1)) {
                return $result[0];
            }

            /**
             * Create record objects for fetched records.
             */
            $records = new Vector();

            foreach ($result as $record) {
                $created = new $modelClass();

                foreach ($record as $column => $value) {
                    $created->{$column} = $value;
                }

                $records[] = $created;
            }

            if ($records->length() === 1 && count(get_object_vars($records[0])) === 1) {
                return get_object_vars($records[0])[array_key_first(get_object_vars($records[0]))];
            }

            /**
             * If the result consists of exactly one element, return it instead of vector.
             */
            if ($records->length() === 1) {
                return $records[0];
            }

            return $records;
        } catch (PDOException $exception) {
            Handler::handle($exception);
        }
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

    public function __destruct() {
        $this->pdo = null;
    }
}
