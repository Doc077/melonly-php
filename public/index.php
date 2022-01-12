<?php

use Melonly\Bootstrap\Application;

// Path to framework files.
// Change this path when moving to server from local environment.

const INCLUDE_PATH = '/../melonly';

require_once __DIR__ . '/' . INCLUDE_PATH . '/bootstrap/Application.php';

$app = new Application();

/*

    .___  ___.  _______  __        ______   .__   __.  __      ____    ____ 
    |   \/   | |   ____||  |      /  __  \  |  \ |  | |  |     \   \  /   / 
    |  \  /  | |  |__   |  |     |  |  |  | |   \|  | |  |      \   \/   /  
    |  |\/|  | |   __|  |  |     |  |  |  | |  . `  | |  |       \_    _/   
    |  |  |  | |  |____ |  `----.|  `--'  | |  |\   | |  `----.    |  |     
    |__|  |__| |_______||_______| \______/  |__| \__| |_______|    |__|     
                                                                            

    ------------------------------------

    Melonly - The Modern PHP Framework

    (c) 2022 Dominik Rajkowski

    Documentation: melonly.dev/docs

*/
