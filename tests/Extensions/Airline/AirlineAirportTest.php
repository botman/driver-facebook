<?php

namespace Tests\Extensions\Airline;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlineAirport;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class AirlineAirportTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $airport = new AirlineAirport('JFK', 'New York');
        $this->assertInstanceOf(AirlineAirport::class, $airport);
    }

    /**
 * @test
 **/
    public function it_can_set_a_terminal_and_a_gate()
    {
        $airport = new AirlineAirport('JFK', 'New York');
        $airport->terminal('T1');

        $this->assertSame('T1', Arr::get($airport->toArray(), 'terminal'));
    }

    /**
     * @test
     **/
    public function it_can_set_a_gate()
    {
        $airport = new AirlineAirport('JFK', 'New York');
        $airport->gate('D57');

        $this->assertSame('D57', Arr::get($airport->toArray(), 'gate'));
    }
}
