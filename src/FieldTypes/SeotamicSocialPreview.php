<?php

namespace Cnj\Seotamic\FieldTypes;

class SeotamicSocialPreview extends SeotamicType
{
    public function preload()
    {
        return [
            'seotamic' => $this->getSeotamicGlobals(),
            'config' => config('seotamic'),
            't' => [
                'demo_title' => __('seotamic::general.meta_field_demo_title'),
                'default_description' => __('seotamic::general.meta_field_default_description'),
            ]
        ];
    }
}
