<?php

namespace Cnj\Seotamic\GraphQL;

use Statamic\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;
use Statamic\Contracts\Entries\Entry;

class SeotamicSocialField extends Field
{
    protected $attributes = [
        'description'   => 'Seotamic social information',
    ];

    public function type(): Type
    {
        return GraphQL::type(SeotamicSocialType::NAME);
    }

    protected function resolve(Entry $entry)
    {
        $ignoreBlueprints = config('seotamic.ignore_blueprints', []);

        if (in_array($entry->blueprint()->handle(), $ignoreBlueprints)) {
            return null;
        }

        return $entry->seotamic_social;
    }
}
