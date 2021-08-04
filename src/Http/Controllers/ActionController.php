<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Silentz\Akismet\Spam\Submission;
use Statamic\Facades\Action;
use Statamic\Facades\User;
use Statamic\Facades\YAML;
use Statamic\Http\Controllers\CP\ActionController as StatamicActionController;

class ActionController extends StatamicActionController
{
    public function run(Request $request)
    {
        if ($request->input('action') == 'delete') {
            $request->merge(['action' => 'delete_spam']);
        }

        return parent::run($request);
    }

    protected function getSelectedItems($ids, $context)
    {
        /** @var \Statamic\Forms\Form */
        $form = $this->request->route('form');

        return $ids->map(fn ($id) => $form->makeSubmission()
                ->id($id)
                ->data(YAML::parse(Storage::get(Submission::path($form->handle(), $id)))));
    }
}
