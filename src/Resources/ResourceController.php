<?php

namespace XtendPackages\RESTPresenter\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;
use XtendPackages\RESTPresenter\Concerns\WithResourceFiltering;
use XtendPackages\RESTPresenter\Concerns\WithResourceRouteActions;

abstract class ResourceController extends Controller
{
    use InteractsWithModel;
    use InteractsWithPresenter;
    use InteractsWithRequest;
    use WithResourceFiltering;
    use WithResourceRouteActions;

    public array $sorts;

    public function __construct(Request $request, bool $init = true)
    {
        if ($init) {
            $this->init($request);
        }
    }

    protected function init(Request $request)
    {
        $this->setModelForResource();

        $query = $this->getModelQuery();

        $this->sorts = $request->sorts ?? [];
        $this->applyFilters($query);

        $this->setModelQuery($query);
    }

    /**
     * @throws \Exception
     */
    protected function setModelForResource(): void
    {
        if (static::$model === Model::class) {
            match (class_basename(static::class)) {
                'AuthResourceController', 'UserResourceController' => $this->setModel(config('rest-presenter.resources.user.model')),
                default => throw new \Exception('Model not found for resource controller'),
            };
        }
    }
}
