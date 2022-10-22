<?php

namespace Cnj\Seotamic\FieldTypes;

use Cnj\Seotamic\File\File;
use Statamic\Fields\Fieldtype;

abstract class SeotamicType extends Fieldtype
{
    protected $categories = [];
    protected $selectableInForms = false;

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
        return $this->field->parent()->data()->get('title');
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
