<?php

namespace App\Service;

use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\WeatherRepository;

class RequestReport
{
    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var CountryRepository
     */
    private $countryRepository;

    /**
     * @var WeatherRepository
     */
    private $weatherRepository;

    /**
     * @var RequestApi $requestApi
     */
    private $requestApi;

    public function __construct(CityRepository $cityRepository, CountryRepository $countryRepository, WeatherRepository $weatherRepository, RequestApi $requestApi)
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->weatherRepository = $weatherRepository;
        $this->requestApi = $requestApi;
    }

    public function process(?string $country = '', ?string $city = '')
    {
        /*
        We have 3 cases here:

        1) We provide the country and the city.
        We need to check if this city is in the list. If the city is not in the list we return the error

        2) We provide the country only
        We need to check if this country is in the list. If the country is not in the list we return the error

        3) We don't provide any of the geo info
        We get the list of cities from the DB and try to update info for all of them
        */

        //1. If we provide city we don't care about the country
        if (!empty($city)) {
            // check if the city is in the database
            $cityInfo = $this->cityRepository->getCityInfoIfExists($city);
            if (!$cityInfo) {
                return ['error' => 'Sorry, it looks like the city name is wrong.'];
            }
            $reportInfo[] = [
                'city_id' => $cityInfo['city_id'],
                'city_name' => $city,
                'country_code' => $cityInfo['country_code']
            ];
        } elseif (!empty ($country)) { //2. The city is empty so we get the list of the cities in this country
            $countryInfo = $this->countryRepository->getCountryInfoIfExists($country);
            if (!$countryInfo) {
                return ['error' => 'Sorry, it looks like the country name is wrong.'];
            }

            $country_id = $countryInfo['country_id'];
            $country_code = $countryInfo['country_code'];

            $reportInfo = $this->cityRepository->getCitiesInCountryInfo($country_id, $country_code);
        } else { //3. No city, no country set, we need to get all cities
            $reportInfo = $this->cityRepository->getAllCitiesInfo();
        }

        //In all cases we populate the $reportInfo with the cities information

        foreach ($reportInfo as $cityInfo)
        {
            // get the info for the spcific city(es)
            $result = $this->requestApi->get($cityInfo['country_code'], $cityInfo['city_name']);

            $weatherData = [];
            foreach ($result as $data)
            {
                //we don't use timezone for now. Probably we may use it to make more accurate date filtering
                if (isset($data->timezone)) $timezone = $data->timezone;

                if (isset($data->data)) {
                    foreach ($data->data as $datesData)
                    {
                        $weatherData[] = [
                            'datetime' => $datesData->datetime,
                            'temp' => $datesData->temp,
                            'max_temp' => $datesData->max_temp,
                            'min_temp' => $datesData->min_temp,
                        ];
                    }
                }
            }

            foreach ($weatherData as $dayWeatherData)
            {
                // update the info for this city in the db
                $this->weatherRepository->store($cityInfo['city_id'], $dayWeatherData);
            }
        }

        return ['message' => 'The data is successfully updated!'];
    }
}