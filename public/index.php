<?php

use Melonly\Bootstrap\Application;

// Path to framework files.
// Change this path when moving to server from local environment.

const INCLUDE_PATH = '/../melonly';

require_once __DIR__ . '/' . INCLUDE_PATH . '/bootstrap/Application.php';

$app = new Application();

/*

    __  __      _             _       
    |  \/  |    | |           | |      
    | \  / | ___| | ___  _ __ | |_   _ 
    | |\/| |/ _ \ |/ _ \| '_ \| | | | |
    | |  | |  __/ | (_) | | | | | |_| |
    |_|  |_|\___|_|\___/|_| |_|_|\__, |
                                __/ |
                                |___/ 

    ------------------------------------

    Melonly - The Modern PHP Framework

    (c) 2022 Dominik Rajkowski

    Documentation: melonly.dev/docs

*/
