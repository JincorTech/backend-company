<?php


/**
 * Inherited Methods.
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

   /*
    * Define custom actions here
    */

    /**
     * @param $object object
     * @param string $propertyName
     * @return mixed
     */
    public function getObjectPropertyValue($object, string $propertyName)
    {
        return Closure::bind(function & () use ($propertyName) {
            return $this->$propertyName;
        }, $object, $object)->__invoke();
    }
}
