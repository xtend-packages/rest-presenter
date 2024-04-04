<?php

namespace XtendPackages\RESTPresenter\Base;

use Illuminate\Filesystem\Filesystem;

abstract class StarterKit
{
    public function __construct(protected Filesystem $filesystem) {}

    abstract public function autoDiscover(): array;
}
