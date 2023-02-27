<?php

namespace Cnj\Seotamic\Http\Controllers;

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
            ->filter(function ($entry) {
                return $entry->status() === 'published';
            })
            ->reject(function ($entry) {
                return is_null($entry->uri());
            })
            ->map(function ($entry) {
                return [
                    'loc' => $entry->absoluteUrl(),
                    'lastmod' => $entry->lastModified()->toAtomString(),
                ];
            });

        $content = Cache::remember('seotamic_sitemap', 1, function () use ($entries) {
            return view('seotamic::sitemap', [
                'entries' => $entries,
            ])->render();
        });

        return response($content)->header('Content-Type', 'text/xml');
    }
}
