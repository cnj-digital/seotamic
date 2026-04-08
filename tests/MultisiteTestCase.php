<?php

namespace Cnj\Seotamic\Tests;

abstract class MultisiteTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('statamic.system.multisite', true);
        $app['config']->set('statamic.editions.pro', true);

        // Only use the pages collection which has proper multisite structure.
        // The protected collection only has sites: [en] and causes Stache errors in multisite mode.
        $app['config']->set('statamic.stache.stores.collections.directory', __DIR__ . '/__fixtures__/content/multisite-collections');
        $app['config']->set('statamic.stache.stores.entries.directory', __DIR__ . '/__fixtures__/content/multisite-collections');
    }
}
