<?php

namespace BotMan\Drivers\Facebook\Events;

class Standby extends FacebookEvent
{
    /**
     * Return the event name to match.
     *
     * @return string
     */
    public function getName()
    {
        return 'standby';
    }
}
