<?php

namespace XtendPackages\RESTPresenter\Concerns;

trait WithResourcePagination
{
    public function applyPagination($query, $request)
    {
        $query->paginate($request->input('limit', 10));
    }
}
