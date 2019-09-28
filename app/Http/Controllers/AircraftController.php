<?php

namespace App\Http\Controllers;

use App\Aircraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AircraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            )->orderBy('callsign', 'asc')
            ->get();

        return view('aircraft/index', ['title' => 'Aircraft', 'aircrafts' => $aircrafts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aircraftTypes = DB::table('aircraft_types')
            ->select(
                'aircraft_types.*'
            )->orderBy('designator', 'asc')
            ->get();

        return view('aircraft/create', ['title' => 'Aircraft', 'aircraft_types' => $aircraftTypes]);
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
            'callsign' => 'required|string|unique:aircrafts',
            'type' => 'required|string',
            'load' => 'required|digits_between:0,10000'
        ]);

        $aircraft = new Aircraft();
        $aircraft->callsign = $validatedData['callsign'];
        $aircraft->type = $validatedData['type'];
        $aircraft->load = $validatedData['load'];
        $aircraft->save();

        return redirect()->action('AircraftController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function show(Aircraft $aircraft)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function edit(Aircraft $aircraft)
    {
        $aircraftTypes = DB::table('aircraft_types')
            ->select(
                'aircraft_types.*'
            )->orderBy('designator', 'asc')
            ->get();

        return view('aircraft/edit', ['title' => 'Aircraft', 'aircraft' => $aircraft, 'aircraft_types' => $aircraftTypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aircraft $aircraft)
    {
        $validatedData = $request->validate([
            'callsign' => 'required|string|unique:aircrafts',
            'type' => 'required|string',
            'load' => 'required|digits_between:0,10000'
        ]);

        $aircraft->callsign = $validatedData['callsign'];
        $aircraft->type = $validatedData['type'];
        $aircraft->load = $validatedData['load'];
        $aircraft->save();

        return redirect()->action('AircraftController@index');
    }

    /**
     * Prepare to remove the specified resource from storage.
     *
     * @param  \App\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function prepareDestroy($id)
    {
        $aircraft = Aircraft::findOrFail($id);

        return view(
            'common/delete',
            [
                'title' => 'Aircraft',
                'text' => 'Do you really want to remove '.$aircraft->callsign.'?',
                'delete_link' => action('AircraftController@destroy', ['id' => $aircraft->id]),
                'back_link' => action('AircraftController@index')
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aircraft $aircraft)
    {
        $aircraft->delete();
        return redirect()->action('AircraftController@index');
    }
}
