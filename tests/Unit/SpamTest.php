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

        $path = __DIR__.'/../__fixtures__/contact_us.yaml';
        $contents = YAML::file($path)->parse();
        $blueprint = Blueprint::make()->setContents($contents);

        Blueprint::shouldReceive('find')->with('forms.contact_us')->andReturn($blueprint);

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

    /** @test */
    public function can_detect_spam_with_only_email()
    {
        config([
            'akismet.forms' => [
                'contact_us' => [
                    'email_field' => 'email',
                ],
            ],
        ]);

        $submission = $this->form->makeSubmission()->data(
            [
                'email' => 'akismet-guaranteed-spam@example.com',
            ]
        );

        $this->assertFalse(FormSubmitted::dispatch($submission));
    }
}
