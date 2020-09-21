<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class ReceiptElement implements JsonSerializable
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $subtitle;

    /** @var int */
    protected $quantity;

    /** @var int */
    protected $price = 0;

    /** @var string */
    protected $currency;

    /** @var string */
    protected $image_url;

    /**
     * @param string $title
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptElement
     */
    public static function create(string $title)
    {
        return new static($title);
    }

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $subtitle
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptElement
     */
    public function subtitle(string $subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @param int $quantity
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptElement
     */
    public function quantity(int $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @param float $price
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptElement
     */
    public function price(float $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param string $currency
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptElement
     */
    public function currency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param string $image_url
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptElement
     */
    public function image(string $image_url)
    {
        $this->image_url = $image_url;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'currency' => $this->currency,
            'image_url' => $this->image_url,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
