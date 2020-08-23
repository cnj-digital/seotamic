<?php

namespace Cnj\Seotamic\File;

use Statamic\Yaml\Yaml;
use Statamic\Sites\Sites;
use Statamic\Filesystem\Manager;
use Statamic\Yaml\ParseException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Config\Repository as Config;

class File
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var Yaml
     */
    protected $yaml;

    /**
     * @var string
     */
    protected $configFile;

    /**
     * @var string
     */
    protected $locale;

    /**
     * File constructor.
     *
     * @param Config $config
     * @param Manager $manager
     * @param Yaml $yaml
     */
    public function __construct(Config $config, Manager $manager, Yaml $yaml, Sites $sites) {
        $this->manager = $manager;
        $this->yaml = $yaml;
        $this->configFile = $config->get('seotamic.file');

        // Set default locale value
        $this->locale = $sites->current()->locale();
    }

    /**
     * Reads the YAML settings file and returns an array of settings
     *
     * Read is done from the cache, if the appropriate key exists and $fromCache
     * is set to true (default).
     *
     * @param   bool fromCache
     * @return  array
     * @throws  ParseException
     */
    public function read($fromCache = true) {
        if ($fromCache && Cache::has($this->cacheKey())) {
            return Cache::get($this->cacheKey());
        }

        $values = $this->yaml->parse($this->manager->disk()->get($this->file()));
        Cache::forever($this->cacheKey(), $values);

        return $values;
    }

    /**
     * Writes the given array to the Yaml settings file and clears the cache for this key
     *
     * @param array $values
     * @return void
     */
    public function write($values) {
        Cache::forget($this->cachekey());

        $this->manager->disk()->put($this->file(), $this->yaml->dump($values));
    }

    /**
     * Set the locale value
     *
     * @param string $value
     * @return void
     */
    public function setLocale($value) {
        $this->locale = $value;
    }

    /**
     * Returns the file name with the locale appended
     *
     * @return string
     */
    private function file() {
        return base_path("content/{$this->configFile}_{$this->locale}.yaml");
    }

    /**
     * Returns the cache key with the locale appended
     *
     * @return string
     */
    private function cacheKey() {
        return "seotamic_{$this->locale}";
    }
}
