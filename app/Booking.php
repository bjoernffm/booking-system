<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Booking extends Model implements Auditable
{
    use Uuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    /**
     * Get the slot record associated with the booking.
     */
    public function slot()
    {
        return $this->hasOne('App\Slot', 'id', 'slot_id');
    }
}
