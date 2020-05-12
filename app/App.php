<?php
/**
 *
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:21
 */

namespace app;

use app\helpers\CommandReader;
use app\services\interfaces\RepositoryServiceInterface;
use app\RepositoryServiceFactory;

class App {

    private $options;
    private $owner;
    private $repository;
    private $branch;
    const ERROR_MESSAGES = [                            // maybe should be moved to config file in the future
        'empty_argv' => 'Empty script parameters. Please use params as: [OWNER]/[REPOSITORY] [BRANCH_NAME]',
        'wrong_params' => 'Wrong parameters. Please use params as: [OWNER]/[REPOSITORY] [BRANCH_NAME]',
        'unknown_service' => 'Unknown service. For now there is only "github" service available',
        'service_wrong_interface' => 'Repository service must implements RepositoryServiceInterface',
        'service_not_exists' => 'Repository service class does not exists',
        'unknown_error' => 'Something went wrong. Try again later',
        'github_fetch_error' => 'Github repository is unavailable. Provided parameters are wrong or repo is private.',
        'github_empty_params' => 'Github service has empty parameters',

    ];
    const DEFAULT_SERVICE = 'github';
    const REGISTERED_SERVICES = [
        'github' => 'GithubService',
    ];

    /**
     * App constructor.
     */
    public function __construct()
    {
        echo "Starting app...\n";
    }

    public function run(Array $arguments)
    {
        try {
            if (count($arguments) < 3) throw new \Exception('empty_argv');
            array_shift($arguments);        // removes php script filename
            $this->parseArguments($arguments);
            $repositoryService = $this->setService($this->options);
            $this->setServiceArguments($repositoryService);
            $sha = $repositoryService->fetchLastSHA();
            if (empty($sha)) throw new \Exception('unknown_error');
            echo $sha;
        } catch (Exception $e) {
            echo self::ERROR_MESSAGES[$e->getMessage()];
        }
        exit();
    }

    private function parseArguments(Array $arguments)
    {
        $arguments = (new CommandReader())->parseArguments($arguments);
        $this->options = $arguments['options'];
        $this->owner = $arguments['owner'];
        $this->repository = $arguments['repository'];
        $this->branch = $arguments['branch'];
    }

    private function setService(Array $options):RepositoryServiceInterface
    {
        $serviceParam = strtolower($options['service'] ?? self::DEFAULT_SERVICE);
        $registeredServices = self::REGISTERED_SERVICES;
        if (!isset($registeredServices[$serviceParam])) throw new \Exception('unknown_service');
        return RepositoryServiceFactory::getRepositoryService($registeredServices[$serviceParam]);
    }

    private function setServiceArguments(RepositoryServiceInterface &$repositoryService)
    {
        $repositoryService->setOwner($this->owner);
        $repositoryService->setRepository($this->repository);
        $repositoryService->setBranch($this->branch);
    }
}