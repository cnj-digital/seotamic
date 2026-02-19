<?php

namespace Cnj\Seotamic;

use Statamic\Events;
use Illuminate\Support\Str;

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

        // Do not add fields to blueprints that are in the ignore list
        if (in_array($this->blueprint->handle(), $ignoreBlueprints)) {
            return;
        }

        // Do not add fields to the blueprint editor (this avoids adding the fields on blueprint changes)
        if (Str::containsAll(request()->path(), [config('statamic.cp.route', 'cp'), 'blueprints'])) {
            return;
        }

        // This should not be translated, so we can target them
        $this->blueprint->ensureFieldsInTab($this->getMetaFields(), 'SEO');
        $this->blueprint->ensureFieldsInTab($this->getSocialFields(), 'Social');
    }

    /**
     * Array of SEOtamic Meta fields
     *
     * @return array
     */
    private function getMetaFields()
    {
        return [
            'seotamic_meta_section' =>  [
                'type' => 'section',
                'localizable' => false,
                'listable' => 'hidden',
                'display' => __('seotamic::seo.section_meta_title'),
                'instructions' => __('seotamic::seo.section_meta_instructions'),
            ],
            'seotamic_meta' =>  [
                'type' => 'seotamic_meta',
                'localizable' => true,
                'listable' => 'hidden',
                'display' => ''
            ],
            'seotamic_canonical' =>  [
                'display' => __('seotamic::seo.canonical_title'),
                'instructions' => __('seotamic::seo.canonical_instructions'),
                'localizable' => true,
                'listable' => 'hidden',
                'input_type' => 'text',
                'type' => 'text',
            ],
            'seotamic_robots_none' =>  [
                'type' => 'toggle',
                'localizable' => true,
                'display' => __('seotamic::seo.robots_title'),
                'instructions' => __('seotamic::seo.robots_instructions'),
                'default' => false,
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
            'seotamic_social_section' =>  [
                'type' => 'section',
                'localizable' => true,
                'listable' => 'hidden',
                'display' => __('seotamic::social.section_social_title'),
                'instructions' => __('seotamic::social.section_social_instructions'),
            ],

            'seotamic_social' =>  [
                'type' => 'seotamic_social',
                'localizable' => true,
                'listable' => 'hidden',
                'display' => '',
            ],
            'seotamic_image' =>  [
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
        ];
    }
}
