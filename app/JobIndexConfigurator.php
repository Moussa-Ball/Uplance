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
        //
    ];
}
