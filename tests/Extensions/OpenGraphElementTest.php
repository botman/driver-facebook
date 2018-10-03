<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\OpenGraphElement;

class OpenGraphElementTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $element = new OpenGraphElement();
        $this->assertInstanceOf(OpenGraphElement::class, $element);
    }

    /**
     * @test
     **/
    public function it_can_set_an_url()
    {
        $element = new OpenGraphElement();
        $element->url('https://example.com');

        $this->assertSame('https://example.com', Arr::get($element->toArray(), 'url'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_button()
    {
        $element = new OpenGraphElement();
        $element->addButton(ElementButton::create('button1')->url('https://en.wikipedia.org/wiki/Rickrolling')->removeHeightRatio());

        $this->assertSame('button1', Arr::get($element->toArray(), 'buttons.0.title'));
    }

    /**
     * @test
     **/
    public function it_can_add_multiple_buttons()
    {
        $element = new OpenGraphElement();
        $element->addButtons([
            ElementButton::create('button1')->url('https://en.wikipedia.org/wiki/Rickrolling')->removeHeightRatio(),
            ElementButton::create('button2')->url('https://en.wikipedia.org/')->removeHeightRatio(),
        ]);

        $this->assertSame('button1', Arr::get($element->toArray(), 'buttons.0.title'));
        $this->assertSame('button2', Arr::get($element->toArray(), 'buttons.1.title'));
    }
}
