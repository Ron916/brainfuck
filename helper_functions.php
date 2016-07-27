<?php

function projectroot()
{
    return __DIR__;
}

function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function dd()
{
    foreach(func_get_args() as $arg) {
        $string = highlight_string("<?php\n" . var_export($arg, true), true);
        echo preg_replace('/&lt;\?php<br \/>/', '', $string);
    }
    die();
}