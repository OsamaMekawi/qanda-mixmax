<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Question;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QAndACommandTest extends TestCase
{
    use DatabaseTransactions, WithFaker;


    public $question;
    public $answer;
    /**
     * This method is called befor each test
     */
    public function setUp() :void
    {
        parent::setUp();
        $this->question = $this->faker->text(50).' ?';
        $this->answer = $this->faker->text(10);
        $this->initialTestData();

    }

    public function initialTestData(): void
    {
        $this->artisan('qanda:interactive')
            ->expectsQuestion('Choose from the following list : ',0)
            ->expectsQuestion('What is your question?',$this->question)
            ->expectsQuestion('Answer the following question, '.$this->question,$this->answer)
            ->expectsOutput('Question has been created successfully!')
            ->expectsQuestion('Choose from the following list : ',2)
            ->assertExitCode(0);
    }
    /**
     * @test
     * @return void
     */
    public function it_call_qanda_command_to_create_question_and_answer(): void
    {
        $this->assertCount(1,Question::all());
    }

}
