<?php

namespace Cnj\Seotamic\Fieldtypes;

use Statamic\Addons\Addon;
use Statamic\Facades\Addon as AddonFacade;
use Statamic\Entries\Entry;
use Cnj\Seotamic\File\File;
use Statamic\Fields\Fieldtype;
use Illuminate\Support\Facades\URL;

abstract class SeotamicType extends Fieldtype
{
    protected File $file;
    protected Addon $addon;

    public function __construct(File $file)
    {
        $this->file = $file;
        $this->addon = AddonFacade::get('cnj/seotamic');
    }

    /**
     * Get the title of the Entry
     *
     * @return string
     */
    protected function getTitle(): string
    {
        if ($this->field->parent() instanceof \Statamic\Entries\Collection) {
            return '';
        }

        return $this->field->parent()->value('title') ?? '';
    }

    /**
     * Get the permalink of the Entry
     *
     * @return string
     */
    protected function getPermalink(): string
    {
        $parent = $this->field()->parent();

        if (!$parent instanceof \Statamic\Contracts\Entries\Entry || !$parent->id()) {
            return '';
        }

        return $parent->absoluteUrl();
    }

    /**
     * Fetch the Seotamic global config from the Yaml file
     *
     * @return array
     */
    protected function getSeotamicGlobals(): array
    {
        $config = $this->file->read(false);

        // We make sure all the keys are present in the data
        $config = array_replace_recursive($this->defaultGlobalData(), $config);

        return $config;
    }

    /**
     * Default data for the Social fieldtype
     *
     * @return array
     */
    protected function defaultSocialData(): array
    {
        return [
            "title" => [
                "type" => "title",
                "value" => "",
                "custom_value" => ""
            ],
            "description" => [
                "value" => "",
                "custom_value" => "",
                "type" => "meta"
            ]
        ];
    }

    /**
     * Default data for the Meta fieldtype
     *
     * @return array
     */
    protected function defaultMetaData(): array
    {
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

    /**
     * Default data for the global config
     *
     * @return array
     */
    protected function defaultGlobalData(): array
    {
        return [
            "title_prepend" => "",
            "title_append" => "",
            "social_title" => "",
            "social_description" => "",
            "social_image" => "",
            "social_image_compress" => true,
            "open_graph_display" => true,
            "twitter_display" => true,
            "preview_domain" => "",
            "robots_none" => false,
            "social_image_override" => []
        ];
    }
}
