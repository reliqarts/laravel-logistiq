# Laravel Logistiq
###### Multi-purpose tracking/logistics package for Laravel

Logistiq is a plug-and-play tracking package which allows you to track any *trackable* entity (i.e. orders, shipments, etc.)
It is highly-configurable and easy to use.

[![Built For Laravel](https://img.shields.io/badge/built%20for-laravel-red.svg?style=flat-square)](http://laravel.com)
[![License](https://poser.pugx.org/reliqarts/laravel-logistiq/license?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-logistiq)
[![CircleCI (all branches)](https://img.shields.io/circleci/project/github/reliqarts/laravel-logistiq/master.svg?style=flat-square)](https://circleci.com/gh/reliqarts/laravel-logistiq/tree/master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/reliqarts/laravel-logistiq.svg?style=flat-square)](https://scrutinizer-ci.com/g/reliqarts/laravel-logistiq/)
[![Codecov](https://img.shields.io/codecov/c/github/reliqarts/laravel-logistiq.svg?style=flat-square)](https://codecov.io/gh/reliqarts/laravel-logistiq)
[![Latest Stable Version](https://poser.pugx.org/reliqarts/laravel-logistiq/version?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-logistiq)

## Key Features
- Track any *model* through different user-defined statuses.
- Configure one or more events to be fired when a model enters any user-defined status.
- Supports [laravel-event-projector](https://github.com/spatie/laravel-event-projector) in case you're using Event Sourcing ([What's this?](https://kickstarter.engineering/event-sourcing-made-simple-4a2625113224)).

## Installation & Usage

1. Install via composer:

    ```metadata bash
    composer require reliqarts/laravel-logistiq
    ```

2. Configuration & Setup:
   1. Publish config file via artisan:
        ```metadata bash
        php artisan vendor:publish --tag=reliqarts-logistiq-config
        ```
    2. The model you intend to track must implement `ReliqArts\Logistiq\Tracking\Contracts\Trackable` or extend `ReliqArts\Logistiq\Tracking\Models\Trackable`.
        
        e.g. `App\Order::class`:
        ```metadata php
        // ...
        use ReliqArts\Logistiq\Tracking\Models\Trackable;
        
        class Order extends Trackable
        {
            // ...
        }
        // ...
        ```
    3. Create your `Status` model and implement the `ReliqArts\Logistiq\Tracking\Contracts\Status` contract therein.
        
        e.g. `App\Status::class`:
        ```metadata php
        // ...
        use ReliqArts\Logistiq\Utility\Eloquent\Model;
        use ReliqArts\Logistiq\Tracking\Contracts\Status as LogistiqStatusContract;
        
        class Status extends Model implements LogistiqStatusContract
        {   
            // ...
        }
        ```
    3. Configure `event_map` to fire additional events when a particular `Status` is hit. 
        
        e.g. excerpt from `/config/reliqats-logistiq`:
        ```metadata php
        <?php
        // ...
        'event_map' => [
            '230c6c51-3b5b-4eea-9ef2-415e4d8fee00' => [ProductShipped::class, ProductMoved::class]
        ],
        // ...
        ```
        **Explanation:** With the above snipped of code, whenever the Status with identifier `230c6c51-3b5b-4eea-9ef2-415e4d8fee00` is reached by a *trackable* model the `ProductShipped` and `ProductMoved` events will be fired.
        You have full control over what these events trigger, however each event must expect a `Trackable` as the first constructor argument.

*More on the way...* :truck:

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
