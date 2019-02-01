<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\WeatherService;

class WeatherController
{
    public function index(Request $request, WeatherService $weatherService)
    {
        $page = $request->get('page') ?? 1;
        $city_id = $request->get('city_id');

        $start_date = $request->get('start_date') ?? date('Y-m-d', strtotime('-7 days')); // last 7 days by default
        $end_date = $request->get('end_date') ?? date('Y-m-d'); // last 7 days by default

        $temperature = $request->get('temperature');
        $direction = $request->get('direction') ?? 'less'; // 'higher'

        $weatherService->setDatePeriod($start_date, $end_date);
        $weatherService->setTemperatureBarrier($temperature, $direction);
        $weatherService->setCityId($city_id);

        //we make this as a service so we are free to use it from the command line or from the web interface
        $apiResponse = $weatherService->getAll($page);

        if ($apiResponse['status'] != Response::HTTP_OK) {
            return new JsonResponse($apiResponse, $apiResponse['status']);
        }

        return JsonResponse::create($apiResponse['weather']);
    }
}