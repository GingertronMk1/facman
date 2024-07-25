<?php 

// Automatically autoload Composer dependencies
$autoloadLocation = getcwd() . '/vendor/autoload.php';
if (is_file($autoloadLocation)) {
    require_once $autoloadLocation;
}

