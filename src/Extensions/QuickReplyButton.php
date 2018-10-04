<?php

namespace BotMan\Drivers\Facebook\Extensions;

use BotMan\BotMan\Interfaces\QuestionActionInterface;

class QuickReplyButton implements QuestionActionInterface
{
    const TYPE_TEXT = 'text';

    /** @var string */
    protected $contentType = self::TYPE_TEXT;

    /** @var string */
    protected $title;

    /** @var string */
    protected $payload;

    /** @var string */
    protected $imageUrl;

    /**
     * @param string $title
     *
     * @return \BotMan\Drivers\Facebook\Extensions\QuickReplyButton
     */
    public static function create(string $title = '')
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
     * Set the button type.
     *
     * @param string $type
     *
     * @return \BotMan\Drivers\Facebook\Extensions\QuickReplyButton
     */
    public function type(string $type)
    {
        $this->contentType = $type;

        return $this;
    }

    /**
     * @param string $payload
     *
     * @return \BotMan\Drivers\Facebook\Extensions\QuickReplyButton
     */
    public function payload(string $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return \BotMan\Drivers\Facebook\Extensions\QuickReplyButton
     */
    public function imageUrl(string $url)
    {
        $this->imageUrl = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $buttonArray = [];

        if ($this->contentType === self::TYPE_TEXT) {
            $buttonArray = [
                'content_type' => $this->contentType,
                'title' => $this->title,
                'payload' => $this->payload,
                'image_url' => $this->imageUrl,
            ];
        } else {
            $buttonArray['content_type'] = $this->contentType;
        }

        return $buttonArray;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
