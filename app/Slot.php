<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Slot extends Model implements Auditable
{
    use Uuids;
    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

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
     * Get the booking that owns the slot.
     */
    public function booking()
    {
        return $this->belongsTo('App\Booking', 'id', 'slot_id');
    }
}
