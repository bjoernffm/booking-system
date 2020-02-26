<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Searchable;

class Ticket extends Model implements Auditable
{
    use Uuids;
    use Shortcodes;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    public function toSearchableArray()
    {
        return [
            'title' => $this->shortcode,
            'item' => $this->toArray(),
            'entity' => get_class($this)
        ];
    }

    /**
     * Get the booking record associated with the ticket.
     */
    public function booking()
    {
        return $this->hasOne('App\Booking', 'id', 'booking_id');
    }
}
