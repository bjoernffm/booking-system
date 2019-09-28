<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use Firebase\JWT\JWT;

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
            ->leftJoin('aircrafts', 'slots.aircraft', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->leftJoin('bookings', 'slots.id', '=', 'bookings.slot')
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
            ->leftJoin('aircrafts', 'slots.aircraft', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->leftJoin('bookings', 'slots.id', '=', 'bookings.slot')
            ->select(
                'slots.*',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator',
                'bookings.id as booking_id'
            )->orderBy('starts_on', 'asc')
            ->whereNull('bookings.id')
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
            ->leftJoin('aircrafts', 'slots.aircraft', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->leftJoin('bookings', 'slots.id', '=', 'bookings.slot')
            ->select(
                'slots.*',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator',
                'bookings.id as booking_id'
            )->orderBy('starts_on', 'asc')
            ->whereNull('bookings.id')
            ->where('slots.id', $request->input('slot_id'))
            ->first();

        if ($slot === null) {
            abort(404);
        }

        /*$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $ret = $phoneUtil->getSupportedRegions();
        foreach($ret as $country) {
            echo '<option value="'.$country.'">+'.$phoneUtil->getCountryCodeForRegion($country).'</option>'.PHP_EOL;
        } */

        return view(
            'bookings/create',
            [
                'title' => 'Add Booking',
                'slot' => $slot,
                'prices' => [
                    'adult' => env('PRICE_ADULT'),
                    'child' => env('PRICE_CHILD')
                ]
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
        $booking = new Booking();
        $booking->passengers = json_encode($request->input('pax'));
        $booking->adults = 0;
        $booking->children = 0;
        $booking->small_headsets = 0;
        $booking->email = $request->input('email');
        $booking->internal_information = $request->input('internal_information');
        $booking->slot = $request->input('slot_id');

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $number = $phoneUtil->parse($request->input('mobile'), $request->input('mobile_country'));
            $booking->mobile = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::E164);
        } catch (\libphonenumber\NumberParseException $e) {
            //var_dump($e);
            //abort(404);
            $booking->mobile = $request->input('mobile');
        }

        foreach($request->input('pax') as $passenger) {
            if (isset($passenger['child']) and $passenger['child'] == "yes") {
                $booking->children++;
            } else {
                $booking->adults++;
            }

            if (isset($passenger['small_headset']) and $passenger['small_headset'] == "yes") {
                $booking->small_headsets++;
            }
        }

        $booking->price = ($booking->adults*env('PRICE_ADULT')) + ($booking->children*env('PRICE_CHILD'));

        $booking->save();

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
            ->leftJoin('aircrafts', 'slots.aircraft', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->leftJoin('bookings', 'slots.id', '=', 'bookings.slot')
            ->select(
                'slots.starts_on',
                'slots.ends_on',
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
            ->first();

        if ($booking === null) {
            abort(404);
        }

        $calculatedHash = json_encode([
            $booking->id,
            $booking->passengers,
            $booking->slot,
        ]);
        $calculatedHash = hash_hmac('sha256', $calculatedHash, env('APP_KEY'));
        $calculatedHash = substr($calculatedHash, 0, 5);

        if ($calculatedHash != $hash) {
            //abort(403);
            echo 'achtung, etwas hat sich seit der Buchung geändert';
            exit();
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

        return view('bookings/mobileAccess', ['title' => 'Add Booking', 'booking' => $booking]);
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
            $booking->slot,
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
