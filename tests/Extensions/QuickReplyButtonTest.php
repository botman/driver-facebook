<?php

namespace Tests\Extensions;

use BotMan\Drivers\Facebook\Extensions\QuickReplyButton;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class QuickReplyButtonTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $button = new QuickReplyButton('click me');
        $this->assertInstanceOf(QuickReplyButton::class, $button);
    }

    /**
     * @test
     **/
    public function standard_content_type_is_text()
    {
        $button = new QuickReplyButton('click me');

        $this->assertSame('text', Arr::get($button->toArray(), 'content_type'));
    }

    /**
     * @test
     **/
    public function it_can_set_type()
    {
        $button = new QuickReplyButton('click me');
        $button->type('user_email');

        $this->assertSame('user_email', Arr::get($button->toArray(), 'content_type'));
    }

    /**
     * @test
     **/
    public function it_can_set_title()
    {
        $button = new QuickReplyButton('click me');

        $this->assertSame('click me', Arr::get($button->toArray(), 'title'));
    }

    /**
     * @test
     **/
    public function it_can_set_payload()
    {
        $button = new QuickReplyButton('click me');
        $button->payload('clickme');

        $this->assertSame('clickme', Arr::get($button->toArray(), 'payload'));
    }

    /**
     * @test
     **/
    public function it_can_set_image_url()
    {
        $button = new QuickReplyButton('click me');
        $button->imageUrl('https://botman.io/img/logo.png');

        $this->assertSame('https://botman.io/img/logo.png', Arr::get($button->toArray(), 'image_url'));
    }
}
