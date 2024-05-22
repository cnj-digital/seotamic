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
}
