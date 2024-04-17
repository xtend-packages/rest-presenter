<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

trait WithResourcePagination
{
    public function applyPagination($query, $request): void
    {
        $query->paginate($request->input('limit', 10));
    }
}
