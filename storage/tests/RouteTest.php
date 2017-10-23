<?php

class RouteTest extends TestCase
{


    public function testRoute()
    {

        \Artisan::call('route:list');
        $this->assertTrue(true);

    }

   
}
