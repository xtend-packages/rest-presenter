<?php

declare(strict_types=1);

use XtendPackages\RESTPresenter\Models\WebhookSubscription;
use XtendPackages\RESTPresenter\Models\User;

it('defines fillable attributes', function (): void {
    $subscription = new WebhookSubscription();
    $this->assertEquals([
        'event_type',
        'target_url',
        'subscribable_type',
        'subscribable_id',
        'secret',
        'active',
    ], $subscription->getFillable());
});

it('casts attributes correctly', function (): void {
    $subscription = new WebhookSubscription();
    $casts = $subscription->getCasts();
    $this->assertArrayHasKey('active', $casts);
    $this->assertEquals('boolean', $casts['active']);
    $this->assertArrayHasKey('subscribable_id', $casts);
    $this->assertEquals('integer', $casts['subscribable_id']);
});

it('returns correct factory instance', function (): void {
    $factory = WebhookSubscription::newFactory();
    $this->assertInstanceOf(XtendPackages\RESTPresenter\Factories\WebhookSubscriptionFactory::class, $factory);
});

it('can have a morphTo relationship with subscribable models', function (): void {
    $user = User::factory()->create();
    
    $subscription = WebhookSubscription::factory()->create([
        'subscribable_type' => User::class,
        'subscribable_id' => $user->id,
    ]);
    
    $this->assertInstanceOf(User::class, $subscription->subscribable);
    $this->assertEquals($user->id, $subscription->subscribable->id);
});
