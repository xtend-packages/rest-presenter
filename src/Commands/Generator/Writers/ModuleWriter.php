<?php

namespace XtendPackages\RESTPresenter\Commands\Generator\Writers;

use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Structures\TypesCollection;
use Spatie\TypeScriptTransformer\Writers\Writer;

class ModuleWriter implements Writer
{
    public function format(TypesCollection $collection): string
    {
        $output = '';

        /** @var \ArrayIterator $iterator */
        $iterator = $collection->getIterator();

        $iterator->uasort(function (TransformedType $a, TransformedType $b) {
            return strcmp($a->name, $b->name);
        });

        foreach ($iterator as $type) {
            /** @var TransformedType $type */
            if ($type->isInline) {
                continue;
            }

            $type->keyword = config('rest-presenter.generator.ts_types_keyword');
            $type->trailingSemicolon = config('rest-presenter.generator.ts_types_trailing_semicolon');

            $output .= "export {$type->toString()}" . PHP_EOL;
        }

        return $output;
    }

    public function replacesSymbolsWithFullyQualifiedIdentifiers(): bool
    {
        return false;
    }
}
