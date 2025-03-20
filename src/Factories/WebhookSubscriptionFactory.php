<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Models\WebhookSubscription;

/**
 * @extends Factory<WebhookSubscription>
 */
class WebhookSubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WebhookSubscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_type' => $this->faker->randomElement(['model.created', 'model.updated', 'model.deleted']),
            'target_url' => $this->faker->url(),
            'secret' => Str::random(32),
            'active' => true,
        ];
    }

    /**
     * Indicate that the webhook subscription is inactive.
     */
    public function inactive(): self
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate that the webhook subscription has a specific event type.
     */
    public function forEvent(string $eventType): self
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => $eventType,
        ]);
    }
}
