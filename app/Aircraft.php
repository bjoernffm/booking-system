<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Aircraft extends Model implements Auditable
{
    use Uuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    /**
     * Get the aircraft record associated with the slot.
     */
    public function aircraftType()
    {
        return $this->hasOne('App\AircraftType', 'id', 'type');
    }
}
