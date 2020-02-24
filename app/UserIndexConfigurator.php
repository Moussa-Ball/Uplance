<?php

namespace App;

use ScoutElastic\Migratable;
use ScoutElastic\IndexConfigurator;

class UserIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'analyzer' => [
                'en_std' => [
                    'type' => 'standard',
                    'stopwords' => '_english_'
                ]
            ]
        ]
    ];
}
