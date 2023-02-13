<?php

namespace Cnj\Seotamic\FieldTypes;

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
            'robots' => $robots_none ? 'nofollow,noindex' : ''
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
        if (get_class($this->field->parent()) === "Statamic\Entries\Collection") {
            return "";
        }

        $url = $this->field->parent()->permalink;

        // First child option can return 404 if there is no first child
        if ($this->field->parent()->value('seotamic_canonical') !== null) {
            $url = $this->field->parent()->value('seotamic_canonical');

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

        return $url ?? "";
    }
}
