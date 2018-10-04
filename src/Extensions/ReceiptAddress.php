<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class ReceiptAddress implements JsonSerializable
{
    /**
     * @var string
     */
    protected $street_1;

    /**
     * @var string
     */
    protected $street_2;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $postal_code;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $country;

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAddress
     */
    public static function create(): self
    {
        return new static;
    }

    /**
     * @param string $street
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAddress
     */
    public function street1(string $street): self
    {
        $this->street_1 = $street;

        return $this;
    }

    /**
     * @param string $street
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAddress
     */
    public function street2(string $street): self
    {
        $this->street_2 = $street;

        return $this;
    }

    /**
     * @param string $city
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAddress
     */
    public function city(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param string $postalCode
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAddress
     */
    public function postalCode(string $postalCode): self
    {
        $this->postal_code = $postalCode;

        return $this;
    }

    /**
     * @param string $state
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAddress
     */
    public function state(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param $country
     *
     * @return $this
     */
    public function country(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'street_1' => $this->street_1,
            'street_2' => $this->street_2,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'state' => $this->state,
            'country' => $this->country,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
