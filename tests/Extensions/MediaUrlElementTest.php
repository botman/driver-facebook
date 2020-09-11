<?php

namespace Tests\Extensions;

use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\MediaUrlElement;
use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;

class MediaUrlElementTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $element = new MediaUrlElement('video');
        $this->assertInstanceOf(MediaUrlElement::class, $element);
    }

    /**
     * @test
     **/
    public function it_can_set_media_type()
    {
        $element = new MediaUrlElement('video');
        $this->assertSame('video', Arr::get($element->toArray(), 'media_type'));
    }

    /**
     * @test
     **/
    public function it_can_set_url()
    {
        $element = new MediaUrlElement('video');
        $element->url('https://botman.io');
        $this->assertSame('https://botman.io', Arr::get($element->toArray(), 'url'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_button()
    {
        $element = new MediaUrlElement('video');
        $element->addButton(ElementButton::create('Button1'));
        $this->assertSame('Button1', Arr::get($element->toArray(), 'buttons.0.title'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_buttons()
    {
        $element = new MediaUrlElement('video');
        $element->addButtons([ElementButton::create('Button1'), ElementButton::create('Button2')]);
        $this->assertSame('Button1', Arr::get($element->toArray(), 'buttons.0.title'));
        $this->assertSame('Button2', Arr::get($element->toArray(), 'buttons.1.title'));
    }
}
