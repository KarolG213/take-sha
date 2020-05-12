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
    const USER_AGENT = 'KarolG213/take-sha';

    /**
     * Trigger for service - returns SHA of last commit for provided owner/repo branch
     *
     * @return string
     */
    public function fetchLastSHA():string
    {
        $response = $this->fetchData($this->getAccessPointUrl());
        //print_r($response);
        return $response['commit']['sha'];
    }

    /**
     * @param string $param
     */
    public function setOwner(string $param):void
    {
        $this->owner = $param;
    }

    /**
     * @param string $param
     */
    public function setRepository(string $param):void
    {
        $this->repository = $param;
    }

    /**
     * @param string $param
     */
    public function setBranch(string $param):void
    {
        $this->branch = $param;
    }

    /**
     * Fetch data from Rest API - return associated array
     *
     * @param $url
     * @return Array
     * @throws \Exception
     */
    private function fetchData($url):Array
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
            $json=curl_exec($ch);
            if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                throw new \Exception('github_fetch_error');
            }
            curl_close($ch);
            $result = json_decode($json, true);
        }  catch (Exception $e) {
            throw new \Exception('github_fetch_error');
        }

        if (empty($result)) throw new \Exception('github_fetch_error');
        return $result;
    }

    /**
     * Creates rest api url from provided owner/repo branch
     *
     * @return string
     * @throws \Exception
     */
    private function getAccessPointUrl():string
    {
        if (empty($this->owner) || empty($this->repository) || empty($this->branch)) throw new \Exception('github_empty_params');
        return self::HOST."/repos/{$this->owner}/{$this->repository}/branches/{$this->branch}";
    }
}