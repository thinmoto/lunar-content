<?php

return [
    'templates' => [
        'default' => [
            [
                'title' => 'lunarcontent::titles.content',
                'alias' => 'content',
                'kind' => 'content',
            ],
        ],
        'homepage' => [
            [
                'title' => 'lunarcontent::titles.title',
                'alias' => 'title',
                'kind' => 'text',
            ],
            [
                'title' => 'lunarcontent::titles.stats',
                'alias' => 'stats',
                'kind' => 'textblocks',
                'fields' => ['thumb', 'text']
            ],
            [
                'title' => 'lunarcontent::titles.categories',
                'alias' => 'categories',
                'kind' => 'textblocks',
                'fields' => ['thumb', 'title', 'url', 'text']
            ],
            [
                'title' => 'lunarcontent::titles.slider',
                'alias' => 'slider',
                'kind' => 'slider',
            ],
            [
                'title' => 'lunarcontent::titles.seo',
                'alias' => 'seo',
                'kind' => 'seo',
            ],
        ],
    ]
];
