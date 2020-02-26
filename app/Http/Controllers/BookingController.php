<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Ticket;
use App\Slot;
use App\Mail\BookingCreated;
use App\Mail\BookingInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Rules\SlotAvailable as SlotAvailableRule;
use App\Rules\Mobile as MobileRule;

use Carbon\Carbon;

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
            ->whereNull('slots.deleted_at')
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
            ->whereNull('slots.deleted_at')
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
                'slot' => $slot,
                'countryMap' => \libphonenumber\CountryCodeToRegionCodeMap::$countryCodeToRegionCodeMap
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
        $validatedData = $request->validate([
            'pax' => 'array|required',
            'pax.firstname' => 'string',
            'pax.lastname' => 'string',
            'pax.small_headset' => 'in:yes|nullable',
            'pax.discounted' => 'in:yes|nullable',
            'email' => 'email|nullable',
            'mobile' => [new MobileRule, 'nullable'],
            'mobile_country' => 'string|nullable',
            'internal_information' => 'string|nullable',
            'slot_id' => [new SlotAvailableRule]
        ]);

        $passengers = $validatedData['pax'];
        array_pop($passengers);

        $booking = new Booking();
        $booking->generateShortcode();
        $booking->regular = 0;
        $booking->discounted = 0;
        $booking->small_headsets = 0;
        $booking->slot_id = $validatedData['slot_id'];

        if ($validatedData['email'] !== null) {
            $booking->email = $validatedData['email'];
        }

        if ($validatedData['internal_information'] !== null) {
            $booking->internal_information = $validatedData['internal_information'];
        }

        if ($validatedData['mobile'] !== null) {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            try {
                $number = $phoneUtil->parse($validatedData['mobile_country'].$validatedData['mobile']);
                $booking->mobile = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::E164);
            } catch (\libphonenumber\NumberParseException $e) {
                $booking->mobile = $validatedData['mobile'];
            }
        }

        $tickets = [];
        foreach($passengers as $passenger) {
            $ticket = new Ticket();
            $ticket->generateShortcode();
            $ticket->firstname = $passenger['firstname'];
            $ticket->lastname = $passenger['lastname'];
            $ticket->small_headset = 0;

            if (isset($passenger['discounted']) and $passenger['discounted'] == "yes") {
                $booking->discounted++;
                $ticket->type = 'discounted';
                $ticket->price = env('PRICE_CHILD');
            } else {
                $booking->regular++;
                $ticket->type = 'regular';
                $ticket->price = env('PRICE_ADULT');
            }

            if (isset($passenger['small_headset']) and $passenger['small_headset'] == "yes") {
                $booking->small_headsets++;
                $ticket->small_headset = 1;
            }

            $tickets[] = $ticket;
        }

        $booking->price = ($booking->regular*env('PRICE_ADULT')) + ($booking->discounted*env('PRICE_CHILD'));

        $booking->save();

        $booking->tickets()->saveMany($tickets);
        $booking->slot->status = 'booked';
        $booking->slot->save();

        if ($validatedData['email'] !== null) {
            Mail::to($validatedData['email'])->bcc('b.ebbrecht@rl-3.de')->send(new BookingCreated($booking));
            Mail::to($validatedData['email'])->bcc('b.ebbrecht@rl-3.de')->send(new BookingInvoice($booking));
        }

        return redirect()->action('BookingController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function fastAccess($ticketId, $hash)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $calculatedHash = json_encode([
            $ticket->booking->id,
            $ticket->id,
            $ticket->booking->slot->id,
        ]);
        $calculatedHash = hash_hmac('sha256', $calculatedHash, env('APP_KEY'));
        $calculatedHash = substr($calculatedHash, 0, 10);

        if ($calculatedHash != $hash) {
            return view('mobile/corrupt_booking');
        }

        $slot = $ticket->booking->slot;
        #$slot->pilot;
        #$slot->aircraft;
        #$slot->aircraft->aircraftType;
        #$slot->bookings;
        $slot->bookings->map(function($booking) {
            $booking->tickets = $booking->tickets->map(function($ticket) {
                $ticket->firstname = decrypt($ticket->firstname);
                $ticket->lastname = decrypt($ticket->lastname);
                return $ticket;
            });
            return $booking;
        });

        return $slot->bookings;

        return $slot;

        return view('mobile/booking', ['title' => 'Booking', 'slot' => $slot]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $data = [
            'slot' => [
                'flight_number' => $booking->slot->flight_number,
                'starts_at' => (new Carbon($booking->slot->starts_on, 'UTC'))->toIso8601ZuluString(),
                'boarding' => (new Carbon($booking->slot->starts_on, 'UTC'))->subMinutes(15)->toIso8601ZuluString(),
                'duration' => ((new Carbon($booking->slot->ends_on, 'UTC'))->diff((new Carbon($booking->slot->starts_on, 'UTC'))))->format('%H:%I'),
            ],
            'booking' => [
                'shortcode' => $booking->shortcode
            ],
            'tickets' => $booking->tickets->map(function ($ticket) use ($booking) {
                $hash = json_encode([
                    $booking->id,
                    $ticket->id,
                    $booking->slot->id,
                ]);
                $hash = hash_hmac('sha256', $hash, env('APP_KEY'));
                $hash = substr($hash, 0, 10);

                $text = action('BookingController@fastAccess', ['ticket_id' => $ticket->id, 'hash' => $hash]);

                $pdf417 = new PDF417();
                $pdf417->setSecurityLevel(4);
                $data = $pdf417->encode($text);

                $renderer = new ImageRenderer([
                    'format' => 'data-url',
                    'ratio' => 2
                ]);
                $img = $renderer->render($data);

                $special = '';
                if ($ticket->small_headset == 1) {
                    $special = 'SMALL HEADSET';
                }

                return [
                    'shortcode' => $ticket->shortcode,
                    'passenger' => $ticket->firstname.' '.$ticket->lastname,
                    'created_at' => (new Carbon($ticket->created_at, 'UTC'))->toIso8601ZuluString(),
                    'price' => 'EUR '.$ticket->price.',–',
                    'special' => $special,
                    'pdf417' => $img->encoded
                ];
            })
        ];

        return view('bookings/print', ['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        $bookingResult = DB::table('bookings')
            ->leftJoin('slots', 'bookings.slot_id', '=', 'slots.id')
            ->leftJoin('aircrafts', 'slots.aircraft_id', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot_id', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->select(
                'bookings.*',
                'slots.starts_on as starts_on',
                'slots.ends_on as ends_on',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator'
            )
            ->where('bookings.id', $booking->id)
            ->first();

        if ($bookingResult === null) {
            abort(404);
        }

        $bookingResult->passengers = json_decode($bookingResult->passengers);
        for($i = 0; $i < count($bookingResult->passengers); $i++) {
            $bookingResult->passengers[$i]->firstname = decrypt($bookingResult->passengers[$i]->firstname);
            $bookingResult->passengers[$i]->lastname = decrypt($bookingResult->passengers[$i]->lastname);
        }
        $bookingResult->passengers = json_encode($bookingResult->passengers);

        if ($bookingResult->email !== null) {
            $bookingResult->email = decrypt($bookingResult->email);
        }

        if ($bookingResult->mobile !== null) {
            $bookingResult->mobile = decrypt($bookingResult->mobile);
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

            $parsedNumber = $phoneUtil->parse($bookingResult->mobile);
            $bookingResult->parsedNumberNational = $parsedNumber->getNationalNumber();
            $bookingResult->parsedNumberCountryCode = $parsedNumber->getCountryCode();
        } else {
            $bookingResult->parsedNumberNational = "";
            $bookingResult->parsedNumberCountryCode = "+49";
        }

        if ($bookingResult->internal_information !== null) {
            $bookingResult->internal_information = decrypt($bookingResult->internal_information);
        }

        return view(
            'bookings/edit',
            [
                'title' => 'Bookings',
                'booking' => $bookingResult,
                'countryMap' => \libphonenumber\CountryCodeToRegionCodeMap::$countryCodeToRegionCodeMap
            ]);
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
                'title' => 'Bookings',
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
        $booking->tickets()->delete();
        $booking->slot->status = 'available';
        $booking->slot->save();
        $booking->delete();
        return redirect()->action('BookingController@index');
    }
}
