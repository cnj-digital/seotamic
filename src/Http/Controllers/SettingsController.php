<?php

namespace Cnj\Seotamic\Http\Controllers;

use Statamic\Facades\Config;
use Statamic\Support\Arr;
use Cnj\Seotamic\File\File;
use Statamic\Facades\Site;
use Illuminate\Http\Request;
use Statamic\Facades\Blueprint;
use Illuminate\Support\Facades\Session;
use Statamic\Http\Controllers\CP\CpController;

class SettingsController extends CpController
{
    /**
     * @var File
     */
    protected $file;

    public function __construct(Request $request, File $file)
    {
        $this->file = $file;

        parent::__construct($request);
    }

    public function index(Request $request)
    {
        $this->setLocale();

        $blueprint = $this->formBlueprint();
        $fields = $blueprint->fields();

        $values = $this->file->read(false);

        $fields = $fields->addValues($values);

        $fields = $fields->preProcess();

        return view('seotamic::settings', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
        ]);
    }

    public function update(Request $request)
    {
        $this->setLocale();

        $blueprint = $this->formBlueprint();
        $fields = $blueprint->fields()->addValues($request->all());

        // Perform validation. Like Laravel's standard validation, if it fails,
        // a 422 response will be sent back with all the validation errors.
        $fields->validate();

        // Perform post-processing. This will convert values the Vue components
        // were using into values suitable for putting into storage.
        $values = $fields->process()->values();

        $this->file->write($values->toArray());
    }

    /**
     * Since we are accessing the files via CP, we need to fetch the
     * current language via a session variable, and set the locale
     *
     * @return void
     */
    private function setLocale()
    {
        $this->file->setLocale(
            session('statamic.cp.selected-site') ?
                Site::get(session('statamic.cp.selected-site'))->locale() :
            Site::current()->locale()
        );
    }

    protected function formBlueprint()
    {
        return Blueprint::makeFromSections([
            'name' => [
                'display' => __('seotamic::general.meta_section'),
                'fields' => [
                    'section_title' => [
                        'type' => 'section',
                        'display' => __('seotamic::general.title'),
                        'instructions' => __('seotamic::general.title_instructions')
                    ],
                    'title_prepend' => [
                        'type' => 'text',
                        'character_limit' => '25',
                        'display' => __('seotamic::general.title_prepend'),
                        'instructions' => __('seotamic::general.title_prepend_instructions'),
                    ],
                    'title_append' => [
                        'type' => 'text',
                        'character_limit' => '25',
                        'display' => __('seotamic::general.title_append'),
                        'instructions' => __('seotamic::general.title_append_instructions'),
                    ],
                ],
            ],
            'social' => [
                'display' => __('seotamic::general.social_section'),
                'fields' => [
                    'social_image' => [
                        'type' => 'assets',
                        'container' => config('seotamic.container'),
                        'max_files' => 1,
                        'display' => __('seotamic::general.social_image'),
                        'instructions' => __('seotamic::general.social_image_instructions'),
                    ],
                    'open_graph_display' => [
                        'type' => 'toggle',
                        'display' => __('seotamic::general.social_open_graph'),
                        'instructions' => __('seotamic::general.social_open_graph_instructions'),
                        'default' => true,
                    ],
                    'twitter_display' => [
                        'type' => 'toggle',
                        'display' => __('seotamic::general.social_twitter'),
                        'instructions' => __('seotamic::general.social_twitter_instructions'),
                        'default' => true,
                    ],
                    'social_site_name' => [
                        'type' => 'text',
                        'character_limit' => '50',
                        'display' => __('seotamic::general.social_site_name'),
                        'instructions' => __('seotamic::general.social_site_name_instructions'),
                    ],
                    'social_title' => [
                        'type' => 'text',
                        'character_limit' => '60',
                        'display' => __('seotamic::general.social_title'),
                        'instructions' => __('seotamic::general.social_title_instructions'),
                    ],
                    'social_description' => [
                        'type' => 'textarea',
                        'character_limit' => '60',
                        'display' => __('seotamic::general.social_description'),
                        'instructions' => __('seotamic::general.social_description_instructions'),
                    ],
                ]
            ],
            'Settings' => [
                'display' => __('seotamic::general.settings_section'),
                'fields' => [
                    'preview_url' => [
                        'type' => 'text',
                        'character_limit' => '50',
                        'display' => __('seotamic::general.settings_preview_url'),
                        'instructions' => __('seotamic::general.settings_preview_url_instructions'),
                    ],
                    'preview_domain' => [
                        'type' => 'text',
                        'character_limit' => '60',
                        'display' => __('seotamic::general.settings_preview_domain'),
                        'instructions' => __('seotamic::general.settings_preview_domain_instructions'),
                    ]
                ]
            ],
        ]);
    }
}
