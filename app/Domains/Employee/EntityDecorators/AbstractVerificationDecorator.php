<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 23/03/2017
 * Time: 03:06
 */

namespace App\Domains\Employee\EntityDecorators;


abstract class AbstractVerificationDecorator implements EmployeeVerificationDecoratorInterface
{


    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}(...$arguments);
        } else {
            return $this->getVerification()->{$name}(...$arguments);
        }
    }

}