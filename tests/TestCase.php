<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=');
    }
}
