<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Pipeline\Pipeline;

trait InteractsWithPipeline
{
    /**
     * @param  array<int, mixed>  $pipes
     */
    protected function prepareThroughPipeline(mixed $passable, array $pipes, string $method = 'handle'): mixed
    {
        return app(Pipeline::class)
            ->send($passable)
            ->through($pipes)
            ->via($method)
            ->thenReturn();
    }
}
