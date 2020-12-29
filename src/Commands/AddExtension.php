<?php

namespace Silentz\Akismet\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Folder;

class AddExtension extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spam:add-extension';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Adds a '.yaml' extension to the spam files that are missing them";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Folder::disk(config('filesystems.default'))
            ->getFilesRecursively('spam')
            ->each(fn ($path) => $this->addExtension($path));

        return 0;
    }

    private function addExtension($path)
    {
        if (! ($extension = pathinfo($path, PATHINFO_EXTENSION)) || is_numeric($extension)) {
            Storage::move($path, $path.'.yaml');
        }
    }
}
