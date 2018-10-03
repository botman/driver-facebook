<?php

namespace Tests\Extensions;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;
use BotMan\Drivers\Facebook\Extensions\AirlineUpdateTemplate;
use BotMan\Drivers\Facebook\Interfaces\Airline;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class AirlineUpdateTemplateTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $updateTemplate = new AirlineUpdateTemplate(
            Airline::UPDATE_TYPE_DELAY,
            'en_US',
            'CF23G2',
            AirlineFlightInfo::create(
                'c001',
                AirlineAirport::create('SFO', 'San Francisco'),
                AirlineAirport::create('SLC', 'Salt Lake City'),
                AirlineFlightSchedule::create('2016-01-02T19:45')
            )
        );
        $this->assertInstanceOf(AirlineUpdateTemplate::class, $updateTemplate);
    }

    public function it_can_set_an_intro_message()
    {
        $updateTemplate = new AirlineUpdateTemplate(
            Airline::UPDATE_TYPE_DELAY,
            'en_US',
            'CF23G2',
            AirlineFlightInfo::create(
                'c001',
                AirlineAirport::create('SFO', 'San Francisco'),
                AirlineAirport::create('SLC', 'Salt Lake City'),
                AirlineFlightSchedule::create('2016-01-02T19:45')
            )
        );
        $updateTemplate->introMessage('Your flight is delayed');

        $this->assertSame('Your flight is delayed', Arr::get($updateTemplate->toArray(), 'attachment.payload.intro_message'));
    }
}
