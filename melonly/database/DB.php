<?php

namespace Melonly\Database;

use PDOException;
use Melonly\Services\Container;
use Melonly\Support\Containers\Vector;
use Melonly\Exceptions\Handler;

class DB implements DBInterface {
    public static function query(string $sql, array $boundParams = []): Vector | Record | array {
        try {
            $pdo = Container::get(DBConnection::class)->getConnection();

            $pdo->query('SET NAMES UTF8');
            $pdo->query('SET CHARACTER SET UTF8');

            $query = $pdo->prepare($sql);

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
                $created = new Record();

                foreach ($record as $column => $value) {
                    $created->{$column} = $value;
                }

                $records[] = $created;
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
}
