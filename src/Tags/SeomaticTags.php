<?php

namespace Cnj\Seotamic\Tags;

use Statamic\Facades\Image;

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
        $title = $this->context->get('title');

        if (! $this->context->get('seotamic_title')) {
            return $title;
        }

        if ($this->context->get('seotamic_title')->value() === 'custom') {
            $title = $this->context->get('seotamic_custom_title');
        }

        if (array_key_exists('title_append', $this->values)) {
            if ($this->context->get('seotamic_title_append') && $this->values['title_append']) {
                $title .= " {$this->values['title_append']}";
            }
        }

        if (array_key_exists('title_prepend', $this->values)) {
            if ($this->context->get('seotamic_title_prepend') && $this->values['title_prepend']) {
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
        if (! $this->context->get('seotamic_meta_description')) {
            return '';
        }

        if ($this->context->get('seotamic_meta_description')->value() === 'empty') {
            return '';
        }

        if ($this->context->get('seotamic_meta_description')->value() === 'general') {
            return $this->values['meta_description'];
        }

        if ($this->context->get('seotamic_meta_description')->value() === 'custom') {
            return $this->context->get('seotamic_custom_meta_description');
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
        $url = $this->context->get('permalink');

        // TODO: use the env url? Check how permalink is generated

        // First child option can return 404 if there is no first child
        if ($this->context->get('seotamic_canonical') !== null && $this->context->get('seotamic_canonical') !== 404) {
            $url = $this->context->get('seotamic_canonical');

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

        $output = "<meta name=\"og:url\" content=\"{$this->canonical()}\">";

        if (array_key_exists('open_graph_site_name', $this->values)) {
            $output .= "<meta name=\"og:site_name\" content=\"{$this->values['open_graph_site_name']}\">";
        }

        $output .= "<meta name=\"og:title\" content=\"{$this->social_field('open_graph_title', $this->context->get('title'))}\">";
        $output .= "<meta name=\"og:description\" content=\"{$this->social_field('open_graph_description', $this->description())}\">";

        // TODO: Check on multisite, here we have locale_full and site_locale
        $output .= "<meta name=\"og:locale\" content=\"{$this->context->get('locale_full')}\">";

        // image
        $output .= "<meta name=\"og:image\" content=\"{$this->social_image($image)}\">";

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
        $output .= "<meta name=\"twitter:title\" content=\"{$this->social_field('twitter_title', $this->context->get('title'))}\">";
        $output .= "<meta name=\"twitter:description\" content=\"{$this->social_field('twitter_description', $this->description())}\">";

        // image
        $output .= "<meta name=\"og:twitter\" content=\"{$this->social_image($image)}\">";

        return $output;
    }

    private function social_field($name, $default) {
        $field = $default;

        if ($this->context->get("seotamic_${name}")) {
            if (($this->context->get("seotamic_${name}")->value() === 'general') && array_key_exists($name, $this->values)) {
                $field = $this->values[$name];
            } else if (($this->context->get("seotamic_${name}")->value() === 'custom')) {
                $field = $this->context->get("seotamic_custom_${name}");
            }
        }

        return $field;
    }

    private function social_image($image = null) {
        $image = '';
        $assets = $this->config->get('seotamic.container');

        if (substr($assets, -1) !== '/') {
            $assets = $assets . '/';
        }

        if ($image !== null) {
            $image = url(Image::manipulate('assets/' . $image, ['w' => 1200, 'q' => '70']));
        } else {
            if ($this->context->get('seotamic_image') && $this->context->get('seotamic_image') !== null) {
                // TODO: check if image exists
                $image = url(Image::manipulate('assets/' . $this->context->get('seotamic_image'), ['w' => 1200, 'q' => '70']));
            } else {
                if (array_key_exists('social_image', $this->values)) {
                    $image = url(Image::manipulate('assets/' . $this->values['social_image'], ['w' => 1200, 'q' => '70']));
                }
            }
        }

        return $image;
    }
}
