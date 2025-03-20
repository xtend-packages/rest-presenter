<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Facades;

use Illuminate\Support\Facades\Facade;
use XtendPackages\RESTPresenter\Models\WebhookSubscription;

/**
 * @method static void dispatch(string $eventType, array $payload = [])
 * @method static void sendWebhook(WebhookSubscription $subscription, array $payload)
 * 
 * @see \XtendPackages\RESTPresenter\Support\WebhookDispatcher
 */
class Webhook extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'webhook-dispatcher';
    }
}
