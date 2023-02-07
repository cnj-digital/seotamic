<?php

namespace Cnj\Seotamic\FieldTypes;

use Statamic\Entries\Entry;
use Cnj\Seotamic\File\File;
use Statamic\Fields\Fieldtype;
use Illuminate\Support\Facades\URL;

abstract class SeotamicType extends Fieldtype
{
    protected $categories = [];
    protected $selectableInForms = false;
    protected File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Set the Type config field values, to be set from the CP
     *
     * @return array
     */
    protected function configFieldItems(): array
    {
        return [];
    }

    /**
     * Get the title of the Entry
     *
     * @return string
     */
    protected function getTitle(): string
    {
        if (get_class($this->field->parent()) === "Statamic\Entries\Collection") {
            return "";
        }

        return $this->field->parent()->value('title');
    }

    /**
     * Get the permalink of the Entry
     *
     * @return string
     */
    protected function getPermalink(): string
    {
        if (get_class($this->field->parent()) === "Statamic\Entries\Collection") {
            return "";
        }

        return URL::to("/") . Entry::find($this->field->parent()->id)->url();
    }

    /**
     * Fetch the Seotamic global config from the Yaml file
     *
     * @return array
     */
    protected function getSeotamicGlobals(): array
    {
        return $this->file->read(false);
    }
}
