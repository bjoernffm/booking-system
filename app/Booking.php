<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Searchable;

class Booking extends Model implements Auditable
{
    use Uuids;
    use Shortcodes;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    public function toSearchableArray()
    {
        $this->tickets;

        return [
            'title' => $this->shortcode,
            'item' => $this->toArray(),
            'entity' => get_class($this)
        ];
    }

    /**
     * Get the slot record associated with the booking.
     */
    public function slot()
    {
        return $this->hasOne('App\Slot', 'id', 'slot_id');
    }

    /**
     * Get the tickets for the booking.
     */
    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }
}
