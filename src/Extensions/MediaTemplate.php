<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;
use BotMan\BotMan\Interfaces\WebAccess;

class MediaTemplate implements JsonSerializable, WebAccess
{
    /**
     * @var string
     */
    protected $mediaType;

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\MediaTemplate
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param $element
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaTemplate
     */
    public function element($element): self
    {
        $this->elements[] = $element->toArray();

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
                    'template_type' => 'media',
                    'elements' => $this->elements,
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
            'type' => $this->mediaType,
            'elements' => $this->elements,
        ];
    }
}
