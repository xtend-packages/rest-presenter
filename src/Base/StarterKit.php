<?php

namespace XtendPackages\RESTPresenter\Base;

use Illuminate\Filesystem\Filesystem;

abstract class StarterKit
{
    public function __construct(protected Filesystem $filesystem) {}

    /**
     * @return array<string, array<string, string>>
     */
    abstract public function autoDiscover(): array;
}
