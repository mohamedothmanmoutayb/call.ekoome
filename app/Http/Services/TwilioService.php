<?php
namespace App\Http\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            env('TWILIO_SID'),
            env('TWILIO_AUTH_TOKEN')
        );
    }

    public function sendWhatsAppMessageWithButtons($userPhone, $message, $buttons = [])
    {
        
        return $this->client->messages->create(
            "whatsapp:+212718751708", // Recipient's WhatsApp number
            [
                'from' => "whatsapp:+14155238886", // Twilio WhatsApp number
                'body' => $message,
                'actions' => $buttons, // Buttons must be under the `actions` key
            ]
        );
    }
}