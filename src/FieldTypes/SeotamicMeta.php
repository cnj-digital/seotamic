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
                        "type" => "general"
                    ]
            ];
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
                'title_title' => __('seotamic::general.meta_field_title_title'),
                'title_instructions' => __('seotamic::general.meta_field_title_instructions'),
                'prepend_label' => __('seotamic::general.meta_field_prepend_label'),
                'append_label' => __('seotamic::general.meta_field_append_label'),
                'description_title' => __('seotamic::general.meta_field_description_title'),
                'description_instructions' => __('seotamic::general.meta_field_description_instructions'),
                'default_description' => __('seotamic::general.meta_field_default_description'),
                'label_title' => __('seotamic::general.meta_field_label_title'),
                'label_custom' => __('seotamic::general.meta_field_label_custom'),
                'label_empty' => __('seotamic::general.meta_field_label_empty'),
                'preview_title' => __('seotamic::general.meta_field_preview_title'),

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

            if ($value['title']['prepend'] && $seotamic['title_prepend'] ) {
                $output['title'] = $seotamic['title_prepend'] . ' ' . $output['title'];
            }

            if ($value['title']['append'] && $seotamic['title_append'] ) {
                $output['title'] .= ' ' . $seotamic['title_append'];
            }
        }

        return $output;
    }
}
