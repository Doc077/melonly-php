<?php

use Melonly\Bootstrap\Application;

// Path to framework files & plugins.
// Change this path when moving to server from local environment.

const INCLUDE_PATH = '../';

require __DIR__ . '/' . INCLUDE_PATH . '/plugins/autoload.php';

Application::start();

/**
    888b     d888          888                   888          
    8888b   d8888          888                   888          
    88888b.d88888          888                   888          
    888Y88888P888  .d88b.  888  .d88b.  88888b.  888 888  888 
    888 Y888P 888 d8P  Y8b 888 d88""88b 888 "88b 888 888  888 
    888  Y8P  888 88888888 888 888  888 888  888 888 888  888 
    888   "   888 Y8b.     888 Y88..88P 888  888 888 Y88b 888 
    888       888  "Y8888  888  "Y88P"  888  888 888  "Y88888 
                                                        888 
                                                    Y8b d88P 
                                                    "Y88P"  

    Melonly - The Modern PHP Framework

    (c) 2021 - 2022 Dominik Rajkowski

    Documentation: melonly.dev/docs
    Repository: https://github.com/Doc077/melonly
*/
