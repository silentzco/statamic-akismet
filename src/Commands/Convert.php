<?php

namespace Silentz\Akismet\Commands;

use ErrorException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Folder;
use Statamic\Facades\YAML;

class Convert extends Command
{
    protected $signature = 'spam:convert';

    protected $description = 'Convert serialized spam to unserialized spam';

    public function handle()
    {
        Folder::disk(config('filesystems.default'))
            ->getFilesByTypeRecursively('spam', 'yaml')
            ->each(fn ($path) => $this->convert($path));

        return 0;
    }

    private function convert($path)
    {
        try {
            if ($data = unserialize(Storage::get($path))) {
                Storage::put($path, YAML::dump(array_filter($data)));
            }
        } catch (ErrorException $e) {
        }
    }
}
