<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 12.05.2020
 * Time: 08:55
 */

use PHPUnit\Framework\TestCase;


class GithubServiceTest extends TestCase
{
    use tests\HelperTrait;

    public function testSetters()
    {
        $owner = 'owner-value';
        $repository = 'repository-value';
        $branch = 'branch-value';
        $service = new \app\services\GithubService();
        $service->setOwner($owner);
        $service->setRepository($repository);
        $service->setBranch($branch);

        $this->assertEquals($owner, $this->getPrivateProperty($service, 'owner'));
        $this->assertEquals($repository, $this->getPrivateProperty($service, 'repository'));
        $this->assertEquals($branch, $this->getPrivateProperty($service, 'branch'));

    }
    public function testFetchData()
    {
        $url = "https://api.github.com/repos/jquery/jquery/branches/master";
        $service = new \app\services\GithubService();
        $data = $this->invokeProtectedMethod($service, 'fetchData', [$url]);
        $this->assertEquals('master', $data['name']);       // check if master branch is fetched correctly
    }
    public function testGetAccessPointUrl()
    {
        $owner = 'owner-v';
        $repository = 'repository-v';
        $branch = 'branch-v';

        $service = new \app\services\GithubService();
        $this->changeProtectedProperty($service, 'owner', $owner);
        $this->changeProtectedProperty($service, 'repository', $repository);
        $this->changeProtectedProperty($service, 'branch', $branch);

        $url = $this->invokeProtectedMethod($service, 'getAccessPointUrl', []);
        $this->assertEquals($service::HOST."/repos/{$owner}/{$repository}/branches/{$branch}", $url);
    }

    // this is a functional test which check whole service
    public function testFetchLastSHA()
    {
        $owner = 'jquery';
        $repository = 'jquery';
        $branch = 'master';
        $service = new \app\services\GithubService();
        $service->setOwner($owner);
        $service->setRepository($repository);
        $service->setBranch($branch);
        $sha = $this->invokeProtectedMethod($service, 'fetchLastSHA', []);
        $this->assertTrue((bool) preg_match('/^[0-9a-f]{40}$/i', $sha));
    }
}