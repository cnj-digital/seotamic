<?php

namespace Cnj\Seotamic\FieldTypes;

use Cnj\Seotamic\Http\Controllers\SitemapController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SeotamicMeta extends SeotamicType
{
    public function preProcess(mixed $data): array
    {
        if ($data === null) {
            $data = [];
        }

        // We make sure all the keys are present in the data
        $data = array_replace_recursive($this->defaultMetaData(), $data);

        // if title type is title, set it as the parent title
        if ($data['title']['type'] === "title") {
            $data['title']['value'] = $this->getTitle();
        }

        return $data;
    }

    public function preload()
    {
        return [
            'permalink' => $this->getPermalink(),
            'title' => $this->getTitle(),
            'seotamic' => $this->getSeotamicGlobals(),
            'config' => config('seotamic'),
            't' => [
                'title_title' => __('seotamic::seo.meta_title_title'),
                'title_instructions' => __('seotamic::seo.meta_title_instructions'),
                'prepend_label' => __('seotamic::seo.meta_prepend_label'),
                'append_label' => __('seotamic::seo.meta_append_label'),
                'description_title' => __('seotamic::seo.meta_description_title'),
                'description_instructions' => __('seotamic::seo.meta_description_instructions'),
                'default_description' => __('seotamic::seo.meta_default_description'),
                'label_title' => __('seotamic::seo.meta_label_title'),
                'label_custom' => __('seotamic::seo.meta_label_custom'),
                'label_empty' => __('seotamic::seo.meta_label_empty'),
                'preview_title' => __('seotamic::seo.meta_preview_title'),
            ]
        ];
    }

    public function augment($value): array
    {
        // Non PRO edition, return empty array
        $edition = $this->addon ? $this->addon->edition() : 'lite';
        if (str_starts_with(request()->path(), config('statamic.api.route')) && $edition !== 'pro') {
            return [];
        }

        if ($value === null) {
            $value = [];
        }

        // We make sure all the keys are present in the data
        $value = array_replace_recursive($this->defaultMetaData(), $value);

        $seotamic = $this->getSeotamicGlobals();
        $robots_none = $seotamic['robots_none'] ? true : ($this->field->parent()->value('seotamic_robots_none') ?? false);

        $output = [
            'title' => $this->getTitle(),
            'description' => $value['description']['value'] ?? '',
            'canonical' => $this->getCanonical(),
            'robots' => $robots_none ? 'nofollow,noindex' : '',
            'related' => $this->getRelatedPages(),
        ];

        if (isset($value['title']) && isset($value['title']['value'])) {
            if ($value['title']['type'] === 'custom') {
                $output['title'] = $value['title']['value'];
            }

            if ($value['title']['prepend'] && array_key_exists('title_prepend', $seotamic) && $seotamic['title_prepend']) {
                $output['title'] = $seotamic['title_prepend'] . ' ' . $output['title'];
            }

            if ($value['title']['append'] && array_key_exists('title_append', $seotamic) && $seotamic['title_append']) {
                $output['title'] .= ' ' . $seotamic['title_append'];
            }
        }

        return $output;
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
        if ($this->field->parent() instanceof \Statamic\Entries\Collection) {
            return "";
        }

        $uri = $this->field->parent()->url;
        $config = config('seotamic');
        $base_url = env('APP_URL');

        if (isset($config['headless_mode']) && $config['headless_mode'] !== false) {
            $base_url = $config['headless_mode'];
        }

        // remove trailing slash from base url
        if (substr($base_url, -1) === '/') {
            $base_url = substr($base_url, 0, -1);
        }

        // First child option can return 404 if there is no first child
        if ($this->field->parent()->value('seotamic_canonical') !== null) {
            $url = $this->field->parent()->value('seotamic_canonical');

            // We have to make sure the given url is formatted correctly
            // If it's a relative path it must have a / prepended
            if (substr($url, 0, 4) !== 'http') {
                if (substr($url, 0, 1) !== '/') {
                    $url = '/' . $url;
                }

                $url = $base_url . $url;
            }
        } else {
            $url = $base_url . $uri;
        }

        return $url ?? "";
    }

    /**
     * Returns an array of related/alternate pages
     *
     * This pages are linked but available in different languages/options
     *
     * @return array
     */
    protected function getRelatedPages(): ?Collection
    {
        if ($this->field->parent() instanceof \Statamic\Entries\Collection) {
            return null;
        }

        $sitemapEntry = SitemapController::sitemapEntry($this->field->parent());

        if (!$sitemapEntry && !isset($sitemapEntry['alternates'])) {
            return null;
        }

        return $sitemapEntry['alternates'];
    }
}
