<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use XtendPackages\RESTPresenter\Models\WebhookSubscription;

class WebhookDispatcher
{
    /**
     * Dispatch a webhook event to all subscribers.
     */
    public function dispatch(string $eventType, array $payload = []): void
    {
        $subscriptions = WebhookSubscription::where('event_type', $eventType)
            ->where('active', true)
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->sendWebhook($subscription, $payload);
        }
    }

    /**
     * Send a webhook to a specific subscription.
     */
    public function sendWebhook(WebhookSubscription $subscription, array $payload): void
    {
        $fullPayload = array_merge($payload, [
            'event_type' => $subscription->event_type,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Add resource information if available
        if ($subscription->subscribable_type && $subscription->subscribable_id) {
            $fullPayload['resource_type'] = $subscription->subscribable_type;
            $fullPayload['resource_id'] = $subscription->subscribable_id;
        }

        $headers = ['Content-Type' => 'application/json'];
        
        // Add signature for security if secret is available
        if ($subscription->secret) {
            $signature = hash_hmac('sha256', json_encode($fullPayload), $subscription->secret);
            $headers['X-Webhook-Signature'] = $signature;
        }

        try {
            $response = Http::withHeaders($headers)
                ->timeout(5)
                ->post($subscription->target_url, $fullPayload);
            
            if (!$response->successful()) {
                Log::warning('Webhook delivery failed', [
                    'subscription_id' => $subscription->id,
                    'target_url' => $subscription->target_url,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Webhook delivery exception', [
                'subscription_id' => $subscription->id,
                'target_url' => $subscription->target_url,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
