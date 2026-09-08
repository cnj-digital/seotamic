<?php

namespace Cnj\Seotamic\Tests;

use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\DataProvider;
use Statamic\Facades\Addon;
use Statamic\Facades\Entry;
use Statamic\Facades\Site;

class SitemapHreflangTest extends MultisiteTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->copyFixtureGlobals();
        Addon::get('cnj/seotamic')->editions(['lite', 'pro']);
        config(['statamic.editions.addons' => ['cnj/seotamic' => 'pro']]);
        Cache::flush();
    }

    protected function tearDown(): void
    {
        $this->cleanupFixtureGlobals();

        parent::tearDown();
    }

    public static function languages(): array
    {
        return [
            'locale fallback retains region' => [null, 'sl-SI'],
            'explicit language without region' => ['sl', 'sl'],
            'explicit language with different region' => ['sl-IT', 'sl-IT'],
        ];
    }

    #[DataProvider('languages')]
    public function test_hreflang_in_sitemap_and_page_links(?string $lang, string $expected): void
    {
        if ($lang !== null) {
            Site::get('sl')->set('lang', $lang);
        }

        $response = $this->get('/sitemap.xml')->assertOk();
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $xml->registerXPathNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
        $href = Entry::find('home')->in('sl')->absoluteUrl();
        $links = $xml->xpath('//xhtml:link[@href="' . $href . '"]');
        $this->assertNotEmpty($links);

        foreach ($links as $link) {
            $this->assertSame($expected, (string) $link['hreflang']);
        }

        $html = view('seotamic::partials._related', [
            'meta' => Entry::find('home')->seotamic_meta,
        ])->render();
        $this->assertStringContainsString('hreflang="' . $expected . '"', $html);
        $this->assertStringNotContainsString('hreflang="sl_SI"', $html);
        $this->assertSame('sl_SI', Site::get('sl')->locale());
    }
}
