<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;

class SocialTest extends TestCase
{
    public function test_meta_has_all_the_expected_keys(): void
    {
        $entry = Entry::find('home');

        $this->assertArrayHasKey('open_graph', $entry->seotamic_social);
        $this->assertArrayHasKey('twitter', $entry->seotamic_social);
        $this->assertArrayHasKey('site_name', $entry->seotamic_social);
        $this->assertArrayHasKey('title', $entry->seotamic_social);
        $this->assertArrayHasKey('description', $entry->seotamic_social);
        $this->assertArrayHasKey('image', $entry->seotamic_social);
    }
}
