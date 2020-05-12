<?php
/**
 *
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:21
 */

namespace app\helpers;

/**
 * Class CommandReader
 * Class for parsing command line arguments from $argv
 * @package app\helpers
 */
class CommandReader
{
    const OPTIONS_ABBREVIATIONS = [
        '-s=' => '--service='
    ];                              // maybe to move to config file in the future

    /**
     * Parse provided arguments to array with keys: 'options', 'owner', 'repository', 'branch'
     * @return array
     */
    public function parseArguments(Array $arguments)
    {
        $options = [];
        $parameters = [];
        foreach ($arguments as $arg)
        {
            if (substr($arg, 0, 1) === '-') $options[] = $arg;
            else $parameters[] = $arg;
        }
        $optionsOutput = $this->parseOptions($options);
        list($owner, $repository, $branch) = $this->parseParameters($parameters);
        return [
            'options' => $optionsOutput,
            'owner' => $owner,
            'repository' => $repository,
            'branch' => $branch,
        ];
    }

    /**
     * Parse options to array ['option'=>'value']
     * For now, only --service [-s] option is intended
     * @param $options
     * @return array
     */
    private function parseOptions($options)
    {
        $parsed = [];
        foreach ($options as $option)
        {
            // remove short options, ex: -s changed to --service
            $option = str_replace(array_keys(self::OPTIONS_ABBREVIATIONS), array_values(self::OPTIONS_ABBREVIATIONS), $option);
            list($key, $value) = explode("=", $option);
            $parsed[substr($key, 2)] = $value;
        }
        return $parsed;
    }

    /**
     * Parse parameters to array: [owner, repository, branch]
     * @param $parameters
     * @return array
     * @throws \Exception
     */
    private function parseParameters($parameters)
    {
        if (count($parameters) < 2) throw new \Exception('wrong_params');
        // first parameter should be in format [OWNER]/[REPOSITORY]
        preg_match('/([\w-]+)[\/]{1}([\w-]+)/', $parameters[0], $output_array);
        if (count($output_array)!==3) throw new \Exception('wrong_params');
        return [$output_array[1], $output_array[2], $parameters[1]];
    }
}