<?php

namespace Cnj\Seotamic\FieldTypes;

use Statamic\Facades\Asset;
use Illuminate\Support\Facades\Log;

class SeotamicSocial extends SeotamicType
{
    public function preProcess(mixed $data): array
    {
        // TODO: Advanced checks if the data is valid

        if ($data === null) {
            return [
                    "title" => [
                        "type" => "title",
                        "value" => "",
                        "custom_value" => ""
                    ],
                    "description" => [
                        "value" => "",
                        "custom_value" => "",
                        "type" => "general"
                    ]
            ];
        }

        if ($data['title']['type'] === "title") {
            $data['title']['value'] = $this->getTitle();
        }

        if ($data['title']['type'] === "general") {
            $globals = $this->getSeotamicGlobals();
            $data['title']['value'] = $globals['social_title'];
        }

        if ($data['description']['type'] === "general") {
            $globals = $this->getSeotamicGlobals();
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
        if (get_class($this->field->parent()) === "Statamic\Entries\Entry") {
            $meta = $this->field->parent()->value('seotamic_meta');
            $social_image = $this->field->parent()->value('seotamic_image');
        }

        // If the parent is a collection, we use defaults/empty values
        if (get_class($this->field->parent()) === "Statamic\Entries\Collection") {
            $meta = [
                "title" => [
                    "type" => "title",
                    "value" => "",
                    "custom_value" => ""
                ],
                "description" => [
                    "value" => "",
                    "custom_value" => "",
                    "type" => "general"
                ]
            ];
            $social_image = '';
        }

        return [
            'title' => $this->getTitle(),
            'meta' => $meta,
            'seotamic' => $this->getSeotamicGlobals(),
            'social_image' => $social_image,
            'config' => config('seotamic'),
            't' => [
                'title_title' => __('seotamic::general.social_field_title_title'),
                'title_instructions' => __('seotamic::general.social_field_title_instructions'),
                'description_title' => __('seotamic::general.social_field_description_title'),
                'description_instructions' => __('seotamic::general.social_field_description_instructions'),
                'label_title' => __('seotamic::general.social_field_label_title'),
                'label_custom' => __('seotamic::general.social_field_label_custom'),
                'label_general' => __('seotamic::general.social_field_label_general'),
                'label_meta' => __('seotamic::general.social_field_label_meta'),
                'preview_title' => __('seotamic::general.social_field_preview_title'),
            ]
        ];
    }

    /**
     * Augment the return value of the field
     *
     * @return array
     */
    public function augment($value): array
    {
        $title = $this->getTitle();
        $seotamic = $this->getSeotamicGlobals();
        $meta = $this->field->parent()->data()->get('seotamic_meta');
        $social_image = $this->getImage();

        $output = [
            'open_graph' => array_key_exists('open_graph_display', $seotamic) ? $seotamic['open_graph_display'] : "",
            'twitter' => array_key_exists('twitter_display', $seotamic) ? $seotamic['twitter_display'] : "",
            'site_name' => array_key_exists('social_site_name', $seotamic) ? $seotamic['social_site_name'] : "",
            'title' => $title,
            'description' => array_key_exists('social_description', $seotamic) ? $seotamic['social_description'] : "",
            'image' =>  $social_image
        ];

        if (isset($value['title']) && isset($value['title']['value'])) {
            if ($value['title']['type'] === 'custom') {
                $output['title'] = $value['title']['value'];
            }

            if ($value['title']['type'] === 'general') {
                $output['title'] = $seotamic['social_title'];
            }
        }

        if (isset($value['description']) && isset($value['description']['value'])) {
            if ($value['description']['type'] === 'meta') {
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
    protected function getImage(): string
    {
        $social_image = $this->field->parent()->data()->get('seotamic_image');

        // Use the default seotamic image from globals if entry doesn't have one
        if (!$social_image) {
            $seotamic = $this->getSeotamicGlobals();
            $social_image = array_key_exists('social_image', $seotamic) ? $seotamic['social_image'] : '';
        }

        $asset = Asset::find(config('seotamic.container') . '::' . $social_image);

        if (!$asset) {
            return "";
        }

        if (! $asset->isImage()) {
            return "";
        }

        return url($asset);
    }
}
