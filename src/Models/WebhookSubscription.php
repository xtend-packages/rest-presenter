<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use XtendPackages\RESTPresenter\Factories\WebhookSubscriptionFactory;

/**
 * @property int $id
 * @property string $event_type
 * @property string $target_url
 * @property string|null $subscribable_type
 * @property int|null $subscribable_id
 * @property string|null $secret
 * @property bool $active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder<WebhookSubscription>
 *
 * @use HasFactory<WebhookSubscriptionFactory>
 */
class WebhookSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_type',
        'target_url',
        'subscribable_type',
        'subscribable_id',
        'secret',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'subscribable_id' => 'integer',
    ];

    /**
     * The model factory for this model.
     */
    protected static function newFactory(): WebhookSubscriptionFactory
    {
        return WebhookSubscriptionFactory::new();
    }

    /**
     * Get the subscribable model (the model that the webhook is for).
     */
    public function subscribable(): MorphTo
    {
        return $this->morphTo();
    }
}
