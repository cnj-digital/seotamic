<?php

namespace Cnj\Seotamic\GraphQL;

use Illuminate\Support\Str;
use Statamic\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SeotamicMetaType extends GraphQLType
{
    const NAME = 'SeotamicMeta';

    protected $attributes = [
        'name' => self::NAME,
    ];

    public function fields(): array
    {
        return [
                'title' => [
                    'type' => GraphQL::string(),
                    'description' => 'Entry meta title',
                    'resolve' => $this->resolver()
                ],
                'description' => [
                    'type' => GraphQL::string(),
                    'description' => 'Entry meta description',
                    'resolve' => $this->resolver()
                ],
                'canonical_url' => [
                    'type' => GraphQL::string(),
                    'description' => 'Canonical page URL',
                    'resolve' => $this->resolver()
                ],
            ];
    }

    private function resolver()
    {
        return function (array $values, $args, $context, ResolveInfo $info) {
            return $values[$info->fieldName];
        };
    }
}
