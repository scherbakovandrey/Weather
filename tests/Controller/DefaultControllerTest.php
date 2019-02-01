<?php

namespace App\Tests\Letgo\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexRoute()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(['hello' => 'world!'], json_decode($client->getResponse()->getContent(), true));
    }
}