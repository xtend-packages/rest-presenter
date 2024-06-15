<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait InteractsWithCollectionExports
{
    protected function downloadExportedCollection(string $type): BinaryFileResponse
    {
        $filenameSuffix = match ($type) {
            'insomnia' => 'insomnia_collection.json',
            'postman' => 'postman_collection.json',
            default => throw new InvalidArgumentException('Invalid type'),
        };

        config(['rest-presenter.exporters.provider' => $type]);
        Artisan::call('rest-presenter:generate-api-collection');

        $latestExportedCollection = $this->getLatestExportedCollection($type, $filenameSuffix);

        return response()->download($latestExportedCollection);
    }

    protected function getLatestExportedCollection(string $type, string $filenameSuffix): string
    {
        $collections = glob(resource_path("rest-presenter/$type/*_$filenameSuffix"));
        if (empty($collections)) {
            throw new InvalidArgumentException('No collections found');
        }

        return end($collections);
    }
}
