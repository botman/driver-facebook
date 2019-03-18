<?php

namespace BotMan\Drivers\Facebook\Events;

class MessagingAccountLinking extends FacebookEvent
{
    /**
     * Return the event name to match.
     *
     * @return string
     */
    public function getName()
    {
        return 'messaging_account_linking';
    }
}
