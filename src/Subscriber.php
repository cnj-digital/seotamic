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
            $events->listen($event, self::class.'@'.$method);
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
        if (in_array($this->blueprint->handle(), $ignoreBlueprints))
            return;

        $fields = $this->getFields();

        collect($fields)->each(function($field) {
            $this->blueprint->ensureFieldInSection($field['handle'], $field['field'] , 'SEO');
        });
    }

    /**
     * Array of SEOtamic fields
     *
     * @return array
     */
    private function getFields()
    {
        return [[
                'handle' => 'seotamic_meta',
                'field' => [
                    'display' => 'Meta',
                    'listable' => 'hidden',
                    'type' => 'section',
                    'localizable'=> false
                ],
            ],
            [
                'handle' => 'seotamic_title',
                'field' =>  [
                    'options' => [
                        'title' => 'Title',
                        'custom' => 'Custom'
                    ],
                    'clearable' => false,
                    'multiple' => false,
                    'searchable' => true,
                    'taggable' => false,
                    'push_tags' => false,
                    'cast_booleans' => false,
                    'type' => 'select',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'default' => 'title',
                    'display' => 'Title',
                    'instructions' => 'It can be used to determine the title used on search engine results pages.'
                ]
            ],
            [
                'handle' => 'seotamic_custom_title',
                'field' =>  [
                    'input_type' => 'text',
                    'character_limit' => 100,
                    'type' => 'text',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Custom Meta Title',
                    'if' => [
                        'seotamic_title'=> 'equals custom'
                    ]
                ]
            ],
            [
                'handle' => 'seotamic_title_prepend',
                'field' =>  [
                    'type' => 'toggle',
                    'instructions' => 'Prepends to title the text set in General SEO settings',
                    'localizable' => false,
                    'default' => true,
                    'width' => 50,
                    'listable' => 'hidden',
                    'display' => 'Title prepend',
                ]
            ],
            [
                'handle' => 'seotamic_title_append',
                'field' =>  [
                    'type' => 'toggle',
                    'localizable' => false,
                    'instructions' => 'Appends to title the text set in General SEO settings',
                    'width' => 50,
                    'listable' => 'hidden',
                    'default' => true,
                    'display' => 'Title append',
                ]
            ],
            [
                'handle' => 'seotamic_meta_description',
                'field' =>  [
                    'options' => [
                        'empty' => 'Empty',
                        'general' => 'General',
                        'custom' => 'Custom',
                    ],
                    'clearable' => false,
                    'default' => 'empty',
                    'multiple' => false,
                    'searchable' => true,
                    'taggable' => false,
                    'push_tags' => false,
                    'cast_booleans' => false,
                    'type' => 'select',
                    'instructions' => 'It can be used to determine the text used under the title on search engine results pages. If empty, search engines will automatically generate this text.',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Meta description',
                ]
            ],
            [
                'handle' => 'seotamic_custom_meta_description',
                'field' =>  [
                    'input_type' => 'text',
                    'character_limit' => 200,
                    'type' => 'textarea',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Custom Meta description',
                    'if' => [
                        'seotamic_meta_description' => 'equals custom'
                    ]
                ]
            ],
            [
                'handle' => 'seotamic_canonical',
                'field' =>  [
                    'type' => 'link',
                    'instructions' => 'By default it will be set to the page url',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Canonical',
                ]
            ],
            [
                'handle' => 'seotamic_social',
                'field' =>  [
                    'type' => 'section',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Social',
                ]
            ],
            [
                'handle' => 'seotamic_open_graph_title',
                'field' =>  [
                    'options' => [
                        'title' => 'Title',
                        'general' => 'General',
                        'custom' => 'Custom',
                    ],
                    'clearable' => false,
                    'default' => 'title',
                    'multiple' => false,
                    'searchable' => true,
                    'taggable' => false,
                    'push_tags' => false,
                    'cast_booleans' => false,
                    'type' => 'select',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Open Graph title',
                ]
            ],
            [
                'handle' => 'seotamic_custom_open_graph_title',
                'field' =>  [
                    'input_type' => 'text',
                    'character_limit' => 100,
                    'type' => 'text',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Custom Open Graph title',
                    'if' => [
                        'seotamic_open_graph_title' => 'equals custom'
                    ]
                ]
            ],
            [
                'handle' => 'seotamic_open_graph_description',
                'field' =>  [
                    'options' => [
                        'meta' => 'Meta description',
                        'general' => 'General description',
                        'custom' => 'Custom',
                    ],
                    'clearable' => false,
                    'default' => 'general',
                    'multiple' => false,
                    'searchable' => true,
                    'taggable' => false,
                    'push_tags' => false,
                    'cast_booleans' => false,
                    'type' => 'select',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Open Graph description',
                ]
            ],
            [
                'handle' => 'seotamic_custom_open_graph_description',
                'field' =>  [
                    'input_type' => 'text',
                    'character_limit' => 200,
                    'type' => 'textarea',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Custom Open Graph description',
                    'if' => [
                        'seotamic_open_graph_description' => 'equals custom'
                    ]
                ]
            ],
            [
                'handle' => 'seotamic_twitter_title',
                'field' =>  [
                    'options' => [
                        'title' => 'Title',
                        'general' => 'General',
                        'custom' => 'Custom',
                    ],
                    'clearable' => false,
                    'default' => 'title',
                    'multiple' => false,
                    'searchable' => true,
                    'taggable' => false,
                    'push_tags' => false,
                    'cast_booleans' => false,
                    'type' => 'select',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Twitter title',
                ]
            ],
            [
                'handle' => 'seotamic_custom_twitter_title',
                'field' =>  [
                    'input_type' => 'text',
                    'character_limit' => 100,
                    'type' => 'text',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Custom Twitter title',
                    'if' => [
                        'seotamic_twitter_title' => 'equals custom'
                    ]
                ]
            ],
            [
                'handle' => 'seotamic_twitter_description',
                'field' =>  [
                    'options' => [
                        'meta' => 'Meta description',
                        'general' => 'General description',
                        'custom' => 'Custom',
                    ],
                    'clearable' => false,
                    'default' => 'general',
                    'multiple' => false,
                    'searchable' => true,
                    'taggable' => false,
                    'push_tags' => false,
                    'cast_booleans' => false,
                    'type' => 'select',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Twitter description',
                ]
            ],
            [
                'handle' => 'seotamic_custom_twitter_description',
                'field' =>  [
                    'input_type' => 'text',
                    'character_limit' => 200,
                    'type' => 'textarea',
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Custom Twitter description',
                    'if' => [
                        'seotamic_twitter_description' => 'equals custom'
                    ]
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
                    'localizable' => false,
                    'listable' => 'hidden',
                    'display' => 'Image',
                ]
            ]
        ];
    }
}
