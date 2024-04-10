<?php

namespace Cnj\Seotamic\Http\Controllers;

use Statamic\Entries\Entry;
use Statamic\Facades\Addon;
use Statamic\Facades\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $addon = Addon::get('cnj/seotamic');
        abort_unless(config('seotamic.sitemap') && $addon->edition() === 'pro', 404);

        $content = Cache::rememberForever('seotamic_sitemap', function () {
            $entries = Collection::all()
                ->flatMap(function ($collection) {
                    return $collection->cascade('seo') !== false && $collection->cascade('social') !== false
                        ? $collection->queryEntries()->get()
                        : collect();
                })
                ->filter(function (Entry $entry) {
                    return self::shouldBeIndexed($entry);
                })
                ->map(function (Entry $entry) {
                    return self::sitemapEntry($entry);
                });

            return view('seotamic::sitemap', [
                'header' => '<?xml version="1.0" encoding="UTF-8"?>',
                'entries' => $entries,
            ])->render();
        });

        return response($content)->header('Content-Type', 'text/xml');
    }

    private static function shouldBeIndexed(Entry $entry)
    {
        return Cache::rememberForever(
            'seotamic_sitemap_should_be_indexed' . $entry->id(),
            function () use ($entry) {
                if (is_null($entry->uri())) {
                    return false;
                }

                if ($entry->status() !== 'published') {
                    return false;
                }

                if (!empty($entry->seotamic_meta['robots'])) {
                    return false;
                }

                return true;
            }
        );
    }

    public static function sitemapEntry(Entry $entry)
    {
        return Cache::rememberForever(
            'seotamic_sitemap_entry' . $entry->id(),
            function () use ($entry) {
                return [
                    'loc' => self::entryAbsoluteUrl($entry),
                    'lastmod' => $entry->lastModified()->toAtomString(),
                    'alternates' => $entry->sites()
                        ->map(fn ($site) => $entry->in($site))
                        ->filter(function (?Entry $page) use ($entry) {
                            return $page && self::shouldBeIndexed($page) && $page->id() !== $entry->id();
                        })
                        ->map(function (Entry $entry) {
                            return array(
                                'lang' => $entry->locale,
                                'href' => self::entryAbsoluteUrl($entry)
                            );
                        })->values()
                ];
            }
        );
    }

    private static function entryAbsoluteUrl(Entry $entry)
    {
        // if seotamic headless mode is set, use that domain, otherwise use the entry absoluteUrl
        if (config('seotamic.headless_mode')) {
            return config('seotamic.headless_mode') . $entry->url();
        }

        return $entry->absoluteUrl();
    }
}
