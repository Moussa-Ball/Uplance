<?php

namespace App;

use ScoutElastic\Migratable;
use ScoutElastic\IndexConfigurator;

class JobIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        "analysis" => [
            "analyzer" => [
                "camel" => [
                    "tokenizer" => "my_pattern",
                    "filter" => [
                        "my_gram"
                    ]
                ]
            ],
            "filter" => [
                "my_gram" => [
                    "type" => "edge_ngram",
                    "max_gram" => 10
                ]
            ],
            "tokenizer" => [
                "my_pattern" => [
                    "type" => "pattern",
                    "pattern" => "([^\\p{L}\\d]+)|(?<=\\D)(?=\\d)|(?<=\\d)(?=\\D)|(?<=[\\p{L}&&[^\\p{Lu}]])(?=\\p{Lu})|(?<=\\p{Lu})(?=\\p{Lu}[\\p{L}&&[^\\p{Lu}]])"
                ]
            ]
        ]
    ];
}
