<?php

namespace App\Tests\WebTests;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowPublicPasteApiTest extends WebTestCase
{
    public function testShowForm(): void
    {
        $client = new Client();
        $request = $client->get('http://172.19.0.3/api/v1/paste');
        $data = json_decode($request->getBody(), true);

        $this->assertEquals(200, $request->getStatusCode());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
    }
}