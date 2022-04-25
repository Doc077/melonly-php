<?php

use Melonly\Bootstrap\Application;

/**
 * The Modern PHP Framework

 * (c) 2021 - 2022 Dominik Rajkowski

 * Documentation: https://github.com/Doc077/melonly-php/blob/7.x/README.md
 * Repository: https://github.com/Doc077/melonly-php
 */

// Path to framework files and packages.
// Change this path when moving to server from local environment.

const INCLUDE_PATH = '../';

require __DIR__ . '/' . INCLUDE_PATH . '/vendor/autoload.php';

Application::start();
