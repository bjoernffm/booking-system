<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Searchable;

class Slot extends Model implements Auditable
{
    use Uuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Searchable;

    public $incrementing = false;

    public function toSearchableArray()
    {
        return [
            'title' => $this->flight_number,
            'item' => $this->toArray(),
            'entity' => get_class($this)
        ];
    }

    /**
     * Get the pilot record associated with the slot.
     */
    public function pilot()
    {
        return $this->hasOne('App\User', 'id', 'pilot_id');
    }

    /**
     * Get the aircraft record associated with the slot.
     */
    public function aircraft()
    {
        return $this->hasOne('App\Aircraft', 'id', 'aircraft_id');
    }

    /**
     * Get the bookins for the slot.
     */
    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }
}
