<?php

namespace Melonly\Database;

use Exception;
use Melonly\Exceptions\Handler;
use Melonly\Support\Containers\Vector;
use PDO;
use PDOException;

class Connection implements ConnectionInterface
{
    protected readonly string $system;

    protected readonly string $host;

    protected readonly string $user;

    protected readonly string $password;

    protected readonly string $database;

    protected ?PDO $pdo = null;

    public function __construct()
    {
        $this->system = config('database.system');

        $this->host = config('database.host');

        $this->user = config('database.username');

        $this->password = config('database.password');

        $this->database = config('database.database');

        switch ($this->system) {
            case 'mysql':
                $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8";

                break;
            case 'sqlite':
                $dbFile = $this->database;

                $dsn = "sqlite:$dbFile";

                break;
            default:
                throw new UnsupportedDBDriverException("Unsupported database driver '$this->system'");
        }

        $this->pdo = new PDO(
            $dsn,
            $this->user,
            $this->password,
            [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    /**
     * Execute raw SQL query.
     */
    public function query(string $sql, string $modelClass = Record::class, array $boundParams = []): object|array
    {
        if (!$this->pdo) {
            throw new Exception('Database connection failed. Provide config credentials or check your database');
        }

        try {
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
            if (!empty($result) && is_array($result[0] && count($result[0]) === 1)) {
                return $result[0];
            }

            /**
             * Create record objects for fetched records.
             */
            $records = new Vector();

            foreach ($result as $record) {
                $created = new $modelClass();

                foreach ($record as $column => $value) {
                    if (!is_int($column)) {
                        $created->{$column} = $value;
                    }
                }

                $records[] = $created;
            }

            /**
             * Return single column value if the result contains only that.
             */
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

    public function from(string $table): Query
    {
        return (new Query())->setTable($table);
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}
