<?php

namespace App\Broadcasting;

use App\Sms\Sms;

class SmsChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function send($client): bool
    {
        $client->getCriticalTasks()->each(function ($tasks) use ($client, &$message) {
            foreach ($tasks as $task) {
                $message = [];
                $message[] = "{$client->name}\n\n";
                $message[] = "užregistruota naują užduotį: \n\n";
                $message[] = "{$task->name}\n\n";
                $message[] = "🚨🚨🚨🚨🚨🚨🚨🚨 \n ";

                $sms = new Sms();
                $sms->sendSms("+37066611211", 'OneSoft', implode($message));
                $sms->sendSms("+37068649722", 'PrestaPro', implode($message));
                $sms->sendSms("+37066035554", 'OneSoft', implode($message));
                $sms->sendSms("+37066035552", 'OneSoft', implode($message));
            }
        });

        return true;
    }
}
