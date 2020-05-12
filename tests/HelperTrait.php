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
}