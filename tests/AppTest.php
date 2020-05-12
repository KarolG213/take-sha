<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:35
 */

use PHPUnit\Framework\TestCase;

use app\App;
use app\services\interfaces\RepositoryServiceInterface;

class AppTest extends TestCase
{
    use tests\HelperTrait;

    public function testAppClassExists()
    {
        $this->assertInstanceOf(App::class, new App([]));
    }

    public function testParsingArguments()
    {
        $inputs = [
            0 => ['--service=github', 'owner/repo', 'branch'],
        ];

        foreach($inputs as $input) {
            $app = new App();
            $this->invokeProtectedMethod($app, 'parseArguments', [$input]);
        }
        $this->assertTrue(true);
    }

    public function testSettingService()
    {
        $options = ['service'=>'github'];
        $app = new App();
        $repositoryService = $this->invokeProtectedMethod($app, 'setService', [$options]);
        $this->assertInstanceof(RepositoryServiceInterface::class, $repositoryService);

        $this->expectErrorMessage('unknown_service');
        $options = ['service'=>'bitbucket'];
        $app = new App();
        $this->invokeProtectedMethod($app, 'setService', [$options]);
    }

    public function testSettingServiceArguments()
    {
        $repositoryService = $this->createMock(RepositoryServiceInterface::class);
        $owner = 'owner';
        $repository = 'repository';
        $branch = 'branch';
        $app = new App();
        $this->changeProtectedProperty($app, 'owner', $owner);
        $this->changeProtectedProperty($app, 'repository', $repository);
        $this->changeProtectedProperty($app, 'branch', $branch);
        $repositoryService->expects($this->once())
            ->method('setOwner')
            ->with($this->equalTo($owner));
        $repositoryService->expects($this->once())
            ->method('setRepository')
            ->with($this->equalTo($repository));
        $repositoryService->expects($this->once())
            ->method('setBranch')
            ->with($this->equalTo($branch));
        $this->invokeProtectedMethodByReference($app, 'setServiceArguments', $repositoryService);
    }
}