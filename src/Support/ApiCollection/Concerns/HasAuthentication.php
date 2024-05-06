<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Concerns;

use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Support\ApiCollection\Authentication\AuthenticationMethod;

trait HasAuthentication
{
    protected ?AuthenticationMethod $authentication = null;

    public function resolveAuth(string $provider): self
    {
        $config = type(config('rest-presenter.exporters.'.$provider.'.authentication'))->asArray();

        if ($config['method']) {
            $className = Str::of(__NAMESPACE__)
                ->replace('Concerns', 'Authentication')
                ->append('\\')
                ->append(ucfirst((string) $config['method']))
                ->toString();

            if (is_subclass_of($className, AuthenticationMethod::class)) {
                $this->authentication = new $className;
            }
        }

        return $this;
    }
}
