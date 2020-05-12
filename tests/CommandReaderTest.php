<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 11.05.2020
 * Time: 8:35
 */

use PHPUnit\Framework\TestCase;

class CommandReaderTest extends TestCase
{
    use tests\HelperTrait;

    public function testParseOptions()
    {
        $testSets = [
            ['input'=>['-s=github','--option2=value2'], 'output'=>['service'=>'github', 'option2'=>'value2']],
            ['input'=>['--service=github','--option2=value2'], 'output'=>['service'=>'github', 'option2'=>'value2']],
            ['input'=>['--service=github'], 'output'=>['service'=>'github']],
        ];

        foreach($testSets as $testSet)
        {
            $commandReader = new \app\helpers\CommandReader();
            $optionsArray = $this->invokeProtectedMethod($commandReader, 'parseOptions', [$testSet['input']]);
            $this->assertEquals($testSet['output'], $optionsArray);
        }

    }

    public function testParsingArguments()
    {
        $testSets = [
            ['input'=>['owner-1/repository-1','branch-1'], 'output'=>['owner'=>'owner-1', 'repository'=>'repository-1', 'branch'=>'branch-1']],
            ['input'=>['OwNer-1/rEpos_Itory-1','Br_anCh-1'], 'output'=>['owner'=>'OwNer-1', 'repository'=>'rEpos_Itory-1', 'branch'=>'Br_anCh-1']],
        ];
        foreach($testSets as $testSet)
        {
            $commandReader = new \app\helpers\CommandReader();
            $optionsArray = $this->invokeProtectedMethod($commandReader, 'parseParameters', [$testSet['input']]);
            $this->assertEquals($testSet['output'], ['owner'=>$optionsArray[0], 'repository'=>$optionsArray[1], 'branch'=>$optionsArray[2]]);
        }
    }

    public function testLackOfArguments()
    {
        $testSets = [
            ['input'=>['owner-1/repository-1'], 'output'=>[]],
            ['input'=>[], 'output'=>[]],
        ];

        foreach($testSets as $testSet)
        {
            $this->expectErrorMessage('wrong_params');
            $commandReader = new \app\helpers\CommandReader();
            $this->invokeProtectedMethod($commandReader, 'parseParameters', [$testSet['input']]);
        }
    }
}