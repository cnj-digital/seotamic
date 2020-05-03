<?php

namespace Cnj\Seomatic\Tags;

use Cnj\Seomatic\File\File;
use Statamic\Yaml\ParseException;
use Statamic\Tags\Tags as StatamicTags;
use Illuminate\Config\Repository as Config;

abstract class Tags extends StatamicTags
{
    /**
     * @var array
     */
    public $values;

    /**
     * @var Config
     */
    public $config;

    /**
     * Tags constructor.
     *
     * @param File $file
     * @param Config $config
     * @throws ParseException
     */
    public function __construct(File $file, Config $config)
    {
        $this->values = $file->read();

        $this->config = $config;
    }
}
