<?php

namespace App\Console\Commands;

use App\Question;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class QAndA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an interactive command line based Q And A system.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->mainMenu();

    }

    /**
     * main menu prompt options
     */
    public function mainMenu(): void
    {
        $type = $this->choice('Choose from the following list : ',
            ['Create Question and Answer',
                'View Previous Questions and answers',
                'Exit',
                'Reset'
            ],2);


        Switch($type){
            case  'Create Question and Answer':
                $this->createQuestionWithAnswer();
                break;
            case 'View Previous Questions and answers':
                $this->viewQuestionsWithAnswer();
                break;
            case 'Reset' :
                $this->resetQuestions();
                break;
            case 'Exit' :
                $this->exit();

        }
    }

    /**
     * create question and answer and save them
     */
    public function createQuestionWithAnswer(): void
    {
        $question = $this->ask('What is your question?');
        $answer = $this->ask('Answer the following question, '.$question);


        Question::create([
            'question'=>$question,
            'answer'=>$answer
        ]);

        $this->info("Question has been created successfully!");

        $this->mainMenu();

    }

    /**
     * view all question and choose one to answer it 
     */
    public function viewQuestionsWithAnswer(): void
    {

        $questions =  Question::where('user_answer',null)->get()->pluck('question')->toArray();

//        check on questions finished 
        if(!$questions){

            $this->info("you have answered all Questions");
            $this->info($this->getProgress());
            $this->mainMenu();

        }else{
            
            $questions[] = 'Back';
            
            $question = $this->choice('Choose from the following questions : ',$questions,0);
            $this->answerSpecificQuestion($question);
            $this->viewQuestionsWithAnswer();
        }
    }

    /**
     * @param $question
     * save user answer to question and give him his progress grade
     */
    public function answerSpecificQuestion($question)
    {
        if($question == 'Back'){
            return $this->back();
        }

        $answer = $this->ask($question);

        $question = Question::where('question',$question)->first();

        $question->update(['user_answer'=>$answer]);

        $response = $question->answer == $answer ? 'Your answer is correct' : "your answer is false";

        $this->info($response);
        $this->info($this->getProgress());

    }

    /**
     * @return string
     * calculate user progress
     */
    public function getProgress(): string
    {
        $grade = 0;
        $questions =  Question::get();
        foreach($questions as $question){
//            check if user answer matchs the question's answer gaven
            if($question->answer === $question->user_answer ){
                $grade++;
            }


        }
        return 'your grades are '. $grade .'/'. $questions->count();

    }

    /**
     * Reset all user answers
     */
    public function resetQuestions(): void
    {

        $questions = Question::where('user_answer','!=',null)->get();

        foreach($questions as $question){
            $question->update(['user_answer'=>null]);

        }

        $this->info("Question has been reset successfully!");

        $this->mainMenu();
    }


    /**
     * redirect to main menu
     */
    public function back()
    {
        $this->mainMenu();
    }

    /**
     * exit the console
     */
    public function exit()
    {
        $this->info("Good Bye!");

    }
}
