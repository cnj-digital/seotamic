<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;

class SocialFieldsTest extends TestCase
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

    // --- Title type tests ---

    public function test_social_title_type_title_uses_entry_title(): void
    {
        // Contact entry has social title type: title, meta title type: title
        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $social = $entry->seotamic_social;

        $this->assertEquals('Contact', $social['title']);
    }

    public function test_social_title_type_custom_uses_custom_value(): void
    {
        $entry = Entry::find('home');
        $entry->set('seotamic_social', [
            'title' => [
                'type' => 'custom',
                'value' => 'My Custom Social Title',
                'custom_value' => 'My Custom Social Title',
            ],
            'description' => [
                'value' => '',
                'custom_value' => '',
                'type' => 'meta',
            ],
        ]);

        $social = $entry->seotamic_social;

        $this->assertEquals('My Custom Social Title', $social['title']);
    }

    public function test_social_title_type_general_uses_global_social_title(): void
    {
        $entry = Entry::find('home');
        $entry->set('seotamic_social', [
            'title' => [
                'type' => 'general',
                'value' => '',
                'custom_value' => '',
            ],
            'description' => [
                'value' => '',
                'custom_value' => '',
                'type' => 'meta',
            ],
        ]);

        $social = $entry->seotamic_social;

        // EN globals have social_title: 'General social title'
        $this->assertEquals('General social title', $social['title']);
    }

    public function test_social_title_type_title_with_custom_meta_uses_meta_value(): void
    {
        // Set meta to custom type, social to title type
        $entry = Entry::find('home');
        $entry->set('seotamic_meta', [
            'title' => [
                'type' => 'custom',
                'value' => 'Custom Meta Title',
                'custom_value' => 'Custom Meta Title',
                'append' => false,
                'prepend' => false,
            ],
            'description' => [
                'value' => '',
                'custom_value' => '',
                'type' => 'empty',
            ],
        ]);
        $entry->set('seotamic_social', [
            'title' => [
                'type' => 'title',
                'value' => '',
                'custom_value' => '',
            ],
            'description' => [
                'value' => '',
                'custom_value' => '',
                'type' => 'meta',
            ],
        ]);

        $social = $entry->seotamic_social;

        // Social type 'title' + meta type 'custom' → uses meta custom value
        $this->assertEquals('Custom Meta Title', $social['title']);
    }

    // --- Description type tests ---

    public function test_social_description_type_custom_uses_custom_value(): void
    {
        $entry = Entry::find('home');
        $entry->set('seotamic_social', [
            'title' => [
                'type' => 'title',
                'value' => '',
                'custom_value' => '',
            ],
            'description' => [
                'value' => 'My custom social description',
                'custom_value' => 'My custom social description',
                'type' => 'custom',
            ],
        ]);

        $social = $entry->seotamic_social;

        $this->assertEquals('My custom social description', $social['description']);
    }

    public function test_social_description_type_meta_with_custom_meta_description(): void
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
                'value' => 'Custom meta description',
                'custom_value' => 'Custom meta description',
                'type' => 'custom',
            ],
        ]);
        $entry->set('seotamic_social', [
            'title' => [
                'type' => 'title',
                'value' => '',
                'custom_value' => '',
            ],
            'description' => [
                'value' => '',
                'custom_value' => '',
                'type' => 'meta',
            ],
        ]);

        $social = $entry->seotamic_social;

        $this->assertEquals('Custom meta description', $social['description']);
    }

    // --- Open Graph & Twitter flags ---

    public function test_social_open_graph_flag_from_globals(): void
    {
        $entry = Entry::find('home');
        $social = $entry->seotamic_social;

        // EN globals have open_graph_display: true
        $this->assertTrue($social['open_graph']);
    }

    public function test_social_twitter_flag_from_globals(): void
    {
        $entry = Entry::find('home');
        $social = $entry->seotamic_social;

        // EN globals have twitter_display: true
        $this->assertTrue($social['twitter']);
    }

    public function test_social_site_name_from_globals(): void
    {
        $entry = Entry::find('home');
        $social = $entry->seotamic_social;

        // EN globals have social_site_name: 'Demo Site'
        $this->assertEquals('Demo Site', $social['site_name']);
    }

    // --- Image handling ---

    public function test_social_image_empty_when_no_image_set(): void
    {
        $entry = Entry::find('home');
        $social = $entry->seotamic_social;

        // No seotamic_image set, no global social_image
        $this->assertEquals('', $social['image']);
    }
}
