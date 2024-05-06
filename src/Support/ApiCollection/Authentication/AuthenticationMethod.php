<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Authentication;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<array-key, mixed>
 */
abstract class AuthenticationMethod implements Arrayable
{
    public function __construct(protected ?string $token = null)
    {
    }

    abstract public function prefix(): string;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function hasToken(): bool
    {
        return $this->token !== null;
    }

    public function toArray(): array
    {
        return [
            'key' => 'Authorization',
            'value' => sprintf('%s %s', $this->prefix(), $this->token ?? '{{token}}'),
        ];
    }
}
