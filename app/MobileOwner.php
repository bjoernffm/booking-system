<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

trait MobileOwner
{
    /**
     * Determine if the user has verified their mobile number.
     *
     * @return bool
     */
    public function hasVerifiedMobile()
    {
        return ! is_null($this->mobile_verified_at);
    }

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the sms verification notification.
     *
     * @return void
     */
    public function sendSmsVerificationNotification()
    {
        $url = URL::temporarySignedRoute(
            'verify_mobile', now()->addMinutes(30), ['user' => $this->id]
        );

        $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        $message = $twilio->messages->create(
            $this->mobile,
            [
                "body" => "Verfication of this mobile number has been requested. Click the following link to validate:\n\n".$url,
                "from" => "FVL Booking"
            ]
        );
    }
}
