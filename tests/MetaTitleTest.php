<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Cache;

class MetaTitleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->copyFixtureGlobals();
    }

    protected function tearDown(): void
    {
        $this->cleanupFixtureGlobals();

        parent::tearDown();
    }

    public function test_title_type_title_uses_entry_title(): void
    {
        // Contact entry has type: title
        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $meta = $entry->seotamic_meta;

        $this->assertStringContainsString('Contact', $meta['title']);
    }

    public function test_title_type_custom_uses_custom_value(): void
    {
        // Home entry has type: custom, value: asd
        $entry = Entry::find('home');
        $meta = $entry->seotamic_meta;

        $this->assertStringContainsString('asd', $meta['title']);
    }

    public function test_title_append_adds_suffix(): void
    {
        // EN globals have title_append: '- Something'
        // Contact entry has append: true, type: title
        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $meta = $entry->seotamic_meta;

        $this->assertStringEndsWith('- Something', $meta['title']);
        $this->assertEquals('Contact - Something', $meta['title']);
    }

    public function test_title_prepend_adds_prefix(): void
    {
        // Update globals to add a prepend value
        $globals = base_path('content/seotamic_en_US.yaml');
        $content = file_get_contents($globals);
        $content = str_replace('title_prepend: null', 'title_prepend: \'Prefix |\'', $content);
        file_put_contents($globals, $content);

        Cache::flush();

        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $meta = $entry->seotamic_meta;

        $this->assertStringStartsWith('Prefix |', $meta['title']);
        $this->assertEquals('Prefix | Contact - Something', $meta['title']);
    }

    public function test_title_no_append_when_flag_is_false(): void
    {
        $entry = Entry::find('home');
        $entry->set('seotamic_meta', [
            'title' => [
                'type' => 'title',
                'value' => '',
                'custom_value' => '',
                'append' => false,
                'prepend' => false,
            ],
            'description' => [
                'value' => '',
                'custom_value' => '',
                'type' => 'empty',
            ],
        ]);

        $meta = $entry->seotamic_meta;

        $this->assertEquals('Home', $meta['title']);
    }

    public function test_title_both_prepend_and_append(): void
    {
        $globals = base_path('content/seotamic_en_US.yaml');
        $content = file_get_contents($globals);
        $content = str_replace('title_prepend: null', 'title_prepend: \'Site |\'', $content);
        file_put_contents($globals, $content);

        Cache::flush();

        // Contact entry has both append: true and prepend: true
        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $meta = $entry->seotamic_meta;

        $this->assertEquals('Site | Contact - Something', $meta['title']);
    }
}
