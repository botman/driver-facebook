<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class ReceiptSummary implements JsonSerializable
{
    /** @var int */
    protected $subtotal;

    /** @var int */
    protected $shipping_cost;

    /** @var int */
    protected $total_tax;

    /** @var int */
    protected $total_cost;

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public static function create()
    {
        return new static;
    }

    /**
     * @param float $subtotal
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function subtotal(float $subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * @param float $shippingCost
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function shippingCost(float $shippingCost)
    {
        $this->shipping_cost = $shippingCost;

        return $this;
    }

    /**
     * @param float $totalTax
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function totalTax(float $totalTax)
    {
        $this->total_tax = $totalTax;

        return $this;
    }

    /**
     * @param float $totalCost
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ReceiptSummary
     */
    public function totalCost(float $totalCost)
    {
        $this->total_cost = $totalCost;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
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
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
