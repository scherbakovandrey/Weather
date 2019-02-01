<?php

namespace App\Tests\Letgo\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountriesControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/countries');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(6, count(json_decode($client->getResponse()->getContent())));

        $list = [
            [
                'id' => 1,
                'country_code' => 'DE',
                'country_name' => 'Germany',
            ],

            [
                'id' => 2,
                'country_code' => 'ES',
                'country_name' => 'Spain',
            ],

            [
                'id' => 3,
                'country_code' => 'AT',
                'country_name' => 'Austria',
            ],

            [
                'id' => 4,
                'country_code' => 'PL',
                'country_name' => 'Poland',
            ],

            [
                'id' => 5,
                'country_code' => 'NL',
                'country_name' => 'Netherlands',
            ],

            [
                'id' => 6,
                'country_code' => 'UK',
                'country_name' => 'United Kingdom',
            ],
        ];

        $this->assertEquals($list, json_decode($client->getResponse()->getContent(), true));
    }
}