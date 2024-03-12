<?php

namespace XtendPackages\RESTPresenter\Controllers;

use Illuminate\Http\Request;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;
use XtendPackages\RESTPresenter\Concerns\WithResourceFiltering;

abstract class ResourceBaseController
{
    use InteractsWithRequest;
    use InteractsWithModel;
    use WithResourceFiltering;

    public array $sorts;

    public function __construct(Request $request)
    {
        $query = $this->getModelQuery();

        $this->sorts = $request->sorts ?? [];

        $this->applyFilters($query);
        $this->setModelQuery($query);
    }
}
