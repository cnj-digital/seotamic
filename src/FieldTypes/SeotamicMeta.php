<?php

namespace Cnj\Seotamic\FieldTypes;

use Cnj\Seotamic\File\File;
use Statamic\Fields\Fieldtype;
use Statamic\Support\Str;

class SeotamicMeta extends Fieldtype
{
    protected $categories = [];
    protected $selectableInForms = false;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    protected function configFieldItems(): array
    {
        return [];
    }

    public function preload()
    {
        return [
            'title' => $this->getTitle(),
            'seotamic' => $this->getSeotamicGlobals(),
            'config' => config('seotamic')
        ];
    }

    public function augment($value)
    {
        $title = $this->getTitle();
        $seotamic = $this->getSeotamicGlobals();

        $output = [
            'title' => $title,
            'description' => $value['description']['value'] ?? '',
        ];

        if ($value['title']['type'] === 'custom') {
            $output['title'] = $value['title']['value'];
        }

        if ($value['title']['prepend'] && $seotamic['title_prepend'] ) {
            $output['title'] = $seotamic['title_prepend'] . ' ' . $output['title'];
        }

        if ($value['title']['append'] && $seotamic['title_append'] ) {
            $output['title'] .= ' ' . $seotamic['title_append'];
        }

        return $output;
    }

    protected function getTitle(): string
    {
        return $this->field->parent()->data()->get('title');
    }

    protected function getSeotamicGlobals()
    {
        return $this->file->read(false);
    }
}
