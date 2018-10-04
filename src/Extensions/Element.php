<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class Element implements JsonSerializable
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $image_url;

    /** @var string */
    protected $item_url;

    /** @var string */
    protected $subtitle;

    /** @var object */
    protected $buttons;

    /** @var object */
    protected $default_action;

    /**
     * @param string $title
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Element
     */
    public static function create(string $title)
    {
        return new static($title);
    }

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $title
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Element
     */
    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $subtitle
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Element
     */
    public function subtitle(string $subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @param string $image_url
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Element
     */
    public function image(string $image_url)
    {
        $this->image_url = $image_url;

        return $this;
    }

    /**
     * @param string $item_url
     *
     * @return $this
     */
    public function itemUrl(string $item_url)
    {
        $this->item_url = $item_url;

        return $this;
    }

    /**
     * @param \BotMan\Drivers\Facebook\Extensions\ElementButton $button
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Element
     */
    public function addButton(ElementButton $button)
    {
        $this->buttons[] = $button->toArray();

        return $this;
    }

    /**
     * @param array $buttons
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Element
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
     * @param \BotMan\Drivers\Facebook\Extensions\ElementButton $defaultAction
     *
     * @return \BotMan\Drivers\Facebook\Extensions\Element
     */
    public function defaultAction(ElementButton $defaultAction)
    {
        $defaultAction->type(ElementButton::TYPE_WEB_URL);
        $this->default_action = $defaultAction->toArray();

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'image_url' => $this->image_url,
            'item_url' => $this->item_url,
            'subtitle' => $this->subtitle,
            'default_action' => $this->default_action,
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
