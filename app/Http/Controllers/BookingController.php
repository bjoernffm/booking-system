<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Slot;
use App\Mail\BookingCreated;
use App\Mail\BookingInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use Firebase\JWT\JWT;
use Twilio\Rest\Client;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = DB::table('slots')
            ->leftJoin('aircrafts', 'slots.aircraft_id', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot_id', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->leftJoin('bookings', 'slots.id', '=', 'bookings.slot_id')
            ->select(
                'slots.*',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator',
                'bookings.id as booking_id',
                'bookings.price as price'
            )->orderBy('starts_on', 'asc')
            ->whereNotNull('bookings.id')
            ->whereNull('bookings.deleted_at')
            ->get();

        return view('bookings/index', ['title' => 'Bookings', 'bookings' => $bookings]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addIndex()
    {
        $openSlots = DB::table('slots')
            ->leftJoin('aircrafts', 'slots.aircraft_id', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot_id', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->select(
                'slots.*',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator'
            )->orderBy('starts_on', 'asc')
            ->where('slots.status', 'available')
            ->get();

        return view('bookings/addIndex', ['title' => 'Add Booking', 'openSlots' => $openSlots]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $slot = DB::table('slots')
            ->leftJoin('aircrafts', 'slots.aircraft_id', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot_id', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->select(
                'slots.*',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator'
            )->orderBy('starts_on', 'asc')
            ->where('slots.status', 'available')
            ->where('slots.id', $request->input('slot_id'))
            ->first();

        if ($slot === null) {
            abort(404);
        }

        return view(
            'bookings/create',
            [
                'title' => 'Add Booking',
                'slot' => $slot
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $passengers = $request->input('pax');
        array_pop($passengers);

        $booking = new Booking();
        $booking->passengers = json_encode($passengers);
        $booking->regular = 0;
        $booking->discounted = 0;
        $booking->small_headsets = 0;
        $booking->email = $request->input('email');
        $booking->internal_information = $request->input('internal_information');
        $booking->slot_id = $request->input('slot_id');

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $number = $phoneUtil->parse($request->input('mobile'), $request->input('mobile_country'));
            $booking->mobile = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::E164);
        } catch (\libphonenumber\NumberParseException $e) {
            //var_dump($e);
            //abort(404);
            $booking->mobile = $request->input('mobile');
        }

        foreach($passengers as $passenger) {
            if (isset($passenger['discounted']) and $passenger['discounted'] == "yes") {
                $booking->discounted++;
            } else {
                $booking->regular++;
            }

            if (isset($passenger['small_headset']) and $passenger['small_headset'] == "yes") {
                $booking->small_headsets++;
            }
        }

        $booking->price = ($booking->regular*env('PRICE_ADULT')) + ($booking->discounted*env('PRICE_CHILD'));

        $booking->save();
        $booking->slot->status = 'booked';
        $booking->slot->save();

        if ($booking->email !== null) {
            Mail::to($booking->email)->bcc('b.ebbrecht@rl-3.de')->send(new BookingCreated($booking));
            Mail::to($booking->email)->bcc('b.ebbrecht@rl-3.de')->send(new BookingInvoice($booking));
        }

        return redirect()->action('BookingController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function fastAccess($bookingId, $hash)
    {
        $booking = DB::table('slots')
            ->leftJoin('aircrafts', 'slots.aircraft_id', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot_id', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->leftJoin('bookings', 'slots.id', '=', 'bookings.slot_id')
            ->select(
                'slots.starts_on',
                'slots.ends_on',
                'slots.status',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'users.mobile as pilot_mobile',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator',
                'bookings.*'
            )->orderBy('starts_on', 'asc')
            ->where('bookings.id', $bookingId)
            ->whereNull('bookings.deleted_at')
            ->first();

        if ($booking === null) {
            return view('mobile/corrupt_booking');
        }

        $calculatedHash = json_encode([
            $booking->id,
            $booking->passengers,
            $booking->slot_id,
        ]);
        $calculatedHash = hash_hmac('sha256', $calculatedHash, env('APP_KEY'));
        $calculatedHash = substr($calculatedHash, 0, 5);

        if ($calculatedHash != $hash) {
            return view('mobile/corrupt_booking');
        }

        $booking->passengers = json_decode($booking->passengers);
        for($i = 0; $i < count($booking->passengers); $i++) {
            $booking->passengers[$i]->infoText = [];

            if (isset($booking->passengers[$i]->child) and $booking->passengers[$i]->child == 'yes') {
                $booking->passengers[$i]->infoText[] = 'Child';
            }
            if (isset($booking->passengers[$i]->child) and $booking->passengers[$i]->small_headset == 'yes') {
                $booking->passengers[$i]->infoText[] = 'Small Headset';
            }

            $booking->passengers[$i]->infoText = implode(', ', $booking->passengers[$i]->infoText);
        }

        return view('mobile/booking', ['title' => 'Add Booking', 'booking' => $booking]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $hash = json_encode([
            $booking->id,
            $booking->passengers,
            $booking->slot_id,
        ]);
        $hash = hash_hmac('sha256', $hash, env('APP_KEY'));
        $hash = substr($hash, 0, 5);

        $text = 'https://192.168.178.26/booking-system/bookings/ma/'.$booking->id.'/'.$hash.'/';
        echo '<input type="text" value="'.$text.'" />';

        // Encode the data, returns a BarcodeData object
        $pdf417 = new PDF417();
        $pdf417->setSecurityLevel(4);
        $data = $pdf417->encode($text);

        $renderer = new ImageRenderer([
            'format' => 'data-url',
            'ratio' => 2
        ]);
        $img = $renderer->render($data);
        echo '<img src="'.$img->encoded.'" />';
        exit();
        return $booking;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Prepare to remove the specified resource from storage.
     *
     * @param  \App\Booking  $id
     * @return \Illuminate\Http\Response
     */
    public function prepareDestroy($id)
    {
        $booking = Booking::findOrFail($id);

        return view(
            'common/delete',
            [
                'title' => 'Booking',
                'text' => 'Do you really want to remove this Booking?',
                'delete_link' => action('BookingController@destroy', ['booking' => $booking->id]),
                'back_link' => action('BookingController@index')
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $booking->slot->status = 'available';
        $booking->slot->save();
        $booking->delete();
        return redirect()->action('BookingController@index');
    }
}
