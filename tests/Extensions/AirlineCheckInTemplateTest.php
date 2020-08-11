<?php

namespace Tests\Extensions;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;
use BotMan\Drivers\Facebook\Extensions\AirlineCheckInTemplate;
use PHPUnit_Framework_TestCase;

class AirlineCheckInTemplateTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $checkInTemplate = new AirlineCheckInTemplate(
            'Check-in is available now.',
            'en_US',
            'ABCDEF',
            [
                AirlineFlightInfo::create(
                    'c001',
                    AirlineAirport::create('SFO', 'San Francisco'),
                    AirlineAirport::create('SLC', 'Salt Lake City'),
                    AirlineFlightSchedule::create('2016-01-02T19:45')
                ),
            ],
            'https://www.airline.com/check-in'
        );
        $this->assertInstanceOf(AirlineCheckInTemplate::class, $checkInTemplate);
    }
}
