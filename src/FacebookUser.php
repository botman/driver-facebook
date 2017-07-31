<?php

namespace BotMan\Drivers\Facebook;

use BotMan\BotMan\Users\User;

class FacebookUser extends User
{
    /**
     * @var string
     */
    protected $profile_pic;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var integer
     */
    protected $timezone;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var boolean
     */
    protected $is_payment_enabled;

    /**
     * @var array
     */
    protected $last_ad_referral;

    public function __construct(
        $id = null,
        $first_name = null,
        $last_name = null,
        $username = null,
        $profile_pic = null,
        $locale = null,
        $timezone = null,
        $gender = null,
        $is_payment_enabled = null,
        $last_ad_referral = null,
        array $user_info = []
    ) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->profile_pic = $profile_pic;
        $this->locale = $locale;
        $this->timezone = $timezone;
        $this->gender = $gender;
        $this->is_payment_enabled = $is_payment_enabled;
        $this->last_ad_referral = $last_ad_referral;
        $this->user_info = (array) $user_info;
    }

    /**
     * @return string
     */
    public function getProfilePic()
    {
        return $this->profile_pic;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return integer
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return boolean
     */
    public function getIsPaymentEnabled()
    {
        return $this->is_payment_enabled;
    }

    /**
     * @return array
     */
    public function getLastAdReferral()
    {
        return $this->last_ad_referral;
    }
}
