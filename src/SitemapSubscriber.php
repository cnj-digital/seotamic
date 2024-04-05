<?php

namespace Cnj\Seotamic;

use Statamic\Events\EntrySaved;
use Statamic\Events\EntryCreated;
use Statamic\Events\EntryDeleted;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;

class SitemapSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            EntrySaved::class => 'flushSitemapCache',
            EntryCreated::class => 'flushSitemapCache',
            EntryDeleted::class => 'flushSitemapCache',
        ];
    }

    /**
     * Flush the sitemap cache
     *
     */
    public function flushSitemapCache($event)
    {
        Cache::forget('seotamic_sitemap_should_be_indexed' . $event->entry->id());
        Cache::forget('seotamic_sitemap_sitemap_entry', $event->entry->id());
        Cache::forget('seotamic_sitemap');
    }
}
