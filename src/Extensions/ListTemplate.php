<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;
use BotMan\BotMan\Interfaces\WebAccess;

class ListTemplate implements JsonSerializable, WebAccess
{
    /** @var array */
    protected $elements = [];

    /** @var array */
    protected $globalButton;

    /** @var string */
    protected $top_element_style = 'large';

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\ListTemplate
     */
    public static function create()
    {
        return new static;
    }

    /**
     * @param \BotMan\Drivers\Facebook\Extensions\Element $element
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ListTemplate
     */
    public function addElement(Element $element)
    {
        $this->elements[] = $element->toArray();

        return $this;
    }

    /**
     * @param array $elements
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ListTemplate
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
     * @param \BotMan\Drivers\Facebook\Extensions\ElementButton $button
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ListTemplate
     */
    public function addGlobalButton(ElementButton $button)
    {
        $this->globalButton = $button->toArray();

        return $this;
    }

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\ListTemplate
     */
    public function useCompactView()
    {
        $this->top_element_style = 'compact';

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
                    'template_type' => 'list',
                    'top_element_style' => $this->top_element_style,
                    'elements' => $this->elements,
                    'buttons' => [
                        $this->globalButton,
                    ],
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
            'globalButtons' => [$this->globalButton],
        ];
    }
}
