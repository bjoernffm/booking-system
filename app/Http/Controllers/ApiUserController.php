<?php

namespace App\Http\Controllers;

use App\User;
use App\Notifications\SmsVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;

class ApiUserController extends Controller
{
    public function createSmsVerification($id)
    {
        $user = User::findOrFail($id);

        if($user->mobile == null) {
            abort(412, 'Mobile number missing');
        }

        $token = [
            'iss' => url(''),
            'aud' => url(''),
            'sub' => 'sms_validation',
            'iat' => time(),
            'exp' => time()+300,
            'user_id' => $user->id,
            'user_mobile' => $user->mobile
        ];

        $jwt = JWT::encode($token, env('APP_KEY'));
        $url = url('jwt_gate/'.$jwt);

        $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));

        $message = $twilio->messages->create(
            $user->mobile,
            [
                "body" => "Validation of this mobile number has been requested. Click the following link to validate:\n\n".$url,
                "from" => "FVL Booking"
            ]
        );

        return response()->json(['data' => ['sid' => $message->sid]], 201);
    }
}
