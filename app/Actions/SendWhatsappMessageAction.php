<?php

namespace App\Actions;

use Twilio\Rest\Client;

class SendWhatsappMessageAction extends BaseAction
{
    protected array $validationRules = [
        'phone' => 'required|numeric|digits:13',
        'message' => 'required',
    ];
    public function handle(array $data)
    {
        $this->validateData($data);
        $twilioSid = config('whatsapp.TWILIO_SID');
        $twilioToken = config('whatsapp.TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = config('whatsapp.TWILIO_WHATSAPP_NUMBER');

        $twilio = new Client($twilioSid, $twilioToken);
        $twilio->messages->create(
            "whatsapp:+".$data['phone'],
            [
                "from" => "whatsapp:+". $twilioWhatsAppNumber,
                "body" => $data['message'],
            ]
        );
    }
}
