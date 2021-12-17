<?php

namespace Melonly\Database;

use PDO;

interface DBConnectionInterface {
    public function getConnection(): PDO;
}
