<?php

namespace App\Services;

class TelegramService
{
    protected $botToken;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
    }

    public function sendMessage($text, $chat_id)
    {
        $query = http_build_query([
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage?{$query}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
