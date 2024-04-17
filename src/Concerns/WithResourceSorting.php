<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

trait WithResourceSorting
{
    public array $sorts;

    protected function applySorting($query, $request)
    {
        foreach ($this->getSorts($request) as $sort => $direction) {
            if (method_exists($this, $sort)) {
                $this->{$sort}($query, $direction);
            }
        }
    }

    protected function getSorts($request)
    {
        return $request->only($this->sorts);
    }
}
