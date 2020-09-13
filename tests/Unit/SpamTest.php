<?php

namespace Silentz\Akismet\Tests\Unit;

use Silentz\Akismet\Tests\TestCase;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\Form as FormAPI;
use Statamic\Facades\YAML;
use Statamic\Fields\Blueprint;
use Statamic\Forms\Form as Form;
use Statamic\Forms\Submission;

class SpamTest extends TestCase
{
    private Form $form;
    private Submission $submission;

    public function setUp(): void
    {
        parent::setUp();

        FormAPI::all()->each->delete();

//        $blueprint = (new Blueprint)->setHandle('contact')->save();
        $path = __DIR__.'/../__fixtures__/contact_us.yaml';
        $contents = YAML::file($path)->parse();

        (new Blueprint)
            ->setInitialPath($path)
            ->setHandle('contact_us')
            ->setContents($contents);

        $this->form = FormAPI::make('contact_us')
            ->title('Contact Us');

        $this->form->save();

        $this->submission = $this->form->makeSubmission()->data(
            [
                'name'=>'viagra-test-123',
                'email' => 'akismet-guaranteed-spam@example.com',
                'message' => '',
            ]
        );
    }

    /** @test */
    public function can_detect_spam()
    {
        config([
            'akismet.forms' => [
                'contact_us' => [
                    'author_field' => 'name',
                    'email_field' => 'email',
                    'content_field' => 'message',
                ],
            ],
        ]);

        $this->assertFalse(FormSubmitted::dispatch($this->submission));
    }
}
