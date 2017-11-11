<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;

class ElementButtonTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $button = new ElementButton('click me');
        $this->assertInstanceOf(ElementButton::class, $button);
    }

    /**
     * @test
     **/
    public function it_can_set_title()
    {
        $button = new ElementButton('click me');

        $this->assertSame('click me', Arr::get($button->toArray(), 'title'));
    }

    /**
     * @test
     **/
    public function standard_type_is_web_url()
    {
        $button = new ElementButton('click me');

        $this->assertSame('web_url', Arr::get($button->toArray(), 'type'));
    }

    /**
     * @test
     **/
    public function it_can_set_type()
    {
        $button = new ElementButton('click me');
        $button->type('postback');

        $this->assertSame('postback', Arr::get($button->toArray(), 'type'));
    }

    /**
     * @test
     **/
    public function it_ignores_title_for_account_link_type()
    {
        $button = new ElementButton('click me');
        $button->type('account_link');

        $this->assertSame('account_link', Arr::get($button->toArray(), 'type'));
        $this->assertSame(null, Arr::get($button->toArray(), 'title'));
        $this->assertFalse(array_key_exists('title', $button->toArray()));
    }

    /**
     * @test
     **/
    public function it_ignores_title_for_account_unlink_type()
    {
        $button = new ElementButton('click me');
        $button->type('account_unlink');

        $this->assertSame('account_unlink', Arr::get($button->toArray(), 'type'));
        $this->assertSame(null, Arr::get($button->toArray(), 'title'));
        $this->assertFalse(array_key_exists('title', $button->toArray()));
    }

    /**
     * @test
     **/
    public function it_can_set_url()
    {
        $button = new ElementButton('click me');
        $button->url('http://botman.io/');

        $this->assertSame('http://botman.io/', Arr::get($button->toArray(), 'url'));
    }

    /**
     * @test
     **/
    public function it_can_set_payload()
    {
        $button = new ElementButton('click me');
        $button->payload('clickme')->type('postback');

        $this->assertSame('clickme', Arr::get($button->toArray(), 'payload'));
    }

    /**
     * @test
     **/
    public function it_can_set_fallback_url()
    {
        $button = new ElementButton('click me');
        $button->enableExtensions();
        $button->fallbackUrl('www.google.de');

        $this->assertTrue(Arr::get($button->toArray(), 'messenger_extensions'));
        $this->assertSame('www.google.de', Arr::get($button->toArray(), 'fallback_url'));
    }

    /**
     * @test
     **/
    public function it_can_disable_share()
    {
        $button = new ElementButton('click me');
        $button->disableShare();

        $this->assertSame('HIDE', Arr::get($button->toArray(), 'webview_share_button'));
    }

    /**
     * @test
     **/
    public function it_can_set_height_ratio()
    {
        $button = new ElementButton('click me');
        $button->heightRatio(ElementButton::RATIO_COMPACT);

        $this->assertSame('compact', Arr::get($button->toArray(), 'webview_height_ratio'));
    }

    /**
     * @test
     **/
    public function it_can_set_share_contents()
    {
        $button = new ElementButton('click me');
        $share = GenericTemplate::create()
            ->addElement(
                Element::create('share')
                    ->addButton(
                        ElementButton::create('share')->url('https://botman.io')
                    )
            );
        $button->shareContents($share)->type(ElementButton::TYPE_SHARE);

        $this->assertSame($share->toArray(), Arr::get($button->toArray(), 'share_contents'));
    }
}
