<?php

namespace App\Service;

class RequestApi
{
    const WEATHER_API_BASE_URL = 'https://yoc-media.github.io/weather/report/';

    public function get(string $countryCode, string $city)
    {
        $url = WEATHER_API_BASE_URL . rawurlencode($countryCode) . '/' . rawurlencode($city) . '.json';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = json_decode(curl_exec($curl));
        curl_close($curl);

        return $result;
    }
}
