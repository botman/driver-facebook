<?php

namespace BotMan\Drivers\Facebook\Extensions;

use JsonSerializable;

class MediaAttachmentElement implements JsonSerializable
{
    /** @var string */
    protected $media_type;

    /** @var string */
    protected $attachment_id;

    /** @var array */
    protected $buttons;

    /**
     * @param string $mediaType
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaAttachmentElement
     */
    public static function create(string $mediaType)
    {
        return new static($mediaType);
    }

    /**
     * MediaAttachmentElement constructor.
     *
     * @param string $mediaType
     */
    public function __construct(string $mediaType)
    {
        $this->media_type = $mediaType;
    }

    /**
     * @param string $attachmentId
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaAttachmentElement
     */
    public function attachmentId(string $attachmentId)
    {
        $this->attachment_id = $attachmentId;

        return $this;
    }

    /**
     * @param \BotMan\Drivers\Facebook\Extensions\ElementButton $button
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaAttachmentElement
     */
    public function addButton(ElementButton $button)
    {
        $this->buttons[] = $button->toArray();

        return $this;
    }

    /**
     * @param array $buttons
     *
     * @return \BotMan\Drivers\Facebook\Extensions\MediaAttachmentElement
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
            'media_type' => $this->media_type,
            'attachment_id' => $this->attachment_id,
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
