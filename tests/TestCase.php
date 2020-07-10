<?php

namespace Cnj\Seotamic\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        require_once(__DIR__.'/ExceptionHandler.php');

        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Statamic\Providers\StatamicServiceProvider::class,
            \Cnj\Seotamic\ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(\Statamic\Extend\Manifest::class)->manifest = [
            'cnj/seotamic' => [
                'id' => 'cnj/seotamic',
                'namespace' => 'Cnj\\Seotamic\\',
            ],
        ];
    }
}
