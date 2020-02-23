<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\Mobile as MobileRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            ->whereNull('deleted_at')
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
        return view('users/create', ['title' => 'Users']);
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
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'unique:App\User,email',
            'mobile' => ['unique:App\User,mobile', new MobileRule],
            'mobile_country' => 'required|string'
        ]);

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $number = $phoneUtil->parse($validatedData['mobile'], $validatedData['mobile_country']);

        $user = new User();

        $user->firstname = $validatedData['firstname'];
        $user->lastname = $validatedData['lastname'];
        $user->email = $validatedData['email'];
        $user->mobile = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::E164);
        $user->password = '';

        $user->email_verified_at = null;
        $user->mobile_verified_at = null;

        $user->save();

        $user->sendEmailVerificationNotification();
        $user->sendSmsVerificationNotification();

        return redirect()->action('UserController@index');
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $user->parsedNumber = $phoneUtil->parse($user->mobile);

        return view(
            'users/edit',
            [
                'title' => 'Users',
                'user' => $user,
                'countryMap' => \libphonenumber\CountryCodeToRegionCodeMap::$countryCodeToRegionCodeMap
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'unique:App\User,email,'.$user->id,
            'mobile' => ['unique:App\User,mobile,'.$user->id, new MobileRule]
        ]);

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $number = $phoneUtil->parse($validatedData['mobile']);

        $oldUser = User::find($user->id);

        $user->firstname = $validatedData['firstname'];
        $user->lastname = $validatedData['lastname'];
        $user->email = $validatedData['email'];
        $user->mobile = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::E164);

        if ($oldUser->email != $validatedData['email']) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        if ($oldUser->mobile != $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::E164)) {
            $user->mobile_verified_at = null;
            $user->sendSmsVerificationNotification();
        }

        $user->save();

        return redirect()->action('UserController@index');
    }

    /**
     * Prepare to remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function prepareDestroy($id)
    {
        $user = User::findOrFail($id);

        return view(
            'common/delete',
            [
                'title' => 'Users',
                'text' => 'Do you really want to remove this User?',
                'delete_link' => action('UserController@destroy', ['user' => $user->id]),
                'back_link' => action('UserController@index')
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->action('UserController@index');
    }

    public function showOnboarding()
    {
        if (Auth::user()->password != "") {
            return redirect('/');
        }

        return view(
            'auth/passwords/set',
            []);
    }

    public function setOnboarding(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|string'
        ]);

        if (Auth::user()->password == "") {
            Auth::user()->password = Hash::make($validatedData['password']);
            Auth::user()->save();
        }

        return redirect('/');
    }
}
