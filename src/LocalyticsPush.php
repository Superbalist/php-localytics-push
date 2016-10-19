<?php

namespace Superbalist\LocalyticsPush;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

class LocalyticsPush
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $uri = 'https://messaging.localytics.com/v2/push/';

    /**
     * @var string
     */
    protected $appID;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiSecret;

    /**
     * @param Client $client
     * @param string $appID
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct(Client $client, $appID = null, $apiKey = null, $apiSecret = null)
    {
        $this->client = $client;
        $this->appID = $appID;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Set the uri.
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    /**
     * Return the uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the App ID.
     *
     * The App ID can be found on the settings screen of your dashboard.
     * https://dashboard.localytics.com/settings/apps
     *
     * @param string $appID
     */
    public function setAppID($appID)
    {
        $this->appID = $appID;
    }

    /**
     * Return the App ID.
     *
     * @return string
     */
    public function getAppID()
    {
        return $this->appID;
    }

    /**
     * Set the API Key.
     *
     * The API Key can be found on the settings screen of your dashboard.
     * https://dashboard.localytics.com/settings/apps
     *
     * @param string $apiKey
     */
    public function setAPIKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Return the API Key.
     *
     * @return string
     */
    public function getAPIKey()
    {
        return $this->apiKey;
    }

    /**
     * Set the API Secret.
     *
     * The API Secret can be found on the settings screen of your dashboard.
     * https://dashboard.localytics.com/settings/apps
     *
     * @param string $apiSecret
     */
    public function setAPISecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

    /**
     * Return the API Secret.
     *
     * @return string
     */
    public function getAPISecret()
    {
        return $this->apiSecret;
    }

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request
     * @return array
     * @throws \Exception
     */
    protected function sendRequest(RequestInterface $request)
    {
        $response = $this->client->send($request);
        return json_decode($response->getBody(), true);
    }

    /**
     * Send one or many push notification messages.
     *
     * @param string $targetType (broadcast|customer_id|audience_id|profile)
     * @param array $messages
     * @param string $requestId
     * @param string $campaignKey
     * @return array
     */
    public function pushMessages($targetType, array $messages, $requestId = null, $campaignKey = null)
    {
        $data = [
            'request_id' => $requestId,
            'campaign_key' => $campaignKey,
            'target_type' => $targetType,
            'messages' => $messages,
        ];

        $options = [
            'auth' => [
                $this->apiKey,
                $this->apiSecret,
            ],
            'json' => $data,
        ];
        $uri = rtrim($this->uri, '/') . '/' . $this->appID;
        $response = $this->client->post($uri, $options);
        return json_decode($response->getBody(), true);
    }

    /**
     * Send a single push notification message.
     *
     * **Example**
     *
     * ```php
     * $message = [
     *     'target' => [
     *         'profile' => [
     *             'criteria' => [
     *                 [
     *                     'key' => '$email',
     *                     'scope' => 'Organization',
     *                     'type' => 'string',
     *                     'op' => 'in',
     *                     'values' => [
     *                         'matthew@superbalist.com',
     *                     ]
     *                 ]
     *             ]
     *         ]
     *     ],
     *     'alert' => [
     *         'title' => 'Message Title',
     *         'body' => 'This is my message content!',
     *     ],
     * ];
     * $response = $localytics->pushMessage('profile', $message);
     *
     * @param string $targetType (broadcast|customer_id|audience_id|profile)
     * @param array $message
     * @param string $requestId
     * @param string $campaignKey
     * @return array
     */
    public function pushMessage($targetType, array $message, $requestId = null, $campaignKey = null)
    {
        return $this->pushMessages($targetType, [$message], $requestId, $campaignKey);
    }
}
