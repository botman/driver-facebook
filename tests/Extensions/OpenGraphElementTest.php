<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\OpenGraphElement;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

class ElementTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $button = new OpenGraphElement('Rick Roll');
        $this->assertInstanceOf(Element::class, $button);
    }

    /**
     * @test
     **/
    public function it_can_set_title()
    {
        $element = new Element('Rick Roll');

        $this->assertSame('Rick Roll', Arr::get($element->toArray(), 'title'));
    }

    /**
     * @test
     **/
    public function it_can_set_url()
    {
        $element = new Element('Rick Roll');
        $element->url('https://open.spotify.com/track/7GhIk7Il098yCjg4BQjzvb');

        $this->assertSame('https://open.spotify.com/track/7GhIk7Il098yCjg4BQjzvb', Arr::get($element->toArray(), 'url'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_button()
    {
        $template = new Element('Here are some buttons');
        $template->addButton(ElementButton::create('button1')
								->url('https://en.wikipedia.org/wiki/Rickrolling')
								->removeHeightRatio());

        $this->assertSame('button1', Arr::get($template->toArray(), 'buttons.0.title'));
    }

    /**
     * @test
     **/
    public function it_can_add_multiple_buttons()
    {
        $template = new Element('Here are some buttons');
        $template->addButtons([ElementButton::create('button1')
								->url('https://en.wikipedia.org/wiki/Rickrolling')
								->removeHeightRatio(),
							   ElementButton::create('button2')
								->url('https://en.wikipedia.org/')
								->removeHeightRatio()]);

        $this->assertSame('button1', Arr::get($template->toArray(), 'buttons.0.title'));
        $this->assertSame('button2', Arr::get($template->toArray(), 'buttons.1.title'));
    }

}
