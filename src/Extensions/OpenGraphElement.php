<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class OpenGraphElement implements JsonSerializable
{
    /** @var string */
    protected $url;

    /** @var object */
    protected $buttons;

    /**
     * @return static
     */
    public static function create()
    {
        return new static;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param ElementButton $button
     * @return $this
     */
    public function addButton(ElementButton $button)
    {
        $this->buttons[] = $button->toArray();

        return $this;
    }

    /**
     * @param array $buttons
     * @return $this
     */
    public function addButtons(array $buttons)
    {
        if (isset($buttons) && is_array($buttons)) {
            foreach ($buttons as $button) {
                if ($button instanceof ElementButton) {
                    $this->buttons[] = $button->toArray();
                }
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
            'url' => $this->url,
            'buttons' => $this->buttons,
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
