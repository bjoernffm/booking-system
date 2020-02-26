<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;

class Item extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        \App\Slot::class,
        \App\Aircraft::class,
        \App\User::class,
        \App\Booking::class,
        \App\Ticket::class,
    ];

}
