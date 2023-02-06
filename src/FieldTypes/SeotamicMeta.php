<?php

namespace Cnj\Seotamic\FieldTypes;

class SeotamicMeta extends SeotamicType
{
    public function preProcess(mixed $data): array
    {
        // TODO: Advanced checks if the data is valid
        if ($data === null) {
            return [
                "title" => [
                    "append" => true,
                    "prepend" => true,
                    "type" => "title",
                    "value" => "",
                    "custom_value" => ""
                ],
                "description" => [
                    "value" => "",
                    "custom_value" => "",
                    "type" => "empty"
                ]
            ];
        }

        // if title type is title, set it as the parent title
        if ($data['title']['type'] === "title") {
            $data['title']['value'] = $this->getTitle();
        }

        return $data;
    }

    public function preload()
    {
        return [
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
        $title = $this->getTitle();
        $seotamic = $this->getSeotamicGlobals();

        $output = [
            'title' => $title,
            'description' => $value['description']['value'] ?? '',
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
}
