<?php

namespace BotMan\Drivers\Facebook\Extensions;

use BotMan\BotMan\Users\User as BotManUser;

class User extends BotManUser
{
    /**
     * @return string|null
     */
    public function getProfilePic()
    {
        if (isset($this->user_info['profile_pic'])) {
            return $this->user_info['profile_pic'];
        }

        // Workplace (Facebook for companies) uses picture parameter
        if (isset($this->user_info['picture'])) {
            return $this->user_info['picture']['data']['url'];
        }
    }

    /**
     * @return string|null
     */
    public function getLocale()
    {
        return $this->user_info['locale'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getTimezone()
    {
        return $this->user_info['timezone'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getGender()
    {
        return $this->user_info['gender'] ?? null;
    }

    /**
     * @return bool|null
     */
    public function getIsPaymentEnabled()
    {
        return $this->user_info['is_payment_enabled'] ?? null;
    }

    /**
     * @return array|null
     */
    public function getLastAdReferral()
    {
        return $this->user_info['last_ad_referral'] ?? null;
    }
}
