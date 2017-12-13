<?php

namespace Tests\Drivers;

use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\User;

class FacebookWorkplaceUserTest extends PHPUnit_Framework_TestCase
{
    public function createTestUser()
    {
        $userInfo = [
            'id' => '1234',
            'first_name' => 'Christine',
            'last_name' => 'Manning',
            'email' => 'christine.manning@example.com',
            'title' => null,
            'departure' => 'sales',
            'employee_number' => 123,
            'primary_phone' => '004366466121212', 
            'primary_address' => 'address',
            'picture' => 'http://profilepic.com',
            'link' => 'http://workplace-link.facebook.com',
            'locale' => 'en_US',
            'name' => 'Christine Manning',
            'name_format' => 'Christine Manning'
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

        $this->assertNull($user->getTimezone());
    }

    public function testGender()
    {
        $user = $this->createTestUser();

        $this->assertNull($user->getGender());
    }

    public function testIsPaymentEnabled()
    {
        $user = $this->createTestUser();

        $this->assertNull($user->getIsPaymentEnabled());
    }

    public function testLastAdReferral()
    {
        $user = $this->createTestUser();

        $this->assertNull($user->getLastAdReferral());
    }
}
