<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\CpsMail;

/**
 * Description of CpsMailMergeFiled
 *
 * @author truong.nguyen
 */
class CpsMailMergeFiled extends \ArrayObject
{

    public function __construct($attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setProperty($name, $value);
        }
    }

    private function setProperty($name, $value)
    {
        $this->$name = $value;
        $this[$name] = $value;
    }

    public function getValueFromVisitor($visitor)
    {
        $value = $this->value;
        return is_callable($value) ? $value($visitor) : $value;
    }
}
