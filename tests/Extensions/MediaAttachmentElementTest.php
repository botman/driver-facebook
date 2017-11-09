<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\MediaAttachmentElement;

class MediaAttachmentElementTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $element = new MediaAttachmentElement('video');
        $this->assertInstanceOf(MediaAttachmentElement::class, $element);
    }

    /**
     * @test
     **/
    public function it_can_set_media_type()
    {
        $element = new MediaAttachmentElement('video');
        $this->assertSame('video', Arr::get($element->toArray(), 'media_type'));
    }

    /**
     * @test
     **/
    public function it_can_set_attachment_id()
    {
        $element = new MediaAttachmentElement('video');
        $element->attachmentId('1234');
        $this->assertSame('1234', Arr::get($element->toArray(), 'attachment_id'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_button()
    {
        $element = new MediaAttachmentElement('video');
        $element->addButton(ElementButton::create('Button1'));
        $this->assertSame('Button1', Arr::get($element->toArray(), 'buttons.0.title'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_buttons()
    {
        $element = new MediaAttachmentElement('video');
        $element->addButtons([ElementButton::create('Button1'), ElementButton::create('Button2')]);
        $this->assertSame('Button1', Arr::get($element->toArray(), 'buttons.0.title'));
        $this->assertSame('Button2', Arr::get($element->toArray(), 'buttons.1.title'));
    }
}
