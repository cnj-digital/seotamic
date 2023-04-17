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

    // Do we want to add a /sitemap.xml to the site?
    'sitemap' => true,

    // Default recommended field lengths.
    // They are just guidelines and can be ignored on the frontend
    'meta_title_length' => 60,
    'meta_description_length' => 160,
    'social_title_length' => 60,
    'social_description_length' => 60,

    // Key value store of blueprint names and the field name that contains the
    // social image. This is used to override the default social image behaviour
    // WARNING: API not final, subject to change in the future, do not use in production
    'social_image_override' => [
        'sample_blueprint' => "sample_field_name"
    ],
];
