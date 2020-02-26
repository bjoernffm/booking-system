<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Searchable;

class Aircraft extends Model implements Auditable
{
    use Uuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Searchable;

    public $incrementing = false;

    public function toSearchableArray()
    {
        // load
        $this->aircraftType;

        return [
            'title' => $this->callsign.' - '.$this->aircraftType->designator,
            'item' => $this->toArray(),
            'entity' => get_class($this)
        ];
    }

    /**
     * Get the aircraft record associated with the slot.
     */
    public function aircraftType()
    {
        return $this->hasOne('App\AircraftType', 'id', 'type');
    }
}
