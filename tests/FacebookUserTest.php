<?php

namespace Tests\Drivers;

use BotMan\Drivers\Facebook\FacebookUser;
use Mockery as m;
use BotMan\BotMan\Http\Curl;
use PHPUnit_Framework_TestCase;
use React\Promise\TestCase;
use Symfony\Component\HttpFoundation\Request;
use BotMan\Drivers\Facebook\FacebookVideoDriver;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class FacebookUserTest extends PHPUnit_Framework_TestCase
{

    public function createTestUser()
    {
        $user = new FacebookUser(
            '1234',
            'Christine',
            'Manning',
            null,
            'http://profilepic.com',
            'en_US',
            -2,
            'female',
            true,
            [
                'source' => 'ADS',
                'type' => 'OPEN_THREAD',
                'ad_id' => '6045246247433'
            ]
        );

        return $user;
    }

    public function testFirstName()
    {
        $user = $this->createTestUser();

        $this->assertEquals('Christine', $user->getFirstName());
    }

    public function testLastName()
    {
        $user = $this->createTestUser();

        $this->assertEquals('Manning', $user->getLastName());
    }

    public function testUsername()
    {
        $user = $this->createTestUser();

        $this->assertNull($user->getUsername());
    }

    public function testProfilePic()
    {
        $user = $this->createTestUser();

        $this->assertEquals('http://profilepic.com', $user->getProfilePic());
    }

    public function testLocale()
    {
        $user = $this->createTestUser();

        $this->assertEquals('en_US', $user->getLocale());
    }

    public function testTimezone()
    {
        $user = $this->createTestUser();

        $this->assertEquals(-2, $user->getTimezone());
    }

    public function testGender()
    {
        $user = $this->createTestUser();

        $this->assertEquals('female', $user->getGender());
    }

    public function testIsPaymentEnabled()
    {
        $user = $this->createTestUser();

        $this->assertEquals(true, $user->getIsPaymentEnabled());
    }

    public function testLastAdReferral()
    {
        $user = $this->createTestUser();

        $this->assertEquals( [
            'source' => 'ADS',
            'type' => 'OPEN_THREAD',
            'ad_id' => '6045246247433'
        ], $user->getLastAdReferral());
    }
}