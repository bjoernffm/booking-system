<?php

namespace App\Http\Controllers;

use App\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slots = DB::table('slots')
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slot $slot)
    {
        //
    }
}
