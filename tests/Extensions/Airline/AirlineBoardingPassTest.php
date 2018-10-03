<?php

namespace Tests\Extensions\Airline;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineBoardingPass;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;
use BotMan\Drivers\Facebook\Interfaces\Airline;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class AirlineBoardingPassTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $this->assertInstanceOf(AirlineBoardingPass::class, $boardingPass);
    }

    /** @test */
    public function it_can_set_a_travel_class()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->travelClass(Airline::TRAVEL_TYPE_BUSINESS);

        $this->assertSame(Airline::TRAVEL_TYPE_BUSINESS, Arr::get($boardingPass->toArray(), 'travel_class'));
    }

    /** @test */
    public function it_can_set_a_seat()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->seat('74J');

        $this->assertSame('74J', Arr::get($boardingPass->toArray(), 'seat'));
    }

    /** @test */
    public function it_can_add_an_auxiliary_field()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->addAuxiliaryField('Terminal', 'T1');

        $this->assertSame('Terminal', Arr::get($boardingPass->toArray(), 'auxiliary_fields.0.label'));
        $this->assertSame('T1', Arr::get($boardingPass->toArray(), 'auxiliary_fields.0.value'));
    }

    /** @test */
    public function it_can_add_auxiliary_fields()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->addAuxiliaryFields(['Terminal' => 'T1', 'Departure' => '30OCT 19:05']);

        $this->assertSame('Terminal', Arr::get($boardingPass->toArray(), 'auxiliary_fields.0.label'));
        $this->assertSame('T1', Arr::get($boardingPass->toArray(), 'auxiliary_fields.0.value'));
        $this->assertSame('Departure', Arr::get($boardingPass->toArray(), 'auxiliary_fields.1.label'));
        $this->assertSame('30OCT 19:05', Arr::get($boardingPass->toArray(), 'auxiliary_fields.1.value'));
    }

    /** @test */
    public function it_can_add_a_secondary_field()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->addSecondaryField('Terminal', 'T1');

        $this->assertSame('Terminal', Arr::get($boardingPass->toArray(), 'secondary_fields.0.label'));
        $this->assertSame('T1', Arr::get($boardingPass->toArray(), 'secondary_fields.0.value'));
    }

    /** @test */
    public function it_can_add_secondary_fields()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->addSecondaryFields(['Terminal' => 'T1', 'Departure' => '30OCT 19:05']);

        $this->assertSame('Terminal', Arr::get($boardingPass->toArray(), 'secondary_fields.0.label'));
        $this->assertSame('T1', Arr::get($boardingPass->toArray(), 'secondary_fields.0.value'));
        $this->assertSame('Departure', Arr::get($boardingPass->toArray(), 'secondary_fields.1.label'));
        $this->assertSame('30OCT 19:05', Arr::get($boardingPass->toArray(), 'secondary_fields.1.value'));
    }

    /** @test */
    public function it_can_set_a_header_image_url()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->headerImageUrl('https://www.example.com/en/fb/header.png');

        $this->assertSame('https://www.example.com/en/fb/header.png', Arr::get($boardingPass->toArray(), 'header_image_url'));
    }

    /** @test */
    public function it_can_set_a_header_text_field()
    {
        $boardingPass = new AirlineBoardingPass(
            'Smith Nicolas',
            'CG4X7U',
            'https://www.example.com/en/logo.png',
            'M1SMITH NICOLAS  CG4X7U nawouehgawgnapwi3jfa0wfh',
            'https://www.example.com/en/PLAT.png',
            AirlineFlightInfo::create(
                'KL0642',
                AirlineAirport::create('JFK', 'New York'),
                AirlineAirport::create('AMS', 'Amsterdam'),
                AirlineFlightSchedule::create('2016-01-02T19:05')
            )
        );
        $boardingPass->headerTextField('Boarding Pass');

        $this->assertSame('Boarding Pass', Arr::get($boardingPass->toArray(), 'header_text_field'));
    }
}
