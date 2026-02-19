<?php

namespace Cnj\Seotamic\Fieldtypes;

class SeotamicSearchPreview extends SeotamicType
{
    public function preload()
    {
        return [
            'seotamic' => $this->getSeotamicGlobals(),
            'config' => config('seotamic'),
        ];
    }
}
