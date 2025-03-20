<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Resources\RestHooks;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Models\WebhookSubscription;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\Resources\RestHooks\Data\Request\WebhookSubscriptionRequest;
use XtendPackages\RESTPresenter\Resources\RestHooks\Data\Response\WebhookSubscriptionData;

final class WebhookResourceController extends ResourceController
{
    public static bool $isAuthenticated = true;
    
    /**
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    public static string $model = WebhookSubscription::class;

    /**
     * @return Collection<int, Data>
     */
    public function index(Request $request): Collection
    {
        /** @var Collection<int, WebhookSubscription> $subscriptions */
        $subscriptions = $this->getModelQueryInstance()->where('active', true)->get();

        return $subscriptions->map(
            fn ($subscription): Data => $this->present($request, $subscription),
        );
    }

    public function show(Request $request, WebhookSubscription $webhook): Data
    {
        return $this->present($request, $webhook);
    }
    
    /**
     * Subscribe to a webhook.
     */
    public function store(WebhookSubscriptionRequest $request): Data
    {
        $subscription = new WebhookSubscription();
        $subscription->event_type = $request->event_type;
        $subscription->target_url = $request->target_url;
        $subscription->secret = Str::random(32);
        
        // If resource type and ID are provided, associate with that resource
        if ($request->resource_type && $request->resource_id) {
            // Make sure the resource type is a valid model class
            if (class_exists($request->resource_type)) {
                $subscription->subscribable_type = $request->resource_type;
                $subscription->subscribable_id = $request->resource_id;
            }
        }
        
        $subscription->save();
        
        return WebhookSubscriptionData::from($subscription);
    }
    
    /**
     * Unsubscribe from a webhook.
     */
    public function destroy(WebhookSubscription $webhook): Data
    {
        $webhook->delete();
        
        return WebhookSubscriptionData::from($webhook);
    }

    /**
     * @return array<int, string>
     */
    public function filters(): array
    {
        $filters = config('rest-presenter.resources.webhook.filters', []);

        assert(is_array($filters));

        return $filters;
    }

    /**
     * @return array<int, string>
     */
    public function presenters(): array
    {
        $presenters = config('rest-presenter.resources.webhook.presenters', [
            'webhook' => WebhookSubscription::class,
        ]);

        assert(is_array($presenters));

        return $presenters;
    }
}
