<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 5/21/13
 * Time: 2:38 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Defdev;

class RonForms {

    private $formheader = '';

    private $form = '';

    private $formfooter = '</form></table>';

    private $tablewidth = 2;

    public function __construct($input) {
        $default = array(
            'name' => 'form',
            'method' => 'POST',
            'action' => '',
            'class'  => 'table',
            'tablewidth'  => 2              //not decided on what to do here yet
        );
        //if anything missing from input array, assign the default value
        foreach ($default as $k => $v) {
            if (!isset($input[ $k ])) {
                $input[ $k ] = $v;
            }
        }
        $this->formheader .= "
        <table class='" . $input['class'] . "'>
            <form name=" . $input['name'] . " action=" . $input['action'] . " method=" . $input['method'] . ">
        ";
        $this->tablewidth = (isset($input['tablewidth']) ? $input['tablewidth'] : 2);
    }

    public function addTitle($title) {
        $this->formheader .= "<th colspan='" . $this->tablewidth . "'><h3>" . $title . "</h3></th>";
    }

    public function addInput($input = array(
            'label' => 'Default Label',
            'type'  => 'text',
            'value' => '',
            'name'  => 'defaultname'
    )) {
        if (strtolower($input['type']) == 'hidden') {
            $this->form .= "
                <input type='hidden' value='" . $input['value'] . "' name='" . $input['name'] . "'>
            ";
            return;
        }
        if (strtolower($input['type']) == 'textarea') {
            $this->form .= "
            <tr>
                <td>" . $input['label'] . "</td><td><textarea name='" . $input['name'] . "' rows='10' cols='20'>" . $input['value'] . "</textarea></td>
            </tr>
            ";
            return;
        }
        if (strtolower($input['type']) == 'select') {
            $this->form .= "
            <tr>
                <td>" . $input['label'] . "</td><td>" . "
                    <select name='" . $input['name'] . "'>
            ";
            foreach ($input['value'] as $v) {
                $this->form .= "<option value='" . $v . "'>" . $v . "</option>";
            }
            $this->form .= "
                    </select
                </td>
            </tr>
            ";
            return;
        }
        $this->form .= "
        <tr>
            <td>" . $input['label'] . "</td><td><input type='" . $input['type'] . "' value='" . $input['value'] . "' name='" . $input['name'] . "'></td>
        </tr>
        ";
        return;
    }

    public function addFooter($input) {
        $this->formfooter = "
        <tr>
            <td colspan=''>" .
                $input
            . "</td>
        </tr>" . $this->formfooter;
    }

    public function render() {
        echo $this->formheader . $this->form . $this->formfooter;
    }
}