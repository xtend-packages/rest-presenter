<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class TestCoverage extends Model
{
    use Sushi;

    protected $rows = [
        ['id' => 1, 'name' => 'TestSuite'],
    ];

    public function getRows(): array
    {
        return $this->rows;
    }
}
