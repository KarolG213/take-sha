<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:26
 */

namespace app\services;

use app\services\interfaces\RepositoryServiceInterface;

/**
 * Class GithubService
 * Service uses Github Rest API v3 (without authorization so private data is unavailable)
 * There is possibility to use GraphQL API v4 (it needs authorization even for fetching public data - add graphql-php to dependencies)
 * @package app\services
 */
class GithubService implements RepositoryServiceInterface
{
    private $owner;
    private $repository;
    private $branch;

    const HOST = 'https://api.github.com';

    public function fetchLastSHA():string
    {
        $response = $this->fetchData($this->getAccessPointUrl());
        return $response['commit']['sha'];
    }

    public function setOwner(string $param):void
    {
        $this->owner = $param;
    }

    public function setRepository(string $param):void
    {
        $this->repository = $param;
    }

    public function setBranch(string $param):void
    {
        $this->branch = $param;
    }

    private function fetchData($url):Array
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            $json=curl_exec($ch);
            curl_close($ch);
            $result = json_decode($json, true);
        }  catch (Exception $e) {
            throw new \Exception('github_fetch_error');
        }
        if (empty($result)) throw new \Exception('github_fetch_error');
        return $result;
    }

    private function getAccessPointUrl():string
    {
        if (empty($this->owner) || empty($this->repository) || empty($this->branch)) throw new \Exception('github_empty_params');
        return self::HOST."/repos/{$this->owner}/{$this->repository}/branches/{$this->branch}";
    }
}