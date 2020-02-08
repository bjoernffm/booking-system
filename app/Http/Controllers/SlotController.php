<?php

namespace App\Http\Controllers;

use App\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slots = DB::table('slots')
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
            ->whereNull('slots.deleted_at')
            ->get();

        return view('slots/index', ['title' => 'Slots', 'slots' => $slots]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aircrafts = DB::table('aircrafts')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->select(
                'aircrafts.*',
                'aircraft_types.manufacturer',
                'aircraft_types.model',
                'aircraft_types.designator',
                'aircraft_types.description',
                'aircraft_types.engine_count',
                'aircraft_types.engine_type'
            )
            ->whereNull('deleted_at')
            ->orderBy('callsign', 'asc')
            ->get();

        $pilots = DB::table('users')
            ->whereNull('deleted_at')
            ->get();

        return view('slots/create', ['title' => 'Slot', 'aircrafts' => $aircrafts, 'pilots' => $pilots]);
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
            'starts_on' => 'required|date',
            'ends_on' => 'required|date',
            'aircraft_id' => 'required|exists:aircrafts,id',
            'pilot_id' => 'required|exists:users,id'
        ]);

        $validatedData['starts_on'] = \Carbon\Carbon::parse($validatedData['starts_on'], 'Europe/Berlin')->setTimezone('UTC')->format('Y-m-d H:i');
        $validatedData['ends_on'] = \Carbon\Carbon::parse($validatedData['ends_on'], 'Europe/Berlin')->setTimezone('UTC')->format('Y-m-d H:i');

        $slot = new Slot();
        $slot->starts_on = $validatedData['starts_on'];
        $slot->ends_on = $validatedData['ends_on'];
        $slot->aircraft_id = $validatedData['aircraft_id'];
        $slot->pilot_id = $validatedData['pilot_id'];
        $slot->save();

        return redirect()->action('SlotController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function show(Slot $slot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function edit(Slot $slot)
    {
        $aircrafts = DB::table('aircrafts')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->select(
                'aircrafts.*',
                'aircraft_types.manufacturer',
                'aircraft_types.model',
                'aircraft_types.designator',
                'aircraft_types.description',
                'aircraft_types.engine_count',
                'aircraft_types.engine_type'
            )
            ->whereNull('deleted_at')
            ->orderBy('callsign', 'asc')
            ->get();

        $pilots = DB::table('users')
            ->whereNull('deleted_at')
            ->get();

        return view('slots/edit', ['title' => 'Slot', 'slot' => $slot, 'aircrafts' => $aircrafts, 'pilots' => $pilots]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slot $slot)
    {
        $validatedData = $request->validate([
            'flight_number' => 'required|string',
            'starts_on' => 'required|date',
            'ends_on' => 'required|date',
            'aircraft_id' => 'required|exists:aircrafts,id',
            'pilot_id' => 'required|exists:users,id'
        ]);

        $validatedData['starts_on'] = \Carbon\Carbon::parse($validatedData['starts_on'], 'Europe/Berlin')->setTimezone('UTC')->format('Y-m-d H:i');
        $validatedData['ends_on'] = \Carbon\Carbon::parse($validatedData['ends_on'], 'Europe/Berlin')->setTimezone('UTC')->format('Y-m-d H:i');

        $slot->flight_number = strtoupper($validatedData['flight_number']);
        $slot->starts_on = $validatedData['starts_on'];
        $slot->ends_on = $validatedData['ends_on'];
        $slot->aircraft_id = $validatedData['aircraft_id'];
        $slot->pilot_id = $validatedData['pilot_id'];
        $slot->save();

        return redirect()->action('SlotController@index');
    }

    /**
     * Prepare to remove the specified resource from storage.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function prepareDestroy($id)
    {
        $slot = Slot::findOrFail($id);

        return view(
            'common/delete',
            [
                'title' => 'Slot',
                'text' => 'Do you really want to remove this Slot?',
                'delete_link' => action('SlotController@destroy', ['slot' => $slot->id]),
                'back_link' => action('SlotController@index')
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slot $slot)
    {
        $bookings = $slot->booking()->withTrashed()->delete();
        $slot->delete();
        return redirect()->action('BookingController@index');
    }
}
