<?php

namespace Tests\Extensions\Airline;

use BotMan\Drivers\Facebook\Extensions\Airline\AirlinePassengerSegmentInfo;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class AirlinePassengerSegmentInfoTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $passengerSegmentInfo = new AirlinePassengerSegmentInfo('s001', 'p001', '12A', 'Business');
        $this->assertInstanceOf(AirlinePassengerSegmentInfo::class, $passengerSegmentInfo);
    }

    /** @test */
    public function it_can_add_a_product_info()
    {
        $passengerSegmentInfo = new AirlinePassengerSegmentInfo('s001', 'p001', '12A', 'Business');
        $passengerSegmentInfo->addProductInfo('Lounge', 'Complimentary lounge access');

        $this->assertSame('Lounge', Arr::get($passengerSegmentInfo->toArray(), 'product_info.0.title'));
        $this->assertSame('Complimentary lounge access', Arr::get($passengerSegmentInfo->toArray(), 'product_info.0.value'));
    }

    /** @test */
    public function it_can_add_multiple_products_info()
    {
        $passengerSegmentInfo = new AirlinePassengerSegmentInfo('s001', 'p001', '12A', 'Business');
        $passengerSegmentInfo->addProductsInfo([
            'Lounge' => 'Complimentary lounge access',
            'Baggage' => '1 extra bag 50lbs',
        ]);

        $this->assertSame('Lounge', Arr::get($passengerSegmentInfo->toArray(), 'product_info.0.title'));
        $this->assertSame('Complimentary lounge access', Arr::get($passengerSegmentInfo->toArray(), 'product_info.0.value'));
        $this->assertSame('Baggage', Arr::get($passengerSegmentInfo->toArray(), 'product_info.1.title'));
        $this->assertSame('1 extra bag 50lbs', Arr::get($passengerSegmentInfo->toArray(), 'product_info.1.value'));
    }
}
