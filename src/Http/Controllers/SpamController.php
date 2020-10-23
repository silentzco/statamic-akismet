<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Silentz\Akismet\Spam\Submission;
use Statamic\Extensions\Pagination\LengthAwarePaginator;
use Statamic\Facades\Config;
use Statamic\Forms\Form;
use Statamic\Forms\Submission as StatamicSubmission;
use Statamic\Http\Controllers\Controller;
use Statamic\Http\Resources\CP\Submissions\Submissions;

class SpamController extends Controller
{
    public function show(Form $form, string $id)
    {
        if (! $submission = Submission::createFromQueue($form, $id)->submission()) {
            return $this->pageNotFound();
        }

        $blueprint = $submission->blueprint();
        $fields = $blueprint->fields()->addValues($submission->data())->preProcess();
        // statamic.cp.akismet.spam.show
        return view('akismet::cp.spam.show', [
            'form' => $form,
            'submission' => $submission,
            'blueprint' => $blueprint->toPublishArray(),
            'values' => $fields->values(),
            'meta' => $fields->meta(),
            'title' => $submission->date()->format('M j, Y @ H:i'),
        ]);
    }

    public function index(Form $form, Request $request)
    {
        $paths = Storage::files("spam/{$form->handle()}");

        $submissions = collect($paths)->map(function ($path) use ($form) {
            return tap(new StatamicSubmission(), function ($submission) use ($path, $form) {
                $submission->id(basename($path));
                $submission->form($form);
                $submission->data(unserialize(Storage::get($path)));
            });
        });

        // Paginate submissions.
        $totalSubmissionCount = $submissions->count();
        $perPage = request('perPage') ?? Config::get('statamic.cp.pagination_size');
        $currentPage = (int) $request->page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $submissions = $submissions->slice($offset, $perPage);
        $paginator = new LengthAwarePaginator($submissions, $totalSubmissionCount, $perPage, $currentPage);

        return (new Submissions($paginator))
            ->blueprint($form->blueprint())
            ->columnPreferenceKey("forms.{$form->handle()}.columns");
    }
}
