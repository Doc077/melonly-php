<?php

use Melonly\Bootstrap\Application;

/**
 * Framework files path.
 * Adjust this path when moving to server from local environment.
 */
const INCLUDE_PATH = '/../melonly';

require_once __DIR__ . '/' . INCLUDE_PATH . '/bootstrap/Application.php';

$app = new Application();
