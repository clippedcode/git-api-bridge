<?php namespace Gogs;

define('GPAC_BASE_PATH', realpath(dirname(__FILE__)));

// Autoload classes, withing the Gogs-prefix
spl_autoload_register(function($class) {

    $prefix = "Gogs\\";
    $len = strlen($prefix);

    // Checks if prefix starts with Gogs\
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $file = GPAC_BASE_PATH . DIRECTORY_SEPARATOR;
    $file .= str_replace(
        "\\", DIRECTORY_SEPARATOR, substr($class, $len)
    ) . ".php";

    require_once $file;
});

return;
?>
