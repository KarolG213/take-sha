<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 12.05.2020
 * Time: 8:35
 */

use PHPUnit\Framework\TestCase;
use app\RepositoryServiceFactory;
use app\services\interfaces\RepositoryServiceInterface;

class RepositoryServiceFactoryTest extends TestCase
{
    use tests\HelperTrait;

    public function testGetRepositoryService()
    {
        $serviceName = 'GithubService';
        $repositoryService = RepositoryServiceFactory::getRepositoryService($serviceName);
        $this->assertInstanceof(RepositoryServiceInterface::class, $repositoryService);

        $serviceName = 'ServiceNotExists';
        $this->expectErrorMessage('service_not_exists');
        RepositoryServiceFactory::getRepositoryService($serviceName);
    }
}