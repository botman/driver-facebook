<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class MediaUrlElement implements JsonSerializable
{
    /**
     * @var string
     */
    protected $media_type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $buttons;

    /**
     * @param string $mediaType
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaUrlElement
     */
    public static function create(string $mediaType): self
    {
        return new static($mediaType);
    }

    /**
     * MediaUrlElement constructor.
     *
     * @param string $mediaType
     */
    public function __construct(string $mediaType)
    {
        $this->media_type = $mediaType;
    }

    /**
     * @param string $url
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaUrlElement
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param \BotMan\Drivers\Facebook\Extensions\ElementButton $button
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaUrlElement
     */
    public function addButton(ElementButton $button): self
    {
        $this->buttons[] = $button->toArray();

        return $this;
    }

    /**
     * @param array $buttons
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaUrlElement
     */
    public function addButtons(array $buttons): self
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
    public function toArray(): array
    {
        return [
            'media_type' => $this->media_type,
            'url' => $this->url,
            'buttons' => $this->buttons,
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
