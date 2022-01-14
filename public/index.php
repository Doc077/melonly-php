<?php

use Melonly\Bootstrap\Application;

// Path to framework files.
// Change this path when moving to server from local environment.

const INCLUDE_PATH = '/../melonly';

require __DIR__ . '/' . INCLUDE_PATH . '/bootstrap/Application.php';

Application::start();

/*

    o     o        8              8        
    8b   d8        8              8        
    8`b d'8 .oPYo. 8 .oPYo. odYo. 8 o    o 
    8 `o' 8 8oooo8 8 8    8 8' `8 8 8    8 
    8     8 8.     8 8    8 8   8 8 8    8 
    8     8 `Yooo' 8 `YooP' 8   8 8 `YooP8 
                                        8 
                                    ooP'.


    Melonly - The Modern PHP Framework

    (c) 2022 Dominik Rajkowski

    Documentation: melonly.dev/docs

*/
