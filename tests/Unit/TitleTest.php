<?php

namespace Tests\Unit;

use Mockery;
use Cnj\Seotamic\Tests\TestCase;

class TitleTest extends TestCase
{
    public $tag;

    public function setUp(): void {
        parent::setUp();

        $this->tag = $this->app->make('Cnj\Seotamic\Tags\SeotamicTags');
        $this->tag->context = Mockery::mock();
    }

    public function test_default_title()
    {
        $title = 'My Title';

        $this->tag->context->shouldReceive('raw')->with('title')->andReturns($title);
        $this->tag->context->shouldReceive('raw')->with('seotamic_title')->andReturns('title');

        $this->assertEquals($title, $this->tag->title());
    }

    public function test_custom_title()
    {
        $title = 'My Custom Title';

        $this->tag->context->shouldReceive('raw')->with('title')->andReturns('');
        $this->tag->context->shouldReceive('raw')->with('seotamic_title')->andReturns('custom');
        $this->tag->context->shouldReceive('raw')->with('seotamic_custom_title')->andReturns($title);

        $this->assertEquals($title, $this->tag->title());
    }

    public function test_title_append()
    {
        $title = 'My Title';
        $append = 'Append';
        $this->tag->values['title_append'] = $append;

        $this->tag->context->shouldReceive('raw')->with('title')->andReturns($title);
        $this->tag->context->shouldReceive('raw')->with('seotamic_title')->andReturns('title');
        $this->tag->context->shouldReceive('raw')->with('seotamic_title_append')->andReturns(true);

        $this->assertEquals("${title} ${append}", $this->tag->title());
    }

    public function test_title_prepend()
    {
        $title = 'My Title';
        $prepend = 'Prepend';
        $this->tag->values['title_prepend'] = $prepend;

        $this->tag->context->shouldReceive('raw')->with('title')->andReturns($title);
        $this->tag->context->shouldReceive('raw')->with('seotamic_title')->andReturns('title');
        $this->tag->context->shouldReceive('raw')->with('seotamic_title_prepend')->andReturns(true);

        $this->assertEquals("${prepend} ${title}", $this->tag->title());
    }
}
