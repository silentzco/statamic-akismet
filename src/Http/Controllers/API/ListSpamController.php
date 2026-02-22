<?php

namespace Silentz\Akismet\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Folder;
use Statamic\Facades\YAML;
use Statamic\Forms\Form;
use Statamic\Forms\Submission as StatamicSubmission;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Http\Resources\CP\Submissions\Submissions;

class ListSpamController extends CpController
{
    public function __invoke(Form $form)
    {
        $paths = Folder::disk(config('filesystems.default'))->getFilesByType("spam/{$form->handle()}", 'yaml');

        $submissions = collect($paths)->map(function ($path) use ($form) {
            return tap(new StatamicSubmission, function ($submission) use ($path, $form) {
                $submission->id(basename($path, '.yaml'));
                $submission->form($form);
                $submission->data(YAML::parse(Storage::get($path)));
            });
        });

        return (new Submissions($submissions))->blueprint($form->blueprint());
    }
}
