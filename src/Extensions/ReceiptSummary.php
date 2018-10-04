<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class ReceiptSummary implements JsonSerializable
{
    /**
     * @var int
     */
    protected $subtotal;

    /**
     * @var int
     */
    protected $shipping_cost;

    /**
     * @var int
     */
    protected $total_tax;

    /**
     * @var int
     */
    protected $total_cost;

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public static function create(): self
    {
        return new static;
    }

    /**
     * @param float $subtotal
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function subtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * @param float $shippingCost
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function shippingCost(float $shippingCost): self
    {
        $this->shipping_cost = $shippingCost;

        return $this;
    }

    /**
     * @param float $totalTax
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function totalTax(float $totalTax): self
    {
        $this->total_tax = $totalTax;

        return $this;
    }

    /**
     * @param float $totalCost
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function totalCost(float $totalCost): self
    {
        $this->total_cost = $totalCost;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping_cost,
            'total_tax' => $this->total_tax,
            'total_cost' => $this->total_cost,
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
