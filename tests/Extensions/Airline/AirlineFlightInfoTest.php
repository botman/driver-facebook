<?php

namespace Tests\Extensions\Airline;

use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;

class AirlineFlightInfoTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $flighInfo = new AirlineFlightInfo(
            'c001',
            AirlineAirport::create('SFO', 'San Francisco'),
            AirlineAirport::create('SLC', 'Salt Lake City'),
            AirlineFlightSchedule::create('2016-01-02T19:45')
        );
        $this->assertInstanceOf(AirlineFlightInfo::class, $flighInfo);
    }
}
