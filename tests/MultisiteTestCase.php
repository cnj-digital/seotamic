<?php

namespace Cnj\Seotamic\Tests;

abstract class MultisiteTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('statamic.system.multisite', true);
        $app['config']->set('statamic.editions.pro', true);
    }
}
