<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\OpenGraphElement;
use BotMan\Drivers\Facebook\Extensions\OpenGraphTemplate;

class OpenGraphTemplateTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $template = new OpenGraphTemplate;
        $this->assertInstanceOf(OpenGraphTemplate::class, $template);
    }

    /**
     * @test
     **/
    public function it_can_add_an_element()
    {
        $template = new OpenGraphTemplate;
        $template->addElement(OpenGraphElement::create()->url('https://example.com'));

        $this->assertSame('https://example.com', Arr::get($template->toArray(), 'attachment.payload.elements.0.url'));
    }

    /**
     * @test
     **/
    public function it_can_add_multiple_elements()
    {
        $template = new OpenGraphTemplate;
        $template->addElements([
            OpenGraphElement::create()->url('https://example.com'),
            OpenGraphElement::create()->url('https://example.com'),
        ]);

        $this->assertSame('https://example.com', Arr::get($template->toArray(), 'attachment.payload.elements.0.url'));
        $this->assertSame('https://example.com', Arr::get($template->toArray(), 'attachment.payload.elements.1.url'));
    }
}
