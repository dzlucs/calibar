<?php

namespace Tests\Unit\Access;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AdminsProfileAccessTest extends TestCase
{
    private Client $client;

    public function setup(): void
    {
        parent::setUp();
        $this->client = new Client([
            'allow_redirects' => false, // Disable following redirects
            'base_uri' => 'http://web:8080'
        ]);
    }

    public function test_should_not_access_the_admin_index_route_if_not_authenticated(): void
    {
        $response = $this->client->get('/admin');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_read_route_if_not_authenticated(): void
    {
        $response = $this->client->get('/admin/drinks');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_create_get_route_if_not_authenticated(): void
    {
        $response = $this->client->get('/admin/drinks/new');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_create_post_route_if_not_authenticated(): void
    {
        $response = $this->client->post('/admin/drinks');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_edit_route_if_not_authenticated(): void
    {
        $response = $this->client->get('/admin/drinks/1/edit');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_update_route_if_not_authenticated(): void
    {
        $response = $this->client->put('/admin/drinks/1');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_delete_route_if_not_authenticated(): void
    {
        $response = $this->client->delete('/admin/drinks/1');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_show_route_if_not_authenticated(): void
    {
        $response = $this->client->get('/admin/drinks/1');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function test_should_not_access_the_paginate_route_if_not_authenticated(): void
    {
        $response = $this->client->get('/admin/drinks/page/1');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }
}
