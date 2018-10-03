<?php

namespace BotMan\Drivers\Facebook\Extensions;

use BotMan\BotMan\Interfaces\WebAccess;
use BotMan\Drivers\Facebook\Exceptions\FacebookException;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightInfo;
use BotMan\Drivers\Facebook\Interfaces\Airline;
use JsonSerializable;

class AirlineUpdateTemplate implements JsonSerializable, WebAccess, Airline
{
    /**
     * @var string
     */
    protected $introMessage;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $updateType;

    /**
     * @var string
     */
    protected $pnrNumber;

    /**
     * @var \BotMan\Drivers\Facebook\Extensions\AirlineFlightInfo
     */
    protected $updateFlightInfo;

    /**
     * AirlineUpdateTemplate constructor.
     *
     * @param string                                                        $updateType
     * @param string                                                        $locale
     * @param string                                                        $pnrNumber
     * @param \BotMan\Drivers\Facebook\Extensions\Airline\AirlineFlightInfo $updateFlightInfo
     *
     * @throws \BotMan\Drivers\Facebook\Exceptions\FacebookException
     */
    public function __construct(
        string $updateType,
        string $locale,
        string $pnrNumber,
        AirlineFlightInfo $updateFlightInfo
    ) {
        if (!\in_array($updateType, self::UPDATE_TYPES, true)) {
            throw new FacebookException(
                sprintf('update_type must be either "%s"', implode(', ', self::UPDATE_TYPES))
            );
        }

        $this->updateType = $updateType;
        $this->locale = $locale;
        $this->pnrNumber = $pnrNumber;
        $this->updateFlightInfo = $updateFlightInfo;
    }

    /**
     * @param string $introMessage
     *
     * @return \BotMan\Drivers\Facebook\Extensions\AirlineUpdateTemplate
     */
    public function introMessage(string $introMessage): self
    {
        $this->introMessage = $introMessage;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => 'airline_update',
                    'intro_message' => $this->introMessage,
                    'update_type' => $this->updateType,
                    'locale' => $this->locale,
                    'pnr_number' => $this->pnrNumber,
                    'update_flight_info' => $this->updateFlightInfo,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the instance as a web accessible array.
     * This will be used within the WebDriver.
     *
     * @return array
     */
    public function toWebDriver(): array
    {
        return [
            'template_type' => 'airline_update',
            'intro_message' => $this->introMessage,
            'update_type' => $this->updateType,
            'locale' => $this->locale,
            'pnr_number' => $this->pnrNumber,
            'update_flight_info' => $this->updateFlightInfo,
        ];
    }
}
