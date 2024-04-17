<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands\Generator\Writers;

use ArrayIterator;
use Exception;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Structures\TypesCollection;
use Spatie\TypeScriptTransformer\Writers\Writer;

final class ModuleWriter implements Writer
{
    /**
     * @throws Exception
     */
    public function format(TypesCollection $collection): string
    {
        $output = '';

        /** @var ArrayIterator<int, \Spatie\TypeScriptTransformer\Structures\TransformedType> $iterator */
        $iterator = $collection->getIterator();

        $iterator->uasort(fn (TransformedType $a, TransformedType $b): int => strcmp(type($a->name)->asString(), type($b->name)->asString()));

        foreach ($iterator as $type) {
            /** @var TransformedType $type */
            if ($type->isInline) {
                continue;
            }

            $type->keyword = type(config('rest-presenter.generator.ts_types_keyword'))->asString();
            $type->trailingSemicolon = type(config('rest-presenter.generator.ts_types_trailing_semicolon'))->asBool();

            $output .= "export {$type->toString()}".PHP_EOL;
        }

        return $output;
    }

    public function replacesSymbolsWithFullyQualifiedIdentifiers(): bool
    {
        return false;
    }
}
