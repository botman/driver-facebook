<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\OpenGraphTemplate;
use BotMan\Drivers\Facebook\Extensions\OpenGraphElement;

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
        $template->element(OpenGraphElement::create('Rick Roll'));

        $this->assertSame('Rick Roll',
            Arr::get($template->toArray(), 'attachment.payload.elements.0.title'));
    }
}
