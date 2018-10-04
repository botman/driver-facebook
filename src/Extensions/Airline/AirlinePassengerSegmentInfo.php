<?php

namespace BotMan\Drivers\Facebook\Extensions\Airline;

use JsonSerializable;

class AirlinePassengerSegmentInfo implements JsonSerializable
{
    /** @var string */
    protected $segmentId;

    /** @var string */
    protected $passengerId;

    /** @var string */
    protected $seat;

    /** @var string */
    protected $seatType;

    /** @var array */
    protected $productInfo = [];

    /**
     * @param string $segmentId
     * @param string $passengerId
     * @param string $seat
     * @param string $seatType
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Airline\AirlinePassengerSegmentInfo
     */
    public static function create(string $segmentId, string $passengerId, string $seat, string $seatType)
    {
        return new static($segmentId, $passengerId, $seat, $seatType);
    }

    /**
     * AirlinePassengerSegmentInfo constructor.
     *
     * @param string $segmentId
     * @param string $passengerId
     * @param string $seat
     * @param string $seatType
     */
    public function __construct(string $segmentId, string $passengerId, string $seat, string $seatType)
    {
        $this->segmentId = $segmentId;
        $this->passengerId = $passengerId;
        $this->seat = $seat;
        $this->seatType = $seatType;
    }

    /**
     * @param string $title
     * @param string $value
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Airline\AirlinePassengerSegmentInfo
     */
    public function addProductInfo(string $title, string $value)
    {
        $this->productInfo[] = [
            'title' => $title,
            'value' => $value,
        ];

        return $this;
    }

    /**
     * @param array $productsInfo
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Airline\AirlinePassengerSegmentInfo
     */
    public function addProductsInfo(array $productsInfo)
    {
        foreach ($productsInfo as $title => $value) {
            $this->productInfo[] = [
                'title' => $title,
                'value' => $value,
            ];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [
            'segment_id' => $this->segmentId,
            'passenger_id' => $this->passengerId,
            'seat' => $this->seat,
            'seat_type' => $this->seatType,
            'product_info' => $this->productInfo,
        ];

        return array_filter($array);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
