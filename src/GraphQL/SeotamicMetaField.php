<?php

namespace Cnj\Seotamic\GraphQL;

use Statamic\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;
use Statamic\Contracts\Entries\Entry;

class SeotamicMetaField extends Field
{
    protected $attributes = [
        'description'   => 'Seotamic meta information',
    ];

    public function type(): Type
    {
        return GraphQL::type(SeotamicMetaType::NAME);
    }

    protected function resolve(Entry $entry)
    {
        $ignoreBlueprints = config('seotamic.ignore_blueprints', []);

        if (in_array($entry->blueprint()->handle(), $ignoreBlueprints)) {
            return null;
        }

        return [
            ...$entry->seotamic_meta,
            'canonical_url' => $entry->seotamic_canonical,
        ];
    }
}
