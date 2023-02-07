<?php

namespace Cnj\Seotamic;

use Statamic\Events;

class Subscriber
{
    protected $blueprint;

    /**
     * List of subscribed events
     *
     * @var array
     */
    protected $events = [
        Events\EntryBlueprintFound::class => 'addFields',
    ];

    /**
     * Registers event listeners
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        foreach ($this->events as $event => $method) {
            $events->listen($event, self::class . '@' . $method);
        }
    }

    /**
     * Add SEOtamic fields to the collection blueprint
     *
     * @param mixed $event
     */
    public function addFields($event)
    {
        $this->blueprint = $event->blueprint;

        $ignoreBlueprints = config('seotamic.ignore_blueprints', []);

        if (in_array($this->blueprint->handle(), $ignoreBlueprints)) {
            return;
        }

        $metaFields = $this->getMetaFields();
        $socialFields = $this->getSocialFields();

        collect($metaFields)->each(function ($field) {
            $this->blueprint->ensureFieldInSection($field['handle'], $field['field'], __('seotamic::seo.tab_title'));
        });

        collect($socialFields)->each(function ($field) {
            $this->blueprint->ensureFieldInSection($field['handle'], $field['field'], __('seotamic::social.tab_title'));
        });
    }

    /**
     * Array of SEOtamic Meta fields
     *
     * @return array
     */
    private function getMetaFields()
    {
        return [
            [
                'handle' => 'seotamic_meta_section',
                'field' =>  [
                    'type' => 'section',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => __('seotamic::seo.section_meta_title'),
                    'instructions' => __('seotamic::seo.section_meta_instructions'),
                ]
            ],
            [
                'handle' => 'seotamic_meta',
                'field' =>  [
                    'type' => 'seotamic_meta',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => __('seotamic::seo.meta_title'),
                ]
            ],
            [
                'handle' => 'seotamic_canonical',
                'field' =>  [
                    'display' => __('seotamic::seo.canonical_title'),
                    'instructions' => __('seotamic::seo.canonical_instructions'),
                    'localizable' => true,
                    'listable' => 'hidden',
                    'input_type' => 'text',
                    'type' => 'text',
                ]
            ],
            [
                'handle' => 'robots_none',
                'field' =>  [
                    'type' => 'toggle',
                    'display' => __('seotamic::seo.robots_title'),
                    'instructions' => __('seotamic::seo.robots_instructions'),
                    'default' => false,
                ]
            ]
        ];
    }

    /**
     * Array of SEOtamic Meta fields
     *
     * @return array
     */
    private function getSocialFields()
    {
        return [
            [
                'handle' => 'seotamic_social_section',
                'field' =>  [
                    'type' => 'section',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => __('seotamic::social.section_social_title'),
                    'instructions' => __('seotamic::social.section_social_instructions'),
                ]
            ],
            [
                'handle' => 'seotamic_social',
                'field' =>  [
                    'type' => 'seotamic_social',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => __('seotamic::social.social_title'),
                ]
            ],
            [
                'handle' => 'seotamic_image',
                'field' =>  [
                    'container' => config('seotamic.container'),
                    'mode' => 'grid',
                    'restrict' => false,
                    'allow_uploads' => true,
                    'max_files' => 1,
                    'type' => 'assets',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => __('seotamic::social.social_image_title'),
                    'instructions' => __('seotamic::social.social_image_instructions'),
                ]
            ]
        ];
    }
}
