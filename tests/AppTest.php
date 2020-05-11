<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 10.05.2020
 * Time: 18:35
 */

use PHPUnit\Framework\TestCase;

use app\App;

class AppTest extends TestCase
{
    public function testAppClassExists()
    {
        $this->assertInstanceOf(App::class, new App([]));
        $this->expectOutputString("Starting app...\n");
    }

    public function testParsingArguments()
    {
        $inputs = [
            0 => ['--service=github', 'owner/repo', 'branch'],
        ];

        foreach($inputs as $input) {
            $app = new App();
            $app->parseArguments($input);
        }
    }

    public function testSettingService()
    {

    }
}