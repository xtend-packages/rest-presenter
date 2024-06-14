<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait InteractsWithMingles
{
    public $mingleId;

    public function mingleBoot(Collection $data): Collection
    {
        //

        return $data;
    }

    public function mingleData(Collection $data): Collection
    {
        //

        return $data;
    }

    public function mountInteractsWithMingles(): void
    {
        $this->mingleId = 'mingle-' . Str::random();
    }

    public function render(): View
    {
        return view('mingle::mingle');
    }
}
