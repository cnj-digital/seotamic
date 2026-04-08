<?php

namespace Cnj\Seotamic\Tests;

use Cnj\Seotamic\ServiceProvider;
use Statamic\Testing\AddonTestCase;
use Statamic\Facades\YAML;
use Statamic\Facades\Site;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;

    protected function setUp(): void
    {
        parent::setUp();

        Site::setSites(YAML::file(__DIR__ . "/__fixtures__/sites.yaml")->parse());
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('statamic.stache.stores.collection-trees.directory', __DIR__ . '/__fixtures__/content/trees/collections');
        $app['config']->set('statamic.stache.stores.nav-trees.directory', __DIR__ . '/__fixtures__/content/trees/navigation');
    }

    protected function copyFixtureGlobals(): void
    {
        $destination = base_path('content');

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        foreach (glob(__DIR__ . '/__fixtures__/content/seotamic_*.yaml') as $file) {
            copy($file, $destination . '/' . basename($file));
        }
    }

    protected function cleanupFixtureGlobals(): void
    {
        $destination = base_path('content');

        foreach (glob($destination . '/seotamic_*.yaml') as $file) {
            unlink($file);
        }
    }
}
