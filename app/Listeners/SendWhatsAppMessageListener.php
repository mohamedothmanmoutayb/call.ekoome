<?php

namespace App\Listeners;

use App\Events\NewLeadCreated;
use App\Http\Services\TwilioService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWhatsAppMessageListener implements ShouldQueue
{
    public function handle(NewLeadCreated $event)
    {
        $twilioService = new TwilioService();

        $message = "Hello! You have a new lead:\n\n"
            . "Lead Name: {$event->leadName}\n"
            . "Quantity Ordered: {$event->quantity}\n\n"
            . "Please choose an option:";

        $buttons = [
            [
                'button' => [
                    'type' => 'reply',
                    'reply' => [
                        'id' => 'option_1',
                        'title' => 'Order 2 and get a discount!',
                    ],
                ],
            ],
            [
                'button' => [
                    'type' => 'reply',
                    'reply' => [
                        'id' => 'option_2',
                        'title' => 'Order 3 and get a bigger discount!',
                    ],
                ],
            ],
            [
                'button' => [
                    'type' => 'reply',
                    'reply' => [
                        'id' => 'option_3',
                        'title' => 'Keep my current order',
                    ],
                ],
            ],
        ];

        $twilioService->sendWhatsAppMessageWithButtons(
            $event->userPhone,
            $message,
            $buttons
        );
    }
}