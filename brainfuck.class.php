<?php
/**
 * Created by JetBrains PhpStorm.
 * Created by Ron @ DefiniteDev.com
 * Date: 6/5/13
 * Time: 3:58 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Defdev;

class BrainFuck
{

    private $tape = '';

    public function compute($tape = '')
    {
        $tape = ($tape == '' ? $this->tape : $tape);
        $memory = array();
        $mempointer = 0;
        $tapepointer = 0;
        $output = '';
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
                    //should probably use session_start() to transfer the arrays and pointers over ???
                    return;
                    break;
                case ".":
                    $output .= chr($memory[$mempointer]);
                    break;
                default:
                    break;
            }
            $tapepointer++;
        }
        return $this->tape = $output;
    }

    public function encode($input)
    {
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
        //create brainfuck initialization string (first part of string) that sets each memory cell to start point
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

        //create program string from input
        $cellpointer = 0;
        while (strlen($input) > 0) {
            $byte = ord(substr($input, 0, 1));
            if ($byte > 255 || $byte < 32) {
                echo "Your string is not valid.";
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
            $input = substr($input, 1);
        }
        return str_replace(" ", "", $output);
    }

}