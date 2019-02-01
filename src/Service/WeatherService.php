<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Weather;

class WeatherService
{
    private $entityManager;

    private $start_date;
    private $end_date;

    private $temperature;
    private $direction;

    private $city_id;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setDatePeriod($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function setTemperatureBarrier($temperature, $direction)
    {
        $this->temperature = $temperature;
        $this->direction = $direction;
    }

    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
    }

    public function getAll($page = 1)
    {
        if ( !empty($page) && ( !is_numeric($page) || $page < 1)) {
            return [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'Page parameter MUST be equal or more than 1.',
            ];
        }

        //We may add more parameters checking logic (for dates, for temp)

        $repository = $this->entityManager->getRepository(Weather::class);

        $weatherData = $repository->getAll($page, $this->start_date, $this->end_date, $this->temperature, $this->direction, $this->city_id);

        /* At first decided to show the error message when there are no data based on the filters, but then thought we just return empty array with no error message
        if (empty($weatherData))
        {
            return [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'Not data based on the filters and page number.',
            ];
        }
        */

        return [
            'status' => Response::HTTP_OK,
            'weather' => $weatherData,
            'error' => '',
        ];
    }
}