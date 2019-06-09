<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function jsonRequest($method, $uri, $data = null) {
        $user = factory('App\User')->create();
        return $this->actingAs($user)->call($method, $uri, 
        [], 
        [], 
        [], 
        $this->transformHeadersToServerVars([
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json'
        ]),
        json_encode($data));
    }
}
