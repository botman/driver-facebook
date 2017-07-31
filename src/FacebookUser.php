<?php

namespace BotMan\Drivers\Facebook;

use BotMan\BotMan\Users\User;

class FacebookUser extends User
{
    /**
     * @var array
     */
    protected $user_info;

    public function __construct(
        $id = null,
        $first_name = null,
        $last_name = null,
        $username = null,
        array $user_info = []
    ) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->user_info = (array) $user_info;
    }

    /**
     * @return string
     */
    public function getProfilePic()
    {
        return $this->user_info['profile_pic'] ?? null;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->user_info['locale'] ?? null;
    }

    /**
     * @return int
     */
    public function getTimezone()
    {
        return $this->user_info['timezone'] ?? null;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->user_info['gender'] ?? null;
    }

    /**
     * @return bool
     */
    public function getIsPaymentEnabled()
    {
        return $this->user_info['is_payment_enabled'] ?? null;
    }

    /**
     * @return array
     */
    public function getLastAdReferral()
    {
        return $this->user_info['last_ad_referral'] ?? null;
    }
}
