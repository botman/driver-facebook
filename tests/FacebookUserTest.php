<?php

namespace Tests\Drivers;

use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\User;

class FacebookUserTest extends PHPUnit_Framework_TestCase
{
    public function createTestUser()
    {
        $userInfo = [
            'id' => '1234',
            'first_name' => 'Christine',
            'last_name' => 'Manning',
            'username' => null,
            'profile_pic' => 'http://profilepic.com',
            'locale' => 'en_US',
            'timezone' => -2,
            'gender' => 'female',
            'is_payment_enabled' => true,
            'last_ad_referral' => [
                'source' => 'ADS',
                'type' => 'OPEN_THREAD',
                'ad_id' => '6045246247433',
            ],
        ];

        $user = new User(
            '1234',
            'Christine',
            'Manning',
            null,
            $userInfo
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

        $this->assertEquals([
            'source' => 'ADS',
            'type' => 'OPEN_THREAD',
            'ad_id' => '6045246247433',
        ], $user->getLastAdReferral());
    }
}
