<?php

/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 11.05.2020
 * Time: 09:30
 */

namespace tests;

trait HelperTrait
{
    /**
     * Call private or protected method of an object.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $params Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeProtectedMethod(&$object, $methodName, array $params = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $params);
    }

    /**
     * Same as method higher but call method with One parameter passed by reference
     *
     * @param $object
     * @param $methodName
     * @param $param
     * @return mixed
     */
    public function invokeProtectedMethodByReference(&$object, $methodName, &$param)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, array(&$param));
    }

    /**
     * Change private or protected property of an object.
     *
     * @param object &$object    Instantiated object on which property will be changed
     * @param string $propertyName Property name to change
     * @param mixed  $value Value of property to set
     *
     * @return mixed Method return.
     */
    public function changeProtectedProperty(&$object, $propertyName, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $reflectionProperty = $reflection->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }
}