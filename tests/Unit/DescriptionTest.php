<?php

namespace Tests\Unit;

use Mockery;
use Cnj\Seotamic\Tests\TestCase;

class DescriptionTest extends TestCase
{
    public $tag;

    public function setUp(): void {
        parent::setUp();

        $this->tag = $this->app->make('Cnj\Seotamic\Tags\SeotamicTags');
        $this->tag->context = Mockery::mock();
    }

    public function test_default_description()
    {
        $this->tag->context->shouldReceive('raw')->with('seotamic_meta_description')->andReturns('');

        $this->assertEquals('', $this->tag->description());
    }

    public function test_empty_description()
    {
        $this->tag->context->shouldReceive('raw')->with('seotamic_meta_description')->andReturns('empty');

        $this->assertEquals('', $this->tag->description());
    }

    public function test_general_description()
    {
        $description = 'This is a general description.';
        $this->tag->values['meta_description'] = $description;

        $this->tag->context->shouldReceive('raw')->with('seotamic_meta_description')->andReturns('general');

        $this->assertEquals($description, $this->tag->description());
    }

    public function test_custom_description()
    {
        $description = 'This is a custom description.';

        $this->tag->context->shouldReceive('raw')->with('seotamic_meta_description')->andReturns('custom');
        $this->tag->context->shouldReceive('raw')->with('seotamic_custom_meta_description')->andReturns($description);

        $this->assertEquals($description, $this->tag->description());
    }
}
