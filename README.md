# php-localytics-push

A PHP client for sending push notifications via the [Localytics Push Notification](https://www.localytics.com/features/push-messaging/) service

[![Author](http://img.shields.io/badge/author-@superbalist-blue.svg?style=flat-square)](https://twitter.com/superbalist)
[![Build Status](https://img.shields.io/travis/Superbalist/php-localytics-push/master.svg?style=flat-square)](https://travis-ci.org/Superbalist/php-localytics-push)
[![StyleCI](https://styleci.io/repos/71333548/shield?branch=master)](https://styleci.io/repos/71333548)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/superbalist/php-localytics-push.svg?style=flat-square)](https://packagist.org/packages/superbalist/php-localytics-push)
[![Total Downloads](https://img.shields.io/packagist/dt/superbalist/php-localytics-push.svg?style=flat-square)](https://packagist.org/packages/superbalist/php-localytics-push)


## Installation

```bash
composer require superbalist/php-localytics-push
```

## Integrations

Want to get started quickly? Check out some of these integrations:

* Laravel - https://github.com/Superbalist/laravel-localytics-push

## Usage

```php
$client = new GuzzleHttp\Client();
$appID = 'your-app-id';
$apiKey = 'your-api-key';
$apiSecret = 'your-api-secret';

$localytics = new \Superbalist\LocalyticsPush\LocalyticsPush($client, $appID, $apiKey, $apiSecret);
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
$response = $localytics->pushMessage('profile', $message);
```
