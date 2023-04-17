<?php

namespace Cnj\Seotamic\FieldTypes;

use Statamic\Facades\Asset;

class SeotamicSocialPreview extends SeotamicType
{

    public function preload()
    {
        $globals = $this->getSeotamicGlobals();

        return [
            'seotamic' => $globals,
            'image' => $this->getImageUrl($globals['social_image']),
            'config' => config('seotamic'),
            't' => [
                'demo_title' => __('seotamic::general.meta_field_demo_title'),
                'default_description' => __('seotamic::general.meta_field_default_description'),
            ]
        ];
    }

    protected function getImageUrl($image): string
    {
        $asset = Asset::find(config('seotamic.container') . '::' . $image);

        if (!$asset || !$asset->isImage()) {
            return "";
        }

        return $asset->url();
    }
}
