<?php

namespace Cnj\Seotamic\Tests;

use Cnj\Seotamic\Tags\SeotamicTags;
use Cnj\Seotamic\File\File as Settings;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Statamic\Facades\Blink;
use Statamic\Facades\Entry;

class StaticSiteTest extends TestCase
{
    private string $directory;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('statamic.routes.enabled', false);
    }

    protected function getPackageProviders($app)
    {
        return [...parent::getPackageProviders($app), \Wilderborn\Partyline\ServiceProvider::class, \Statamic\StaticSite\ServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->copyFixtureGlobals();
        Blink::flush();
        $this->directory = sys_get_temp_dir() . '/seotamic-ssg-' . bin2hex(random_bytes(8));
        File::makeDirectory($this->directory . '/assets', 0755, true);
        config([
            'filesystems.disks.assets' => ['driver' => 'local', 'root' => $this->directory . '/assets', 'url' => '/assets'],
            'statamic.ssg.base_url' => 'https://static.example.com',
            'statamic.ssg.destination' => $this->directory . '/output',
            'statamic.ssg.copy' => [$this->directory . '/assets' => 'assets'],
            'statamic.ssg.failures' => 'errors',
        ]);
        $image = imagecreatetruecolor(16, 16);
        imagepng($image, $this->directory . '/assets/first.png');
        imagepng($image, $this->directory . '/assets/second.png');
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->directory);
        $this->cleanupFixtureGlobals();
        parent::tearDown();
    }

    public function test_tags_use_the_current_context_in_the_same_render_process(): void
    {
        foreach (['First', 'Second'] as $title) {
            $tag = app(SeotamicTags::class)->setContext([
                'seotamic_meta' => ['title' => $title],
                'seotamic_social' => ['open_graph' => true, 'title' => $title],
            ]);
            $this->assertStringContainsString('content="' . $title . '"', $tag->og()->render());
        }
    }

    public static function imageModes(): array
    {
        return ['compressed' => [true], 'original' => [false]];
    }

    #[DataProvider('imageModes')]
    public function test_ssg_generates_distinct_metadata_and_static_social_images(bool $compress): void
    {
        $settings = app(Settings::class);
        $settings->write(array_replace($settings->read(), ['social_image_compress' => $compress, 'social_image' => 'second.png']));
        foreach (['first' => 'home', 'second' => '7623f560-baef-45eb-9ece-818c6f30eaec'] as $slug => $id) {
            Route::get('/' . $slug, function () use ($slug, $id) {
                $entry = Entry::find($id);
                $entry->set('seotamic_image', $slug === 'first' ? 'first.png' : null);
                $entry->set('seotamic_meta', [
                    'title' => ['type' => 'custom', 'value' => $slug . ' SEO', 'append' => false, 'prepend' => false],
                    'description' => ['type' => 'custom', 'value' => $slug . ' SEO description'],
                ]);
                $entry->set('seotamic_social', [
                    'title' => ['type' => 'custom', 'value' => $slug],
                    'description' => ['type' => 'custom', 'value' => $slug . ' description'],
                ]);
                return app(SeotamicTags::class)->setContext([
                    'seotamic_meta' => $entry->seotamic_meta,
                    'seotamic_social' => $entry->seotamic_social,
                ])->index();
            });
        }

        $exit = \Illuminate\Support\Facades\Artisan::call('statamic:ssg:generate', ['urls' => ['/first', '/second'], '--workers' => 1]);
        $this->assertSame(0, $exit, \Illuminate\Support\Facades\Artisan::output());

        foreach (['first', 'second'] as $slug) {
            $html = File::get($this->directory . '/output/' . $slug . '/index.html');
            $entry = Entry::find($slug === 'first' ? 'home' : '7623f560-baef-45eb-9ece-818c6f30eaec');
            $this->assertStringContainsString('rel="canonical" href="https://static.example.com' . $entry->uri() . '"', $html);
            $this->assertStringContainsString('<title>' . $slug . ' SEO</title>', $html);
            $this->assertStringContainsString('property="og:title" content="' . $slug . '"', $html);
            $this->assertStringContainsString('name="twitter:title" content="' . $slug . '"', $html);
            $this->assertStringContainsString('property="og:url" content="https://static.example.com' . $entry->uri() . '"', $html);
            $this->assertStringContainsString('name="twitter:url" content="https://static.example.com' . $entry->uri() . '"', $html);
            $this->assertSame(1, preg_match('/property="og:image" content="([^"]+)"/', $html, $matches));
            $this->assertStringStartsWith('https://static.example.com/' . ($compress ? 'img/' : 'assets/'), $matches[1]);
            $this->assertStringEndsWith('/' . $slug . '.png', $matches[1]);
            $this->assertFileExists($this->directory . '/output' . parse_url($matches[1], PHP_URL_PATH));
        }
    }
}
