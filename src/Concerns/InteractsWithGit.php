<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Facades\Process;

trait InteractsWithGit
{
    protected bool $gitAutoCommit = false;

    protected function commitChanges(string $message): void
    {
        $this->components->info('Committing changes...');

        Process::run('git add .');
        Process::run('git commit -m "'.$message.'"');

        $this->components->info('Changes committed successfully');
    }

    protected function isCleanWorkingDirectory(): bool
    {
        $cleanDir = (Process::run('git diff --quiet'))->exitCode() === 0;

        if (! $cleanDir) {
            $this->components->warn('Please commit or stash your changes before proceeding with auto-commit');
        }

        return $cleanDir;
    }
}
