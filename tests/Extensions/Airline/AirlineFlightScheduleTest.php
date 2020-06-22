<?php

namespace Tests\Extensions\Airline;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class AirlineFlightScheduleTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $flightSchedule = new AirlineFlightSchedule('2016-01-05T15:45');
        $this->assertInstanceOf(AirlineFlightSchedule::class, $flightSchedule);
    }

    /** @test */
    public function it_can_set_an_arrival_tile()
    {
        $flightSchedule = new AirlineFlightSchedule('2016-01-05T15:45');
        $flightSchedule->arrivalTime('2016-01-05T17:30');

        $this->assertSame('2016-01-05T17:30', Arr::get($flightSchedule->toArray(), 'arrival_time'));
    }

    /** @test */
    public function it_can_set_a_boarding_time()
    {
        $flightSchedule = new AirlineFlightSchedule('2016-01-05T15:45');
        $flightSchedule->boardingTime('2016-01-05T17:30');

        $this->assertSame('2016-01-05T17:30', Arr::get($flightSchedule->toArray(), 'boarding_time'));
    }
}
