<?php

namespace App\Actions;

use App\Dtos\BaseDto;
use Twilio\Rest\Client;


class SendWhatsappMessageDto extends BaseDto
{
    protected int $phone;
    protected string $message;
}
class SendWhatsappMessageAction extends BaseAction
{
    protected array $validationRules = [
        'phone' => 'required|numeric|digits:13',
        'message' => 'required',
    ];
    public function handle(SendWhatsappMessageDto $data)
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
