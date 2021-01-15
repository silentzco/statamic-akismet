<?php

namespace Silentz\Akismet\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Statamic\Extensions\Pagination\LengthAwarePaginator;
use Statamic\Facades\Config;
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
            return tap(new StatamicSubmission(), function ($submission) use ($path, $form) {
                $submission->id(basename($path, '.yaml'));
                $submission->form($form);
                $submission->data(YAML::parse(Storage::get($path)));
            });
        });

        // Search submissions.
        $submissions = $this->searchSubmissions($submissions);

        // Sort submissions.
        $sort = $this->request->sort ?? 'datestamp';
        $order = $this->request->order ?? ($sort === 'datestamp' ? 'desc' : 'asc');
        $submissions = $this->sortSubmissions($submissions, $sort, $order);

        // Paginate submissions.
        $totalSubmissionCount = $submissions->count();
        $perPage = request('perPage') ?? Config::get('statamic.cp.pagination_size');
        $currentPage = (int) $this->request->page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $submissions = $submissions->slice($offset, $perPage);
        $paginator = new LengthAwarePaginator($submissions, $totalSubmissionCount, $perPage, $currentPage);

        return (new Submissions($paginator))
            ->blueprint($form->blueprint())
            ->columnPreferenceKey("forms.{$form->handle()}.columns");
    }

    private function searchSubmissions($submissions)
    {
        if (! $this->request->filled('search')) {
            return $submissions;
        }

        return $submissions
            ->filter(fn ($submission) => collect($submission->data())
                ->filter(fn ($value) => $value && is_string($value))
                ->filter(fn ($value) => Str::contains(strtolower($value), strtolower($this->request->search)))
                ->isNotEmpty())
            ->values();
    }

    private function sortSubmissions($submissions, $sortBy, $sortOrder)
    {
        return $submissions->sortBy(function ($submission) use ($sortBy) {
            return $sortBy === 'datestamp'
                ? $submission->date()->timestamp
                : $submission->get($sortBy);
        }, null, $sortOrder === 'desc')->values();
    }
}
