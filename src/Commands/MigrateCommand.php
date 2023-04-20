<?php

namespace Cnj\Seotamic\Commands;

use Cnj\Seotamic\File\File;
use Statamic\Facades\Site;
use Statamic\Facades\Entry;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Statamic\Facades\Collection;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seotamic:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates SEOtamic data from v2 to v3';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(File $file)
    {
        $this->info('Migrating SEOtamic data from v2 to v3');
        $this->newLine();

        $this->warn('Make sure you have a backup of your data before you continue!');

        if (!$this->confirm('Do you want to continue?', true)) {
            $this->info('Migration aborted');
            return;
        }
        $this->newLine();

        // 1. Go through all blueprints and check if they have the SEOtamic fields
        // If they do, ask the user if he wants to remove them (since they should not be there)
        // If they don't, add them to the skipped list
        $this->info('1. Migrating blueprints…');
        $collections = Collection::all();

        $blueprints = $collections->flatMap(function ($collection) {
            return $collection->entryBlueprints();
        });

        $blueprintsWithSeotamicFields = $blueprints->filter(function ($blueprint) {
            return $blueprint->hasSection('SEO') || $blueprint->hasSection('Social');
        });

        $skippedBlueprints = [];

        // Loop through all blueprintsWithSeotamicFields and ask the user if he wants to remove them
        $blueprintsWithSeotamicFields->each(function ($blueprint) use (&$skippedBlueprints) {
            $this->info('Found blueprint with SEOtamic fields: ' . $blueprint->handle());

            if ($this->confirm('Do you want to remove the SEOtamic fields from this blueprint?', true)) {
                $this->info('Removing SEOtamic fields from blueprint: ' . $blueprint->handle());
                $blueprint->removeSection('SEO');
                $blueprint->removeSection('Social');
                $blueprint->save();
            } else {
                $this->info('Skipping blueprint: ' . $blueprint->handle());
                $skippedBlueprints[] = $blueprint->handle();
            }
        });

        $this->info('…done!');
        $this->newLine();

        // 2. Go through all entries (all languages) and check if they have the SEOtamic fields
        // If they do, convert them to the v3 format and remove the old fields
        // If they dont, do nothing
        $this->info('2. Migrating entries…');

        $entries = Entry::all();

        // on each entry we chech if it has any field with a seotamic_ prefix, if it has we migrate them
        // This also finds v3 fields, but it doesn't matter since we do not overwrite them
        $entriesWithSeotamicFields = $entries->filter(function ($entry) {
            return $entry->values()->keys()->some(function ($key) {
                return Str::startsWith($key, 'seotamic_');
            });
        });

        $seotamicMeta = [
            "title" => [
                "append" => true,
                "prepend" => true,
                "type" => "title",
                "value" => "",
                "custom_value" => ""
            ],
            "description" => [
                "value" => "",
                "custom_value" => "",
                "type" => "empty"
            ]
        ];

        $seotamicSocial = [
            "title" => [
                "type" => "title",
                "value" => "",
                "custom_value" => ""
            ],
            "description" => [
                "value" => "",
                "custom_value" => "",
                "type" => "general"
            ]
        ];

        $entriesWithSeotamicFields->each(function ($entry) use ($seotamicMeta, $seotamicSocial) {
            if (!$entry->has('seotamic_meta')) {
                $seotamicMeta['title']['type'] = $entry->get('seotamic_title', "title");

                if ($entry->get('seotamic_title', "title") === 'custom') {
                    $seotamicMeta['title']['value'] = $entry->get('seotamic_custom_title');
                    $seotamicMeta['title']['custom_value'] = $entry->get('seotamic_custom_title');
                }

                $seotamicMeta['title']['append'] = $entry->get('seotamic_title_append', true);
                $seotamicMeta['title']['prepend'] = $entry->get('seotamic_title_prepend', true);

                $seotamicMeta['description']['type'] = $entry->get('seotamic_meta_description', "empty");

                if ($entry->get('seotamic_meta_description', "empty") === 'custom') {
                    $seotamicMeta['description']['value'] = $entry->get('seotamic_custom_meta_description');
                    $seotamicMeta['description']['custom_value'] = $entry->get('seotamic_custom_meta_description');
                }

                $seotamicSocial['title']['type'] = $entry->get('seotamic_open_graph_title', "title");

                if ($entry->get('seotamic_open_graph_title', "title") === 'custom') {
                    $seotamicSocial['title']['value'] = $entry->get('seotamic_custom_open_graph_title');
                    $seotamicSocial['title']['custom_value'] = $entry->get('seotamic_custom_open_graph_title');
                }

                $seotamicSocial['description']['type'] = $entry->get('seotamic_open_graph_description', "general");

                if ($entry->get('seotamic_open_graph_description', "general") === 'custom') {
                    $seotamicSocial['description']['value'] = $entry->get('seotamic_custom_open_graph_description');
                    $seotamicSocial['description']['custom_value'] = $entry->get('seotamic_custom_open_graph_description');
                }

                if ($entry->has('seotamic_image')) {
                    $seotamicSocial['image'] = $entry->get('seotamic_image');
                }

                if ($entry->has('seotamic_twitter_title')) {
                    $entry->remove('seotamic_twitter_title');
                }

                if ($entry->has('seotamic_custom_twitter_title')) {
                    $entry->remove('seotamic_custom_twitter_title');
                }

                if ($entry->has('seotamic_twitter_description')) {
                    $entry->remove('seotamic_twitter_description');
                }

                if ($entry->has('seotamic_custom_twitter_description')) {
                    $entry->remove('seotamic_custom_twitter_description');
                }

                $entry->set('seotamic_meta', $seotamicMeta);
                $entry->set('seotamic_social', $seotamicSocial);

                $entry->save();
            }
        });

        $this->info('…done!');
        $this->newLine();

        // 3. Go through all general seotamic settings (all languages) and convert/add new v3 and remove unused ones
        $this->info('3. Migrating general SEOtamic settings…');

        $sites = Site::all();

        foreach ($sites as $site) {
            $file->setLocale($site->locale);
            $globals = $file->read(false);

            if (array_key_exists('open_graph_site_name', $globals)) {
                $globals['social_site_name'] = $globals['open_graph_site_name'];
                unset($globals['open_graph_site_name']);
            }

            if (array_key_exists('open_graph_title', $globals)) {
                $globals['social_title'] = $globals['open_graph_title'];
                unset($globals['open_graph_title']);
            }

            if (array_key_exists('open_graph_description', $globals)) {
                $globals['social_description'] = $globals['open_graph_description'];
                unset($globals['open_graph_description']);
            }

            unset($globals['meta_description']);
            unset($globals['twitter_title']);
            unset($globals['twitter_description']);

            $file->write($globals);
        }

        $this->info('…done!');
        $this->newLine();

        // 4. Output success and write to the user which blueprints were skipped, so they can check them manually
        $this->info('Migration complete!');

        if (count($skippedBlueprints) > 0) {
            $this->warn('The following blueprints were skipped, make sure to manually remove seo tabs if needed:');
            $this->warn(implode(', ', $skippedBlueprints));
        }
    }
}
