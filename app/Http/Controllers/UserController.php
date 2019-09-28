<?php

namespace App\Http\Controllers;

use App\Aircraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')
            ->select(
                'users.*'
            )->orderBy('lastname', 'asc')
            ->get();

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $users = $users->map(function ($user) use($phoneUtil) {
            try {
                $number = $phoneUtil->parse($user->mobile);
                $user->mobile_formatted = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
            } catch (\libphonenumber\NumberParseException $e) {
                $user->mobile_formatted = $user->mobile;
            }

            return $user;
        });

        return view('users/index', ['title' => 'Users', 'users' => $users]);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aircraft $aircraft)
    {
        //
    }
}
