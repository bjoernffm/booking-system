<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use Uuids;

    public $incrementing = false;
}
