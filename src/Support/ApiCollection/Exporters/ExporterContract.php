<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters;

interface ExporterContract
{
    public function schema(): void;

    public function saveTo(string $filename): static;

    public function export(): void;

    public function getSchema(): string;
}
