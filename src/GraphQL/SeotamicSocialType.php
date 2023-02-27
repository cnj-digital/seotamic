<?php

namespace Cnj\Seotamic\GraphQL;

use Statamic\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SeotamicSocialType extends GraphQLType
{
    const NAME = 'SeotamicSocial';

    protected $attributes = [
        'name' => self::NAME,
    ];

    public function fields(): array
    {
        return [
            'open_graph' => [
                'type' => GraphQL::boolean(),
                'description' => 'Output Open Graph tags',
                'resolve' => $this->resolver()
            ],
            'twitter' => [
                'type' => GraphQL::boolean(),
                'description' => 'Output Twitter tags',
                'resolve' => $this->resolver()
            ],
            'site_name' => [
                'type' => GraphQL::string(),
                'description' => 'Social site name',
                'resolve' => $this->resolver()
            ],
            'title' => [
                'type' => GraphQL::string(),
                'description' => 'Social title',
                'resolve' => $this->resolver()
            ],
            'description' => [
                'type' => GraphQL::string(),
                'description' => 'Social description',
                'resolve' => $this->resolver()
            ],
            'image' => [
                'type' => GraphQL::string(),
                'description' => 'Social image',
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
