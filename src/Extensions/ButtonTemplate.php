<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;
use BotMan\BotMan\Interfaces\WebAccess;

class ButtonTemplate implements JsonSerializable, WebAccess
{
    /** @var string */
    protected $text;

    /** @var array */
    protected $buttons = [];

    /**
     * @param string $text
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ButtonTemplate
     */
    public static function create(string $text)
    {
        return new static($text);
    }

    /**
     * ButtonTemplate constructor.
     *
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param \BotMan\Drivers\Facebook\Extensions\ElementButton $button
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ButtonTemplate
     */
    public function addButton(ElementButton $button)
    {
        $this->buttons[] = $button->toArray();

        return $this;
    }

    /**
     * @param array $buttons
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ButtonTemplate
     */
    public function addButtons(array $buttons)
    {
        foreach ($buttons as $button) {
            if ($button instanceof ElementButton) {
                $this->buttons[] = $button->toArray();
            }
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
                    'template_type' => 'button',
                    'text' => $this->text,
                    'buttons' => $this->buttons,
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
            'type' => 'buttons',
            'text' => $this->text,
            'buttons' => $this->buttons,
        ];
    }
}
