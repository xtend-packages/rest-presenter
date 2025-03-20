<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Http;
use XtendPackages\RESTPresenter\Models\WebhookSubscription;

trait HasWebhooks
{
    /**
     * Get all webhook subscriptions for this model.
     */
    public function webhookSubscriptions(): MorphMany
    {
        return $this->morphMany(WebhookSubscription::class, 'subscribable');
    }

    /**
     * Subscribe to a webhook event.
     */
    public function subscribeToWebhook(string $eventType, string $targetUrl, ?string $secret = null): WebhookSubscription
    {
        return $this->webhookSubscriptions()->create([
            'event_type' => $eventType,
            'target_url' => $targetUrl,
            'secret' => $secret,
            'active' => true,
        ]);
    }

    /**
     * Unsubscribe from a webhook event.
     */
    public function unsubscribeFromWebhook(int $subscriptionId): bool
    {
        $subscription = $this->webhookSubscriptions()->find($subscriptionId);
        
        if ($subscription) {
            return $subscription->delete();
        }
        
        return false;
    }

    /**
     * Trigger a webhook event.
     */
    public function triggerWebhook(string $eventType, array $payload = []): void
    {
        $subscriptions = $this->webhookSubscriptions()
            ->where('event_type', $eventType)
            ->where('active', true)
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->sendWebhookPayload($subscription, $payload);
        }
    }

    /**
     * Send the webhook payload to the target URL.
     */
    protected function sendWebhookPayload(WebhookSubscription $subscription, array $payload): void
    {
        $fullPayload = array_merge($payload, [
            'event_type' => $subscription->event_type,
            'resource_type' => get_class($this),
            'resource_id' => $this->getKey(),
            'timestamp' => now()->toIso8601String(),
        ]);

        $headers = ['Content-Type' => 'application/json'];
        
        if ($subscription->secret) {
            $signature = hash_hmac('sha256', json_encode($fullPayload), $subscription->secret);
            $headers['X-Webhook-Signature'] = $signature;
        }

        Http::withHeaders($headers)->post($subscription->target_url, $fullPayload);
    }
}
