<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\Scalar;

use Illuminate\Contracts\View\View;
use XtendPackages\RESTPresenter\Models\Endpoint;

class APiClient
{
    public function __invoke(int $endpointId): View
    {
        $endpoint = Endpoint::findOrFail($endpointId);
        return view('rest-presenter::components.scalar-client', compact('endpoint'));
    }
}
