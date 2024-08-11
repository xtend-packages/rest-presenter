<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Models;

use Illuminate\Database\Eloquent\Model;
use XtendPackages\RESTPresenter\Concerns\InteractsWithSushi;

class TestReport extends Model
{
    use InteractsWithSushi;

    /**
     * @var array<array<string, mixed>>
     */
    protected array $rows = [
        ['id' => 1, 'name' => 'TestSuite'],
    ];

    /**
     * @return array<array<string, mixed>>
     */
    public function getRows(): array
    {
        return $this->rows;
    }
}
