<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser()
    {
        $client = static::createClient();

        $client->request(
            'POST', '/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(
                [
                    'email' => 'test@example.com',
                    'firstName' => 'Test',
                    'lastName' => 'User',
                ]
            )
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
