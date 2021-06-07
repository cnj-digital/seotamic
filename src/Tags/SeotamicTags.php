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
    public function index() {
        $output = "<title>{$this->title()}</title>";
        $output .= "<meta name=\"description\" content=\"{$this->description()}\">";
        $output .= "<link rel=\"canonical\" href=\"{$this->canonical()}\" />";
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
        // Set the page title as the default value
        $title = $this->context->value('title');

        // If Seotamic is not set for this public page, just return the title
        if (! $this->context->value('seotamic_title')) {
            return $title;
        }

        // If set to custom, use the custom title
        if ($this->context->raw('seotamic_title') === 'custom') {
            $title = $this->context->value('seotamic_custom_title');
        }

        if (array_key_exists('title_append', $this->values)) {
            if ($this->context->raw('seotamic_title_append') && $this->values['title_append']) {
                $title .= " {$this->values['title_append']}";
            }
        }

        if (array_key_exists('title_prepend', $this->values)) {
            if ($this->context->raw('seotamic_title_prepend') && $this->values['title_prepend']) {
                $title = "{$this->values['title_prepend']} {$title}";
            }
        }

        return $title;
    }

    /**
     * SEO Meta Description
     *
     * By default it returns an empty string (so the Search engines generate this
     * by themselves).
     *
     * @return string
     */
    public function description() {
        if (! $this->context->raw('seotamic_meta_description')) {
            return '';
        }

        if ($this->context->raw('seotamic_meta_description') === 'empty') {
            return '';
        }

        if ($this->context->raw('seotamic_meta_description') === 'general') {
            return $this->values['meta_description'];
        }

        if ($this->context->raw('seotamic_meta_description') === 'custom') {
            return $this->context->raw('seotamic_custom_meta_description');
        }

        return '';
    }

    /**
     * Canonical URL
     *
     * By default it returns the current entry permalink. We can overide this
     * by selecting an entry or writing down the preferred URL in the SEO tab.
     *
     * @return string
     */
    public function canonical() {
        $url = $this->context->raw('permalink');

        // TODO: use the env url? Check how permalink is generated

        // First child option can return 404 if there is no first child
        if ($this->context->raw('seotamic_canonical') !== null && $this->context->raw('seotamic_canonical') !== 404) {
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
    public function og($image = null) {
        if (! array_key_exists('open_graph_display', $this->values)) {
            return '';
        }

        if (! $this->values['open_graph_display']) {
            return '';
        }

        $output = "<meta property=\"og:url\" content=\"{$this->canonical()}\">";

        if (array_key_exists('open_graph_site_name', $this->values)) {
            $output .= "<meta property=\"og:site_name\" content=\"{$this->values['open_graph_site_name']}\">";
        }

        $output .= "<meta property=\"og:title\" content=\"{$this->social_field('open_graph_title', $this->context->raw('title'))}\">";
        $output .= "<meta property=\"og:description\" content=\"{$this->social_field('open_graph_description', $this->description())}\">";

        // TODO: Check on multisite, here we have locale_full and site_locale
        $output .= "<meta property=\"og:locale\" content=\"{$this->context->value('site')->locale()}\">";

        // image
        $output .= "<meta property=\"og:image\" content=\"{$this->social_image($image)}\">";

        return $output;
    }

    // TODO: Add check if title/desc same as OG
    public function twitter($image = null) {
        if (! array_key_exists('twitter_display', $this->values)) {
            return '';
        }

        if (! $this->values['twitter_display']) {
            return '';
        }

        $output = "<meta name=\"twitter:card\" content=\"summary_large_image\">";
        $output .= "<meta name=\"twitter:url\" content=\"{$this->canonical()}\">";
        $output .= "<meta name=\"twitter:title\" content=\"{$this->social_field('twitter_title', $this->context->raw('title'))}\">";
        $output .= "<meta name=\"twitter:description\" content=\"{$this->social_field('twitter_description', $this->description())}\">";

        // image
        $output .= "<meta name=\"og:twitter\" content=\"{$this->social_image($image)}\">";

        return $output;
    }

    private function social_field($name, $default) {
        $field = $default;

        if ($this->context->raw("seotamic_${name}")) {
            if (($this->context->raw("seotamic_${name}") === 'general') && array_key_exists($name, $this->values)) {
                $field = $this->values[$name];
            } else if (($this->context->raw("seotamic_${name}") === 'custom')) {
                $field = $this->context->raw("seotamic_custom_${name}");
            }
        }

        return $field;
    }

    private function social_image($image = null) {
        $image = '';
        $assets = $this->config->get('seotamic.container');

        if (substr($assets, -1) === '/') {
            $assets = substr($assets, 0, -1);
        }

        if (! empty($image)) {
            $image = $this->image_manipulation($assets, $image);
        } else {
            if (! empty($this->context->raw('seotamic_image'))) {
                $image = $this->image_manipulation($assets, $this->context->raw('seotamic_image'));
            } else {
                if (array_key_exists('social_image', $this->values)) {

                    if (! empty($this->values['social_image'])) {
                        $image = $this->image_manipulation($assets, $this->values['social_image']);
                    }
                }
            }
        }

        return $image;
    }

    private function image_manipulation($assets, $image) {
        $asset = Asset::find($assets. '::'.$image);

        if (! $asset) {
            return null;
        }

        if (! $asset->isImage()) {
            return null;
        }

        return url($asset->manipulate(['w' => 1200, 'q' => '70']));
    }
}
