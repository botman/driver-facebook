<?php

namespace Tests\Drivers;

use Mockery as m;
use BotMan\BotMan\Http\Curl;
use PHPUnit_Framework_TestCase;
use BotMan\Drivers\Facebook\FacebookDriver;
use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Attachments\Audio;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\Question;
use Symfony\Component\HttpFoundation\Request;
use BotMan\BotMan\Drivers\Events\GenericEvent;
use Symfony\Component\HttpFoundation\Response;
use BotMan\Drivers\Facebook\Events\MessagingReads;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\Drivers\Facebook\Events\MessagingOptins;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\Drivers\Facebook\Events\MessagingReferrals;
use BotMan\Drivers\Facebook\Events\MessagingDeliveries;
use BotMan\Drivers\Facebook\Extensions\QuickReplyButton;
use BotMan\Drivers\Facebook\Exceptions\FacebookException;
use BotMan\Drivers\Facebook\Events\MessagingCheckoutUpdates;

class FacebookDriverTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    private function getRequest($responseData)
    {
        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn($responseData);

        return $request;
    }

    private function getDriver($responseData, array $config = null, $signature = '', $htmlInterface = null)
    {
        if ($config === null) {
            $config = [
                'facebook' => [
                    'token' => 'Foo',
                ],
            ];
        }
        $request = $this->getRequest($responseData);
        $request->headers->set('X_HUB_SIGNATURE', $signature);

        if ($htmlInterface === null) {
            $htmlInterface = m::mock(Curl::class);
        }

        return new FacebookDriver($request, $config, $htmlInterface);
    }

    /** @test */
    public function it_returns_the_driver_name()
    {
        $driver = $this->getDriver('');
        $this->assertSame('Facebook', $driver->getName());
    }

    /** @test */
    public function it_matches_the_request()
    {
        $request = '{}';
        $driver = $this->getDriver($request);
        $this->assertFalse($driver->matchesRequest());

        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi"}}]}]}';
        $driver = $this->getDriver($request);
        $this->assertTrue($driver->matchesRequest());

        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"is_echo":true,"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi"}}]}]}';
        $driver = $this->getDriver($request);
        $this->assertFalse($driver->matchesRequest());

        $config = [
            'facebook' => [
                'token' => 'Foo',
                'app_secret' => 'Bar',
            ],
        ];
        $request = '{}';
        $driver = $this->getDriver($request, $config);
        $this->assertFalse($driver->matchesRequest());

        $signature = 'Foo';

        $config = [
            'facebook' => [
                'token' => 'Foo',
                'app_secret' => 'Bar',
            ],
        ];
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi"}}]}]}';
        $driver = $this->getDriver($request, $config, $signature);
        $this->assertFalse($driver->matchesRequest());

        $signature = 'sha1=74432bfe572675092cc81b5ac903ff3f971b04e5';

        $config = [
            'facebook' => [
                'token' => 'Foo',
                'app_secret' => 'Bar',
            ],
        ];
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi"}}]}]}';
        $driver = $this->getDriver($request, $config, $signature);
        $this->assertTrue($driver->matchesRequest());
    }

    /** @test * */
    public function it_matches_postback_requests()
    {
        $request = '{}';
        $driver = $this->getDriver($request);
        $this->assertFalse($driver->matchesRequest());

        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"postback":{"payload":"MY_PAYLOAD"}}]}]}';
        $driver = $this->getDriver($request);
        $this->assertTrue($driver->matchesRequest());
    }

    /** @test * */
    public function it_adds_nlp_data_to_the_message()
    {
        $request = '{}';
        $driver = $this->getDriver($request);
        $this->assertFalse($driver->matchesRequest());

        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1505851195620,"message":{"mid":"mid.$cAABlxfDuVgpkzP8I5Fem7nAgTm_7","seq":159479,"text":"bye, see you tomorrow at 4pm","nlp":{"entities":{"datetime":[{"confidence":0.96819333333333,"values":[{"value":"2017-09-20T16:00:00.000-07:00","grain":"hour","type":"value"}],"value":"2017-09-20T16:00:00.000-07:00","grain":"hour","type":"value"}],"bye":[{"confidence":0.61518204777792,"value":"true"}],"greetings":[{"confidence":0.78910905105147,"value":"true"}]}}}}]}]}';
        $driver = $this->getDriver($request);
        $message = $driver->getMessages()[0];

        $extras = $message->getExtras('nlp');

        $this->assertNotNull($extras);
        $this->assertSame('true', $extras['entities']['bye'][0]['value']);
    }

    /** @test */
    public function it_returns_the_postback_message()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"postback":{"payload":"MY_PAYLOAD"}}]}]}';
        $driver = $this->getDriver($request);
        $this->assertSame('MY_PAYLOAD', $driver->getMessages()[0]->getText());
    }

    /** @test */
    public function it_sets_the_postback_check_value()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"postback":{"payload":"MY_PAYLOAD"}}]}]}';
        $driver = $this->getDriver($request);
        $driver->getMessages();
        $this->assertTrue($driver->isPostback());
    }

    /** @test */
    public function it_shows_that_postback_is_no_event_anymore()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"postback":{"payload":"MY_PAYLOAD"}}]}]}';
        $driver = $this->getDriver($request);
        $event = $driver->hasMatchingEvent();
        $this->assertFalse($event);
    }

    /** @test */
    public function it_returns_the_message()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';
        $driver = $this->getDriver($request);

        $this->assertSame('Hi Julia', $driver->getMessages()[0]->getText());

        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{}]}]}';
        $driver = $this->getDriver($request);

        $this->assertSame('', $driver->getMessages()[0]->getText());
    }

    /** @test */
    public function it_returns_the_message_as_reference()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';
        $driver = $this->getDriver($request);

        $hash = spl_object_hash($driver->getMessages()[0]);

        $this->assertSame($hash, spl_object_hash($driver->getMessages()[0]));
    }

    /** @test */
    public function it_returns_the_user_object()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';

        $facebookResponse = '{"first_name":"John","last_name":"Doe","profile_pic":"https://facebook.com/profilepic","locale":"en_US","timezone":2,"gender":"male","is_payment_enabled":true}';

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('get')->once()->with('https://graph.facebook.com/v3.0/1433960459967306?fields=name,first_name,last_name,profile_pic&access_token=Foo')->andReturn(new Response($facebookResponse));

        $driver = $this->getDriver($request, null, '', $htmlInterface);
        $message = $driver->getMessages()[0];
        $user = $driver->getUser($message);

        $this->assertSame($user->getId(), '1433960459967306');
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertNull($user->getUsername());
        $this->assertEquals('https://facebook.com/profilepic', $user->getProfilePic());
        $this->assertEquals('en_US', $user->getLocale());
        $this->assertEquals('2', $user->getTimezone());
        $this->assertEquals('male', $user->getGender());
        $this->assertEquals(true, $user->getIsPaymentEnabled());
        $this->assertNull($user->getLastAdReferral());
        $this->assertEquals(json_decode($facebookResponse, true), $user->getInfo());
    }

    /** @test */
    public function it_returns_the_user_first_name()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';
        $facebookResponse = '{"first_name":"John"}';
        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('get')->once()->with('https://graph.facebook.com/v3.0/1433960459967306?fields=first_name&access_token=Foo')->andReturn(new Response($facebookResponse));
        $driver = $this->getDriver($request, null, '', $htmlInterface);
        $message = $driver->getMessages()[0];
        $user = $driver->getUserWithFields(['first_name'], $message);
        $this->assertSame($user->getId(), '1433960459967306');
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals(json_decode($facebookResponse, true), $user->getInfo());
    }

    /** @test */
    public function it_throws_exception_in_get_user()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('get')->once()->with('https://graph.facebook.com/v3.0/1433960459967306?fields=name,first_name,last_name,profile_pic&access_token=Foo')->andReturn(new Response('{}'));

        $driver = $this->getDriver($request, null, '', $htmlInterface);
        $driver->getMessages()[0];

        try {
            $driver->getUser($driver->getMessages()[0]);
        } catch (\Throwable $t) {
            $this->assertSame(FacebookException::class, get_class($t));
        }
    }

    /** @test */
    public function it_returns_an_empty_message_if_nothing_matches()
    {
        $request = '';
        $driver = $this->getDriver($request);

        $this->assertSame('', $driver->getMessages()[0]->getText());
    }

    /** @test */
    public function it_detects_bots()
    {
        $driver = $this->getDriver('');
        $this->assertFalse($driver->isBot());
    }

    /** @test */
    public function it_returns_the_user_id()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';
        $driver = $this->getDriver($request);

        $this->assertSame('1433960459967306', $driver->getMessages()[0]->getSender());
    }

    /** @test */
    public function it_returns_the_recipient_id()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';
        $driver = $this->getDriver($request);

        $this->assertSame('111899832631525', $driver->getMessages()[0]->getRecipient());
    }

    /** @test */
    public function it_can_reply_string_messages()
    {
        $responseData = [
            'object' => 'page',
            'event' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '1234567890',
                            ],
                            'recipient' => [
                                'id' => '0987654321',
                            ],
                            'message' => [
                                'text' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'text' => 'Test',
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode($responseData));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload('Test', $message));
    }

    /** @test */
    public function it_can_reply_with_additional_parameters()
    {
        $responseData = [
            'object' => 'page',
            'event' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '1234567890',
                            ],
                            'recipient' => [
                                'id' => '0987654321',
                            ],
                            'message' => [
                                'text' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'text' => 'Test',
                ],
                'access_token' => 'Foo',
                'custom' => 'payload',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode($responseData));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload('Test', $message, [
            'custom' => 'payload',
        ]));
    }

    /** @test */
    public function it_throws_exception_while_sending_message()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi Julia"}}]}]}';

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'text' => 'Test',
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(new Response('', 400));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');

        try {
            $driver->sendPayload($driver->buildServicePayload('Test', $message));
        } catch (\Throwable $t) {
            $this->assertSame(FacebookException::class, get_class($t));
        }
    }

    /** @test */
    public function it_returns_answer_from_interactive_messages()
    {
        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode([]));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], m::mock(Curl::class));

        $message = new IncomingMessage('Red', '0987654321', '1234567890', [
            'sender' => [
                'id' => '1234567890',
            ],
            'recipient' => [
                'id' => '0987654321',
            ],
            'message' => [
                'text' => 'Red',
                'quick_reply' => [
                    'payload' => 'DEVELOPER_DEFINED_PAYLOAD',
                ],
            ],
        ]);

        $this->assertSame('Red', $driver->getConversationAnswer($message)->getText());
        $this->assertSame($message, $driver->getConversationAnswer($message)->getMessage());
        $this->assertSame('DEVELOPER_DEFINED_PAYLOAD', $driver->getConversationAnswer($message)->getValue());
    }

    /** @test */
    public function it_returns_answer_from_regular_messages()
    {
        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode([]));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], m::mock(Curl::class));

        $message = new IncomingMessage('Red', '0987654321', '1234567890', [
            'sender' => [
                'id' => '1234567890',
            ],
            'recipient' => [
                'id' => '0987654321',
            ],
            'message' => [
                'text' => 'Red',
            ],
        ]);

        $this->assertSame('Red', $driver->getConversationAnswer($message)->getText());
        $this->assertSame(null, $driver->getConversationAnswer($message)->getValue());
    }

    /** @test */
    public function it_can_reply_questions()
    {
        $question = Question::create('How are you doing?')->addButton(Button::create('Great')->value('great'))->addButton(Button::create('Good')->value('good'));

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'text' => 'How are you doing?',
                    'quick_replies' => [
                        [
                            'content_type' => 'text',
                            'title' => 'Great',
                            'payload' => 'great',
                            'image_url' => null,
                        ],
                        [
                            'content_type' => 'text',
                            'title' => 'Good',
                            'payload' => 'good',
                            'image_url' => null,
                        ],
                    ],
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn('[]');

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload($question, $message));
    }

    /** @test */
    public function it_can_reply_questions_with_additional_button_parameters()
    {
        $question = Question::create('How are you doing?')->addButton(Button::create('Great')->value('great')->additionalParameters(['foo' => 'bar']))->addButton(Button::create('Good')->value('good'));

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'text' => 'How are you doing?',
                    'quick_replies' => [
                        [
                            'content_type' => 'text',
                            'title' => 'Great',
                            'payload' => 'great',
                            'image_url' => null,
                            'foo' => 'bar',
                        ],
                        [
                            'content_type' => 'text',
                            'title' => 'Good',
                            'payload' => 'good',
                            'image_url' => null,
                        ],
                    ],
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn('[]');

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload($question, $message));
    }

    /** @test */
    public function it_can_reply_quick_replies_with_special_types()
    {
        $question = Question::create('How are you doing?')
            ->addAction(QuickReplyButton::create()->type('user_email'))
            ->addAction(QuickReplyButton::create()->type('location'))
            ->addAction(QuickReplyButton::create()->type('user_phone_number'));

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
            'messaging_type' => 'RESPONSE',
            'recipient' => [
                'id' => '1234567890',
            ],
            'message' => [
                'text' => 'How are you doing?',
                'quick_replies' => [
                    [
                        'content_type' => 'user_email',
                    ],
                    [
                        'content_type' => 'location',
                    ],
                    [
                        'content_type' => 'user_phone_number',
                    ],
                ],
            ],
            'access_token' => 'Foo',
        ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn('[]');

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload($question, $message));
    }

    /** @test */
    public function it_is_configured()
    {
        $request = m::mock(Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn('');
        $htmlInterface = m::mock(Curl::class);

        $config = [
            'facebook' => [
                'token' => 'Foo',
                'app_secret' => 'Bar',
            ],
        ];
        $driver = new FacebookDriver($request, $config, $htmlInterface);

        $this->assertTrue($driver->isConfigured());

        $config = [
            'facebook' => [
                'token' => null,
                'app_secret' => 'Bar',
            ],
        ];
        $driver = new FacebookDriver($request, $config, $htmlInterface);

        $this->assertFalse($driver->isConfigured());

        $driver = new FacebookDriver($request, [], $htmlInterface);

        $this->assertFalse($driver->isConfigured());
    }

    /** @test */
    public function it_can_reply_message_objects()
    {
        $responseData = [
            'object' => 'page',
            'event' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '1234567890',
                            ],
                            'recipient' => [
                                'id' => '0987654321',
                            ],
                            'message' => [
                                'text' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'text' => 'Test',
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode($responseData));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload(\BotMan\BotMan\Messages\Outgoing\OutgoingMessage::create('Test'),
            $message));
    }

    /** @test */
    public function it_can_reply_message_objects_with_image()
    {
        $responseData = [
            'object' => 'page',
            'event' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '1234567890',
                            ],
                            'recipient' => [
                                'id' => '0987654321',
                            ],
                            'message' => [
                                'text' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'attachment' => [
                        'type' => 'image',
                        'payload' => [
                            'is_reusable' => false,
                            'url' => 'http://image.url//foo.png',
                        ],
                    ],
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode($responseData));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload(\BotMan\BotMan\Messages\Outgoing\OutgoingMessage::create('Test',
            Image::url('http://image.url//foo.png')), $message));
    }

    /** @test */
    public function it_can_reply_message_objects_with_audio()
    {
        $responseData = [
            'object' => 'page',
            'event' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '1234567890',
                            ],
                            'recipient' => [
                                'id' => '0987654321',
                            ],
                            'message' => [
                                'text' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'attachment' => [
                        'type' => 'audio',
                        'payload' => [
                            'is_reusable' => false,
                            'url' => 'http://image.url//foo.mp3',
                        ],
                    ],
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode($responseData));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload(\BotMan\BotMan\Messages\Outgoing\OutgoingMessage::create('Test',
            Audio::url('http://image.url//foo.mp3')), $message));
    }

    /** @test */
    public function it_can_reply_message_objects_with_file()
    {
        $responseData = [
            'object' => 'page',
            'event' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '1234567890',
                            ],
                            'recipient' => [
                                'id' => '0987654321',
                            ],
                            'message' => [
                                'text' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'messaging_type' => 'RESPONSE',
                'recipient' => [
                    'id' => '1234567890',
                ],
                'message' => [
                    'attachment' => [
                        'type' => 'file',
                        'payload' => [
                            'is_reusable' => false,
                            'url' => 'http://image.url//foo.pdf',
                        ],
                    ],
                ],
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode($responseData));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->sendPayload($driver->buildServicePayload(\BotMan\BotMan\Messages\Outgoing\OutgoingMessage::create('Test',
            File::url('http://image.url//foo.pdf')), $message));
    }

    /** @test */
    public function it_can_reply_message_objects_with_reusable_file()
    {
        $responseData = [
            'object' => 'page',
            'event' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '1234567890',
                            ],
                            'recipient' => [
                                'id' => '0987654321',
                            ],
                            'message' => [
                                'text' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
            'messaging_type' => 'RESPONSE',
            'recipient' => [
                'id' => '1234567890',
            ],
            'message' => [
                'attachment' => [
                    'type' => 'file',
                    'payload' => [
                        'is_reusable' => true,
                        'url' => 'http://image.url//foo.pdf',
                    ],
                ],
            ],
            'access_token' => 'Foo',
        ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn(json_encode($responseData));

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $file = File::url('http://image.url//foo.pdf');
        $file->addExtras('is_reusable', true);

        $driver->sendPayload($driver->buildServicePayload(\BotMan\BotMan\Messages\Outgoing\OutgoingMessage::create('Test', $file), $message));
    }

    /** @test */
    public function it_calls_referral_event()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"referral":{"ref":"MY_REF","source": "MY_SOURCE","type": "MY_TYPE"}}]}]}';
        $driver = $this->getDriver($request);

        $event = $driver->hasMatchingEvent();
        $this->assertInstanceOf(MessagingReferrals::class, $event);
        $this->assertSame('messaging_referrals', $event->getName());
    }

    /** @test */
    public function it_has_message_for_referral_event()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"referral":{"ref":"MY_REF","source": "MY_SOURCE","type": "MY_TYPE"}}]}]}';
        $driver = $this->getDriver($request);

        $message = $driver->getMessages()[0];
        $this->assertSame('1433960459967306', $message->getSender());
        $this->assertSame('111899832631525', $message->getRecipient());
    }

    /** @test */
    public function it_calls_optin_event()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"optin": {"ref":"optin","user_ref":"1234"}}]}]}';
        $driver = $this->getDriver($request);

        $event = $driver->hasMatchingEvent();
        $this->assertInstanceOf(MessagingOptins::class, $event);
        $this->assertSame('messaging_optins', $event->getName());
    }

    /** @test */
    public function it_has_message_for_optin_event()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"optin": {"ref":"optin","user_ref":"1234"}}]}]}';
        $driver = $this->getDriver($request);

        $message = $driver->getMessages()[0];
        $this->assertSame('1234', $message->getSender());
        $this->assertSame('111899832631525', $message->getRecipient());
    }

    /** @test */
    public function it_calls_delivery_event()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"USER_ID"},"recipient":{"id":"PAGE_ID"},"delivery":{"mids":["mid.1458668856218:ed81099e15d3f4f233"],"watermark":1458668856253,"seq":37}}]}]}';
        $driver = $this->getDriver($request);

        $event = $driver->hasMatchingEvent();
        $this->assertInstanceOf(MessagingDeliveries::class, $event);
        $this->assertSame('messaging_deliveries', $event->getName());
    }

    /** @test */
    public function it_calls_read_event()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"USER_ID"},"recipient":{"id":"PAGE_ID"},"timestamp":1458668856463,"read":{"watermark":1458668856253,"seq":38}}]}]}';
        $driver = $this->getDriver($request);

        $event = $driver->hasMatchingEvent();
        $this->assertInstanceOf(MessagingReads::class, $event);
        $this->assertSame('messaging_reads', $event->getName());
    }

    /** @test */
    public function it_calls_checkout_update_event()
    {
        $request = '{"object": "page","entry": [{"id": "PAGE_ID","time": 1473204787206,"messaging": [{"recipient": {"id": "PAGE_ID"},"timestamp": 1473204787206,"sender": {"id": "USER_ID"},"checkout_update": {"payload": "DEVELOPER_DEFINED_PAYLOAD","shipping_address": {"id": 10105655000959552,"country": "US","city": "MENLO PARK","street1": "1 Hacker Way","street2": "","state": "CA","postal_code": "94025"}}}]}]}';
        $driver = $this->getDriver($request);

        $event = $driver->hasMatchingEvent();
        $this->assertInstanceOf(MessagingCheckoutUpdates::class, $event);
        $this->assertSame('messaging_checkout_updates', $event->getName());
    }

    /** @test */
    public function it_calls_generic_event_for_unkown_facebook_events()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"USER_ID"},"recipient":{"id":"PAGE_ID"},"timestamp":1458668856463,"foo":{"watermark":1458668856253,"seq":38}}]}]}';
        $driver = $this->getDriver($request);

        $event = $driver->hasMatchingEvent();
        $this->assertInstanceOf(GenericEvent::class, $event);
        $this->assertSame('foo', $event->getName());
    }

    /** @test */
    public function it_can_reply_mark_seen_sender_action()
    {
        $htmlInterface = m::mock(Curl::class);
        $htmlInterface->shouldReceive('post')->once()->with('https://graph.facebook.com/v3.0/me/messages', [], [
                'recipient' => [
                    'id' => '1234567890',
                ],
                'sender_action' => 'mark_seen',
                'access_token' => 'Foo',
            ])->andReturn(new Response());

        $request = m::mock(\Symfony\Component\HttpFoundation\Request::class.'[getContent]');
        $request->shouldReceive('getContent')->andReturn('[]');

        $driver = new FacebookDriver($request, [
            'facebook' => [
                'token' => 'Foo',
            ],
        ], $htmlInterface);

        $message = new IncomingMessage('', '1234567890', '');
        $driver->markSeen($message);
    }

    public function it_returns_the_quick_reply_postback()
    {
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"quick_reply":{"payload":"MY_PAYLOAD"},"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Red"}}]}]}';

        $driver = $this->getDriver($request);
        $this->assertSame('MY_PAYLOAD', $driver->getMessages()[0]->getText());
    }
}
