<?php

namespace Cnj\Seotamic\Fieldtypes;

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
