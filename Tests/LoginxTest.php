<?php


namespace dorukyy\loginx\Tests;

use Orchestra\Testbench\TestCase;

class LoginxTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return ['dorukyy\loginx\LoginxServiceProvider'];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

    public function testExample()
    {
        $this->assertTrue(true);

    }
}
