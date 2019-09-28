<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Carbon\Carbon;

class JwtGateController extends Controller
{
    public function process($jwt)
    {
        try {
            $jwt = JWT::decode($jwt, env('APP_KEY'), array('HS256'));
        } catch(ExpiredException $e) {
            abort(403, 'Token expired');
        }

        if ($jwt->iss != url('')) {
            abort(403, 'Unknown issuer');
        }

        if ($jwt->aud != url('')) {
            abort(403, 'Unknown audition');
        }

        if ($jwt->sub == 'sms_validation') {
            $return = $this->processSmsValidation($jwt);
        } else {
            abort(403, 'Unknown subject');
        }

        return $return;
    }

    public function processSmsValidation($jwt)
    {
        $user = User::findOrFail($jwt->user_id);

        if ($user->mobile != $jwt->user_mobile) {
            abort(403, 'Unknown mobile number');
        }

        $user->mobile_verified_at = Carbon::now("UTC");
        $user->save();

        return view(
            'mobile/alert',
            [
                'title' => 'Mobile validated',
                'message' => 'Your mobile phone has been successfully validated',
                'type' => 'success'
            ]
        );
    }
}
