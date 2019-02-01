<?php

namespace App\Tests\Letgo\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{
    public function testIndexRoute()
    {
        $client = static::createClient();
        $client->request('GET', '/weather');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}