<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Resources;

use Exception;
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

    /**
     * @var array<int, string>
     */
    public array $sorts;

    public static bool $isAuthenticated = false;

    public function __construct(Request $request, bool $init = true)
    {
        if ($init) {
            $this->init($request);
        }
    }

    protected function init(Request $request): void
    {
        $this->setModelForResource();

        $query = $this->getModelQuery();

        $this->sorts = type($request->sorts ?? [])->asArray();
        $this->applyFilters($query);

        $this->setModelQuery($query);
    }

    /**
     * @throws Exception
     */
    protected function setModelForResource(): void
    {
        if (static::$model === Model::class) {
            $userModelFromConfig = type(config('rest-presenter.resources.user.model'))->asString();
            match (class_basename(static::class)) {
                'AuthResourceController', 'UserResourceController' => $this->setModel($userModelFromConfig),
                default => throw new Exception('Model not found for resource controller'),
            };
        }
    }
}
