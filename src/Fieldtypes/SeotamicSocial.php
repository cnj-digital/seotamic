<?php

namespace Cnj\Seotamic\Fieldtypes;

use Statamic\Facades\Asset;
use Statamic\Facades\Image;

class SeotamicSocial extends SeotamicType
{
    public function preProcess(mixed $data): array
    {
        $globals = $this->getSeotamicGlobals();

        if ($data === null) {
            $data = [];
        }

        // We make sure all the keys are present in the data
        $data = array_replace_recursive($this->defaultSocialData(), $data);

        if ($this->field->parent() instanceof \Statamic\Entries\Entry) {
            $meta = $this->field->parent()->value('seotamic_meta');
        }

        // If the parent is a collection, we use defaults/empty values
        // This happens if it's a new entry
        if ($this->field->parent() instanceof \Statamic\Entries\Collection) {
            $meta = $this->defaultMetaData();
        }

        if ($meta === null) {
            $meta = $this->defaultMetaData();
        }

        if ($data['title']['type'] === "title") {
            if ($meta['title']['type'] === "custom") {
                $data['title']['value'] = $meta['title']['value'];
            } else {
                $data['title']['value'] = $this->getTitle();
            }
        }

        if ($data['title']['type'] === "general") {
            $data['title']['value'] = $globals['social_title'];
        }

        if ($data['description']['type'] === "meta") {
            if ($meta['description']['type'] === "custom") {
                $data['description']['value'] = $meta['description']['value'];
            } else {
                $data['description']['value'] = $globals['social_description'];
            }
        }

        if ($data['description']['type'] === "general") {
            $data['description']['value'] = $globals['social_description'];
        }

        return $data;
    }

    /**
     * Preload the Fieldtype frontend with the given extra data
     *
     * @return array
     */
    public function preload(): array
    {
        if ($this->field->parent() instanceof \Statamic\Entries\Entry) {
            $meta = $this->field->parent()->value('seotamic_meta');
        }

        // If the parent is a collection, we use defaults/empty values
        // This happens if it's a new entry
        if ($this->field->parent() instanceof \Statamic\Entries\Collection) {
            $meta = $this->defaultMetaData();
        }

        return [
            'permalink' => $this->getPermalink(),
            'title' => $this->getTitle(),
            'meta' => $meta,
            'seotamic' => $this->getSeotamicGlobals(),
            'social_image' => $this->getImage(),
            'config' => config('seotamic')
        ];
    }

    /**
     * Augment the return value of the field
     *
     * @return array
     */
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
        $value = array_replace_recursive($this->defaultSocialData(), $value);

        $title = $this->getTitle();
        $seotamic = $this->getSeotamicGlobals();
        $meta = $this->field->parent()->data()->get('seotamic_meta');
        $compress  = array_key_exists('social_image_compress', $seotamic) ? $seotamic['social_image_compress'] : true;
        $social_image = $this->getImage($compress);

        $output = [
            'open_graph' => array_key_exists('open_graph_display', $seotamic) ? $seotamic['open_graph_display'] : "",
            'twitter' => array_key_exists('twitter_display', $seotamic) ? $seotamic['twitter_display'] : "",
            'site_name' => array_key_exists('social_site_name', $seotamic) ? $seotamic['social_site_name'] : "",
            'title' => $title,
            'description' => array_key_exists('social_description', $seotamic) ? $seotamic['social_description'] : "",
            'image' =>  $social_image
        ];

        if (isset($value['title']) && isset($value['title']['value'])) {
            if ($value['title']['type'] === 'title' && isset($meta['title']['value']) && $meta['title']['type'] === 'custom') {
                $output['title'] = $meta['title']['value'];
            }

            if ($value['title']['type'] === 'custom') {
                $output['title'] = $value['title']['value'];
            }

            if ($value['title']['type'] === 'general') {
                $output['title'] = $seotamic['social_title'];
            }
        }

        if (isset($value['description']) && isset($value['description']['value'])) {
            if ($value['description']['type'] === 'meta' && isset($meta['description']['type']) && $meta['description']['type'] === 'custom') {
                $output['description'] = $meta['description']['value'];
            }

            if ($value['description']['type'] === 'custom') {
                $output['description'] = $value['description']['value'];
            }
        }

        return $output;
    }


    /**
     * Get the image absolute url for the social media
     *
     * @return string
     */
    protected function getImage($compress = false): string
    {
        if ($this->field->parent() instanceof \Statamic\Entries\Entry) {
            $blueprint = $this->field->parent()->blueprint();

            // check if image field is overriden in the config
            $social_image_override = config('seotamic.social_image_override', []);
            $image_field = array_key_exists($blueprint->handle, $social_image_override) ? $social_image_override[$blueprint->handle] : false;

            $social_image = $this->field->parent()->value('seotamic_image') ?? $this->field->parent()->data()->get($image_field);
        } else {
            // New Entry
            $social_image = '';
        }

        // Use the default seotamic image from globals if entry doesn't have one
        if (!$social_image) {
            $seotamic = $this->getSeotamicGlobals();
            $social_image = array_key_exists('social_image', $seotamic) ? $seotamic['social_image'] : '';
        }

        $asset = Asset::find(config('seotamic.container') . '::' . $social_image);

        if (!$asset || !$asset->isImage()) {
            return "";
        }

        if ($compress) {
            return url(Image::manipulate($asset, ['w' => 1200, 'h' => 630, 'q' => '70', 'fit' => 'crop']));
        }

        return $asset->absoluteUrl();
    }
}
