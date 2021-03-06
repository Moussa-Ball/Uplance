<?php

/*
 * This file is part of Laravel Hashids.
 *
 * (c) Vincent Klaiber <hello@doubledip.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [
        \App\User::class => [
            'salt' => \App\User::class . 'f8fe4aa8e8d9ef9553864cc473a04fc7',
            'length' => 11,
        ],
        \App\Job::class => [
            'salt' => \App\Job::class . 'f8fe4aa8e8d9ef9553864cc473a04fc7',
            'length' => 11,
        ],
        \App\Proposal::class => [
            'salt' => \App\Proposal::class . 'f8fe4aa8e8d9ef9553864cc473a04fc7',
            'length' => 11,
        ],
        \App\Offer::class => [
            'salt' => \App\Offer::class . 'f8fe4aa8e8d9ef9553864cc473a04fc7',
            'length' => 11,
        ],
        \App\Contract::class => [
            'salt' => \App\Contract::class . 'f8fe4aa8e8d9ef9553864cc473a04fc7',
            'length' => 11,
        ],
        \App\Invoice::class => [
            'salt' => \App\Invoice::class . 'f8fe4aa8e8d9ef9553864cc473a04fc7',
            'length' => 11,
        ],
    ],
];
