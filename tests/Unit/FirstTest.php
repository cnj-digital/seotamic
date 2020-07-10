<?php

namespace Tests\Unit;

use Cnj\Seotamic\Tests\TestCase;

class FirstTest extends TestCase
{
    public $tag;

    public function setUp(): void {
        parent::setUp();

        $this->tag = $this->app->make('Cnj\Seotamic\Tags\SeotamicTags');
    }

    public function test_title()
    {
        $this->assertTrue(true);
    }
}
