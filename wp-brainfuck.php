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
//not working right now.. keeps passing in a wordpress autoload function... I'll fix it in the morning :P
//spl_autoload_register(__NAMESPACE__ . '\\autoload');
//function from FIG PSR-0.md
function autoload($className)
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

include 'ronforms.class.php';
include 'brainfuck.class.php';


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

function brainfuck_main()
{
    $brainfuck = new BrainFuck();
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'encode') {
            $output = $brainfuck->encode(htmlspecialchars($_POST['encode']));
            echo "Your Program Code Is:<br> " . htmlspecialchars($output);
            echo "<br><br>Here ya go, give it a try:<br>";

        } else {

            if ($_GET['action'] == 'input') {
                $memory = unserialize($_POST['memory']);
                $mempointer = unserialize($_POST['mempointer']);
                $program = unserialize($_POST['tape']);
                $tapepointer = unserialize($_POST['tapepointer']);
                $input = substr($_POST['input'], 0, 1);
                $memory[$mempointer] = ord($input);
            } else {
                $program = $_POST['program'];
                echo "<h3>Your Program:</h3>" . htmlspecialchars($program) . "<br>";
                //turn program into an array for ez pointer usage
                for ($i = 0; strlen($program) > 0; $i++) {
                    $tape[$i] = substr($program, 0, 1);
                    $program = substr($program, 1);
                }
                //mark ending
                $tape[$i] = "END";
            }
            echo $brainfuck->compute($tape);

        }
    }
    getForm();
    getEncodeForm();
}




function getForm()
{
    $form = new RonForms(array(
        'name' => 'form',
        'method' => 'POST',
        'action' => '?action=submit'
    ));
    $form->addTitle('BrainFuck Input:');
    $form->addInput(array(
        'label' => 'Please enter a BrainFuck program:',
        'type' => 'text',
        'value' => '',
        'name' => 'program'
    ));
    $form->addInput(array(
        'label' => '',
        'type' => 'submit',
        'value' => 'Submit',
        'name' => ''
    ));
    $form->render();
}

function getEncodeForm()
{
    $form = new RonForms(array(
        'name' => 'form',
        'method' => 'POST',
        'action' => '?action=encode'
    ));
    $form->addTitle('Encode your own BrainFuck string:');
    $form->addInput(array(
        'label' => 'Please enter text to encode into Brainfuck:',
        'type' => 'text',
        'value' => '',
        'name' => 'encode'
    ));
    $form->addInput(array(
        'label' => '',
        'type' => 'submit',
        'value' => 'Submit',
        'name' => ''
    ));
    $form->render();
}

function getClosest($search, $arr)
{
    //original function borrowed from stackoverflow
    $closest = null;
    foreach ($arr as $k => $item) {
        if ($closest == null || abs($search - $closest) > abs($item - $search)) {
            $closest = $item;
        }
    }
    return array_search($closest, $arr);
}