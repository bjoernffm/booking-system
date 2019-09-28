<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    use Uuids;

    public $incrementing = false;
}
