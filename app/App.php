<?php
/**
 *
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:21
 */

namespace app;

use app\helpers\CommandReader;

class App {

    private $options;
    private $repository;
    private $branch;
    private $service;
    const ERROR_MESSAGES = [                            // maybe should be moved to config file in the future
        'empty_argv' => 'Empty script parameters. Please use params as: [OWNER]/[REPOSITORY] [BRANCH_NAME]',
        'wrong_params' => 'Wrong parameters. Please use params as: [OWNER]/[REPOSITORY] [BRANCH_NAME]',
    ];

    /**
     * App constructor.
     */
    public function __construct()
    {
        echo "Starting app....\n";
    }

    public function run(Array $arguments)
    {
        try {
            if (count($arguments) < 3) throw new \Exception('empty_argv');
            array_shift($arguments);
            $this->parseArguments($arguments);
            $this->setService($this->options);
        } catch (Exception $e) {
            echo ERROR_MESSAGES[$e->getMessage()];
            exit();
        }
    }

    public function parseArguments($arguments)
    {
        $arguments = (new CommandReader($arguments))->parseArguments();
        $this->options = $arguments['options'];
        $this->owner = $arguments['owner'];
        $this->repository = $arguments['repository'];
        $this->branch = $arguments['branch'];

    }
}