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
        $button = new OpenGraphElement();
        $this->assertInstanceOf(OpenGraphElement::class, $button);
    }

    /**
     * @test
     **/
    public function it_can_set_an_url()
    {
        $element = new OpenGraphElement();
        $element->setUrl('https://example.com');

        $this->assertSame('https://example.com', Arr::get($element->toArray(), 'url'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_button()
    {
        $template = new OpenGraphElement();
        $template->addButton(ElementButton::create('button1')->url('https://en.wikipedia.org/wiki/Rickrolling')->removeHeightRatio());

        $this->assertSame('button1', Arr::get($template->toArray(), 'buttons.0.title'));
    }

    /**
     * @test
     **/
    public function it_can_add_multiple_buttons()
    {
        $template = new OpenGraphElement();
        $template->addButtons([
            ElementButton::create('button1')->url('https://en.wikipedia.org/wiki/Rickrolling')->removeHeightRatio(),
            ElementButton::create('button2')->url('https://en.wikipedia.org/')->removeHeightRatio(),
        ]);

        $this->assertSame('button1', Arr::get($template->toArray(), 'buttons.0.title'));
        $this->assertSame('button2', Arr::get($template->toArray(), 'buttons.1.title'));
    }
}
