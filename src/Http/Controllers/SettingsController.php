<?php

namespace Cnj\Seotamic\Http\Controllers;

use Cnj\Seotamic\File\File;
use Statamic\Facades\Site;
use Illuminate\Http\Request;
use Statamic\Facades\Blueprint;
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

        return view('seotamic::cp.settings', [
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
        return Blueprint::makeFromTabs([
            'name' => [
                'display' => __('seotamic::general.title_title'),
                'fields' => [
                    'section_title' => [
                        'type' => 'section',
                        'display' => __('seotamic::general.title_section_title'),
                        'instructions' => __('seotamic::general.title_section_instructions')
                    ],
                    'title_prepend' => [
                        'type' => 'text',
                        'character_limit' => '25',
                        'display' => __('seotamic::general.title_prepend_title'),
                        'instructions' => __('seotamic::general.title_prepend_instructions'),
                    ],
                    'title_append' => [
                        'type' => 'text',
                        'character_limit' => '25',
                        'display' => __('seotamic::general.title_append_title'),
                        'instructions' => __('seotamic::general.title_append_instructions'),
                    ],
                    'search_preview' => [
                        'type' => 'seotamic_search_preview',
                        'display' => __('seotamic::general.title_preview_title'),
                    ],
                ],
            ],
            'social' => [
                'display' => __('seotamic::general.social_title'),
                'fields' => [
                    'social_site_name' => [
                        'type' => 'text',
                        'character_limit' => '50',
                        'display' => __('seotamic::general.social_site_name_title'),
                        'instructions' => __('seotamic::general.social_site_name_instructions'),
                    ],
                    'section_social' => [
                        'type' => 'section',
                        'display' => __('seotamic::general.social_info_title'),
                        'instructions' => __('seotamic::general.social_info_instructions')
                    ],
                    'social_title' => [
                        'type' => 'text',
                        'character_limit' => '60',
                        'display' => __('seotamic::general.social_title_title'),
                        'instructions' => __('seotamic::general.social_title_instructions'),
                    ],
                    'social_description' => [
                        'type' => 'textarea',
                        'character_limit' => '60',
                        'display' => __('seotamic::general.social_description_title'),
                        'instructions' => __('seotamic::general.social_description_instructions'),
                    ],
                    'social_image' => [
                        'type' => 'assets',
                        'container' => config('seotamic.container'),
                        'max_files' => 1,
                        'display' => __('seotamic::general.social_image_title'),
                        'instructions' => __('seotamic::general.social_image_instructions'),
                    ],
                    'social_image_compress' => [
                        'type' => 'toggle',
                        'display' => __('seotamic::general.social_image_compress_title'),
                        'instructions' => __('seotamic::general.social_image_compress_instructions'),
                        'default' => true,
                    ],
                    'social_preview' => [
                        'type' => 'seotamic_social_preview',
                        'display' => __('seotamic::general.social_preview_title'),
                    ],
                    'open_graph_display' => [
                        'type' => 'toggle',
                        'display' => __('seotamic::general.social_open_graph_title'),
                        'instructions' => __('seotamic::general.social_open_graph_instructions'),
                        'default' => true,
                    ],
                    'twitter_display' => [
                        'type' => 'toggle',
                        'display' => __('seotamic::general.social_twitter_title'),
                        'instructions' => __('seotamic::general.social_twitter_instructions'),
                        'default' => true,
                    ],
                ]
            ],
            'Settings' => [
                'display' => __('seotamic::general.settings_title'),
                'fields' => [
                    'preview_domain' => [
                        'type' => 'text',
                        'character_limit' => '50',
                        'prepend' => 'https://',
                        'display' => __('seotamic::general.settings_preview_domain_title'),
                        'instructions' => __('seotamic::general.settings_preview_domain_instructions'),
                    ],
                    'robots_none' => [
                        'type' => 'toggle',
                        'display' => __('seotamic::general.settings_robots_title'),
                        'instructions' => __('seotamic::general.settings_robots_instructions'),
                        'default' => false,
                    ],
                ]
            ],
        ]);
    }
}
