<?php

namespace Silentz\Akismet\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Folder;
use Statamic\Facades\YAML;
use Statamic\Support\Str;

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
        $raw = Storage::get($path);

        if (Str::of($raw)->startsWith('a:')) {
            Storage::put($path, YAML::dump(array_filter(unserialize($raw))));
            $this->info('Converted '.$path);

            return;
        }

        $this->info("Didn't need to convert {$path}");
    }
}
