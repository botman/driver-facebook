<?php

namespace Tests\Extensions\Airline;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineExtendedFlightInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;
use BotMan\Drivers\Facebook\Interfaces\Airline;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class AirlineExtendedFlightInfoTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $extendedFlighInfo = new AirlineExtendedFlightInfo(
            'c001',
            's001',
            'KL9123',
            AirlineAirport::create('SFO', 'San Francisco'),
            AirlineAirport::create('SLC', 'Salt Lake City'),
            AirlineFlightSchedule::create('2016-01-02T19:45'),
            Airline::TRAVEL_TYPE_FIRST_CLASS
        );
        $this->assertInstanceOf(AirlineExtendedFlightInfo::class, $extendedFlighInfo);
    }

    /** @test */
    public function it_can_set_an_aircraft_type()
    {
        $extendedFlighInfo = new AirlineExtendedFlightInfo(
            'c001',
            's001',
            'KL9123',
            AirlineAirport::create('SFO', 'San Francisco'),
            AirlineAirport::create('SLC', 'Salt Lake City'),
            AirlineFlightSchedule::create('2016-01-02T19:45'),
            Airline::TRAVEL_TYPE_FIRST_CLASS
        );
        $extendedFlighInfo->aircraftType('Boeing 737');

        $this->assertSame('Boeing 737', Arr::get($extendedFlighInfo->toArray(), 'aircraft_type'));
    }
}
