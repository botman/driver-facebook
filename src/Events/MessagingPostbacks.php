<?php

namespace BotMan\Drivers\Facebook\Events;

class MessagingPostbacks extends FacebookEvent
{
    /**
     * Return the event name to match.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'messaging_postbacks';
    }
}
