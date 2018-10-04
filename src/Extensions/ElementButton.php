<?php

namespace BotMan\Drivers\Facebook\Extensions;

class ElementButton
{
    const TYPE_ACCOUNT_LINK = 'account_link';
    const TYPE_ACCOUNT_UNLINK = 'account_unlink';
    const TYPE_WEB_URL = 'web_url';
    const TYPE_PAYMENT = 'payment';
    const TYPE_POSTBACK = 'postback';
    const TYPE_SHARE = 'element_share';
    const TYPE_CALL = 'phone_number';

    const RATIO_COMPACT = 'compact';
    const RATIO_TALL = 'tall';
    const RATIO_FULL = 'full';

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $type = self::TYPE_WEB_URL;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $fallback_url;

    /**
     * @var string
     */
    protected $payload;

    /**
     * @var string
     */
    protected $webview_height_ratio = self::RATIO_FULL;

    /**
     * @var string
     */
    protected $webview_share_button;

    /**
     * @var bool
     */
    protected $messenger_extensions = false;

    /**
     * @var GenericTemplate
     */
    protected $shareContents;

    /**
     * @param null|string $title
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public static function create($title = null): self
    {
        return new static($title);
    }

    /**
     * @param null|string $title
     */
    public function __construct($title = null)
    {
        $this->title = $title;
    }

    /**
     * Set the button URL.
     *
     * @param string $url
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the button type.
     *
     * @param string $type
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $payload
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function payload(string $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @param string $fallback_url
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function fallbackUrl(string $fallback_url): self
    {
        $this->fallback_url = $fallback_url;

        return $this;
    }

    /**
     * enable messenger extensions.
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function enableExtensions(): self
    {
        $this->messenger_extensions = true;

        return $this;
    }

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function disableShare(): self
    {
        $this->webview_share_button = 'HIDE';

        return $this;
    }

    /**
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function removeHeightRatio(): self
    {
        $this->webview_height_ratio = null;

        return $this;
    }

    /**
     * Set ratio to one of RATIO_COMPACT, RATIO_TALL, RATIO_FULL.
     *
     * @param string $ratio
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function heightRatio(string $ratio = self::RATIO_FULL): self
    {
        $this->webview_height_ratio = $ratio;

        return $this;
    }

    /**
     * Optional. The message that you wish the recipient of the share to see,
     * if it is different from the one this button is attached to.
     * The format follows that used in Send API, but must be a generic template with up to one URL button.
     *
     * @param GenericTemplate $shareContents
     *
     * @return \BotMan\Drivers\Facebook\Extensions\ElementButton
     */
    public function shareContents(GenericTemplate $shareContents): self
    {
        $this->shareContents = $shareContents;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $buttonArray = [
            'type' => $this->type,
        ];

        if ($this->type !== self::TYPE_SHARE) {
            if ($this->type !== self::TYPE_ACCOUNT_LINK && $this->type !== self::TYPE_ACCOUNT_UNLINK) {
                $buttonArray['title'] = $this->title;
            }

            if ($this->type === self::TYPE_POSTBACK || $this->type === self::TYPE_CALL) {
                $buttonArray['payload'] = $this->payload;
            } else {
                $buttonArray['url'] = $this->url;
            }

            if ($this->type === self::TYPE_WEB_URL) {
                if ($this->webview_height_ratio !== null) {
                    $buttonArray['webview_height_ratio'] = $this->webview_height_ratio;
                }
                if ($this->webview_share_button !== null) {
                    $buttonArray['webview_share_button'] = $this->webview_share_button;
                }

                if ($this->messenger_extensions) {
                    $buttonArray['messenger_extensions'] = $this->messenger_extensions;
                    $buttonArray['fallback_url'] = $this->fallback_url ?: $this->url;
                }
            }
        } elseif ($this->type === self::TYPE_SHARE && $this->shareContents !== null) {
            $buttonArray['share_contents'] = $this->shareContents->toArray();
        }

        return $buttonArray;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
