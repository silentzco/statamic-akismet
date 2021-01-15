<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Silentz\Akismet\Spam\Submission;
use Statamic\Facades\Path;
use Statamic\Facades\YAML;
use Statamic\Http\Controllers\CP\ActionController as StatamicActionController;

class ActionController extends StatamicActionController
{
    protected function getSelectedItems($ids, $context)
    {
        /** @var \Statamic\Forms\Form */
        $form = $this->request->route('form');

        return $ids->map(fn ($id) => new Submission($form->makeSubmission()
                ->id($id)
                ->data(YAML::parse(Storage::get(Path::assemble('spam', $form->handle(), $id.'.yaml'))))));
    }
}
