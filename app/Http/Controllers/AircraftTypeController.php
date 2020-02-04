<?php

namespace App\Http\Controllers;

use App\AircraftType;
use Illuminate\Http\Request;

class AircraftTypeController extends Controller
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
        //
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
     * @param  \App\AircraftType  $aircraftType
     * @return \Illuminate\Http\Response
     */
    public function show(AircraftType $aircraftType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AircraftType  $aircraftType
     * @return \Illuminate\Http\Response
     */
    public function edit(AircraftType $aircraftType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AircraftType  $aircraftType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AircraftType $aircraftType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AircraftType  $aircraftType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AircraftType $aircraftType)
    {
        //
    }
}
