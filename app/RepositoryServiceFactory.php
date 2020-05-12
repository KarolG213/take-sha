<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 11.05.2020
 * Time: 12:30
 */

namespace app;

use app\services\interfaces\RepositoryServiceInterface;

class RepositoryServiceFactory
{
    public static function getRepositoryService(String $serviceName):RepositoryServiceInterface
    {
        $className = 'app\services\\'.$serviceName;
        if (!class_exists($className)) throw new \Exception('service_not_exists');
        $service = new $className();
        if (!($service instanceof RepositoryServiceInterface)) throw new \Exception('service_wrong_interface');
        return $service;
    }
}