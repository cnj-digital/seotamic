<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Config;

class SitemapTest extends TestCase
{
    public function test_it_returns_404_when_sitemap_is_disabled(): void
    {
        Config::set('seotamic.sitemap', false);

        $this
            ->get('/sitemap.xml')
            ->assertStatus(404);
    }
}
