<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NorCal
 * Date: 5/31/13
 * Time: 1:31 AM
 * To change this template use File | Settings | File Templates.
 */


namespace Defdev;

/**
 * Main Plugin Control Function
 */
function brainfuck_main()
{
    $brainfuck = new BrainFuck();
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'encode') {

            $output = $brainfuck->encode($_POST['encode']);
            echo "<h3>You Entered In:</h3>" . htmlspecialchars($_POST['encode']) . "<br>";
            echo "<h3>Your Program Code Is:</h3>" . htmlspecialchars($output) . "<br>";
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
                echo "<h3>Your Program:</h3>" . htmlspecialchars($program) . "<br><br>";
                //turn program into an array for ez pointer usage
                for ($i = 0; strlen($program) > 0; $i++) {
                    $tape[$i] = substr($program, 0, 1);
                    $program = substr($program, 1);
                }
                //mark ending
                $tape[$i] = "END";
            }
            echo "<h3>Your Program Output:</h3><br>" . htmlspecialchars($brainfuck->compute($tape)) . "<br><br>";
        }
    }
    getForm();
    getEncodeForm();
}

/**
 * Basic form for entering a program to be computed
 */
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

/**
 * Basic form for entering text to be encoded.
 */
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

/**
 * Will return the key # of the closest array value match to $search
 *
 * @param $search - int to search array for closest match
 * @param $arr - array to search
 * @return mixed
 */
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