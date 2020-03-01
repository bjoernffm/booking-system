<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slot;
use App\Booking;
use App\Ticket;
use Carbon\Carbon;

class OverviewController extends Controller
{
    public function view(Request $request)
    {
        if ($request->has('bySlotId')) {
            $slot = Slot::findOrFail($request->input('bySlotId'));
            $title = "Flight ".$slot->flight_number;
        } else if ($request->has('byBookingId')) {
            $booking = Booking::findOrFail($request->input('byBookingId'));
            $slot = $booking->slot;
            $title = "Booking ".$booking->shortcode;
        } else if ($request->has('byTicketId')) {
            $ticket = Ticket::findOrFail($request->input('byTicketId'));
            $slot = $ticket->booking->slot;
            $title = "Ticket ".$ticket->shortcode;
        } else {
            abort(404);
        }

        $slot->starts_on = new Carbon($slot->starts_on, 'UTC');
        $slot->ends_on = new Carbon($slot->ends_on, 'UTC');
        $slot->boarding_on = clone $slot->starts_on;
        $slot->boarding_on = $slot->boarding_on->subMinutes(15);

        return view(
            'common/overview',
            [
                'title' => $title,
                'slot' => $slot
            ]
        );
    }
}
