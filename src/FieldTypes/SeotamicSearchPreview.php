<?php

namespace Cnj\Seotamic\FieldTypes;

class SeotamicSearchPreview extends SeotamicType
{
    public function preload()
    {
        return [
            'seotamic' => $this->getSeotamicGlobals(),
            'config' => config('seotamic'),
            't' => [
                'demo_title' => __('seotamic::general.title_preview_placeholder_title'),
                'default_description' => __('seotamic::general.title_preview_placeholder_description'),
            ]
        ];
    }
}
