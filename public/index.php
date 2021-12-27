<?php

/**
 * Melonly - The Modern PHP Framework
 * 
 * @author  Dominik Rajkowski
 * @see  melonly.dev/docs
 */

use Melonly\Bootstrap\Application;

// Path to framework files.
// Change this path when moving to server from local environment.
const INCLUDE_PATH = '/../melonly';

require_once __DIR__ . '/' . INCLUDE_PATH . '/bootstrap/Application.php';

$app = new Application();
