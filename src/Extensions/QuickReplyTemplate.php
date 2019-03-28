<?php
/**
 * Created by PhpStorm.
 * User: obeone
 * Date: 2018-12-13
 * Time: 16:58
 */

namespace BotMan\Drivers\Facebook\Extensions;


class QuickReplyTemplate
{
    /** @var string  */
    protected $title;

    /** @var mixed  */
    protected $attachment;

    /** @var array */
    protected $actions;

    /**
     * @param string $title
     *
     * @return QuickReplyTemplate
     */
    public static function create($title = '')
    {
        return new static($title);
    }


    /**
     * QuickReply constructor.
     *
     * @param $title
     */
    public function __construct($title)
    {
        if (is_string($title)) {
            $this->title = $title;
        } else {
            $this->attachment = $title;
        }

        $this->actions = [];
    }


    /**
     * @param QuestionActionInterface $action
     *
     * @return $this
     */
    public function addAction(QuestionActionInterface $action)
    {
        $this->actions[] = $action->toArray();

        return $this;
    }

    /**
     * @param \BotMan\BotMan\Messages\Outgoing\Actions\Button $button
     * @return $this
     */
    public function addButton(QuickReplyButton $button)
    {
        $this->actions[] = $button->toArray();

        return $this;
    }

    /**
     * @param array $buttons
     * @return $this
     */
    public function addButtons(array $buttons)
    {
        foreach ($buttons as $button) {
            $this->actions[] = $button->toArray();
        }

        return $this;
    }

    public function toArray()
    {
        $ret = [];
        if (!is_null($this->title)) {
            $ret['text'] = $this->title;
        } else {
            $ret = $this->attachment->toArray();
        }

        $ret['quick_replies'] = $this->actions;

        return $ret;
    }
}