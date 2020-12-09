<?php

namespace App\Utilities;

class Messenger
{
    /**
     * SMS Config
     */
    private const config = [
        'sms' => [
            'username' => 'solecnetlinks',
            'password' => 'jesusbueze@2013',
            'sender' => 'RESULTS'
        ]
    ];

    /**
     * @param $array
     * @return mixed
     */
    public static function send_sms($array)
    {
        $url = "http://portal.nigerianbulksms.com/api/?username=".self::config['sms']['username']."&password=".self::config['sms']['password']."&message=".$array['message']."&sender=".self::config['sms']['sender']."&mobiles=".$array['mobile'];

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}