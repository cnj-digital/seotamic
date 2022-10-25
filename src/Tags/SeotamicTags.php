<?php

namespace Cnj\Seotamic\Tags;

use Statamic\Facades\Image;
use Statamic\Facades\Asset;

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
        $output = "<title>{$this->title()}</title>";
        $output .= "<meta name=\"description\" content=\"{$this->description()}\">";

        // Output canonical if it's set
        if ($this->context->value('seotamic_canonical')) {
            $output .= "<link rel=\"canonical\" href=\"{$this->canonical()}\">";
        }

        $output .= $this->og();
        $output .= $this->twitter();

        return $output;
    }

    /**
     * SEO title
     *
     * By default it returns the Entry title. This can be overidden in the SEO menu.
     * We can also select if we want to append and/or prepend the title values
     * from the Global SEO settings.
     *
     * @return string
     */
    public function title()
    {
        if (! $this->context->value('seotamic_meta')) {
            return '';
        }

        return $this->context->value('seotamic_meta')['title'];
    }

    /**
     * SEO Meta Description
     *
     * By default it returns an empty string (so the Search engines generate this
     * by themselves).
     *
     * @return string
     */
    public function description()
    {
        if (! $this->context->value('seotamic_meta')) {
            return '';
        }

        return $this->context->value('seotamic_meta')['description'];
    }

    /**
     * Canonical URL
     *
     * By default it returns the current entry permalink. We can overide this
     * by selecting an entry or writing down the preferred URL in the SEO tab.
     *
     * @return string
     */
    public function canonical()
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

    /**
     * Open Graph output
     *
     * Outputs the meta fields with the selected social data. The image is a parameter that can be
     * overriden programatically if wanted.
     *
     * @param $image string Image name
     * @return string
     */
    public function og($image = null)
    {
        if (! $this->context->value('seotamic_social')) {
            return '';
        }

        // Open graph output disabled
        if (!$this->context->value('seotamic_social')['open_graph']) {
            return '';
        }

        $output = "<meta property=\"og:url\" content=\"{$this->canonical()}\">";

        if (array_key_exists('site_name', $this->context->value('seotamic_social'))) {
            $output .= "<meta property=\"og:site_name\" content=\"{$this->context->value('seotamic_social')['site_name']}\">";
        }

        $output .= "<meta property=\"og:title\" content=\"{$this->context->value('seotamic_social')['title']}\">";
        $output .= "<meta property=\"og:description\" content=\"{$this->context->value('seotamic_social')['description']}\">";

        // TODO: Check on multisite, here we have locale_full and site_locale
        $output .= "<meta property=\"og:locale\" content=\"{$this->context->value('site')->locale()}\">";

        // image
        if (array_key_exists('image', $this->context->value('seotamic_social'))) {
            $output .= "<meta property=\"og:image\" content=\"{$this->context->value('seotamic_social')['image']}\">";
        }

        return $output;
    }

    public function twitter($image = null)
    {
       if (! $this->context->value('seotamic_social')) {
            return '';
        }

        // Open graph output disabled
        if (!$this->context->value('seotamic_social')['twitter']) {
            return '';
        }

        $output = "<meta name=\"twitter:card\" content=\"summary_large_image\">";
        $output .= "<meta name=\"twitter:url\" content=\"{$this->canonical()}\">";
        $output .= "<meta name=\"twitter:title\" content=\"{$this->context->value('seotamic_social')['title']}\">";
        $output .= "<meta name=\"twitter:description\" content=\"{$this->context->value('seotamic_social')['description']}\">";

        // image
        if (array_key_exists('image', $this->context->value('seotamic_social'))) {
            $output .= "<meta name=\"og:twitter\" content=\"{$this->context->value('seotamic_social')['image']}\">";
        }

        return $output;
    }
}
