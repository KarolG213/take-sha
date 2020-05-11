<?php
/**
 *
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:21
 */

namespace app\helpers;


class CommandReader
{
    private $arguments;
    private $repository;
    private $owner;
    private $branch;
    private $options;
    const OPTIONS = [
        '-s=' => '--service='
    ];                              // maybe to move to config file in the future

    /**
     * CommandReader constructor. Takes arguments array (intendet to be $argv)
     * @param array $arguments
     */
    public function __construct(Array $arguments)
    {
        $this->arguments = $arguments;

    }

    /**
     * Parse provided arguments to array with keys: 'options', 'owner', 'repository', 'branch'
     * @return array
     */
    public function parseArguments()
    {
        $options = [];
        $parameters = [];
        foreach ($this->arguments as $arg)
        {
            if ($option = substr($arg, 0, 1) === '-') $options[] = $option;
            else $parameters = $arg;
        }
        $this->parseOptions($options);
        $this->parseParameters($parameters);
        return [
            'options' => $this->options,
            'owner' => $this->owner,
            'repository' => $this->repository,
            'branch' => $this->branch,
        ];
    }

    /**
     * Parse options to $options property ['option'=>'value']
     * For now, only --service [-s] option is intended
     * @param $options
     */
    public function parseOptions($options)
    {
        $parsed = [];
        foreach ($options as &$option)
        {
            // remove short options, ex: -s changed to --service
            $option = str_replace(array_keys(OPTIONS), array_values(OPTIONS), $option);
            list($key, $value) = explode("=", $option);
            $parsed[substr($key, 2)] = $value;
        }
        $this->options = $parsed;
    }

    /**
     * Parse parameters to properties: owner, repository, branch
     * @param $parameters
     * @throws \Exception
     */
    public function parseParameters($parameters)
    {
        if (count($parameters) < 2) throw new \Exception('wrong_params');
        // first parameter should be in format [OWNER]/[REPOSITORY]
        preg_match('/([\w-]+)[\/]{1}([\w-]+)/', $parameters[0], $output_array);
        if (count($output_array)!==3) throw new \Exception('wrong_params');
        $this->owner = $output_array[1];
        $this->repository = $output_array[2];
        $this->branch = $parameters[1];
    }
}