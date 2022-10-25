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
            $this->blueprint->ensureFieldInSection($field['handle'], $field['field'], 'SEO');
        });

        collect($socialFields)->each(function ($field) {
            $this->blueprint->ensureFieldInSection($field['handle'], $field['field'], 'Social');
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
                    'display' => 'Meta',
                ]
            ],
            [
                'handle' => 'seotamic_meta',
                'field' =>  [
                    'type' => 'seotamic_meta',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => 'SEOtamic Meta',
                ]
            ],
            [
                'handle' => 'seotamic_canonical',
                'field' =>  [
                    'instructions' => __('seotamic::general.canonical_instructions'),
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => __('seotamic::general.canonical_display'),
                    'input_type' => 'text',
                    'type' => 'text',
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
                    'display' => 'Social',
                ]
            ],
            [
                'handle' => 'seotamic_social',
                'field' =>  [
                    'type' => 'seotamic_social',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => 'SEOtamic Social',
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
                    'instructions' => __('seotamic::general.social_image_instructions'),
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => __('seotamic::general.social_image_display'),
                ]
            ]
        ];
    }
}
