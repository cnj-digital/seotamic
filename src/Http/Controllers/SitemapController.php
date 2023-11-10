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
                return [
                    'loc' => $entry->absoluteUrl(),
                    'lastmod' => $entry->lastModified()->toAtomString(),
                    'alternates' => $entry->sites()
                        ->map(fn ($site) => $entry->in($site))
                        ->filter(function (?Entry $entry) {
                            return $entry && self::shouldBeIndexed($entry);
                        })
                        ->map(function (Entry $entry) {
                            return array(
                                'lang' => $entry->locale,
                                'href' => $entry->absoluteUrl()
                            );
                        })
                ];
            });

        $content = Cache::remember('seotamic_sitemap', 1, function () use ($entries) {
            return view('seotamic::sitemap', [
                'header' => '<?xml version="1.0" encoding="UTF-8"?>',
                'entries' => $entries,
            ])->render();
        });

        return response($content)->header('Content-Type', 'text/xml');
    }

    private static function shouldBeIndexed(Entry $entry): bool
    {
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
}
