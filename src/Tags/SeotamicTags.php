<?php

namespace Cnj\Seotamic\Tags;

use Illuminate\Support\Arr;
use Statamic\Facades\Blink;

class SeotamicTags extends Tags
{
    /**
     * @var string
     */
    protected static $handle = 'seotamic';

    /**
     * Returns the whole set of tags
     *
     * @return string
     */
    public function index()
    {
        return view('seotamic::all', $this->values());
    }

    /**
     * Returns only the requested tag as a string.
     *
     * This is useful when you want to use the tag in a template.
     * If the tag does not exist, it returns null.
     *
     * @return string | null
     */
    public function wildcard()
    {
        return Arr::get($this->values(), str_replace(":", ".", $this->method));
    }

    /**
     * Outputs only the Open Graph Tags
     */
    public function og()
    {
        return view('seotamic::partials._og', $this->values());
    }

    /**
     * Outputs only the Twitter tags
     */
    public function twitter()
    {
        return view('seotamic::partials._twitter', $this->values());
    }

    /**
     * Canonical URL
     *
     * By default it returns the current entry permalink. We can overide this
     * by selecting an entry or writing down the preferred URL in the SEO tab.
     *
     * @return string
     */
    protected function getCanonical(): string
    {
        $url = $this->context->raw('permalink');

        // First child option can return 404 if there is no first child
        if ($this->context->raw('seotamic_canonical') !== null) {
            $url = $this->context->value('seotamic_canonical');

            // We have to make sure the given url is formatted correctly
            // If it's a relative path it must have a / prepended
            // And we expect the .env APP_URL doesn't
            if (substr($url, 0, 4) !== 'http') {
                $appUrl = env('APP_URL');

                if (substr($appUrl, -1) === '/') {
                    $appUrl = substr($appUrl, 0, -1);
                }

                if (substr($url, 0, 1) !== '/') {
                    $url = '/' . $url;
                }

                $url = $appUrl . $url;
            }
        }

        return $url;
    }

    protected function values(): array
    {
        return Blink::once('seotamic::values', function () {
            return [
                'meta' => $this->context->value('seotamic_meta'),
                'social' => $this->context->value('seotamic_social'),
                'canonical' => $this->getCanonical(),
                'robots' => $this->values['robots_none'] ?? false
            ];
        });
    }
}
