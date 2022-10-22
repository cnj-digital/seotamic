<?php

namespace Cnj\Seotamic\FieldTypes;

use Statamic\Support\Str;
use Cnj\Seotamic\File\File;
use Statamic\Facades\Asset;
use Statamic\Fields\Fieldtype;

class SeotamicSocial extends Fieldtype
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
            'meta' => $this->field->parent()->data()->get('seotamic_meta'),
            'seotamic' => $this->getSeotamicGlobals(),
            'social_image' => $this->field->parent()->data()->get('seotamic_image') ?? '',
            'config' => config('seotamic')
        ];
    }

    public function augment($value)
    {
        $title = $this->getTitle();
        $seotamic = $this->getSeotamicGlobals();
        $meta = $this->field->parent()->data()->get('seotamic_meta');
        $social_image = $this->getImage();

        $output = [
            'open_graph' => $seotamic['open_graph_display'],
            'twitter' => $seotamic['twitter_display'],
            'title' => $title,
            'description' => $seotamic['social_description'],
            'image' =>  $social_image
        ];

        if ($value['title']['type'] === 'custom') {
            $output['title'] = $value['title']['value'];
        }

        if ($value['title']['type'] === 'general') {
            $output['title'] = $seotamic['social_title'];
        }

        if ($value['description']['type'] === 'meta') {
            $output['description'] = $meta['description']['value'];
        }

        if ($value['description']['type'] === 'custom') {
            $output['description'] = $value['description']['value'];
        }

        return $output;
    }

    protected function getImage()
    {
        $seotamic = $this->getSeotamicGlobals();
        $social_image = $this->field->parent()->data()->get('seotamic_image');

        if (!$social_image) {
            $social_image = $seotamic['social_image'];
        }

        $asset = Asset::find(config('seotamic.container') . '::' . $social_image);

        if (!$asset) {
            return null;
        }

        if (! $asset->isImage()) {
            return null;
        }

        return url($asset);
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
