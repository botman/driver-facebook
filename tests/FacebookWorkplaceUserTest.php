<?php

namespace Tests\Drivers;

use BotMan\Drivers\Facebook\Extensions\User;
use PHPUnit_Framework_TestCase;

class FacebookWorkplaceUserTest extends PHPUnit_Framework_TestCase
{
    public function createTestUser()
    {
        $userInfo = [
            'id' => '1234',
            'first_name' => 'Christine',
            'last_name' => 'Manning',
            'email' => 'christine.manning@example.com',
            'title' => 'Experimenter',
            'picture' => [
                'data' => [
                    'height' => 50,
                    'is_silhouette' => true,
                    'url' => 'http://profilepic.com',
                    'width' => 50,
                ],
            ],
            'link' => 'http://workplace-link.facebook.com/app_scoped_user_id/100014245873942/',
            'locale' => 'en_US',
            'name' => 'Christine Manning',
            'name_format' => '{first} {last}',
            'updated_time' => '2016-11-24T06:37:15+0000',
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
