<?php

namespace Tests;

use GuzzleHttp\Client;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Superbalist\LocalyticsPush\LocalyticsPush;

class LocalyticsPushTest extends TestCase
{
    public function testSetGetUri()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $localytics = new LocalyticsPush($guzzleClient);
        $this->assertEquals('https://messaging.localytics.com/v2/push/', $localytics->getUri());
        $localytics->setUri('http://127.0.0.1');
        $this->assertEquals('http://127.0.0.1', $localytics->getUri());
    }

    public function testSetGetAppID()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $localytics = new LocalyticsPush($guzzleClient, '12345');
        $this->assertEquals('12345', $localytics->getAppID());
        $localytics->setAppID('99999');
        $this->assertEquals('99999', $localytics->getAppID());
    }

    public function testSetGetApiKey()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $localytics = new LocalyticsPush($guzzleClient, '12345', 'api-key');
        $this->assertEquals('api-key', $localytics->getAPIKey());
        $localytics->setAPIKey('new-api-key');
        $this->assertEquals('new-api-key', $localytics->getAPIKey());
    }

    public function testSetGetApiSecret()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $localytics = new LocalyticsPush($guzzleClient, '12345', 'api-key', 'api-secret');
        $this->assertEquals('api-secret', $localytics->getAPISecret());
        $localytics->setAPISecret('new-api-secret');
        $this->assertEquals('new-api-secret', $localytics->getAPISecret());
    }

    public function testPushMessages()
    {
        $guzzleResponse = Mockery::mock(RequestInterface::class);
        $guzzleResponse->shouldReceive('getBody')
            ->andReturn('{ "message": "Queued for delivery" }');

        $guzzleClient = Mockery::mock(Client::class);
        $guzzleClient->shouldReceive('post')
            ->withArgs([
                'https://messaging.localytics.com/v2/push/app-id',
                [
                    'auth' => [
                        'api-key',
                        'api-secret',
                    ],
                    'json' => [
                        'request_id' => 'request-id',
                        'campaign_key' => 'campaign-key',
                        'target_type' => 'profile',
                        'messages' => [
                            [
                                'target' => [
                                    'profile' => [
                                        'criteria' => [
                                            [
                                                'key' => '$email',
                                                'scope' => 'Organization',
                                                'type' => 'string',
                                                'op' => 'in',
                                                'values' => [
                                                    'matthew@superbalist.com',
                                                ]
                                            ]
                                        ],
                                        'op' => 'and',
                                    ],
                                ],
                                'alert' => [
                                    'title' => 'Message Title',
                                    'body' => 'This is my message content!',
                                ]
                            ]
                        ],
                    ],
                ],
            ])
            ->andReturn($guzzleResponse);

        $localytics = new LocalyticsPush($guzzleClient, 'app-id', 'api-key', 'api-secret');

        $messages = [
            [
                'target' => [
                    'profile' => [
                        'criteria' => [
                            [
                                'key' => '$email',
                                'scope' => 'Organization',
                                'type' => 'string',
                                'op' => 'in',
                                'values' => [
                                    'matthew@superbalist.com',
                                ]
                            ]
                        ],
                        'op' => 'and',
                    ],
                ],
                'alert' => [
                    'title' => 'Message Title',
                    'body' => 'This is my message content!',
                ]
            ],
        ];
        $response = $localytics->pushMessages('profile', $messages, 'request-id', 'campaign-key');
        $this->assertEquals(['message' => 'Queued for delivery'], $response);
    }

    public function testPushMessage()
    {
        $guzzleResponse = Mockery::mock(RequestInterface::class);
        $guzzleResponse->shouldReceive('getBody')
            ->andReturn('{ "message": "Queued for delivery" }');

        $guzzleClient = Mockery::mock(Client::class);
        $guzzleClient->shouldReceive('post')
            ->withArgs([
                'https://messaging.localytics.com/v2/push/app-id',
                [
                    'auth' => [
                        'api-key',
                        'api-secret',
                    ],
                    'json' => [
                        'request_id' => 'request-id',
                        'campaign_key' => 'campaign-key',
                        'target_type' => 'profile',
                        'messages' => [
                            [
                                'target' => [
                                    'profile' => [
                                        'criteria' => [
                                            [
                                                'key' => '$email',
                                                'scope' => 'Organization',
                                                'type' => 'string',
                                                'op' => 'in',
                                                'values' => [
                                                    'matthew@superbalist.com',
                                                ]
                                            ]
                                        ],
                                        'op' => 'and',
                                    ],
                                ],
                                'alert' => [
                                    'title' => 'Message Title',
                                    'body' => 'This is my message content!',
                                ]
                            ]
                        ],
                    ],
                ],
            ])
            ->andReturn($guzzleResponse);

        $localytics = new LocalyticsPush($guzzleClient, 'app-id', 'api-key', 'api-secret');

        $message = [
            'target' => [
                'profile' => [
                    'criteria' => [
                        [
                            'key' => '$email',
                            'scope' => 'Organization',
                            'type' => 'string',
                            'op' => 'in',
                            'values' => [
                                'matthew@superbalist.com',
                            ]
                        ]
                    ],
                    'op' => 'and',
                ],
            ],
            'alert' => [
                'title' => 'Message Title',
                'body' => 'This is my message content!',
            ]
        ];
        $response = $localytics->pushMessage('profile', $message, 'request-id', 'campaign-key');
        $this->assertEquals(['message' => 'Queued for delivery'], $response);
    }
}
