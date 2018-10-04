<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class ReceiptAdjustment implements JsonSerializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @param string $name
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAdjustment
     */
    public static function create(string $name): self
    {
        return new static($name);
    }

    /**
     * ReceiptAdjustment constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param float $amount
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptAdjustment
     */
    public function amount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
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
