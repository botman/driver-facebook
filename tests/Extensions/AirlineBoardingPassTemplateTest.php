<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineBoardingPass;
use BotMan\Drivers\Facebook\Extensions\AirlineBoardingPassTemplate;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;

class AirlineBoardingPassTemplateTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $boardingPassTemplate = new AirlineBoardingPassTemplate(
            'You are checked in.',
            'en_US',
            [
                AirlineBoardingPass::create(
                    'Jones Farbound',
                    'CG4X7U',
                    'https://www.example.com/en/logo.png',
                    'M1JONES FARBOUND  CG4X7U nawouehgawgnapwi3jfa0wfh',
                    'https://www.example.com/en/PLAT.png',
                    AirlineFlightInfo::create(
                        'c001',
                        AirlineAirport::create('SFO', 'San Francisco'),
                        AirlineAirport::create('SLC', 'Salt Lake City'),
                        AirlineFlightSchedule::create('2016-01-02T19:45')
                    )
                ),
            ]
        );
        $this->assertInstanceOf(AirlineBoardingPassTemplate::class, $boardingPassTemplate);
    }

    /** @test */
    public function it_can_set_a_theme_color()
    {
        $boardingPassTemplate = new AirlineBoardingPassTemplate(
            'You are checked in.',
            'en_US',
            [
                AirlineBoardingPass::create(
                    'Jones Farbound',
                    'CG4X7U',
                    'https://www.example.com/en/logo.png',
                    'M1JONES FARBOUND  CG4X7U nawouehgawgnapwi3jfa0wfh',
                    'https://www.example.com/en/PLAT.png',
                    AirlineFlightInfo::create(
                        'c001',
                        AirlineAirport::create('SFO', 'San Francisco'),
                        AirlineAirport::create('SLC', 'Salt Lake City'),
                        AirlineFlightSchedule::create('2016-01-02T19:45')
                    )
                ),
            ]
        );
        $boardingPassTemplate->themeColor('#FF0000');

        $this->assertSame('#FF0000', Arr::get($boardingPassTemplate->toArray(), 'attachment.payload.theme_color'));
    }
}
