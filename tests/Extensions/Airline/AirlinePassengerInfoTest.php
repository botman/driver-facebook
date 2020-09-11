<?php

namespace Tests\Extensions\Airline;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlinePassengerInfo;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class AirlinePassengerInfoTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $passengerInfo = new AirlinePassengerInfo('p001', 'Farbound Smith Jr');
        $this->assertInstanceOf(AirlinePassengerInfo::class, $passengerInfo);
    }

    /** @test */
    public function it_can_set_a_ticket_number()
    {
        $passengerInfo = new AirlinePassengerInfo('p001', 'Farbound Smith Jr');
        $passengerInfo->ticketNumber('0741234567890');

        $this->assertSame('0741234567890', Arr::get($passengerInfo->toArray(), 'ticket_number'));
    }
}
