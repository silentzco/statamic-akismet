<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Path;
use Statamic\Http\Controllers\CP\ActionController as StatamicActionController;

class ActionController extends StatamicActionController
{
    protected function getSelectedItems($ids, $context)
    {
        /** @var \Statamic\Forms\Form */
        $form = $this->request->route('form');

        return $ids->map(fn ($id) => $form->makeSubmission()
                ->id($id)
                ->data(unserialize(Storage::get(Path::assemble('spam', $form->handle(), $id)))));
    }
}
