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

        $fields = $this->getFields();

        collect($fields)->each(function ($field) {
            $this->blueprint->ensureFieldInSection($field['handle'], $field['field'], 'SEO');
        });
    }

    /**
     * Array of SEOtamic fields
     *
     * @return array
     */
    private function getFields()
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
                    'instructions' => 'By default it can be left empty.',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => 'Canonical',
                    'input_type' => 'text',
                    'type' => 'text',
                ]
            ],
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
                    'instructions' => 'If not set, the general image will be used. It\'s best to use an image with a 1.91:1 aspect ratio that is at least 1200px wide for universal support. The image will be resized to 1200 width.',
                    'localizable' => true,
                    'listable' => 'hidden',
                    'display' => 'Image',
                ]
            ]
        ];
    }
}
