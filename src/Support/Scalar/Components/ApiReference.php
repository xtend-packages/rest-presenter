<?php

namespace XtendPackages\RESTPresenter\Support\Scalar\Components;

use Ijpatricio\Mingle\Concerns\InteractsWithMingles;
use Ijpatricio\Mingle\Contracts\HasMingles;
use Livewire\Component;

class ApiReference extends Component implements HasMingles
{
    use InteractsWithMingles;

    public function component(): string
    {
        return 'resources/js/components/Scalar/ApiReference/index.js';
    }

    public function mingleData()
    {
        return [
            'message' => 'Message in a bottle 🍾',
        ];
    }

    public function doubleIt($amount)
    {
        return $amount * 2;
    }
}
