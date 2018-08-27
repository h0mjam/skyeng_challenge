<?php

namespace src\Integration;

class DataProvider
{
    private $service;

    /**
     * @param $service
     */
    public function __construct(ServiceProvider $service)
    {
        $this->service = $service;
    }
    
    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request)
    {
        // returns a response from external service
    }
}
