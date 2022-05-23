<?php

return [
    // The filename where the settings will be stored. On a multilingual site,
    // each locale will be stored in a seprate file. If this configuration
    // is changed after the first save, the old settings will have to be
    // manually imported into the new file.
    'file' => 'seotamic',

    // Social images asset container. It must exist in Statamic.
    // A sensible default is set.
    'container' => 'assets',

    // A list of blueprints where the SEO fields will not be injected.
    // By default this list is empty.
    'ignore_blueprints' => [],
];
