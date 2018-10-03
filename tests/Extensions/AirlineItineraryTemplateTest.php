<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Interfaces\Airline;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use BotMan\Drivers\Facebook\Extensions\AirlineItineraryTemplate;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlinePassengerInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightSchedule;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineExtendedFlightInfo;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlinePassengerSegmentInfo;

class AirlineItineraryTemplateTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $itineraryTemplate = new AirlineItineraryTemplate(
            'Here\'s your flight itinerary.',
            'en_US',
            'ABCDEF',
            [
                AirlinePassengerInfo::create('p001', 'Farbound Smith Jr'),
            ],
            [
                AirlineExtendedFlightInfo::create(
                    'c001',
                    's001',
                    'KL9123',
                    AirlineAirport::create('SFO', 'San Francisco'),
                    AirlineAirport::create('SLC', 'Salt Lake City'),
                    AirlineFlightSchedule::create('2016-01-02T19:45'),
                    Airline::TRAVEL_TYPE_FIRST_CLASS
                ),
            ],
            [
                AirlinePassengerSegmentInfo::create('s001', 'p001', '12A', 'Business'),
            ],
            '14003',
            'USD'
        );
        $this->assertInstanceOf(AirlineItineraryTemplate::class, $itineraryTemplate);
    }

    /** @test */
    public function it_can_add_price_info()
    {
        $itineraryTemplate = new AirlineItineraryTemplate(
            'Here\'s your flight itinerary.',
            'en_US',
            'ABCDEF',
            [
                AirlinePassengerInfo::create('p001', 'Farbound Smith Jr'),
            ],
            [
                AirlineExtendedFlightInfo::create(
                    'c001',
                    's001',
                    'KL9123',
                    AirlineAirport::create('SFO', 'San Francisco'),
                    AirlineAirport::create('SLC', 'Salt Lake City'),
                    AirlineFlightSchedule::create('2016-01-02T19:45'),
                    Airline::TRAVEL_TYPE_FIRST_CLASS
                ),
            ],
            [
                AirlinePassengerSegmentInfo::create('s001', 'p001', '12A', 'Business'),
            ],
            '14003',
            'USD'
        );
        $itineraryTemplate->addPriceInfo('Fuel surcharge', '1597', 'USD');

        $this->assertSame('Fuel surcharge', Arr::get($itineraryTemplate->toArray(), 'attachment.payload.price_info.0.title'));
        $this->assertSame('1597', Arr::get($itineraryTemplate->toArray(), 'attachment.payload.price_info.0.amount'));
        $this->assertSame('USD', Arr::get($itineraryTemplate->toArray(), 'attachment.payload.price_info.0.currency'));
    }

    /** @test */
    public function it_can_set_a_base_price()
    {
        $itineraryTemplate = new AirlineItineraryTemplate(
            'Here\'s your flight itinerary.',
            'en_US',
            'ABCDEF',
            [
                AirlinePassengerInfo::create('p001', 'Farbound Smith Jr'),
            ],
            [
                AirlineExtendedFlightInfo::create(
                    'c001',
                    's001',
                    'KL9123',
                    AirlineAirport::create('SFO', 'San Francisco'),
                    AirlineAirport::create('SLC', 'Salt Lake City'),
                    AirlineFlightSchedule::create('2016-01-02T19:45'),
                    Airline::TRAVEL_TYPE_FIRST_CLASS
                ),
            ],
            [
                AirlinePassengerSegmentInfo::create('s001', 'p001', '12A', 'Business'),
            ],
            '14003',
            'USD'
        );
        $itineraryTemplate->basePrice('12206');

        $this->assertSame('12206', Arr::get($itineraryTemplate->toArray(), 'attachment.payload.base_price'));
    }

    /** @test */
    public function it_can_set_a_tax()
    {
        $itineraryTemplate = new AirlineItineraryTemplate(
            'Here\'s your flight itinerary.',
            'en_US',
            'ABCDEF',
            [
                AirlinePassengerInfo::create('p001', 'Farbound Smith Jr'),
            ],
            [
                AirlineExtendedFlightInfo::create(
                    'c001',
                    's001',
                    'KL9123',
                    AirlineAirport::create('SFO', 'San Francisco'),
                    AirlineAirport::create('SLC', 'Salt Lake City'),
                    AirlineFlightSchedule::create('2016-01-02T19:45'),
                    Airline::TRAVEL_TYPE_FIRST_CLASS
                ),
            ],
            [
                AirlinePassengerSegmentInfo::create('s001', 'p001', '12A', 'Business'),
            ],
            '14003',
            'USD'
        );
        $itineraryTemplate->tax('12206');

        $this->assertSame('12206', Arr::get($itineraryTemplate->toArray(), 'attachment.payload.tax'));
    }
}
