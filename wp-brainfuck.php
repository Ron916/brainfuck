<?php
/**
Plugin Name: WordPress BrainFuck Machine
Plugin URI: brainfuck.definitedev.com
Description: A plugin to simulate BrainFuck..
Version: .9
Author: Ron @ DefiniteDev.com
Author URI: http://www.definitedev.com
License: GPL2
 **/

namespace Defdev;

//attempt to PSR-0 autoload even though there's only one class not included already :P
//not working right now.. keeps passing in a wordpress autoload function... Needs work.
//spl_autoload_register('\Defdev\autoload');
//function from FIG PSR-0.md
function asdfautoload($className)
{
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.class.php';

    require $fileName;
}
//since the above doesn't work yet.
include 'ronforms.class.php';
include 'brainfuck.class.php';
include 'functions.php';

if (defined('DB_NAME')) { //if installed in wordpress

    register_activation_hook(__FILE__, '\Defdev\activate_wpbrainfuck');
    function activate_wpbrainfuck()
    {
        //nothing to do here yet, just a placeholder for possible future use??
    }

    register_deactivation_hook(__FILE__, '\Defdev\deactivate_wpbrainfuck');
    function deactivate_wpbrainfuck()
    {
        //nothing to do here yet, just a placeholder for possible future use??
    }

    register_uninstall_hook(__FILE__, '\Defdev\uninstall_wpbrainfuck');
    function uninstall_wpbrainfuck()
    {
        //nothing to do here yet, just a placeholder for possible future use??
    }
    add_shortcode('brainfuck', '\Defdev\brainfuck_main');

} elseif (defined('INDEX_FOWRARD')) {
    brainfuck_main();
} else {
    die('No direct script access');
}

