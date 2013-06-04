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
spl_autoload_register(__NAMESPACE__ . '\\autoload');
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
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'encode') {
            $input = $_POST['encode'];
            //how high in the ascii table do we need to go, we don't need to initialize brainfuck to go any higher
            $inputcount = $input;
            $highest = 1;
            while (strlen($inputcount) > 0) {
                $char = substr($inputcount, 0, 1);
                $num = ord($char);
                if ($num > $highest) {
                    $highest = $num;
                }
                $inputcount = substr($inputcount, 1);
            }
            //need to work with ascii 32 to 255, anything outside this range is rejected by brainfuck
            $output = "+++++ +++++"; //initialize counter (cell #0) to 10
            $output .= "["; //use loop to set the next cells to 10/30/60/90/120/150/170/200/230
            //memory cell 1
            $output .= "> +"; //add  1 to cell #1
            $array[1] = 10;
            //memory cell 2
            $output .= "> +++"; //add  3 to cell #2
            $array[2] = 30;
            //optional memory cells depending on max ascii needs
            if ($highest >= 60) {
                $output .= "> +++ +++"; //add  6 to cell #3
                $array[3] = 60;
            }
            if ($highest >= 90) {
                $output .= "> +++ +++ +++"; //add  9 to cell #4
                $array[4] = 90;
            }
            if ($highest >= 120) {
                $output .= "> ++++ ++++ ++++"; //add 12 to cell #5
                $array[5] = 120;
            }
            if ($highest >= 150) {
                $output .= "> +++++ +++++ +++++"; //add 15 to cell #6
                $array[6] = 150;
            }
            if ($highest >= 170) {
                $output .= "> +++++ +++++ +++++ ++"; //add 17 to cell #7
                $array[7] = 170;
            }
            if ($highest >= 200) {
                $output .= "> +++++ +++++ +++++ +++++"; //add 20 to cell #8
                $array[8] = 200;
            }
            if ($highest >= 230) {
                $output .= "> +++++ +++++ +++++ +++++ +++"; //add 23 to cell #9
                $array[9] = 230;
            }
            foreach ($array as $hello_reddit_r_php) {
                $output .= "<";
            }
            $output .= "-"; //decrement counter (cell #0)
            $output .= "]";
            $cellpointer = 0;
            $prevbyte = '';
            while (strlen($input) > 0) {
                $byte = ord(substr($input, 0, 1));
                if ($byte > 255 || $byte < 32) {
                    echo "Your string is not valid.";
                    getForm();
                    getEncodeForm();
                    return;
                }
                //get cell # who's data is closest to the byte we're trying to print
                $closestcell = getClosest($byte, $array);
                if ($cellpointer > $closestcell) {
                    while ($cellpointer != $closestcell) {
                        $output .= "<";
                        $cellpointer--;
                    }
                } elseif ($cellpointer < $closestcell) {
                    while ($cellpointer != $closestcell) {
                        $output .= ">";
                        $cellpointer++;
                    }
                }
                //now modify the cell to match byte
                if ($byte > $array[$closestcell]) {
                    while ($byte != $array [$closestcell]) {
                        $output .= "+";
                        $array [$closestcell]++;
                    }
                } elseif ($byte < $array[$closestcell]) {
                    while ($byte != $array [$closestcell]) {
                        $output .= "-";
                        $array [$closestcell]--;
                    }
                }
                //print byte
                $output .= ".";
                //set up next byte
                $prevbyte = $byte;
                $input = substr($input, 1);
            }
            $output = str_replace(" ", "", $output);
            echo "Your Program Code Is:<br> " . htmlspecialchars($output);
            echo "<br><br>Here ya go, give it a try:<br>";
        } else {
            $memory = array();
            $mempointer = 0;
            $tapepointer = 0;
            $output = array();
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
            while ($tape[$tapepointer] != "END") {
                $command = $tape[$tapepointer];
                if (!array_key_exists($mempointer, $memory)) {
                    $memory[$mempointer] = 0;
                }
                switch ($command) {
                    case "<":
                        $mempointer--;
                        break;
                    case ">":
                        $mempointer++;
                        break;
                    case "+":
                        $memory[$mempointer]++;
                        break;
                    case "-":
                        $memory[$mempointer]--;
                        break;
                    case "]":
                        if ($memory[$mempointer] != 0) {
                            $closingtag = false;
                            $nested = 0;
                            while ($closingtag == false) {
                                $tapepointer--;
                                switch ($tape[$tapepointer]) {
                                    case "]":
                                        $nested++;
                                        break;
                                    case "[":
                                        if ($nested == 0) {
                                            $closingtag = true;
                                        } else {
                                            $nested--;
                                        }
                                        break;
                                }
                            }
                        }
                        break;
                    case "[":
                        if ($memory[$mempointer] == 0) {
                            $closingtag = false;
                            $nested = 0;
                            while ($closingtag = false) {
                                $tapepointer++;
                                switch ($tape[$tapepointer]) {
                                    case "[":
                                        $nested++;
                                        break;
                                    case "]":
                                        if ($nested == 0) {
                                            $closingtag = true;
                                        } else {
                                            $nested--;
                                        }
                                        break;
                                }
                            }
                        }
                        break;
                    case ",":
                        echo "<br>This feature ( , ) is not yet enabled, thanks for playing.";
                        break;
                        $form = new RonForms(array(
                            'action' => '?action=input'
                        ));
                        $form->addTitle('Program is asking for input.');
                        $form->addInput(array(
                            'label' => '',
                            'type' => 'hidden',
                            'value' => serialize($program),
                            'name' => 'program'
                        ));
                        $form->addInput(array(
                            'label' => '',
                            'type' => 'hidden',
                            'value' => serialize($memory),
                            'name' => 'memory'
                        ));
                        $form->addInput(array(
                            'label' => '',
                            'type' => 'hidden',
                            'value' => serialize($mempointer),
                            'name' => 'mempointer'
                        ));
                        $form->addInput(array(
                            'label' => '',
                            'type' => 'hidden',
                            'value' => serialize($tape),
                            'name' => 'tape'
                        ));
                        $form->addInput(array(
                            'label' => '',
                            'type' => 'hidden',
                            'value' => serialize($tapepointer),
                            'name' => 'tapepointer'
                        ));
                        $form->addInput(array(
                            'label' => 'Please enter a single character:',
                            'type' => 'text',
                            'value' => '',
                            'name' => 'input'
                        ));
                        $form->addInput(array(
                            'label' => '',
                            'type' => 'submit',
                            'value' => 'Submit',
                            'name' => ''
                        ));
                        $form->render();
                        return;
                        break;
                    case ".":
                        echo htmlspecialchars(chr($memory[$mempointer]));
                        break;
                    default:
                        break;
                }
                $tapepointer++;
            }

            echo "<br><br>Enter another program:<br>";

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