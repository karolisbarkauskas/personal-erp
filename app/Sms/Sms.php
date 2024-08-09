<?php

namespace App\Sms;

use Illuminate\Support\Facades\Http;

class Sms
{
    private const API_URL = 'https://sms4.tcg.lt/external/get/send.php?';

    /**
     * login - Your login
     * signature - signature
     * phone - One or several numbers separated by commas (no more than 100 numbers in the request).
     *  Numbers should be in international format without + or 00. Example for Lithuania destination 3706XXXXXXX
     * text - Text of SMS message
     * sender - Sender name (one of the approved in your account)
     * timestamp - UTC Timestamp
     * smsType - sms
     * */
    public function sendSms($phone, $sender, $text)
    {
        $login = config('sms.tcg_login');
        $apiKey = config('sms.tcg_api_key');
        $time = file_get_contents('https://sms4.tcg.lt/external/get/timestamp.php');
        $params = [
            'login' => $login,
            'phone' => $phone,
            'sender' => $sender,
            'return' => 'json',
            'text' => $text,
            'timestamp' => $time,
        ];
        $params['signature'] = $this->signature($params, $apiKey);

        $endpoint = 'https://sms4.tcg.lt/external/get/send.php';
        $link = $endpoint . '?' . http_build_query($params);

        return Http::get($link)->json();
    }

    public function signature($params, $apiKey): string
    {
        ksort($params);
        reset($params);

        return md5(implode($params) . $apiKey);
    }
}
