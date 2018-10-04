<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;
use BotMan\BotMan\Interfaces\WebAccess;

class GenericTemplate implements JsonSerializable, WebAccess
{
    const RATIO_HORIZONTAL = 'horizontal';
    const RATIO_SQUARE = 'square';

    /** @var array */
    private static $allowedRatios = [
        self::RATIO_HORIZONTAL,
        self::RATIO_SQUARE,
    ];

    /** @var array */
    protected $elements = [];

    /** @var string */
    protected $imageAspectRatio = self::RATIO_HORIZONTAL;

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\GenericTemplate
     */
    public static function create()
    {
        return new static;
    }

    /**
     * @param \BotMan\Drivers\Facebook\Extensions\Element $element
     *
     * @return \BotMan\Drivers\Facebook\Extensions\GenericTemplate
     */
    public function addElement(Element $element)
    {
        $this->elements[] = $element->toArray();

        return $this;
    }

    /**
     * @param array $elements
     *
     * @return \BotMan\Drivers\Facebook\Extensions\GenericTemplate
     */
    public function addElements(array $elements)
    {
        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $this->elements[] = $element->toArray();
            }
        }

        return $this;
    }

    /**
     * @param string $ratio
     *
     * @return \BotMan\Drivers\Facebook\Extensions\GenericTemplate
     */
    public function addImageAspectRatio(string $ratio)
    {
        if (\ in_array($ratio, self::$allowedRatios, true)) {
            $this->imageAspectRatio = $ratio;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => 'generic',
                    'image_aspect_ratio' => $this->imageAspectRatio,
                    'elements' => $this->elements,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the instance as a web accessible array.
     * This will be used within the WebDriver.
     *
     * @return array
     */
    public function toWebDriver()
    {
        return [
            'type' => 'list',
            'elements' => $this->elements,
        ];
    }
}
